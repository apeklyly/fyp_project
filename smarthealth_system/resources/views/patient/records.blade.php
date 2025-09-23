<x-app-layout>
    <div class="content-header">
        <h1>My Medical Records</h1>
        <p>Here is a history of all your submitted health data.</p>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Date Submitted</th>
                    <th>Heart Rate</th>
                    <th>Blood Sugar</th>
                    <th>Symptoms</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $record->heart_rate }} bpm</td>
                        <td>{{ $record->blood_sugar }} mg/dL</td>
                        <td>{{ implode(', ', json_decode($record->symptoms)) }}</td>
                        <td>
                            <a href="{{ route('patient.record.show', $record->id) }}" class="btn btn-secondary">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">
                            You have not submitted any medical records yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div style="margin-top: 2rem;">
            {{ $records->links('vendor.pagination.simple-custom') }}
        </div>
    </div>
</x-app-layout>