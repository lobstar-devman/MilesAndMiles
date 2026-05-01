<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Enums\AccountType;
use App\Enums\OrganisationPermission;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'account_type' => ['required', Rule::enum(AccountType::class)],
            'organisation_name' => [
                Rule::when(
                    ($input['account_type'] ?? '') === AccountType::Organisational->value,
                    ['required', 'string', 'max:255']
                ),
            ],
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'account_type' => $input['account_type'],
            ]);

            if ($input['account_type'] === AccountType::Organisational->value) {
                $organisation = Organisation::create([
                    'name' => $input['organisation_name'],
                    'owner_id' => $user->id,
                ]);

                $allPermissions = array_map(
                    fn (OrganisationPermission $p) => $p->value,
                    OrganisationPermission::cases()
                );

                $organisation->members()->attach($user->id, [
                    'permissions' => $allPermissions,
                ]);
            }

            return $user;
        });
    }
}
