import { describe, expect, it } from 'vitest';
import {
    buildLayoutTokenCss,
    cloneLayoutTokens,
    extractLayoutTokens,
    layoutPreviewStyles,
    layoutTokenDefaults,
    mergeLayoutTokenCss,
    sanitizeLayoutTokens,
    snapValue,
    type LayoutTokens,
} from '../layoutTokens';

// ---------------------------------------------------------------------------
// helpers
// ---------------------------------------------------------------------------

const TOKEN_START = '/* titan-layout-tokens:start */';
const TOKEN_END = '/* titan-layout-tokens:end */';

function buildSampleCss(overrides: Partial<Record<string, string>> = {}): string {
    const defaults: Record<string, string> = {
        '--grid-columns': '12',
        '--sidebar-width': '256',
        '--content-max-width': '960',
        '--card-min-height': '176',
        '--widget-primary-span': '8',
        '--widget-secondary-span': '4',
        '--widget-primary-height': '224',
        '--widget-secondary-height': '176',
        '--layout-section-gap': '24',
    };
    const merged = { ...defaults, ...overrides };
    const lines = Object.entries(merged).map(([k, v]) => `  ${k}: ${v}px;`);
    return `${TOKEN_START}\n:root {\n${lines.join('\n')}\n}\n${TOKEN_END}`;
}

// ---------------------------------------------------------------------------
// snapValue
// ---------------------------------------------------------------------------

describe('snapValue', () => {
    it('rounds a value to the nearest grid multiple', () => {
        expect(snapValue(260, 8, 192, 384)).toBe(264); // 260 / 8 = 32.5 → rounds to 33 * 8 = 264
        expect(snapValue(256, 8, 192, 384)).toBe(256); // already on-grid
        expect(snapValue(253, 4, 192, 384)).toBe(252); // 253 / 4 = 63.25 → rounds to 63 * 4 = 252
    });

    it('clamps below minimum', () => {
        expect(snapValue(100, 8, 192, 384)).toBe(192);
    });

    it('clamps above maximum', () => {
        expect(snapValue(500, 8, 192, 384)).toBe(384);
    });

    it('handles zero delta (no change)', () => {
        expect(snapValue(256, 8, 192, 384)).toBe(256);
    });
});

// ---------------------------------------------------------------------------
// cloneLayoutTokens
// ---------------------------------------------------------------------------

describe('cloneLayoutTokens', () => {
    it('returns a shallow copy with identical values', () => {
        const clone = cloneLayoutTokens(layoutTokenDefaults);
        expect(clone).toEqual(layoutTokenDefaults);
        expect(clone).not.toBe(layoutTokenDefaults);
    });

    it('mutation of clone does not affect original', () => {
        const clone = cloneLayoutTokens(layoutTokenDefaults) as LayoutTokens & { sidebarWidth: number };
        clone.sidebarWidth = 300;
        expect(layoutTokenDefaults.sidebarWidth).toBe(256);
    });
});

// ---------------------------------------------------------------------------
// sanitizeLayoutTokens
// ---------------------------------------------------------------------------

