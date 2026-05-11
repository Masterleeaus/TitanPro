<?php

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function titanQuotesOwnerSetup(): array
{
    (new RolesAndPermissionsSeeder)->run();

    $organization = Organization::factory()->create();
    $owner = User::factory()->create(['organization_id' => $organization->id]);
    $owner->assignRole('owner');
    $customer = Customer::factory()->create(['organization_id' => $organization->id]);

    return [$owner, $customer];
}

test('owner sees estimates list and create and send surfaces in the titanquotes panel', function () {
    [$owner, $customer] = titanQuotesOwnerSetup();

    $estimate = Estimate::factory()->forCustomer($customer)->draft()->create();

    $this->actingAs($owner)
        ->get('/titanquotes/estimates')
        ->assertOk();

    $this->actingAs($owner)
        ->get('/titanquotes/estimates/create')
        ->assertOk();

    $this->actingAs($owner)
        ->get("/titanquotes/estimates/{$estimate->id}/edit")
        ->assertOk()
        ->assertSee('Send Quote');
});

test('quote pipeline dashboard shows draft sent accepted and converted counts', function () {
    [$owner, $customer] = titanQuotesOwnerSetup();

    Estimate::factory()->forCustomer($customer)->draft()->create();
    Estimate::factory()->forCustomer($customer)->sent()->create();
    Estimate::factory()->forCustomer($customer)->accepted()->create();
    $converted = Estimate::factory()->forCustomer($customer)->accepted()->create();

    Job::factory()->forCustomer($customer)->create([
        'estimate_id' => $converted->id,
    ]);

    $this->actingAs($owner)
        ->get('/titanquotes/quote-pipeline')
        ->assertOk()
        ->assertSee('Draft')
        ->assertSee('Sent')
        ->assertSee('Accepted')
        ->assertSee('Converted')
        ->assertSee('1');
});

test('customers resource is read-only in the titanquotes panel', function () {
    [$owner, $customer] = titanQuotesOwnerSetup();

    $this->actingAs($owner)
        ->get('/titanquotes/customers')
        ->assertOk()
        ->assertSee($customer->last_name);

    $this->actingAs($owner)
        ->get('/titanquotes/customers/create')
        ->assertNotFound();

    $this->actingAs($owner)
        ->get("/titanquotes/customers/{$customer->id}/edit")
        ->assertNotFound();
});
