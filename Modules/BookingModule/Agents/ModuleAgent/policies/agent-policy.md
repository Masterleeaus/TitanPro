# BookingModule Agent Policy

The BookingModuleAgent is internal-only and routed by TitanEchoAssist/TitanZero.

- Never cross company boundaries.
- Reads require BookingModule policy or permission.
- Draft operations must not persist data.
- Write operations must call module Actions and require confirmation.
- Destructive operations require elevated permission and explicit confirmation.