describe('sanitizeLayoutTokens', () => {
    it('fills in defaults for an empty input', () => {
        const result = sanitizeLayoutTokens({});
        expect(result).toEqual(layoutTokenDefaults);
    });

    it('clamps gridColumns to [4, 24]', () => {
        expect(sanitizeLayoutTokens({ gridColumns: 1 }).gridColumns).toBe(4);
        expect(sanitizeLayoutTokens({ gridColumns: 100 }).gridColumns).toBe(24);
        expect(sanitizeLayoutTokens({ gridColumns: 12 }).gridColumns).toBe(12);
    });

    it('clamps sidebarWidth to [192, 384]', () => {
        expect(sanitizeLayoutTokens({ sidebarWidth: 100 }).sidebarWidth).toBe(192);
        expect(sanitizeLayoutTokens({ sidebarWidth: 500 }).sidebarWidth).toBe(384);
    });

    it('clamps contentMaxWidth to [720, 1440]', () => {
        expect(sanitizeLayoutTokens({ contentMaxWidth: 100 }).contentMaxWidth).toBe(720);
        expect(sanitizeLayoutTokens({ contentMaxWidth: 2000 }).contentMaxWidth).toBe(1440);
    });

    it('clamps primaryCardSpan to [3, gridColumns-1]', () => {
        const result = sanitizeLayoutTokens({ gridColumns: 12, primaryCardSpan: 1 });
        expect(result.primaryCardSpan).toBe(3);

        const result2 = sanitizeLayoutTokens({ gridColumns: 12, primaryCardSpan: 12 });
        expect(result2.primaryCardSpan).toBe(11);
    });

    it('clamps sectionGap to [8, 96]', () => {
        expect(sanitizeLayoutTokens({ sectionGap: 0 }).sectionGap).toBe(8);
        expect(sanitizeLayoutTokens({ sectionGap: 200 }).sectionGap).toBe(96);
    });

    it('sets cardMinHeight as min of card heights (clamped to [128, 360])', () => {
        const result = sanitizeLayoutTokens({ primaryCardHeight: 200, secondaryCardHeight: 176, cardMinHeight: 300 });
        // cardMinHeight = min(min(300,200,176), clamp) = min(176, ...) = 176
        expect(result.cardMinHeight).toBeLessThanOrEqual(result.primaryCardHeight);
        expect(result.cardMinHeight).toBeLessThanOrEqual(result.secondaryCardHeight);
    });
});

// ---------------------------------------------------------------------------
// extractLayoutTokens
// ---------------------------------------------------------------------------

describe('extractLayoutTokens', () => {
    it('returns defaults when called with null', () => {
        expect(extractLayoutTokens(null)).toEqual(layoutTokenDefaults);
    });

    it('returns defaults when called with undefined', () => {
        expect(extractLayoutTokens(undefined)).toEqual(layoutTokenDefaults);
    });

    it('returns defaults when CSS has no token block', () => {
        expect(extractLayoutTokens('body { color: red; }')).toEqual(layoutTokenDefaults);
    });

    it('parses all tokens from a well-formed CSS block', () => {
        const css = buildSampleCss();
        const tokens = extractLayoutTokens(css);
        expect(tokens.gridColumns).toBe(12);
        expect(tokens.sidebarWidth).toBe(256);
        expect(tokens.contentMaxWidth).toBe(960);
        expect(tokens.primaryCardSpan).toBe(8);
        expect(tokens.secondaryCardSpan).toBe(4);
        expect(tokens.sectionGap).toBe(24);
    });

    it('parses a custom sidebar width from the token block', () => {
        const css = buildSampleCss({ '--sidebar-width': '320' });
        expect(extractLayoutTokens(css).sidebarWidth).toBe(320);
    });

    it('sanitises out-of-range values extracted from CSS', () => {
        const css = buildSampleCss({ '--sidebar-width': '999' });
        expect(extractLayoutTokens(css).sidebarWidth).toBe(384); // clamped to max
    });

    it('ignores user-written CSS outside the token block', () => {
        const css = `body { background: blue; }\n${buildSampleCss()}`;
        const tokens = extractLayoutTokens(css);
        expect(tokens.sidebarWidth).toBe(256);
    });
});

// ---------------------------------------------------------------------------
// buildLayoutTokenCss
// ---------------------------------------------------------------------------

describe('buildLayoutTokenCss', () => {
    it('wraps output in the titan token block markers', () => {
        const css = buildLayoutTokenCss(layoutTokenDefaults);
        expect(css).toContain(TOKEN_START);
        expect(css).toContain(TOKEN_END);
    });

    it('includes all token custom properties', () => {
        const css = buildLayoutTokenCss(layoutTokenDefaults);
        expect(css).toContain('--grid-columns: 12;');
        expect(css).toContain('--sidebar-width: 256px;');
        expect(css).toContain('--content-max-width: 960px;');
        expect(css).toContain('--card-min-height:');
        expect(css).toContain('--widget-primary-span: 8;');
        expect(css).toContain('--widget-secondary-span: 4;');
        expect(css).toContain('--layout-section-gap: 24px;');
    });

    it('sanitises values before writing', () => {
        const css = buildLayoutTokenCss({ ...layoutTokenDefaults, sidebarWidth: 999 });
        expect(css).toContain('--sidebar-width: 384px;'); // clamped to 384
    });

    it('produces a round-trippable CSS block (extract → build → extract yields same)', () => {
        const first = buildLayoutTokenCss(layoutTokenDefaults);
        const second = extractLayoutTokens(first);
        expect(second).toEqual(layoutTokenDefaults);
    });
});

