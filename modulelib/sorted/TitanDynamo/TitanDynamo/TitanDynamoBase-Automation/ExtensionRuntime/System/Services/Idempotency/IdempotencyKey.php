<?php
namespace App\Extensions\TitanPulse\System\Services\Idempotency;
class IdempotencyKey {
    public static function forSignal(int $teamId, int $ruleId, int $signalId): string { return sprintf('team:%d|rule:%d|signal:%d',$teamId,$ruleId,$signalId); }
    public static function forSweep(int $teamId, int $ruleId, string $subject, string $bucket): string { return sprintf('team:%d|rule:%d|subject:%s|bucket:%s',$teamId,$ruleId,$subject,$bucket); }
}
