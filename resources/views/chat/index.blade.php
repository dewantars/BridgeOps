@extends('layouts.app')

@section('title', 'Chat')
@section('page-title', 'Chat')
@section('page-subtitle', 'Komunikasi 2 arah antara tim dan klien')

@section('header-actions')
    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-secondary bg-[#dce9ff] px-3 py-1.5 rounded-full">
        <span class="material-symbols-outlined text-[14px]">chat</span>
        Pesan Proyek
    </span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    @if($projects->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-2xl bg-[#dce9ff] flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[40px] text-secondary">chat_bubble_outline</span>
            </div>
            <h3 class="text-title-md font-semibold text-on-surface mb-2">Belum ada percakapan</h3>
            <p class="text-body-sm text-on-surface-variant max-w-xs">
                Chat proyek akan muncul di sini. Anda akan terhubung dengan tim atau klien terkait setiap proyek.
            </p>
        </div>
    @else
        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-2">Pilih proyek untuk membuka chat</p>

        @foreach($projects as $project)
        @php
            $lastMsg = $project->chatMessages->first();
        @endphp
        <a wire:navigate href="{{ route('chat.show', $project) }}"
           class="flex items-center gap-4 bg-surface-container-lowest border border-outline-variant/30 rounded-xl px-5 py-4 hover:border-secondary/40 hover:shadow-sm transition-all duration-150 group relative">

            {{-- Project Avatar --}}
            <div class="w-12 h-12 rounded-xl bg-secondary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-white text-[22px]">account_tree</span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-sm text-on-surface truncate group-hover:text-secondary transition-colors">
                        {{ $project->name }}
                    </h3>
                    @if($lastMsg)
                    <span class="text-[11px] text-on-surface-variant shrink-0">
                        {{ $lastMsg->created_at->diffForHumans(null, true) }}
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-on-surface-variant truncate">
                        {{ $lastMsg ? \Str::limit($lastMsg->body, 60) : 'Belum ada pesan. Mulai percakapan.' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 mt-1.5">
                    @php
                        $statusColors = [
                            'on_track'  => 'bg-green-100 text-green-700',
                            'at_risk'   => 'bg-yellow-100 text-yellow-700',
                            'blocked'   => 'bg-red-100 text-red-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                        ];
                        $color = $statusColors[$project->status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="text-[10px] font-bold tracking-wider px-2 py-0.5 rounded-full {{ $color }}">
                        {{ $project->statusLabel() }}
                    </span>
                    <span class="text-[10px] text-on-surface-variant">
                        {{ $project->client_name }}
                    </span>
                </div>
            </div>

            {{-- Unread Badge --}}
            @if($project->unread_count > 0)
            <div class="shrink-0 w-6 h-6 rounded-full bg-secondary flex items-center justify-center">
                <span class="text-[11px] font-bold text-white">{{ $project->unread_count > 9 ? '9+' : $project->unread_count }}</span>
            </div>
            @endif
        </a>
        @endforeach
    @endif
</div>
@endsection
