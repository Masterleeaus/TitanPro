<?php
namespace Modules\Accountings\Events;
class ZeroPayPaymentConfirmed { public function __construct(public array $payload = []) {} }
