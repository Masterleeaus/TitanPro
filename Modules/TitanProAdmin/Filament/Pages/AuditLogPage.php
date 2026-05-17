<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Policies\SuperAdminPolicy;

class AuditLogPage extends Page
{
    private const MIN_LOG_LIMIT = 1;
    private const MAX_LOG_LIMIT = 100;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Audit Log';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'titanproadmin::pages.audit-log';

    public function getTitle(): string
    {
        return 'Audit Log';
    }

    public static function canAccess(): bool
    {
        $user = auth('super_admin')->user() ?? auth()->user();

        return app(SuperAdminPolicy::class)->access($user);
    }

    public function auditLogs(int $limit = 50): Collection
    {
        $safeLimit = max(self::MIN_LOG_LIMIT, min(self::MAX_LOG_LIMIT, $limit));

        return AdminAuditLog::query()->latest('id')->limit($safeLimit)->get();
    }
}
