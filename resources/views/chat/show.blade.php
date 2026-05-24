@extends('layouts.app')

@section('title', 'Chat — ' . $project->name)
@section('page-title', $project->name)
@section('page-subtitle', 'Chat Proyek · ' . $project->client_name)

@section('header-actions')
    <a href="{{ route('chat.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant hover:text-secondary border border-outline-variant/40 hover:border-secondary/40 px-3 py-1.5 rounded-lg transition-colors">
        <span class="material-symbols-outlined text-[14px]">arrow_back</span>
        Semua Chat
    </a>
    <a href="{{ route('projects.show', $project) }}"
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-secondary bg-[#dce9ff] hover:bg-[#c5d8ff] px-3 py-1.5 rounded-lg transition-colors">
        <span class="material-symbols-outlined text-[14px]">account_tree</span>
        Detail Proyek
    </a>
@endsection

@section('content')
{{-- Remove default padding so chat fills height properly --}}
@push('body-class', 'p-0')

<div class="flex flex-col h-full" style="max-height: calc(100vh - 70px);">

    {{-- ─── Messages Area ─────────────────────────────────── --}}
    <div id="chat-messages"
         class="flex-1 overflow-y-auto px-4 md:px-8 py-6 space-y-4 bg-[#f8f9ff]">

        @if($messages->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-[#dce9ff] flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[32px] text-secondary">chat_bubble_outline</span>
            </div>
            <p class="text-sm text-on-surface-variant font-medium">Belum ada pesan. Mulai percakapan!</p>
        </div>
        @endif

        @foreach($messages as $msg)
        @php
            $isOwn   = $msg->sender_id === auth()->id();
            $isStaff = in_array($msg->sender_role, ['admin', 'pm']);
        @endphp

        <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }} gap-3 items-end">

            {{-- Avatar (other party) --}}
            @unless($isOwn)
            <div class="w-8 h-8 rounded-full {{ $isStaff ? 'bg-secondary' : 'bg-[#4CAF50]' }} flex items-center justify-center shrink-0 mb-0.5">
                <span class="text-white text-xs font-bold">
                    {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                </span>
            </div>
            @endunless

            {{-- Bubble --}}
            <div class="flex flex-col {{ $isOwn ? 'items-end' : 'items-start' }} max-w-[75%] md:max-w-[60%]">

                {{-- Sender name (only for messages from others) --}}
                @unless($isOwn)
                <span class="text-[11px] font-semibold text-on-surface-variant mb-1 px-1">
                    {{ $msg->sender->name }}
                    <span class="font-normal opacity-70">· {{ strtoupper($msg->sender_role) }}</span>
                </span>
                @endunless

                <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                    {{ $isOwn
                        ? 'bg-secondary text-white rounded-br-sm'
                        : 'bg-white border border-outline-variant/30 text-on-surface rounded-bl-sm shadow-sm' }}">
                    {{ $msg->body }}
                </div>

                <span class="text-[10px] text-on-surface-variant mt-1 px-1">
                    {{ $msg->created_at->format('d M Y, H:i') }}
                    @if($isOwn && $msg->is_read)
                        <span class="ml-1 text-secondary">✓✓</span>
                    @elseif($isOwn)
                        <span class="ml-1 opacity-50">✓</span>
                    @endif
                </span>
            </div>

            {{-- Avatar (own) --}}
            @if($isOwn)
            <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center shrink-0 mb-0.5">
                <span class="text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- ─── Message Input Bar ──────────────────────────────── --}}
    <div class="shrink-0 border-t border-outline-variant/20 bg-surface-container-lowest px-4 md:px-8 py-4">
        @if($errors->any())
        <div class="mb-3 text-xs text-red-600 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px]">error</span>
            {{ $errors->first('body') }}
        </div>
        @endif

        <form method="POST" action="{{ route('chat.store', $project) }}" id="chat-form"
              class="flex items-end gap-3">
            @csrf
            <div class="flex-1 relative">
                <textarea
                    id="chat-input"
                    name="body"
                    rows="1"
                    placeholder="Tulis pesan..."
                    maxlength="5000"
                    class="w-full resize-none rounded-xl border border-outline-variant/40 bg-[#f0f5ff] px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all max-h-36 overflow-y-auto"
                ></textarea>
            </div>
            <button type="submit"
                    id="chat-send-btn"
                    class="shrink-0 w-11 h-11 rounded-xl bg-secondary text-white flex items-center justify-center hover:bg-secondary/80 active:scale-95 transition-all duration-150 shadow-sm disabled:opacity-50">
                <span class="material-symbols-outlined text-[20px]">send</span>
            </button>
        </form>
        <p class="text-[10px] text-on-surface-variant mt-2 text-right">
            Tekan <kbd class="bg-outline-variant/20 px-1 py-0.5 rounded text-[10px]">Enter</kbd> untuk baris baru,
            <kbd class="bg-outline-variant/20 px-1 py-0.5 rounded text-[10px]">Ctrl+Enter</kbd> untuk kirim
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatMessages = document.getElementById('chat-messages');
    const chatInput    = document.getElementById('chat-input');
    const chatForm     = document.getElementById('chat-form');
    const sendBtn      = document.getElementById('chat-send-btn');

    // Auto-scroll to bottom on page load
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto-resize textarea as user types
    if (chatInput) {
        chatInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 144) + 'px';
            sendBtn.disabled = this.value.trim() === '';
        });

        // Ctrl+Enter to send
        chatInput.addEventListener('keydown', function (e) {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim() !== '') {
                    chatForm.submit();
                }
            }
        });

        // Initially disable send button if empty
        sendBtn.disabled = chatInput.value.trim() === '';

        // Re-check on input
        chatInput.addEventListener('input', function () {
            sendBtn.disabled = this.value.trim() === '';
        });

        // Focus input on load
        chatInput.focus();
    }
});
</script>
@endsection
