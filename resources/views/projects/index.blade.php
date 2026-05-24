@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')
@section('page-subtitle', 'Daftar semua project yang dikelola')

@section('header-actions')
    @can('manage-projects')
    <a wire:navigate href="{{ route('projects.create') }}" id="btn-create-project"
       class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-4 py-2 text-body-sm">
        <span class="material-symbols-outlined text-[20px]" data-icon="add">add</span>
        New Project
    </a>
    @endcan
@endsection

@section('content')
@if($projects->isEmpty())
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="w-16 h-16 bg-[#dce9ff] rounded-2xl flex items-center justify-center mb-4 text-secondary">
        <span class="material-symbols-outlined text-[32px]">folder</span>
    </div>
    <h3 class="text-on-surface font-semibold mb-2">Belum ada project</h3>
    <p class="text-on-surface-variant text-sm mb-6">Mulai dengan membuat project pertama Anda.</p>
    @can('manage-projects')
    <a wire:navigate href="{{ route('projects.create') }}" class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-6 py-3 text-body-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Buat Project Pertama
    </a>
    @endcan
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach($projects as $project)
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden hover:border-secondary-container transition-all duration-200 group ambient-shadow flex flex-col justify-between">
        <div class="p-5">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1 min-w-0">
                    <a wire:navigate href="{{ route('projects.show', $project) }}" class="block">
                        <h3 class="text-on-surface font-bold text-base group-hover:text-secondary transition-colors truncate">{{ $project->name }}</h3>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $project->client_name }}</p>
                    </a>
                </div>
                @php
                    $statusColor = match($project->status) {
                        'on_track'  => 'bg-[#E8F5E9] text-[#2E7D32]',
                        'at_risk'   => 'bg-[#FFF3E0] text-[#E65100]',
                        'blocked'   => 'bg-[#FFEBEE] text-[#C62828]',
                        'completed' => 'bg-[#E3F2FD] text-[#0D47A1]',
                        default     => 'bg-surface-container text-on-surface-variant'
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold font-label-caps uppercase flex-shrink-0 ml-2 {{ $statusColor }}">
                    {{ $project->statusLabel() }}
                </span>
            </div>

            {{-- Description --}}
            @if($project->description)
            <p class="text-on-surface-variant text-xs line-clamp-2 mb-4">{{ $project->description }}</p>
            @endif

            {{-- GitHub repo --}}
            @if($project->github_repo_name)
            <div class="flex items-center gap-2 mb-4 bg-surface-container-low px-3 py-2 rounded-lg">
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">terminal</span>
                <span class="text-xs text-on-surface-variant font-label-code truncate">{{ $project->github_repo_name }}</span>
            </div>
            @endif

            {{-- Stats --}}
            <div class="flex items-center gap-6 pt-4 border-t border-outline-variant/10">
                <div>
                    <p class="text-lg font-bold text-on-surface">{{ $project->engineering_events_count }}</p>
                    <p class="text-xs text-on-surface-variant">Activities</p>
                </div>
                @if($project->start_date)
                <div>
                    <p class="text-xs font-bold text-on-surface">{{ $project->start_date->format('d M Y') }}</p>
                    <p class="text-xs text-on-surface-variant">Start Date</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between px-5 py-3 bg-surface-container-low border-t border-outline-variant/10">
            <a wire:navigate href="{{ route('projects.show', $project) }}"
               class="text-xs text-secondary hover:underline font-semibold transition-colors">
                View Detail →
            </a>
            @can('manage-projects')
            <div class="flex items-center gap-3">
                <a wire:navigate href="{{ route('projects.edit', $project) }}"
                   class="text-on-surface-variant hover:text-secondary transition-colors flex" title="Edit">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </a>
                <form method="POST" action="{{ route('projects.destroy', $project) }}"
                      onsubmit="return confirm('Hapus project {{ $project->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors flex" title="Delete">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $projects->links() }}
</div>
@endif
@endsection
