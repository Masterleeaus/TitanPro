# Security incident: committed development credentials

**Discovered:** 2026-10-02  
**Affected repository:** TitanPro  
**Affected path:** `.env.development`  
**Current tree:** The tracked file has been removed. A credential-free `.env.development.template` has been added.

## Exposure

The committed development environment file contained non-empty values for an application key, database password, Reverb application identifiers/secrets, and an externally hosted URL. The file was present in Git history, so deleting it from the current branch does not erase the historical copies.

Secret values are intentionally not reproduced here.

## Required response

1. Rotate or revoke the exposed database credential and Reverb credentials.
2. Generate a new Laravel `APP_KEY` and assess whether encrypted local data or sessions require invalidation.
3. Confirm whether the external host and database are active; inspect access logs for the exposure window.
4. Review forks, clones, build logs, cached artifacts, and open PRs for copies.
5. Decide whether a coordinated Git history rewrite is warranted. If performed, communicate the rewrite and require collaborators to reclone or carefully reset.
6. Add secret scanning to CI and confirm `.env*` ignore rules cover local variants while allowing the committed `.env.example`.
7. Re-scan repository history after remediation and record verification without placing secret values in logs.

This document records the exposure and work performed in the repository only. It does not assert that credentials have been rotated or that the historical exposure has been fully contained.
