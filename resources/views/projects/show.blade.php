@extends('layouts.app')

@section('title', $project->name)
@section('page-title', $project->name)
@section('page-subtitle', 'Client: ' . $project->client_name)

@section('header-actions')
    @can('manage-projects')
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('reports.generate', $project) }}" id="form-generate-report">
            @csrf
            <button type="submit" id="btn-generate-report"
                    class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-4 py-2 text-body-sm">
                <span class="material-symbols-outlined text-[20px]">description</span>
                Generate AI Report
            </button>
        </form>
        <a href="{{ route('projects.edit', $project) }}" id="btn-edit-project"
           class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-4 py-2 rounded-lg hover:bg-surface-container transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">edit</span>
            Edit
        </a>
    </div>
    @endcan
@endsection

@section('content')
{{-- Project Header Info --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Project Info --}}
    <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-on-surface font-bold text-lg">{{ $project->name }}</h2>
                <p class="text-on-surface-variant text-sm mt-1">{{ $project->client_name }}</p>
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
            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold font-label-caps uppercase {{ $statusColor }}">
                {{ $project->statusLabel() }}
            </span>
        </div>
        @if($project->description)
        <p class="text-on-surface-variant text-sm mb-4">{{ $project->description }}</p>
        @endif
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm pt-4 border-t border-outline-variant/10">
            @if($project->github_repo_name)
            <div class="flex items-center gap-2 text-on-surface-variant">
                <span class="material-symbols-outlined text-[16px]">terminal</span>
                <a href="{{ $project->repository_url }}" target="_blank" class="hover:text-secondary font-label-code text-xs font-semibold">{{ $project->github_repo_name }}</a>
            </div>
            @endif
            @if($project->start_date)
            <div class="text-on-surface-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                <span>{{ $project->start_date->format('d M Y') }} — {{ $project->end_date?->format('d M Y') ?? 'Ongoing' }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-4 text-center ambient-shadow flex flex-col justify-center">
            <p class="text-2xl font-bold text-on-surface">{{ $stats['total_activities'] }}</p>
            <p class="text-xs text-on-surface-variant mt-1">Activities</p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-4 text-center ambient-shadow flex flex-col justify-center">
            <p class="text-2xl font-bold text-[#E65100]">{{ $stats['open_issues'] }}</p>
            <p class="text-xs text-on-surface-variant mt-1">Open Issues</p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-4 text-center ambient-shadow flex flex-col justify-center">
            <p class="text-2xl font-bold text-[#D84315]">{{ $stats['error_logs'] }}</p>
            <p class="text-xs text-on-surface-variant mt-1">Error Logs</p>
        </div>
        <div class="bg-error-container/30 border border-error/20 rounded-2xl p-4 text-center ambient-shadow flex flex-col justify-center">
            <p class="text-2xl font-bold text-error">{{ $stats['high_risk'] }}</p>
            <p class="text-xs text-error/80 mt-1">High Risk</p>
        </div>
    </div>
</div>

