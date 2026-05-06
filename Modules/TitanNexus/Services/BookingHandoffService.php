<?php

namespace Modules\TitanNexus\Services;

/** Builds booking payloads for dispatch. */
class BookingHandoffService
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Builds booking payloads for dispatch.'];
    }
}
