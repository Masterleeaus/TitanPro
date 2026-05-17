<?php

namespace Modules\ZeroPayModule\Mail;

class ZeroPaySessionCreatedMail
{
    public function build()
    {
        return $this->subject('Example record created')->view('zeropay-module::mail.created');
    }
}
