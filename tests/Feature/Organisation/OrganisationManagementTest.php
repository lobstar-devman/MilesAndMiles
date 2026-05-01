<?php

namespace Tests\Feature\Organisation;

use App\Enums\AccountType;
use App\Enums\OrganisationPermission;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_organisation_management(): void
    {
        $this->get(route('organisation.index'))
            ->assertRedirect(route('login'));
    }

    public function test_individual_users_cannot_access_organisation_management(): void
    {
        $user = User::factory()->create(['account_type' => AccountType::Individual]);

        $this->actingAs($user)
            ->get(route('organisation.index'))
            ->assertForbidden();
    }

    public function test_organisational_users_can_access_organisation_management(): void
    {
        $owner = User::factory()->create(['account_type' => AccountType::Organisational]);
        $organisation = Organisation::factory()->create(['owner_id' => $owner->id]);
        $organisation->members()->attach($owner->id, [
            'permissions' => array_map(fn ($p) => $p->value, OrganisationPermission::cases()),
        ]);

        $this->actingAs($owner)
            ->get(route('organisation.index'))
            ->assertOk()
            ->assertSee($owner->name)
            ->assertSee($owner->email);
    }

    public function test_organisation_management_lists_all_members(): void
    {
        $owner = User::factory()->create(['account_type' => AccountType::Organisational]);
        $member = User::factory()->create(['account_type' => AccountType::Individual]);

        $organisation = Organisation::factory()->create(['owner_id' => $owner->id]);

        $allPermissions = array_map(fn ($p) => $p->value, OrganisationPermission::cases());

        $organisation->members()->attach($owner->id, ['permissions' => $allPermissions]);
        $organisation->members()->attach($member->id, ['permissions' => [OrganisationPermission::SubmitJourneyRecord->value]]);

        $this->actingAs($owner)
            ->get(route('organisation.index'))
            ->assertOk()
            ->assertSee($member->name)
            ->assertSee($member->email)
            ->assertSee(OrganisationPermission::SubmitJourneyRecord->label());
    }

    public function test_organisation_management_shows_empty_state_when_user_has_no_organisation(): void
    {
        $user = User::factory()->create(['account_type' => AccountType::Organisational]);

        $this->actingAs($user)
            ->get(route('organisation.index'))
            ->assertOk()
            ->assertSee('You do not have an organisation.');
    }
}
