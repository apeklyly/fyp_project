<x-app-layout>
    <div class="content-header">
        <h1>Doctor Messages</h1>
        <p>A record of all messages from your doctor.</p>
    </div>

    <div class="chat-container">
        @forelse($messages as $message)
            <div class="chat-bubble">
                <div class="chat-sender">
                    Dr. {{ $message->sender->full_name }}
                </div>
                <div class="chat-content">
                    @if($message->message)
                        <p>{{ $message->message }}</p>
                    @endif

                    @if($message->file_path)
                        @php
                            $path = $message->file_path;
                            $extension = pathinfo($path, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp

                        @if($isImage)
                            <div class="attachment-image">
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" title="Click to view full size">
                                    <img src="{{ asset('storage/' . $path) }}" alt="Attached Image">
                                </a>
                            </div>
                        @else
                            <div class="attachment-file">
                                <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Z"></path></svg>
                                    Download Attached File ({{ strtoupper($extension) }})
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="chat-timestamp">
                    {{ $message->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
        @empty
            <div class="card">
                <p>You have no messages from your doctor yet.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>