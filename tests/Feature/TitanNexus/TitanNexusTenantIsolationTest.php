<?php

use App\Models\Organization;
use App\Models\TitanNexus\TitanNexusLead;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('TitanNexusLead::all only returns records from the authenticated tenant', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    TitanNexusLead::withoutGlobalScopes()->create([
        'company_id' => $orgA->id,
        'name' => 'A Lead',
        'email' => 'a@example.test',
        'phone' => '+1000000001',
        'status' => 'new',
    ]);

    TitanNexusLead::withoutGlobalScopes()->create([
        'company_id' => $orgB->id,
        'name' => 'B Lead',
        'email' => 'b@example.test',
        'phone' => '+1000000002',
        'status' => 'new',
    ]);

    $userA = User::factory()->create(['organization_id' => $orgA->id]);

    $this->actingAs($userA);

    $leads = TitanNexusLead::all();

    expect($leads)->toHaveCount(1)
        ->and($leads->pluck('company_id')->unique()->all())->toBe([$orgA->id]);
});

test('cross-tenant lead reads are silently filtered to an empty collection', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    TitanNexusLead::withoutGlobalScopes()->create([
        'company_id' => $orgB->id,
        'name' => 'B Lead',
        'email' => 'tenant-b@example.test',
        'phone' => '+1000000011',
        'status' => 'new',
    ]);

    $userA = User::factory()->create(['organization_id' => $orgA->id]);

    $this->actingAs($userA);

    $crossTenantRows = TitanNexusLead::query()->where('company_id', $orgB->id)->get();

    expect($crossTenantRows)->toHaveCount(0);
});

test('cross-tenant lead writes are rejected when company_id is forged', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $this->actingAs($userA);

    expect(fn () => TitanNexusLead::create([
        'company_id' => $orgB->id,
        'name' => 'Forged Lead',
        'email' => 'forged@example.test',
        'phone' => '+1000000022',
        'status' => 'new',
    ]))->toThrow(ValidationException::class);

    expect(TitanNexusLead::withoutGlobalScopes()->where('email', 'forged@example.test')->exists())->toBeFalse();
});