// ---------------------------------------------------------------------------
// mergeLayoutTokenCss
// ---------------------------------------------------------------------------

describe('mergeLayoutTokenCss', () => {
    it('builds CSS when there is no existing custom CSS', () => {
        const result = mergeLayoutTokenCss(null, layoutTokenDefaults);
        expect(result).toContain(TOKEN_START);
        expect(result).not.toMatch(/^\s*\n/); // no leading blank lines
    });

    it('replaces an existing token block', () => {
        const initial = mergeLayoutTokenCss(null, layoutTokenDefaults);
        const updated = mergeLayoutTokenCss(initial, { ...layoutTokenDefaults, sidebarWidth: 320 });
        const blockCount = updated.split(TOKEN_START).length - 1;
        expect(blockCount).toBe(1);
        expect(updated).toContain('--sidebar-width: 320px;');
    });

    it('preserves user CSS before the token block', () => {
        const userCss = 'body { background: red; }';
        const result = mergeLayoutTokenCss(userCss, layoutTokenDefaults);
        expect(result).toContain(userCss);
        expect(result).toContain(TOKEN_START);
    });

    it('does not duplicate the token block on repeated merges', () => {
        let css = mergeLayoutTokenCss(null, layoutTokenDefaults);
        css = mergeLayoutTokenCss(css, layoutTokenDefaults);
        css = mergeLayoutTokenCss(css, layoutTokenDefaults);
        const blockCount = css.split(TOKEN_START).length - 1;
        expect(blockCount).toBe(1);
    });
});

// ---------------------------------------------------------------------------
// layoutPreviewStyles
// ---------------------------------------------------------------------------

describe('layoutPreviewStyles', () => {
    it('returns a record of CSS custom property strings', () => {
        const styles = layoutPreviewStyles(layoutTokenDefaults);
        expect(styles['--sidebar-width']).toBe('256px');
        expect(styles['--content-max-width']).toBe('960px');
        expect(styles['--grid-columns']).toBe('12');
        expect(styles['--layout-section-gap']).toBe('24px');
    });

    it('sanitises values before mapping', () => {
        const styles = layoutPreviewStyles({ ...layoutTokenDefaults, sidebarWidth: 999 });
        expect(styles['--sidebar-width']).toBe('384px');
    });
});

// ---------------------------------------------------------------------------
// token round-trip
// ---------------------------------------------------------------------------

describe('token round-trip', () => {
    it('extract → build → extract is stable (identity)', () => {
        const css = buildLayoutTokenCss(layoutTokenDefaults);
        const parsed = extractLayoutTokens(css);
        expect(parsed).toEqual(layoutTokenDefaults);
    });

    it('modified tokens survive a full CSS round-trip', () => {
        const modified: LayoutTokens = { ...layoutTokenDefaults, sidebarWidth: 320, contentMaxWidth: 1200, sectionGap: 32 };
        const css = buildLayoutTokenCss(modified);
        const parsed = extractLayoutTokens(css);
        expect(parsed.sidebarWidth).toBe(320);
        expect(parsed.contentMaxWidth).toBe(1200);
        expect(parsed.sectionGap).toBe(32);
    });

    it('merge followed by extract preserves mutations across multiple save cycles', () => {
        let css: string | null = null;
        // First save
        css = mergeLayoutTokenCss(css, { ...layoutTokenDefaults, sidebarWidth: 300 });
        // Second save with different value
        css = mergeLayoutTokenCss(css, { ...layoutTokenDefaults, sidebarWidth: 340 });
        expect(extractLayoutTokens(css).sidebarWidth).toBe(340);
    });
});
