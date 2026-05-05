<?php
namespace Modules\TitanNexus\Mail;
class CampaignDigestMail { public string $subject = 'Campaign performance digest'; public function __construct(public array $payload = []) {} public function template(): string { return 'titan-nexus::CampaignDigestMail'; } }
