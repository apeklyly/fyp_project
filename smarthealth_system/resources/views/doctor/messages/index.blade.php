<x-app-layout>
    <div class="content-header">
        <h1>Patient Messages</h1>
        <p>Select a patient to view your conversation.</p>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->full_name }}</td>
                        <td>
                            <a href="{{ route('doctor.messages.show', $patient) }}" class="btn btn-secondary">
                                Open Chat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">You have no conversations.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>