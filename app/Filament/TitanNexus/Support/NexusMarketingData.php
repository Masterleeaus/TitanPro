<?php

namespace App\Filament\TitanNexus\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait NexusMarketingData
{
    protected function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function countRows(string $table): int
    {
        if (! $this->tableExists($table)) {
            return 0;
        }

        try {
            return (int) DB::table($table)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    protected function latestRows(string $table, array $columns = ['id', 'name', 'created_at'], int $limit = 8): array
    {
        if (! $this->tableExists($table)) {
            return [];
        }

        try {
            $available = Schema::getColumnListing($table);
            $selected = array_values(array_intersect($columns, $available));

            if ($selected === []) {
                $selected = array_slice($available, 0, 4);
            }

            $query = DB::table($table)->select($selected);

            if (in_array('created_at', $available, true)) {
                $query->orderByDesc('created_at');
            } elseif (in_array('id', $available, true)) {
                $query->orderByDesc('id');
            }

            return $query->limit($limit)->get()->map(fn ($row) => (array) $row)->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function sampleRows(string $table, array $columns, int $limit = 8): array
    {
        return $this->latestRows($table, $columns, $limit);
    }

    protected function marketingStats(): array
    {
        return [
            ['label' => 'Contacts', 'value' => $this->countRows('ext_contacts'), 'hint' => 'Prospects and decision makers'],
            ['label' => 'Segments', 'value' => $this->countRows('ext_segments'), 'hint' => 'Vertical and targeting lists'],
            ['label' => 'Contact Lists', 'value' => $this->countRows('ext_contact_lists'), 'hint' => 'Reusable outreach lists'],
            ['label' => 'Campaigns', 'value' => $this->countRows('ext_marketing_campaigns'), 'hint' => 'WhatsApp, Telegram and offer campaigns'],
            ['label' => 'Conversations', 'value' => $this->countRows('ext_marketing_conversations'), 'hint' => 'Inbox threads from outreach'],
            ['label' => 'Messages', 'value' => $this->countRows('ext_marketing_message_histories'), 'hint' => 'Sent and received follow-ups'],
            ['label' => 'WhatsApp Channels', 'value' => $this->countRows('ext_whatsapp_channels'), 'hint' => 'Configured WhatsApp senders'],
            ['label' => 'Telegram Bots', 'value' => $this->countRows('ext_telegram_bots'), 'hint' => 'Configured Telegram bots'],
        ];
    }

    public function dashboardCards(): array
    {
        return $this->marketingStats();
    }

    public function rowsFor(string $table, array $columns = ['id', 'name', 'status', 'created_at']): array
    {
        return $this->sampleRows($table, $columns);
    }

    public function installedMarketingTables(): array
    {
        return collect([
            'ext_contacts',
            'ext_segments',
            'ext_contact_lists',
            'ext_marketing_campaigns',
            'ext_marketing_conversations',
            'ext_marketing_message_histories',
            'ext_whatsapp_channels',
            'ext_telegram_bots',
            'ext_telegram_groups',
            'ext_telegram_contacts',
            'ext_telegram_group_subscribers',
        ])->map(fn (string $table) => [
            'name' => $table,
            'ready' => $this->tableExists($table),
            'rows' => $this->countRows($table),
        ])->all();
    }
}
