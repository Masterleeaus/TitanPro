<?php

namespace Modules\TitanNexus\Mail\Voice;

use Illuminate\Mail\Mailable;

class VoiceLeadCapturedMail extends Mailable
{
    public function __construct(public array $lead) {}

    public function build()
    {
        return $this->subject('New voice lead captured')->view('titannexus::mail.voice-lead-captured');
    }
}
