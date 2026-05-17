<?php

namespace Modules\ZeroPayModule\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\ZeroPayModule\Actions\ExpireZeroPaySessionAction;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPayExpireSessionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zeropay:expire-sessions
                            {--dry-run : List sessions that would be expired without changing them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire ZeroPay sessions that have passed their expires_at timestamp.';

    public function handle(ExpireZeroPaySessionAction $expireAction): int
    {
        $expirable = ZeroPaySession::whereIn('status', [
            ZeroPaySession::STATUS_PENDING,
            ZeroPaySession::STATUS_OPENED,
        ])
            ->where('expires_at', '<=', Carbon::now())
            ->get();

        if ($expirable->isEmpty()) {
            $this->info('No sessions to expire.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'Token', 'Status', 'Expires At'],
                $expirable->map(fn ($s) => [$s->id, $s->session_token, $s->status, $s->expires_at])
            );
            $this->info("Dry-run: {$expirable->count()} session(s) would be expired.");

            return self::SUCCESS;
        }

        $expired = 0;

        foreach ($expirable as $session) {
            try {
                $expireAction->execute($session);
                $expired++;
            } catch (\Throwable $e) {
                $this->warn("Failed to expire session {$session->session_token}: {$e->getMessage()}");
            }
        }

        $this->info("Expired {$expired} session(s).");

        return self::SUCCESS;
    }
}
