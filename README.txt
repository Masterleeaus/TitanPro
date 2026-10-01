# Deployment note

This repository contains deployment-related material, but host-specific paths, production URLs, and credentials must not be committed to the project.

For a deployment:

1. Follow the reviewed deployment guide in `deployment/`.
2. Use a private inventory for the host, domain, user, and document root.
3. Validate the release artifact and backup/rollback procedure before installation.
4. Do not run shell commands copied from unreviewed archive notes.

The original host path and domain were removed from this file. Review Git history and related bundles for additional operational details that should remain private.
