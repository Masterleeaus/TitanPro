# Issue 360 — Install TitanHello module — inbound/outbound phone calling and call inbox

## Files Changed

- `Modules/TitanHello/**` (new module extracted from `modulelib/sorted/TitanTalk__1_/TitanTalk/TitanHelloBase.zip`)
- `Modules/CallingAgent/Http/Controllers/CallingAgentApiController.php`
- `issue-docs/issue-360.md`

## Fixes Applied

- Installed TitanHello module at `Modules/TitanHello` from the requested source zip.
- Updated TitanHello manifest for panel integration:
  - `active: 1` retained
  - `filament_panel: groundzero` added
  - Filament plugin binding added (`Modules\\TitanHello\\Filament\\Plugin\\TitanHelloPlugin`)
- Ensured module boot/install safety:
  - added migration loading from `Modules/TitanHello/Database/Migrations`
  - removed invalid Auth policy bindings to missing classes
  - fixed webhook controller missing job imports used by recording flow
- Added Filament surfaces under TitanHello:
  - `CallInboxResource` (list + detail view)
  - `OutboundDialerResource` (list + dial action)
- Added real-time call status broadcasting:
  - new `Modules\TitanHello\Events\CallStatusUpdated` (Echo/Pusher compatible)
  - dispatches from call ingest and outbound dial service
  - inbox list page listens on `echo:titanhello.calls,call.status.updated` and refreshes
- Connected CallingAgent human escalation into TitanHello inbox:
  - transfer flow now mirrors escalation events into `titanhello_calls`
  - escalation writes status/outcome/meta and emits TitanHello status broadcast
- Added feature tests for TitanHello:
  - inbound webhook creates a call inbox record
  - outbound dispatch persists outbound call with provider SID

## Next Steps

- Run `php artisan module:migrate TitanHello` in a PHP 8.4+ environment.
- Configure Twilio inbound webhook to `POST /titanhello/webhooks/voice/inbound`.
- Verify Filament GroundZero navigation shows **Call Inbox** and **Outbound Dialer** resources.
- Run targeted tests once Composer dependencies are installable in PHP 8.4+.
