@extends('layouts.app')

@section('title', 'Dashboard')
@section('meta_description', 'Ringkasan aktivitas proyek BridgeOps AI')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan semua aktivitas proyek')

@section('header-actions')
    @can('manage-projects')
    <a href="{{ route('projects.create') }}" id="btn-new-project"
       class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-4 py-2 text-body-sm">
        <span class="material-symbols-outlined text-[20px]" data-icon="add">add</span>
        New Project
    </a>
    @endcan
@endsection

@section('content')
<div class="max-w-container-max mx-auto space-y-gutter">
    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-base md:gap-stack-md">
        <!-- Card 1: Projects -->
        <div class="bg-surface-container-lowest rounded-[16px] p-stack-lg border border-outline-variant/30 ambient-shadow flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between mb-4">
                <span class="font-label-caps text-label-caps text-on-surface-variant">PROJECTS</span>
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[18px]" data-icon="folder">folder</span>
                </div>
            </div>
            <div>
                <div class="font-display-lg text-display-lg text-on-surface mb-1">{{ $totalProjects }}</div>
                <div class="font-body-sm text-body-sm text-on-surface-variant">Total project</div>
            </div>
        </div>
        
        <!-- Card 2: Activities -->
        <div class="bg-surface-container-lowest rounded-[16px] p-stack-lg border border-outline-variant/30 ambient-shadow flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between mb-4">
                <span class="font-label-caps text-label-caps text-on-surface-variant">ACTIVITIES</span>
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[18px]" data-icon="timeline">timeline</span>
                </div>
            </div>
            <div>
                <div class="font-display-lg text-display-lg text-on-surface mb-1">{{ $totalActivities }}</div>
                <div class="font-body-sm text-body-sm text-on-surface-variant">Total aktivitas</div>
            </div>
        </div>
        
        <!-- Card 3: Issues -->
        <div class="bg-surface-container-lowest rounded-[16px] p-stack-lg border border-outline-variant/30 ambient-shadow flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between mb-4">
                <span class="font-label-caps text-label-caps text-on-surface-variant">ISSUES</span>
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[18px]" data-icon="warning">warning</span>
                </div>
            </div>
            <div>
                <div class="font-display-lg text-display-lg text-on-surface mb-1">{{ $totalIssues }}</div>
                <div class="font-body-sm text-body-sm text-on-surface-variant">Total issues</div>
            </div>
        </div>
        
        <!-- Card 4: Error Logs -->
        <div class="bg-surface-container-lowest rounded-[16px] p-stack-lg border border-outline-variant/30 ambient-shadow flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between mb-4">
                <span class="font-label-caps text-label-caps text-on-surface-variant">ERROR LOGS</span>
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[18px]" data-icon="error">error</span>
                </div>
            </div>
            <div>
                <div class="font-display-lg text-display-lg text-on-surface mb-1">{{ $totalErrorLogs }}</div>
                <div class="font-body-sm text-body-sm text-on-surface-variant">Manual error logs</div>
            </div>
        </div>
        
        <!-- Card 5: High Risk -->
        <div class="bg-error-container/30 rounded-[16px] p-stack-lg border border-error/20 ambient-shadow flex flex-col justify-between min-h-[140px] relative overflow-hidden">
            <div class="flex items-start justify-between mb-4 relative z-10">
                <span class="font-label-caps text-label-caps text-error">HIGH RISK</span>
                <div class="w-8 h-8 rounded-full bg-error-container flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-[18px]" data-icon="electric_bolt">electric_bolt</span>
                </div>
            </div>
            <div class="relative z-10">
                <div class="font-display-lg text-display-lg text-error mb-1">{{ $highRiskCount }}</div>
                <div class="font-body-sm text-body-sm text-error/80">High + Critical</div>
            </div>
        </div>
    </div>

    <!-- Main Content Split -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-gutter">
        <!-- Left Column: Recent Activity -->
        <div class="xl:col-span-2 space-y-gutter">
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 ambient-shadow">
                <!-- Card Header -->
                <div class="flex items-center justify-between p-stack-lg border-b border-outline-variant/10">
                    <h3 class="font-title-md text-title-md text-on-surface">Recent Activity</h3>
                    <a class="font-body-sm text-body-sm text-secondary hover:underline" href="{{ route('activities.index') }}">View all →</a>
                </div>
                
                <!-- Activity List -->
                <div class="divide-y divide-outline-variant/10">
                    @forelse($recentActivities as $activity)
                    <a href="{{ route('activities.show', $activity) }}" class="p-stack-lg hover:bg-surface-bright transition-colors flex gap-4 group block">
                        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary shrink-0 mt-1">
                            @if($activity->event_type === 'push')
                                <span class="material-symbols-outlined text-[20px]" data-icon="upload">upload</span>
                            @elseif($activity->event_type === 'pull_request')
                                <span class="material-symbols-outlined text-[20px]" data-icon="merge_type">merge_type</span>
                            @elseif($activity->event_type === 'issue')
                                <span class="material-symbols-outlined text-[20px]" data-icon="warning">warning</span>
                            @else
                                <span class="material-symbols-outlined text-[20px]" data-icon="error">error</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-1">
                                <h4 class="font-body-lg text-body-lg font-bold text-on-surface truncate group-hover:text-secondary transition-colors">{{ $activity->title }}</h4>
                                @if($activity->aiSummary)
                                    @php
                                        $risk = $activity->aiSummary->risk_level;
                                        $badgeClasses = match($risk) {
                                            'low' => 'bg-[#E8F5E9] text-[#2E7D32]',
                                            'medium' => 'bg-[#FFF3E0] text-[#E65100]',
                                            'high' => 'bg-[#FBE9E7] text-[#D84315]',
                                            'critical' => 'bg-[#FFEBEE] text-[#C62828]',
                                            default => 'bg-[#E8F5E9] text-[#2E7D32]'
                                        };
                                    @endphp
                                    <span class="shrink-0 px-2.5 py-1 {{ $badgeClasses }} rounded text-xs font-bold font-label-caps uppercase">{{ $risk }}</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 font-label-code text-label-code text-on-surface-variant mb-2">
                                <span>{{ $activity->project->name }}</span>
                                <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                                <span>{{ $activity->actor }}</span>
                                <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            @if($activity->aiSummary)
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ $activity->aiSummary->client_friendly_summary }}</p>
                            @else
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">Aktivitas sedang berjalan sesuai rencana.</p>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="p-stack-lg text-center py-12">
                        <span class="material-symbols-outlined text-[48px] text-outline mb-2" data-icon="timeline">timeline</span>
                        <p class="text-on-surface-variant text-sm font-semibold">Belum ada aktivitas</p>
                        <p class="text-on-surface-variant text-xs mt-1">Aktivitas akan muncul setelah GitHub webhook terhubung.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Widgets -->
        <div class="xl:col-span-1 space-y-gutter">
            <!-- Widget: Status Project -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 ambient-shadow p-stack-lg">
                <h3 class="font-title-md text-title-md text-on-surface mb-6">Status Project</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#388E3C]"></div>
                            <span class="font-body-lg text-body-lg text-on-surface-variant">On Track</span>
                        </div>
                        <span class="font-title-md text-title-md text-on-surface">{{ $projectsByStatus['on_track'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#F57C00]"></div>
                            <span class="font-body-lg text-body-lg text-on-surface-variant">At Risk</span>
                        </div>
                        <span class="font-title-md text-title-md text-on-surface">{{ $projectsByStatus['at_risk'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#D32F2F]"></div>
                            <span class="font-body-lg text-body-lg text-on-surface-variant">Blocked</span>
                        </div>
                        <span class="font-title-md text-title-md text-on-surface">{{ $projectsByStatus['blocked'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#1976D2]"></div>
                            <span class="font-body-lg text-body-lg text-on-surface-variant">Completed</span>
                        </div>
                        <span class="font-title-md text-title-md text-on-surface">{{ $projectsByStatus['completed'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Widget: Quick Actions -->
            @can('manage-projects')
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 ambient-shadow p-stack-lg">
                <h3 class="font-title-md text-title-md text-on-surface mb-6">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('projects.create') }}" class="w-full flex items-center gap-3 p-4 rounded-xl bg-surface hover:bg-surface-container transition-colors border border-outline-variant/20 text-left group block">
                        <div class="w-8 h-8 rounded bg-surface-container-highest flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-on-secondary transition-colors">
                            <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
                        </div>
                        <span class="font-body-lg text-body-lg font-medium text-on-surface">Buat Project Baru</span>
                    </a>
                    <a href="{{ route('manual-errors.create') }}" class="w-full flex items-center gap-3 p-4 rounded-xl bg-surface hover:bg-surface-container transition-colors border border-outline-variant/20 text-left group block">
                        <div class="w-8 h-8 rounded bg-error-container/50 flex items-center justify-center text-error group-hover:bg-error group-hover:text-on-error transition-colors">
                            <span class="material-symbols-outlined text-[18px]" data-icon="error">error</span>
                        </div>
                        <span class="font-body-lg text-body-lg font-medium text-on-surface">Log Error Manual</span>
                    </a>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
