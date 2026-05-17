<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Tools;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;

class ToolRegistry
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function all(): array
    {
        return [
            'booking.availability' => $this->tool('booking.availability', 'Check available booking slots for the requested service window.', 'read', ['day_hint' => 'string|null', 'time_hint' => 'string|null'], false),
            'booking.create' => $this->tool('booking.create', 'Create a booking after qualification details are complete.', 'write', ['customer_id' => 'int|null', 'service' => 'string|null', 'day_hint' => 'string|null', 'time_hint' => 'string|null'], true),
            'booking.lookup' => $this->tool('booking.lookup', 'Lookup an existing booking.', 'read', ['reference' => 'string|null'], false),
            'booking.reschedule' => $this->tool('booking.reschedule', 'Move an existing booking to a new time.', 'write', ['reference' => 'string|null', 'day_hint' => 'string|null', 'time_hint' => 'string|null'], true),
            'booking.cancel' => $this->tool('booking.cancel', 'Cancel an existing booking.', 'write', ['reference' => 'string|null'], true),
            'lead.create' => $this->tool('lead.create', 'Create a new lead from the conversation.', 'write', ['name' => 'string|null', 'phone' => 'string|null', 'intent' => 'string'], false),
            'quote.prepare' => $this->tool('quote.prepare', 'Prepare a quote request record for human review.', 'write', ['customer_id' => 'int|null', 'scope' => 'string|null'], false),
            'customer.lookup' => $this->tool('customer.lookup', 'Search the CRM for an existing customer.', 'read', ['phone' => 'string|null', 'email' => 'string|null', 'name' => 'string|null'], false),
            'ticket.create' => $this->tool('ticket.create', 'Create a support ticket from the conversation.', 'write', ['subject' => 'string|null', 'message' => 'string'], false),
            'ticket.reply' => $this->tool('ticket.reply', 'Add a support response to an existing ticket.', 'write', ['ticket_id' => 'int|null', 'message' => 'string'], true),
            'knowledge.search' => $this->tool('knowledge.search', 'Search company knowledge and SOP documents.', 'read', ['query' => 'string'], false),
            'service.issue.log' => $this->tool('service.issue.log', 'Log a service recovery issue for follow-up.', 'write', ['message' => 'string', 'severity' => 'string|null'], false),
            'invoice.lookup' => $this->tool('invoice.lookup', 'Lookup a customer invoice or payment status.', 'read', ['invoice_reference' => 'string|null', 'customer_id' => 'int|null'], false),
            'invoice.send_copy' => $this->tool('invoice.send_copy', 'Send or queue a copy of an invoice.', 'write', ['invoice_reference' => 'string|null', 'customer_id' => 'int|null'], true),
            'payment.status' => $this->tool('payment.status', 'Check payment status for a customer invoice.', 'read', ['invoice_reference' => 'string|null'], false),
            'human.handoff' => $this->tool('human.handoff', 'Escalate the conversation to a human operator.', 'write', ['reason' => 'string'], false),
        ];
    }

    /**
     * @return list<array<string,mixed>>
     */
    public function forIntent(ConversationIntent $intent): array
    {
        $map = match ($intent) {
            ConversationIntent::BOOKING => ['booking.availability', 'lead.create', 'booking.create'],
            ConversationIntent::QUOTE => ['lead.create', 'quote.prepare', 'customer.lookup'],
            ConversationIntent::SUPPORT => ['ticket.create', 'knowledge.search', 'ticket.reply'],
            ConversationIntent::COMPLAINT => ['ticket.create', 'service.issue.log', 'human.handoff'],
            ConversationIntent::INVOICE => ['invoice.lookup', 'invoice.send_copy', 'payment.status'],
            ConversationIntent::RESCHEDULE => ['booking.lookup', 'booking.reschedule'],
            ConversationIntent::CANCEL => ['booking.lookup', 'booking.cancel'],
            ConversationIntent::HUMAN_HANDOFF => ['human.handoff'],
            ConversationIntent::GENERAL => ['knowledge.search', 'customer.lookup'],
        };

        $all = $this->all();

        return array_values(array_filter(array_map(static fn (string $name): ?array => $all[$name] ?? null, $map)));
    }

    /**
     * @param array<string,string> $parameters
     * @return array<string,mixed>
     */
    protected function tool(string $name, string $description, string $mode, array $parameters, bool $approvalRequired): array
    {
        return [
            'name' => $name,
            'description' => $description,
            'mode' => $mode,
            'parameters' => $parameters,
            'approval_required' => $approvalRequired,
        ];
    }
}
