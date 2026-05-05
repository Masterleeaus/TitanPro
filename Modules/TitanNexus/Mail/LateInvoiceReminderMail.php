<?php
namespace Modules\TitanNexus\Mail;
class LateInvoiceReminderMail { public string $subject = 'Payment reminder'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::LateInvoiceReminderMail'; } }
