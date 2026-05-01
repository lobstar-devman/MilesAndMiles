<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form
            method="POST"
            action="{{ route('register.store') }}"
            class="flex flex-col gap-6"
            x-data="{ accountType: '{{ old('account_type', 'individual') }}' }"
        >
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <!-- Account Type -->
            <flux:radio.group name="account_type" :label="__('Account type')" x-model="accountType">
                <flux:radio value="individual" :label="__('Individual')" :description="__('Personal use — record your own journeys.')" />
                <flux:radio value="organisational" :label="__('Organisational')" :description="__('Business use — manage journeys across your organisation.')" />
            </flux:radio.group>

            <!-- Organisation Name (shown only for organisational accounts) -->
            <div x-show="accountType === 'organisational'" x-cloak>
                <flux:input
                    name="organisation_name"
                    :label="__('Organisation name')"
                    :value="old('organisation_name')"
                    type="text"
                    autocomplete="organization"
                    :placeholder="__('Your company or organisation name')"
                />
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
