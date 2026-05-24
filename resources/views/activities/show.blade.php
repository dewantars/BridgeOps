@extends('layouts.app')

@section('title', 'Activity: ' . $event->title)
@section('page-title', 'Activity Detail')
@section('page-subtitle', $event->project->name . ' · ' . $event->eventTypeLabel())

@section('header-actions')
    <a href="{{ route('activities.index') }}"
       class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-4 py-2 rounded-lg hover:bg-surface-container transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Back to Timeline
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Event Info --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-on-surface font-bold text-lg">{{ $event->title }}</h2>
                <div class="flex items-center gap-2 mt-2">
                    @php
                        $sourceColor = $event->source === 'github' ? 'bg-[#ECEFF1] text-[#37474F]' : 'bg-[#F3E5F5] text-[#6A1B9A]';
                        $typeColor = match($event->event_type) {
                            'push'         => 'bg-[#E3F2FD] text-[#0D47A1]',
                            'pull_request' => 'bg-[#F3E5F5] text-[#6A1B9A]',
                            'issue'        => 'bg-[#FFF3E0] text-[#E65100]',
                            'error_log'    => 'bg-[#FFEBEE] text-[#C62828]',
                            default        => 'bg-surface-container text-on-surface-variant'
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-label-caps uppercase {{ $sourceColor }}">
                        {{ $event->sourceLabel() }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-label-caps uppercase {{ $typeColor }}">
                        {{ $event->eventTypeLabel() }}
                    </span>
                </div>
            </div>
            @if($event->aiSummary)
                @php
                    $risk = $event->aiSummary->risk_level;
                    $riskColor = match($risk) {
                        'low'      => 'bg-[#E8F5E9] text-[#2E7D32]',
                        'medium'   => 'bg-[#FFF3E0] text-[#E65100]',
                        'high'     => 'bg-[#FBE9E7] text-[#D84315]',
                        'critical' => 'bg-[#FFEBEE] text-[#C62828]',
                        default    => 'bg-surface-container text-on-surface-variant'
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold font-label-caps uppercase {{ $riskColor }}">{{ $event->aiSummary->riskLabel() }}</span>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm border-t border-outline-variant/10 pt-4">
            <div>
                <p class="text-on-surface-variant text-xs mb-0.5">Project</p>
                <a href="{{ route('projects.show', $event->project) }}" class="text-secondary hover:underline font-bold text-sm">{{ $event->project->name }}</a>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs mb-0.5">Actor</p>
                <p class="text-on-surface font-bold text-sm">{{ $event->actor ?? '—' }}</p>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs mb-0.5">Branch</p>
                <p class="text-on-surface font-bold text-sm">{{ $event->branch_name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs mb-0.5">Time</p>
                <p class="text-on-surface font-bold text-sm">{{ $event->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        @if($event->description)
        <div class="mt-4 pt-4 border-t border-outline-variant/10">
            <p class="text-on-surface-variant text-xs mb-2">Description</p>
            <pre class="text-on-surface text-sm whitespace-pre-wrap font-label-code bg-surface-container-low rounded-lg p-4 border border-outline-variant/10">{{ $event->description }}</pre>
        </div>
        @endif

        @if($event->github_url)
        <div class="mt-4">
            <a href="{{ $event->github_url }}" target="_blank"
               class="inline-flex items-center gap-2 text-sm text-secondary hover:underline font-semibold">
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                View on GitHub
            </a>
        </div>
        @endif
    </div>

    {{-- AI Summary --}}
    @if($event->aiSummary)
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-outline-variant/10 bg-[#f0f5ff]/50">
            <div class="w-7 h-7 bg-[#dce9ff] rounded-lg flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-[18px]">psychology</span>
            </div>
            <h3 class="text-sm font-bold text-on-surface">BridgeOps AI Analysis</h3>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1.5 font-label-caps">Client Summary</p>
                <p class="text-on-surface font-semibold text-sm">{{ $event->aiSummary->client_friendly_summary }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 font-label-caps">Business Summary</p>
                    <p class="text-on-surface text-sm">{{ $event->aiSummary->business_summary }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 font-label-caps">Technical Summary</p>
                    <p class="text-on-surface text-sm">{{ $event->aiSummary->technical_summary }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-outline-variant/10">
                <div>
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 font-label-caps">Business Impact</p>
                    <p class="text-on-surface text-sm">{{ $event->aiSummary->business_impact }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 font-label-caps">Recommended Action</p>
                    <p class="text-on-surface text-sm">{{ $event->aiSummary->recommended_action }}</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 text-center ambient-shadow">
        <div class="w-10 h-10 bg-surface-container rounded-xl flex items-center justify-center mx-auto mb-3 text-secondary">
            <span class="material-symbols-outlined text-[24px] animate-spin">sync</span>
        </div>
        <p class="text-on-surface-variant text-sm font-medium">AI Summary sedang diproses...</p>
        <p class="text-on-surface-variant text-xs mt-1">Pastikan queue worker berjalan: <code class="text-secondary bg-surface-container px-1.5 py-0.5 rounded font-label-code">php artisan queue:work</code></p>
    </div>
    @endif
</div>
@endsection
