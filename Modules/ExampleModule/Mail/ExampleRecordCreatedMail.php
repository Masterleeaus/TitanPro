<?php

namespace Modules\ExampleModule\Mail;

class ExampleRecordCreatedMail
{
    public function build(){ return $this->subject("Example record created")->view("example-module::mail.created"); }
}
