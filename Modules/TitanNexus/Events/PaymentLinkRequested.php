<?php
namespace Modules\TitanNexus\Events;
class PaymentLinkRequested { public function __construct(public array $payload = []) {} }
