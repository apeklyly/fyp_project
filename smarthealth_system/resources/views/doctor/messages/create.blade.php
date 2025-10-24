<x-app-layout>
    <div class="content-header">
        <h1>Send Message</h1>
        <p>Composing a message for {{ $user->full_name }}.</p>
    </div>

    <div class="card">
        <form action="{{ route('doctor.patient.sendMessage', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="message" class="form-label">Message Content</label>
                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Type your advice or message here..."></textarea>
            </div>

            <div class="form-group">
                <label for="attachment" class="form-label">Attach File (Optional)</label>
                <input type="file" id="attachment" name="attachment" class="form-control">
                <small class="form-text">Allowed types: jpg, png, pdf. Max 5MB.</small>
            </div>

            <div style="display:flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Send Message</button>
                <a href="{{ route('doctor.patient.show', $user->id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>