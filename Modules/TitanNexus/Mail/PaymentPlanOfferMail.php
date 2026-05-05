<?php
namespace Modules\TitanNexus\Mail;
class PaymentPlanOfferMail { public string $subject = 'Payment plan option'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::PaymentPlanOfferMail'; } }
