# Security Workflows

The upgraded module defines explicit workflow states for:

- Goods in/out permits
- Work permits
- Access card requests

The canonical JSON definition lives at `Workflows/Definitions/security.json`. Action classes in `Actions/Approvals` provide reusable approval and validation transitions while legacy controllers remain intact.
