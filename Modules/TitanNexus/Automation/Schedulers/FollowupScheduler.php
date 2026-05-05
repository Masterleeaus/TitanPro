<?php
namespace Modules\TitanNexus\Automation\Schedulers;
final class FollowupScheduler { public function cadence(): array { return ['day_3_sms','day_7_email','day_14_call_script']; } }

