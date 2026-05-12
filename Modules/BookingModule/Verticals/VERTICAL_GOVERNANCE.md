# Vertical Governance

Vertical overlays cannot bypass permissions, tenancy, lifecycle Actions or queue idempotency.

- Vertical behavior must be declared in `Config/verticals.php`.
- Vertical packs may change labels/content/default context only.
- Booking workflow transitions, approvals, cancellations, and signal emission remain in shared BookingModule actions/services (no duplicated vertical mutation paths).
