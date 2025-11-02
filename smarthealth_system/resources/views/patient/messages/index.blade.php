<x-app-layout>
    <div class="content-header">
        <h1>Contact Doctor</h1>
        <p>Select a doctor to view your conversation or start a new one.</p>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>Dr. {{ $doctor->full_name }}</td>
                        <td>
                            <a href="{{ route('patient.messages.show', $doctor) }}" class="btn btn-secondary">
                                Open Chat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">There are no doctors registered in the system.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>