<?php

namespace Modules\CRMCore\Mail;

use Modules\CRMCore\Models\Deal;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DealConvertedToProjectMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Deal $deal, public Job $project)
    {
    }

    public function build(): self
    {
        return $this->subject('Deal converted to project')
            ->view('crmcore::mail.deal-converted-to-project')
            ->with(['deal' => $this->deal, 'project' => $this->project]);
    }
}
