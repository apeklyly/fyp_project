<x-app-layout>
    <div class="content-header">
        <h1>Chat with {{ $patient->full_name }}</h1>
    </div>

    <div class="chat-container">
        @forelse($messages as $message)
            <x-chat-bubble :message="$message" />
        @empty
            <div class="chat-bubble-empty">
                <p>This is the start of your conversation. Send a message to begin.</p>
            </div>
        @endforelse
    </div>

    <form action="{{ route('doctor.messages.store', $patient) }}" method="POST" enctype="multipart/form-data" class="chat-reply-form">
    @csrf
    
    <label for="attachment" class="chat-attachment-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Z"></path></svg>
        <input type="file" name="attachment" id="attachment" style="display: none;">
    </label>
    
    <textarea name="message" rows="1" placeholder="Type your message..."></textarea>
    
    <button type="submit" class="chat-send-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M232,128a16,16,0,0,1-7.39,13.85l-168,96a16,16,0,0,1-23.1-1.94,15.86,15.86,0,0,1-1.92-23.13L56,136H120a8,8,0,0,0,0-16H56L31.59,43.22a16,16,0,0,1,25-21.19l168,96A16,16,0,0,1,232,128Z"></path></svg>
    </button>
</form>
</x-app-layout>