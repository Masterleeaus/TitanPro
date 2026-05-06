#!/usr/bin/env bash
# =============================================================================
# Titan Agent Issue Runner
# =============================================================================
# Picks the oldest open GitHub issue without an agent label and runs a
# Claude Code agent to implement the fix.
#
# Requirements:
#   - claude CLI installed and logged in (claude login)
#   - gh CLI installed and authenticated (gh auth login)
#   - git configured with push access to the repo
#
# Run manually:      bash scripts/agent-issue-runner.sh
# Run on a schedule: add to crontab (see bottom of this file)
# =============================================================================

set -euo pipefail

# ── Config ────────────────────────────────────────────────────────────────────
REPO="masterleeaus/titanpro"
BRANCH="claude/scan-repo-for-bugs-NPTj0"
REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
LOG_FILE="$REPO_DIR/scripts/agent-runner.log"
LOCK_FILE="/tmp/titan-agent-runner.lock"

# ── Colours ───────────────────────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'

log()  { echo -e "${BLUE}[$(date '+%H:%M:%S')]${NC} $*" | tee -a "$LOG_FILE"; }
ok()   { echo -e "${GREEN}[$(date '+%H:%M:%S')] ✓${NC} $*" | tee -a "$LOG_FILE"; }
warn() { echo -e "${YELLOW}[$(date '+%H:%M:%S')] ⚠${NC} $*" | tee -a "$LOG_FILE"; }
err()  { echo -e "${RED}[$(date '+%H:%M:%S')] ✗${NC} $*" | tee -a "$LOG_FILE"; }

# ── Prevent concurrent runs ───────────────────────────────────────────────────
if [ -f "$LOCK_FILE" ]; then
    LOCK_PID=$(cat "$LOCK_FILE" 2>/dev/null || echo "")
    if [ -n "$LOCK_PID" ] && kill -0 "$LOCK_PID" 2>/dev/null; then
        warn "Agent already running (PID $LOCK_PID). Skipping this cycle."
        exit 0
    fi
    rm -f "$LOCK_FILE"
fi
echo $$ > "$LOCK_FILE"
trap 'rm -f "$LOCK_FILE"' EXIT

# ── Check dependencies ────────────────────────────────────────────────────────
for cmd in claude gh git; do
    if ! command -v "$cmd" &>/dev/null; then
        err "Required command not found: $cmd"
        exit 1
    fi
done

# ── Ensure we're on the right branch ─────────────────────────────────────────
cd "$REPO_DIR"
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
    log "Switching to branch $BRANCH..."
    git fetch origin "$BRANCH" --quiet
    git checkout "$BRANCH" --quiet
fi

# Pull latest before starting
git pull origin "$BRANCH" --quiet --rebase 2>/dev/null || true

# ── Find the oldest eligible issue ───────────────────────────────────────────
log "Searching for oldest open issue without agent labels..."

ISSUE_NUM=$(gh issue list \
    --repo "$REPO" \
    --state open \
    --limit 200 \
    --json number,createdAt,labels \
    --jq '[.[] | select(.labels | map(.name) | (contains(["agent-assigned"]) or contains(["agent-completed"]) or contains(["agent-failed"])) | not)] | sort_by(.createdAt) | .[0].number' \
    2>/dev/null || echo "")

if [ -z "$ISSUE_NUM" ] || [ "$ISSUE_NUM" = "null" ]; then
    ok "No eligible open issues found. All issues are assigned or completed."
    exit 0
fi

# Fetch full issue details
ISSUE_TITLE=$(gh issue view "$ISSUE_NUM" --repo "$REPO" --json title --jq '.title')
ISSUE_BODY=$(gh issue view "$ISSUE_NUM" --repo "$REPO" --json body --jq '.body')
ISSUE_LABELS=$(gh issue view "$ISSUE_NUM" --repo "$REPO" --json labels --jq '[.labels[].name] | join(", ")')

log "Selected issue #$ISSUE_NUM: $ISSUE_TITLE"

# ── Mark issue as agent-assigned ─────────────────────────────────────────────
gh issue edit "$ISSUE_NUM" --repo "$REPO" --add-label "agent-assigned" 2>/dev/null || true
log "Labelled issue #$ISSUE_NUM as agent-assigned"

# ── Ensure issue-docs directory exists ───────────────────────────────────────
mkdir -p "$REPO_DIR/issue-docs"

# ── Build the agent prompt ────────────────────────────────────────────────────
PROMPT="You are a senior software engineering agent working on the TitanPro repository.
This is a Laravel 12 + PHP 8.2 + Vue 3 + TypeScript + Filament v3 + Inertia.js + Tailwind CSS v4 codebase.

