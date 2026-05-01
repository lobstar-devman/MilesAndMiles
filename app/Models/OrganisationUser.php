<?php

namespace App\Models;

use App\Enums\OrganisationPermission;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table(incrementing: true)]
class OrganisationUser extends Pivot
{
    protected $table = 'organisation_users';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    /** @return OrganisationPermission[] */
    public function permissionEnums(): array
    {
        return array_map(
            fn (string $value) => OrganisationPermission::from($value),
            $this->permissions ?? []
        );
    }
}
