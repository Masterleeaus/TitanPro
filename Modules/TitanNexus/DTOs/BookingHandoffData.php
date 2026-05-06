<?php
namespace Modules\TitanNexus\DTOs; final readonly class BookingHandoffData { public function __construct(public array $payload = []) {} public function toArray(): array { return $this->payload; } }
