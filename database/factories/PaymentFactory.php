<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'invoice_id'      => Invoice::factory(),
            'recorded_by'     => null,
            'amount'          => fake()->randomFloat(2, 10, 1000),
            'method'          => Payment::METHOD_CASH,
            'reference'       => null,
            'status'          => 'completed',
            'paid_at'         => now(),
            'notes'           => null,
        ];
    }

    /**
     * Create a payment scoped to an existing invoice (and its org).
     */
    public function forInvoice(Invoice $invoice): static
    {
        return $this->state([
            'organization_id' => $invoice->organization_id,
            'invoice_id'      => $invoice->id,
        ]);
    }
}
