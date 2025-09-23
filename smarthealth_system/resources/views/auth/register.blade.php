<x-guest-layout>
    <h2 style="text-align: center; font-size: 1.8rem; color: #f4f7f6; margin-bottom: 2rem;">Create Your Account</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input id="username" class="form-control" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
        </div>

        <div class="form-group">
            <label for="full_name" class="form-label">Full Name</label>
            <input id="full_name" class="form-control" type="text" name="full_name" :value="old('full_name')" required autocomplete="name" />
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="email" />
        </div>

         <div class="form-group">
            <label for="birth_date" class="form-label">Birth Date</label>
            <input id="birth_date" class="form-control" type="date" name="birth_date" :value="old('birth_date')" required />
        </div>

         <div class="form-group">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input id="phone_number" class="form-control" type="tel" name="phone_number" :value="old('phone_number')" required autocomplete="tel" />
        </div>

        <div class="form-group">
            <label for="role" class="form-label">Register as</label>
            <select id="role" name="role" class="form-select" required>
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Already registered?
            </a>

            <button type="submit" class="btn btn-primary" style="margin-left: 1rem;">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>