{{-- Activity Timeline --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        {{-- Recent Activities --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/10">
                <h3 class="text-sm font-semibold text-on-surface">Activity Timeline</h3>
                <a href="{{ route('activities.index', ['project_id' => $project->id]) }}"
                   class="text-xs text-secondary hover:underline">View all →</a>
            </div>
            <div class="divide-y divide-outline-variant/10">
                @forelse($project->engineeringEvents as $event)
                <a href="{{ route('activities.show', $event) }}"
                   class="flex items-start gap-4 px-6 py-4 hover:bg-surface-bright transition-colors group block">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5
                        {{ $event->event_type === 'push' ? 'bg-[#E3F2FD] text-secondary' : '' }}
                        {{ $event->event_type === 'pull_request' ? 'bg-[#F3E5F5] text-purple-600' : '' }}
                        {{ $event->event_type === 'issue' ? 'bg-[#FFF3E0] text-amber-600' : '' }}
                        {{ $event->event_type === 'error_log' ? 'bg-[#FFEBEE] text-error' : '' }}
                    ">
                        @if($event->event_type === 'push')
                            <span class="material-symbols-outlined text-[18px]">upload</span>
                        @elseif($event->event_type === 'pull_request')
                            <span class="material-symbols-outlined text-[18px]">merge_type</span>
                        @elseif($event->event_type === 'issue')
                            <span class="material-symbols-outlined text-[18px]">warning</span>
                        @else
                            <span class="material-symbols-outlined text-[18px]">error</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-on-surface font-bold truncate group-hover:text-secondary transition-colors">{{ $event->title }}</p>
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-1 font-label-code text-label-code text-on-surface-variant">
                            <span>{{ $event->eventTypeLabel() }}</span>
                            <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                            <span>{{ $event->actor }}</span>
                            <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                            <span>{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                        @if($event->aiSummary)
                        <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-2">{{ $event->aiSummary->client_friendly_summary }}</p>
                        @else
                        <span class="text-xs text-on-surface-variant mt-1.5 italic">AI summary pending...</span>
                        @endif
                    </div>
                    @if($event->aiSummary)
                        @php
                            $risk = $event->aiSummary->risk_level;
                            $badgeColor = match($risk) {
                                'low' => 'bg-[#E8F5E9] text-[#2E7D32]',
                                'medium' => 'bg-[#FFF3E0] text-[#E65100]',
                                'high' => 'bg-[#FBE9E7] text-[#D84315]',
                                'critical' => 'bg-[#FFEBEE] text-[#C62828]',
                                default => 'bg-surface-container text-on-surface-variant'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold font-label-caps uppercase flex-shrink-0 {{ $badgeColor }}">{{ $risk }}</span>
                    @endif
                </a>
                @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-on-surface-variant text-sm">Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Error Logs --}}
        @if($project->manualErrorLogs->isNotEmpty())
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
            <div class="px-6 py-4 border-b border-outline-variant/10">
                <h3 class="text-sm font-semibold text-on-surface">Error Logs</h3>
            </div>
            <div class="divide-y divide-outline-variant/10">
                @foreach($project->manualErrorLogs as $log)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm text-on-surface font-bold">{{ $log->title }}</p>
                        @php
                            $severityColor = match($log->severity) {
                                'low' => 'bg-[#E8F5E9] text-[#2E7D32]',
                                'medium' => 'bg-[#FFF3E0] text-[#E65100]',
                                'high' => 'bg-[#FBE9E7] text-[#D84315]',
                                'critical' => 'bg-[#FFEBEE] text-[#C62828]',
                                default => 'bg-surface-container text-on-surface-variant'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold font-label-caps uppercase {{ $severityColor }}">{{ $log->severityLabel() }}</span>
                    </div>
                    <p class="text-xs text-on-surface-variant">{{ $log->environment }} · {{ $log->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Right Sidebar --}}
    <div class="space-y-4">

        {{-- Team Members Card --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/10">
                <h3 class="text-sm font-semibold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-secondary">group</span>
                    Anggota Tim
                </h3>
                <span class="text-xs text-on-surface-variant">{{ $project->members->count() }} orang</span>
            </div>

            {{-- Member List --}}
            <div class="divide-y divide-outline-variant/10">
                @forelse($project->members as $member)
                <div class="flex items-center gap-3 px-4 py-3">
                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 font-bold text-xs text-white
                        {{ $member->role === 'client' ? 'bg-[#4CAF50]' : 'bg-secondary' }}">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-on-surface truncate">{{ $member->name }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $member->email }}</p>
                    </div>
                    {{-- Role Badge --}}
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full
                        {{ $member->role === 'client' ? 'bg-green-100 text-green-700' : 'bg-[#dce9ff] text-secondary' }}">
                        {{ $member->role }}
                    </span>
                    {{-- Remove Button (Admin/PM only, can't remove yourself if last manager) --}}
                    @can('manage-projects')
                    <form method="POST" action="{{ route('project-members.destroy', [$project, $member]) }}"
                          onsubmit="return confirm('Hapus {{ $member->name }} dari proyek ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-on-surface-variant/40 hover:text-error transition-colors ml-1"
                                title="Hapus dari proyek">
                            <span class="material-symbols-outlined text-[16px]">person_remove</span>
                        </button>
                    </form>
                    @endcan
                </div>
                @empty
                <p class="text-xs text-on-surface-variant text-center py-4">Belum ada anggota</p>
                @endforelse
            </div>

            {{-- Add Member Form (Admin/PM only) --}}
            @can('manage-projects')
            @if($nonMembers->isNotEmpty())
            <div class="px-4 py-4 border-t border-outline-variant/10 bg-surface">
                <p class="text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Tambah Anggota</p>
                <form method="POST" action="{{ route('project-members.store', $project) }}"
                      class="flex items-center gap-2">
                    @csrf
                    <select name="user_id"
                            class="flex-1 text-xs border border-outline-variant/40 bg-[#f0f5ff] rounded-lg px-3 py-2 text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary/30">
                        <option value="">Pilih pengguna...</option>
                        @foreach($nonMembers as $u)
                        <option value="{{ $u->id }}">
                            {{ $u->name }} ({{ strtoupper($u->role) }})
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="shrink-0 bg-secondary text-white rounded-lg px-3 py-2 text-xs font-semibold hover:bg-secondary/80 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">person_add</span>
                        Tambah
                    </button>
                </form>
            </div>
            @else
            <div class="px-4 py-3 border-t border-outline-variant/10 bg-surface text-center">
                <p class="text-[11px] text-on-surface-variant">Semua pengguna sudah menjadi anggota</p>
            </div>
            @endif
            @endcan
        </div>

        {{-- Chat Quick Access --}}
        <a href="{{ route('chat.show', $project) }}"
           class="flex items-center gap-3 bg-[#dce9ff] border border-secondary/20 rounded-2xl px-5 py-4 hover:bg-[#c5d8ff] transition-colors group">
            <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-white text-[20px]">chat</span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-secondary group-hover:underline">Buka Chat Proyek</p>
                <p class="text-xs text-secondary/70">Komunikasi langsung dengan tim</p>
            </div>
            <span class="material-symbols-outlined text-secondary text-[18px]">arrow_forward</span>
        </a>

        {{-- Reports --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/10">
                <h3 class="text-sm font-semibold text-on-surface">Reports</h3>
                <a href="{{ route('reports.index', $project) }}" class="text-xs text-secondary hover:underline">All →</a>
            </div>
            <div class="p-4 space-y-2">
                @forelse($project->reports as $report)
                <a href="{{ route('reports.show', [$project, $report]) }}"
                   class="flex items-center gap-3 p-3 bg-surface hover:bg-surface-container border border-outline-variant/20 rounded-xl transition-colors group block">
                    <div class="w-8 h-8 bg-[#dce9ff] rounded flex items-center justify-center flex-shrink-0 text-secondary">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-on-surface font-bold truncate group-hover:text-secondary transition-colors">{{ $report->title }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $report->created_at->format('d M Y') }}</p>
                    </div>
                </a>
                @empty
                <p class="text-xs text-on-surface-variant text-center py-3">Belum ada report</p>
                @endforelse
            </div>
        </div>

        {{-- GitHub Issues --}}
        @if($issueEvents->isNotEmpty())
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
            <div class="px-6 py-4 border-b border-outline-variant/10">
                <h3 class="text-sm font-semibold text-on-surface">GitHub Issues</h3>
            </div>
            <div class="divide-y divide-outline-variant/10">
                @foreach($issueEvents as $issue)
                <div class="px-4 py-3">
                    <p class="text-xs text-on-surface font-semibold line-clamp-1">{{ $issue->title }}</p>
                    <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $issue->actor }} · {{ $issue->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
