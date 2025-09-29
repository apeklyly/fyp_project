<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 style="text-align: center; font-size: 1.8rem; color: #f4f7f6; margin-bottom: 2rem;">Login to Your Account</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

       

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            <div>
                 <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                    New user? Register
                </a>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Log in
            </button>
        </div>
    </form>
</x-guest-layout>