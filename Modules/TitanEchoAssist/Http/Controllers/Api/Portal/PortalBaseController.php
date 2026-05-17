<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Models\Chatbot;

abstract class PortalBaseController extends Controller
{
    protected function firstExistingTable(array $tables): ?string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    protected function scopedQuery(Chatbot $chatbot, string $table): ?Builder
    {
        if (! Schema::hasTable($table)) {
            return null;
        }

        $query = DB::table($table);

        if (Schema::hasColumn($table, 'company_id') && $chatbot->company_id !== null) {
            $query->where('company_id', (int) $chatbot->company_id);
        }

        if (Schema::hasColumn($table, 'chatbot_id')) {
            $query->where('chatbot_id', $chatbot->getKey());
        }

        return $query;
    }

    protected function companyScopedByCustomer(Chatbot $chatbot, string $table, int $customerId, string $customerKey = 'customer_id'): ?Builder
    {
        $query = $this->scopedQuery($chatbot, $table);

        if ($query === null) {
            return null;
        }

        if (Schema::hasColumn($table, $customerKey)) {
            $query->where($customerKey, $customerId);
        }

        return $query;
    }

    protected function portalChatbot(Request $request, Chatbot $chatbot): Chatbot
    {
        return $request->attributes->get('portalChatbot') instanceof Chatbot
            ? $request->attributes->get('portalChatbot')
            : $chatbot;
    }
}
