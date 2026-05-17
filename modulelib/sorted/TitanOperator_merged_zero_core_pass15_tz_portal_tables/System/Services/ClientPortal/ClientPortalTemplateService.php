<?php

namespace App\Extensions\TitanOperator\System\Services\ClientPortal;

use Illuminate\Support\Collection;

class ClientPortalTemplateService
{
    public function templates(): Collection
    {
        return collect([
            [
                'slug' => 'support-desk',
                'title' => 'Support Desk',
                'summary' => 'Best for website chat, FAQs, help articles, and human handoff.',
                'channels' => ['web', 'email', 'messenger'],
                'training' => ['pdf', 'website', 'qa'],
                'recommended_step' => 'train',
                'accent' => 'Support-first',
            ],
            [
                'slug' => 'booking-assistant',
                'title' => 'Booking Assistant',
                'summary' => 'Best for appointment capture, quote intake, and routing to operators.',
                'channels' => ['web', 'whatsapp', 'telegram'],
                'training' => ['website', 'text', 'qa'],
                'recommended_step' => 'configure',
                'accent' => 'Lead capture',
            ],
            [
                'slug' => 'client-portal',
                'title' => 'Client Portal',
                'summary' => 'Best for logged-in customer messaging, ticket updates, and self-service.',
                'channels' => ['web', 'portal'],
                'training' => ['pdf', 'text', 'qa'],
                'recommended_step' => 'customize',
                'accent' => 'Portal-first',
            ],
            [
                'slug' => 'lead-capture',
                'title' => 'Lead Capture',
                'summary' => 'Best for lightweight embed widgets, contact capture, and nurture flows.',
                'channels' => ['web', 'messenger'],
                'training' => ['website', 'text'],
                'recommended_step' => 'embed',
                'accent' => 'Widget-first',
            ],
        ]);
    }

    public function selectedTemplate(?string $slug = null): array
    {
        $selected = $this->templates()->firstWhere('slug', $slug ?: 'client-portal');

        return $selected ?: $this->templates()->first();
    }
}
