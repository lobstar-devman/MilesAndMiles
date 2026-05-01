<?php

namespace Tests\Feature\Auth;

use App\Enums\AccountType;
use App\Enums\OrganisationPermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::registration());
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_individual_users_can_register(): void
    {
        $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'account_type' => 'individual',
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertEquals(AccountType::Individual, $user->account_type);
        $this->assertCount(0, $user->ownedOrganisations);
    }

    public function test_organisational_users_can_register_and_organisation_is_created(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'account_type' => 'organisational',
            'organisation_name' => 'Acme Corp',
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $user = User::where('email', 'jane@example.com')->firstOrFail();
        $this->assertEquals(AccountType::Organisational, $user->account_type);

        $organisation = $user->ownedOrganisations()->first();
        $this->assertNotNull($organisation);
        $this->assertEquals('Acme Corp', $organisation->name);

        $pivot = $organisation->members()->where('user_id', $user->id)->first()?->pivot;
        $this->assertNotNull($pivot);

        $grantedPermissions = array_map(fn ($p) => $p->value, $pivot->permissionEnums());
        $allPermissions = array_map(fn ($p) => $p->value, OrganisationPermission::cases());
        $this->assertEqualsCanonicalizing($allPermissions, $grantedPermissions);
    }

    public function test_organisational_registration_requires_organisation_name(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'account_type' => 'organisational',
        ])->assertSessionHasErrors(['organisation_name']);
    }

    public function test_registration_requires_account_type(): void
    {
        $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors(['account_type']);
    }
}
