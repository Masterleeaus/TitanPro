# Issue 226 – Remove Duplicate Tailwind CSS v3 Entry

## Summary

`package.json` declared Tailwind CSS twice with conflicting major versions:

| Location | Version |
|---|---|
| `devDependencies` | `^3.2.1` (leftover from pre-v4 migration) |
| `dependencies` | `^4.1.1` (correct, current) |

This caused non-deterministic resolution, polluted the lock file, and contradicted the project specification (Tailwind CSS v4 only).

## Files Changed

| File | Change |
|---|---|
| `package.json` | Removed `"tailwindcss": "^3.2.1"` from `devDependencies` |
| `package-lock.json` | Regenerated via `npm install` after removing the duplicate entry |

## Fix Applied

Removed the stale `"tailwindcss": "^3.2.1"` line from `devDependencies` in `package.json`.  
`"tailwindcss": "^4.1.1"` in `dependencies` is kept as the single authoritative entry.

```diff
-        "tailwindcss": "^3.2.1",
         "typescript": "^5.2.2",
```

Then ran:

```bash
npm install
```

This regenerated `package-lock.json` with a single Tailwind v4 resolution.

## Verification

- `npm install` completed with 0 vulnerabilities and no peer-dependency warnings related to Tailwind.
- `package.json` now contains exactly one `tailwindcss` entry (`^4.1.1` in `dependencies`).

## Next Steps

- Verify `tailwind.config.js` (if present) is in v4 format. Tailwind CSS v4 no longer uses `tailwind.config.js` by default — configuration is done via CSS `@theme` directives. Remove or migrate any leftover v3 config file.
- Run `npm run build` in CI to confirm the build completes without Tailwind version warnings.
