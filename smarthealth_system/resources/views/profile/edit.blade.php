<x-app-layout>
    <div class="content-header">
        <h1>Edit Profile</h1>
        <p>Update your account details and password.</p>
    </div>

    <!-- Profile Details Form -->
    <div class="card">
        <h2>Profile Information</h2>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $user->username) }}" required>
            </div>
            <div class="form-group">
                <label for="full_name" class="form-label">Full Name</label>
                <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label for="birth_date" class="form-label">Birth Date</label>
                <input id="birth_date" name="birth_date" type="date" class="form-control" value="{{ old('birth_date', $user->birth_date) }}" required>
            </div>
            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input id="phone_number" name="phone_number" type="tel" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
            </div>

            <div style="display:flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    
</x-app-layout>