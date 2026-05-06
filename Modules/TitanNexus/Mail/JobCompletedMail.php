<?php
namespace Modules\TitanNexus\Mail;
class JobCompletedMail { public string $subject = 'Job complete'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::JobCompletedMail'; } }
