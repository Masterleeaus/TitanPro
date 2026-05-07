export type LayoutTokens = {
    gridColumns: number;
    sidebarWidth: number;
    contentMaxWidth: number;
    cardMinHeight: number;
    primaryCardSpan: number;
    secondaryCardSpan: number;
    primaryCardHeight: number;
    secondaryCardHeight: number;
    sectionGap: number;
};

export const layoutTokenDefaults: LayoutTokens = {
    gridColumns: 12,
    sidebarWidth: 256,
    contentMaxWidth: 960,
    cardMinHeight: 176,
    primaryCardSpan: 8,
    secondaryCardSpan: 4,
    primaryCardHeight: 224,
    secondaryCardHeight: 176,
    sectionGap: 24,
};

const TOKEN_BLOCK_START = '/* titan-layout-tokens:start */';
const TOKEN_BLOCK_END = '/* titan-layout-tokens:end */';

const tokenMap: Record<string, keyof LayoutTokens> = {
    '--grid-columns': 'gridColumns',
    '--sidebar-width': 'sidebarWidth',
    '--content-max-width': 'contentMaxWidth',
    '--card-min-height': 'cardMinHeight',
    '--widget-primary-span': 'primaryCardSpan',
    '--widget-secondary-span': 'secondaryCardSpan',
    '--widget-primary-height': 'primaryCardHeight',
    '--widget-secondary-height': 'secondaryCardHeight',
    '--layout-section-gap': 'sectionGap',
};

function clamp(value: number, minimum: number, maximum: number): number {
    return Math.min(Math.max(value, minimum), maximum);
}

export function cloneLayoutTokens(tokens: LayoutTokens): LayoutTokens {
    return { ...tokens };
}

export function sanitizeLayoutTokens(tokens: Partial<LayoutTokens> = {}): LayoutTokens {
    const merged = { ...layoutTokenDefaults, ...tokens };
    const gridColumns = clamp(Math.round(merged.gridColumns), 4, 24);
    const sidebarWidth = clamp(Math.round(merged.sidebarWidth), 192, 384);
    const contentMaxWidth = clamp(Math.round(merged.contentMaxWidth), 720, 1440);
    const primaryCardSpan = clamp(Math.round(merged.primaryCardSpan), 3, gridColumns - 1);
    const secondaryCardSpan = clamp(Math.round(merged.secondaryCardSpan), 2, gridColumns - 1);
    const primaryCardHeight = clamp(Math.round(merged.primaryCardHeight), 128, 480);
    const secondaryCardHeight = clamp(Math.round(merged.secondaryCardHeight), 128, 480);

    return {
        gridColumns,
        sidebarWidth,
        contentMaxWidth,
        cardMinHeight: clamp(Math.round(Math.min(merged.cardMinHeight, primaryCardHeight, secondaryCardHeight)), 128, 360),
        primaryCardSpan,
        secondaryCardSpan,
        primaryCardHeight,
        secondaryCardHeight,
        sectionGap: clamp(Math.round(merged.sectionGap), 8, 96),
    };
}

export function snapValue(value: number, gridSize: number, minimum: number, maximum: number): number {
    const snapped = Math.round(value / gridSize) * gridSize;

    return clamp(snapped, minimum, maximum);
}

export function extractLayoutTokens(customCss?: string | null): LayoutTokens {
    if (!customCss) {
        return cloneLayoutTokens(layoutTokenDefaults);
    }

    const block = customCss.match(/\/\* titan-layout-tokens:start \*\/[\s\S]*?\/\* titan-layout-tokens:end \*\//);
    if (!block) {
        return cloneLayoutTokens(layoutTokenDefaults);
    }

    const nextTokens: Partial<LayoutTokens> = {};

    for (const [cssVar, tokenKey] of Object.entries(tokenMap)) {
        const match = block[0].match(new RegExp(`${cssVar}\\s*:\\s*([0-9.]+)`));
        if (!match) {
            continue;
        }

        nextTokens[tokenKey] = Number(match[1]);
    }

    return sanitizeLayoutTokens(nextTokens);
}

export function buildLayoutTokenCss(tokens: LayoutTokens): string {
    const sanitized = sanitizeLayoutTokens(tokens);

    return [
        TOKEN_BLOCK_START,
        ':root {',
        `  --grid-columns: ${sanitized.gridColumns};`,
        `  --sidebar-width: ${sanitized.sidebarWidth}px;`,
        `  --content-max-width: ${sanitized.contentMaxWidth}px;`,
        `  --card-min-height: ${sanitized.cardMinHeight}px;`,
        `  --widget-primary-span: ${sanitized.primaryCardSpan};`,
        `  --widget-secondary-span: ${sanitized.secondaryCardSpan};`,
        `  --widget-primary-height: ${sanitized.primaryCardHeight}px;`,
        `  --widget-secondary-height: ${sanitized.secondaryCardHeight}px;`,
        `  --layout-section-gap: ${sanitized.sectionGap}px;`,
        '}',
        TOKEN_BLOCK_END,
    ].join('\n');
}

export function mergeLayoutTokenCss(customCss: string | null | undefined, tokens: LayoutTokens): string {
    const cleanCss = (customCss ?? '')
        .replace(/\/\* titan-layout-tokens:start \*\/[\s\S]*?\/\* titan-layout-tokens:end \*\//g, '')
        .trim();
    const tokenCss = buildLayoutTokenCss(tokens);

    return cleanCss ? `${cleanCss}\n\n${tokenCss}` : tokenCss;
}

export function layoutPreviewStyles(tokens: LayoutTokens): Record<string, string> {
    const sanitized = sanitizeLayoutTokens(tokens);

    return {
        '--grid-columns': String(sanitized.gridColumns),
        '--sidebar-width': `${sanitized.sidebarWidth}px`,
        '--content-max-width': `${sanitized.contentMaxWidth}px`,
        '--card-min-height': `${sanitized.cardMinHeight}px`,
        '--widget-primary-span': String(sanitized.primaryCardSpan),
        '--widget-secondary-span': String(sanitized.secondaryCardSpan),
        '--widget-primary-height': `${sanitized.primaryCardHeight}px`,
        '--widget-secondary-height': `${sanitized.secondaryCardHeight}px`,
        '--layout-section-gap': `${sanitized.sectionGap}px`,
    };
}
