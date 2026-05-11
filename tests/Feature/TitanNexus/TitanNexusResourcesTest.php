<?php

use App\Models\LeadPipelineEntry;
use App\Models\MarketingCampaign;
use App\Models\Organization;
use App\Models\TrainingContentModule;
use App\Models\User;
use App\Models\VerticalPack;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

function titanNexusUser(string $role): array
{
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole($role);

    return [$user, $organization];
}

dataset('titannexus_resources', [
    'verticals' => [VerticalPack::class, '/titannexus/verticals'],
    'lead pipeline' => [LeadPipelineEntry::class, '/titannexus/lead-pipeline'],
    'training content' => [TrainingContentModule::class, '/titannexus/training-content'],
    'marketing campaigns' => [MarketingCampaign::class, '/titannexus/marketing-campaigns'],
]);

dataset('titannexus_roles', ['owner', 'admin']);

test('owner and admin can list, create, view, and edit each TitanNexus resource', function (string $role, string $modelClass, string $basePath) {
    [$user, $organization] = titanNexusUser($role);

    /** @var \Illuminate\Database\Eloquent\Model $record */
    $record = $modelClass::factory()->create(['organization_id' => $organization->id]);

    $this->actingAs($user)->get($basePath)->assertOk();
    $this->actingAs($user)->get("{$basePath}/create")->assertOk();
    $this->actingAs($user)->get("{$basePath}/{$record->id}")->assertOk();
    $this->actingAs($user)->get("{$basePath}/{$record->id}/edit")->assertOk();
})->with('titannexus_roles')->with('titannexus_resources');

test('super admin is denied access to TitanNexus resources', function (string $modelClass, string $basePath) {
    [$user] = titanNexusUser('super_admin');

    $this->actingAs($user)->get($basePath)->assertForbidden();
})->with('titannexus_resources');

test('owner cannot view or edit cross-org TitanNexus resource records', function (string $modelClass, string $basePath) {
    [$user] = titanNexusUser('owner');
    $otherOrg = Organization::factory()->create();

    /** @var \Illuminate\Database\Eloquent\Model $otherRecord */
    $otherRecord = $modelClass::factory()->create(['organization_id' => $otherOrg->id]);

    $this->actingAs($user)->get("{$basePath}/{$otherRecord->id}")->assertNotFound();
    $this->actingAs($user)->get("{$basePath}/{$otherRecord->id}/edit")->assertNotFound();
})->with('titannexus_resources');