You have been assigned to resolve GitHub issue #${ISSUE_NUM}.

ISSUE TITLE: ${ISSUE_TITLE}
ISSUE LABELS: ${ISSUE_LABELS}

ISSUE BODY:
${ISSUE_BODY}

MANDATORY INSTRUCTIONS — follow ALL of these exactly:

1. Read and fully understand the issue before making any changes.
2. Explore all relevant code files using the Read and Bash tools.
3. Implement the complete fix, improvement, or feature described in the issue.
4. REQUIRED: Create the file issue-docs/issue-${ISSUE_NUM}.md containing:
   ## Issue Summary
   (What the issue was and why it mattered)

   ## Root Cause
   (Why the bug existed or what was missing)

   ## Changes Made
   (List every file modified with key before/after for important lines)

   ## Tests Added or Updated
   (What tests were written or changed)

   ## Next Steps
   (Any follow-on work, related issues, limitations, or known gaps)

5. Commit ALL changes (code + issue-docs/issue-${ISSUE_NUM}.md) with message:
   'fix: resolve issue #${ISSUE_NUM} — <short description>'

6. Push commits to branch: ${BRANCH}

CONSTRAINTS:
- Do NOT push to main or master.
- Do NOT create a pull request.
- Do NOT modify unrelated files.
- If the issue cannot be safely implemented, document why in issue-docs/issue-${ISSUE_NUM}.md, commit that file, and exit cleanly.
- Run php artisan test (or ./vendor/bin/pest) after making changes and fix any failures.
- Run vendor/bin/pint on any PHP files you modify.

WORKING DIRECTORY: ${REPO_DIR}
WORKING BRANCH: ${BRANCH}"

# ── Run the Claude agent ──────────────────────────────────────────────────────
log "Starting Claude agent on issue #$ISSUE_NUM..."
echo "──────────────────────────────────────────────────────" | tee -a "$LOG_FILE"

if claude --dangerously-skip-permissions -p "$PROMPT"; then
    AGENT_STATUS="success"
    ok "Claude agent completed issue #$ISSUE_NUM successfully."
else
    AGENT_STATUS="failed"
    err "Claude agent failed on issue #$ISSUE_NUM."
fi

echo "──────────────────────────────────────────────────────" | tee -a "$LOG_FILE"

# ── Label outcome and post comment ───────────────────────────────────────────
if [ "$AGENT_STATUS" = "success" ]; then
    gh issue edit "$ISSUE_NUM" --repo "$REPO" \
        --add-label "agent-completed" \
        --remove-label "agent-assigned" 2>/dev/null || true

    DOC_EXISTS=""
    [ -f "$REPO_DIR/issue-docs/issue-${ISSUE_NUM}.md" ] && DOC_EXISTS=" See \`issue-docs/issue-${ISSUE_NUM}.md\` for a full summary of changes and next steps."

    gh issue comment "$ISSUE_NUM" --repo "$REPO" \
        --body "🤖 **Agent completed.**${DOC_EXISTS} Changes committed to \`${BRANCH}\`." \
        2>/dev/null || true
else
    gh issue edit "$ISSUE_NUM" --repo "$REPO" \
        --add-label "agent-failed" \
        --remove-label "agent-assigned" 2>/dev/null || true

    gh issue comment "$ISSUE_NUM" --repo "$REPO" \
        --body "🤖 **Agent run failed** on this issue. It has been labelled \`agent-failed\` and can be retried by removing that label and running the agent again." \
        2>/dev/null || true
fi

log "Run complete. Status: $AGENT_STATUS"

# =============================================================================
# HOW TO SCHEDULE THIS (pick one option)
# =============================================================================
#
# OPTION 1 — System cron (runs every 5 minutes while machine is on)
# Add this line via: crontab -e
#
#   */5 * * * * /bin/bash /home/user/TitanPro/scripts/agent-issue-runner.sh >> /home/user/TitanPro/scripts/agent-runner.log 2>&1
#
# OPTION 2 — Run once manually
#   bash scripts/agent-issue-runner.sh
#
# OPTION 3 — Run in a loop in a terminal (useful for testing)
#   watch -n 300 bash scripts/agent-issue-runner.sh
#
# OPTION 4 — Laravel scheduler (runs via `php artisan schedule:run`)
#   Add one cron entry: * * * * * php artisan schedule:run
#   Then register the command in routes/console.php:
#     Schedule::command('titan:agent:run')->everyFiveMinutes();
#
# =============================================================================
