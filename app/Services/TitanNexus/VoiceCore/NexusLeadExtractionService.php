<?php

namespace App\Services\TitanNexus\VoiceCore;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NexusLeadExtractionService
{
    public function extractFromCall(array $payload): ?int
    {
        if (! Schema::hasTable('titan_nexus_leads')) {
            return null;
        }

        $phone = $this->cleanPhone(Arr::get($payload, 'from_number') ?: Arr::get($payload, 'phone') ?: Arr::get($payload, 'customer.number'));

        if (! $phone) {
            return null;
        }

        $existing = DB::table('titan_nexus_leads')->where('phone', $phone)->value('id');

        if ($existing) {
            return (int) $existing;
        }

        return (int) DB::table('titan_nexus_leads')->insertGetId([
            'name' => Arr::get($payload, 'name') ?: 'Voice Lead',
            'phone' => $phone,
            'email' => Arr::get($payload, 'email'),
            'company' => Arr::get($payload, 'company'),
            'status' => config('titannexus_voice_core.lead_extraction.default_status', 'new'),
            'score' => 0,
            'payload' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function cleanPhone(?string $phone): ?string
    {
        if (! is_string($phone) || trim($phone) === '') {
            return null;
        }

        $digits = preg_replace('/[^0-9+]/', '', $phone);
        $min = config('titannexus_voice_core.lead_extraction.minimum_phone_digits', 8);

        return strlen(preg_replace('/\D/', '', $digits)) >= $min ? $digits : null;
    }
}
