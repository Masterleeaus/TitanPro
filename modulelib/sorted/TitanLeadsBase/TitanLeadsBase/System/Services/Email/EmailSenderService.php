<?php

namespace App\Extensions\TitanLeads\System\Services\Email;

use App\Extensions\TitanLeads\System\Models\EmailChannel;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailSenderService
{
    public ?EmailChannel $emailChannel = null;

    public function setEmailChannel(int $userId): self
    {
        $this->emailChannel = EmailChannel::query()->where('user_id', $userId)->where('is_active', true)->first();
        return $this;
    }

    public function send(string $to, string $subject, string $body): array
    {
        if (!$this->emailChannel) {
            throw new Exception('Email channel not configured for this user');
        }

        // Optional per-user SMTP override (kept simple for MVP)
        if ($this->emailChannel->smtp_host && $this->emailChannel->smtp_port) {
            Config::set('mail.mailers.smtp.host', $this->emailChannel->smtp_host);
            Config::set('mail.mailers.smtp.port', $this->emailChannel->smtp_port);
            Config::set('mail.mailers.smtp.username', $this->emailChannel->smtp_username);
            Config::set('mail.mailers.smtp.password', $this->emailChannel->smtp_password);
            Config::set('mail.mailers.smtp.encryption', $this->emailChannel->smtp_encryption);
            Config::set('mail.default', 'smtp');
        }

        $fromEmail = $this->emailChannel->from_email ?? config('mail.from.address');
        $fromName  = $this->emailChannel->from_name ?? config('mail.from.name');

        Mail::raw($body, function ($message) use ($to, $subject, $fromEmail, $fromName) {
            $message->to($to)->subject($subject);
            if ($fromEmail) {
                $message->from($fromEmail, $fromName);
            }
        });

        return ['status' => true];
    }
}
