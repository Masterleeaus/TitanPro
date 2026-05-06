<?php
namespace Modules\TitanNexus\Mail;
class JobScheduledMail { public string $subject = 'Job scheduled'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::JobScheduledMail'; } }
