<x-guest-layout>
  <!-- Session Status -->
  <x-auth-session-status
    class="mb-4"
    :status="session('status')"
  />

  <form
    method="POST"
    action="{{ route('login') }}"
  >
    @csrf

    <!-- Email Address -->
    <div>
      <x-input-label
        for="email"
        :value="__('Email')"
      />
      <x-text-input
        id="email"
        class="mt-1 block w-full"
        type="email"
        name="email"
        :value="old('email')"
        required
        autofocus
        autocomplete="username"
      />
      <x-input-error
        :messages="$errors->get('email')"
        class="mt-2"
      />
    </div>

    <!-- Password -->
    <div class="mt-4">
      <x-input-label
        for="password"
        :value="__('Password')"
      />

      <x-text-input
        id="password"
        class="mt-1 block w-full"
        type="password"
        name="password"
        required
        autocomplete="current-password"
      />

      <x-input-error
        :messages="$errors->get('password')"
        class="mt-2"
      />
    </div>

    <!-- Remember Me -->
    <div class="mt-4 block">
      <label
        for="remember_me"
        class="inline-flex items-center"
      >
        <input
          id="remember_me"
          type="checkbox"
          class="border-pocket-gray-300 text-pocket-teal-600 shadow-xs focus:ring-pocket-teal-500 rounded-sm"
          name="remember"
        >
        <span class="text-pocket-gray-500 ms-2 text-sm">{{ __('Remember me') }}</span>
      </label>
    </div>

    <div class="mt-4 flex items-center justify-end">
      @if (Route::has('password.request'))
        <a
          class="text-pocket-gray-500 hover:text-pocket-dark-900 focus:outline-hidden focus:ring-pocket-teal-500 rounded-md text-sm underline focus:ring-2 focus:ring-offset-2"
          href="{{ route('password.request') }}"
        >
          {{ __('Forgot your password?') }}
        </a>
      @endif

      <x-primary-button class="ms-3">
        {{ __('Log in') }}
      </x-primary-button>
    </div>
  </form>
</x-guest-layout>
