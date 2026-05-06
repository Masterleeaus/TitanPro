<?php
namespace Modules\TitanNexus\Events;
class PaymentFailed { public function __construct(public array $payload = []) {} }
