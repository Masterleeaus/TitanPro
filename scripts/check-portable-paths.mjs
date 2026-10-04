import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { dirname, resolve } from "node:path";

const root = resolve(dirname(fileURLToPath(import.meta.url)), "..");
const checks = [
  {
    path: "scripts/agent-issue-runner.sh",
    marker: 'REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"',
  },
  {
    path: "scripts/fix-filament-view.sh",
    marker: 'ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"',
  },
];
const hostSpecificPath = /\/home\/|\/Users\/|\/public_html\b|(?:^|["'=\s])[A-Za-z]:[\\/]/;
const failures = [];

for (const check of checks) {
  const file = resolve(root, check.path);
  const source = readFileSync(file, "utf8");
  if (!source.includes(check.marker)) failures.push(`${check.path}: missing repository-relative root resolution`);
  if (hostSpecificPath.test(source)) failures.push(`${check.path}: host-specific absolute path literal found`);
}

if (failures.length > 0) {
  console.error(failures.join("\n"));
  process.exit(1);
}
console.log(`portable shell path check: ${checks.length} scripts verified`);
