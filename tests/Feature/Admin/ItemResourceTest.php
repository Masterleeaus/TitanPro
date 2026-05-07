<?php

use App\Models\Item;
use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function itemOwnerUser(): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    return $user;
}

test('owner sees expanded add-on pricing and visibility options on the create form', function () {
    $this->actingAs(itemOwnerUser())
        ->get('/admin/items/create')
        ->assertOk()
        ->assertSee('Flat')
        ->assertSee('Per m²')
        ->assertSee('Per unit')
        ->assertSee('Attachable Services')
        ->assertSee('Upsell Visibility')
        ->assertSee('m²')
        ->assertDontSee('sq ft')
        ->assertDontSee('sqft')
        ->assertDontSee('square foot')
        ->assertDontSee('square feet');
});

test('owner sees metric add-on pricing labels on list and edit pages', function () {
    $user = itemOwnerUser();
    $item = Item::factory()->create([
        'organization_id' => $user->organization_id,
        'pricing_type' => 'per_sqm',
        'unit' => 'sqm',
    ]);

    $this->actingAs($user)
        ->get('/admin/items')
        ->assertOk()
        ->assertSee('Per m²')
        ->assertDontSee('sq ft')
        ->assertDontSee('sqft');

    $this->actingAs($user)
        ->get("/admin/items/{$item->id}/edit")
        ->assertOk()
        ->assertSee('Per m²')
        ->assertSee('m²')
        ->assertDontSee('sq ft')
        ->assertDontSee('sqft')
        ->assertDontSee('square foot')
        ->assertDontSee('square feet');
});
