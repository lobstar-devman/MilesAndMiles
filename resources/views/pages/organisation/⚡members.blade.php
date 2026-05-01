<?php

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Organisation Management')] class extends Component
{
    #[Computed]
    public function organisation(): ?Organisation
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->ownedOrganisations()->with('members')->first();
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
    <div>
        <flux:heading size="xl">{{ __('Organisation Management') }}</flux:heading>
        <flux:text class="mt-1">{{ __('View and manage members in your organisation.') }}</flux:text>
    </div>

    @if (! $this->organisation)
        <flux:callout variant="warning" icon="information-circle" heading="{{ __('No Organisation') }}">
            {{ __('You do not have an organisation.') }}
        </flux:callout>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-zinc-500 uppercase dark:text-zinc-400">
                            {{ __('Name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-zinc-500 uppercase dark:text-zinc-400">
                            {{ __('Email') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-zinc-500 uppercase dark:text-zinc-400">
                            {{ __('Permissions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse ($this->organisation->members as $member)
                        <tr wire:key="{{ $member->id }}">
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-zinc-900 dark:text-zinc-100">
                                {{ $member->name }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                                {{ $member->email }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($member->pivot->permissionEnums() as $permission)
                                        <flux:badge color="zinc" size="sm">{{ $permission->label() }}</flux:badge>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-zinc-500">
                                {{ __('No members found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
