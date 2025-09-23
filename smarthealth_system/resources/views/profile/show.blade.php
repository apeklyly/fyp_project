<x-app-layout>
    <div class="content-header">
        <h1>My Profile</h1>
        <p>Your personal account details.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Profile Information</h2>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>

        <div class="profile-details">
            <div class="detail-item">
                <span class="detail-label">Username</span>
                <span class="detail-value">{{ $user->username }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Full Name</span>
                <span class="detail-value">{{ $user->full_name }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email Address</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Birth Date</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($user->birth_date)->format('F j, Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone Number</span>
                <span class="detail-value">{{ $user->phone_number }}</span>
            </div>
             <div class="detail-item">
                <span class="detail-label">Account Type</span>
                <span class="detail-value">{{ ucfirst($user->role) }}</span>
            </div>
        </div>
    </div>
</x-app-layout>