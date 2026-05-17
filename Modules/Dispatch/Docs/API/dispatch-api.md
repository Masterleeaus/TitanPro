# Dispatch API

Base prefix: `/api/dispatch/v1`

## Recommend technicians

`POST /recommend-technicians`

Payload keys: `company_id`, `starts_at`, `service_zone_id`, `skill_ids`.

## Schedule job

`POST /schedule`

Payload keys: `work_order_id`, `technician_id`, `starts_at`, `ends_at`, `shift_id`, `notes`, `dispatch_notes`.

## Update assignment status

`PATCH /assignments/{assignment}/status`

Payload keys: `status`, `notes`.

## Build route

`POST /routes/build`

Payload keys: `technician_id`, `route_date`, `appointment_ids`, `name`, `company_id`.

## Resequence route

`POST /routes/{route}/resequence`

Reorders stops by planned arrival and recalculates fallback travel estimates.
