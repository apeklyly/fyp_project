<x-app-layout>
    <div class="content-header">
        <h1>Doctor Messages</h1>
        <p>View messages and advice sent from your doctor.</p>
    </div>

    <div class="card">
        @forelse ($messages as $message)
            <div class="card" style="margin-bottom: 1.5rem; border-left: 4px solid #34D399;">
                <p><strong>From:</strong> Dr. {{ $message->sender->username }}</p>
                <p><strong>Sent:</strong> {{ $message->created_at->format('F j, Y, g:i a') }}</p>
                <hr style="margin: 1rem 0;">
                <p style="white-space: pre-wrap;">{{ $message->message }}</p>
            </div>
        @empty
            <div class="alert alert-info">
                You have no messages from your doctor yet.
            </div>
        @endforelse
    </div>
</x-app-layout>