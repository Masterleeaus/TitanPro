<?php
namespace Modules\TitanNexus\Mail;
class JobStatusUpdateMail { public string $subject = 'Job status update'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::JobStatusUpdateMail'; } }
