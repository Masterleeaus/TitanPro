<?php
namespace Modules\TitanNexus\Mail;
class PaymentReceiptMail { public string $subject = 'Payment received'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::PaymentReceiptMail'; } }
