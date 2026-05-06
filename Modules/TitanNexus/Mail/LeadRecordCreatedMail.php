<?php

namespace Modules\TitanNexus\Mail;

class LeadRecordCreatedMail
{
    public function build(){ return $this->subject("Example record created")->view("titan-nexus::mail.created"); }
}
