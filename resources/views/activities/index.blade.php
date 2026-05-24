@extends('layouts.app')

@section('title', 'Activity Timeline')
@section('page-title', 'Activity Timeline')
@section('page-subtitle', 'Semua aktivitas teknis dari GitHub dan manual input')

@section('content')
{{-- Filters --}}
<div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-5 mb-6 ambient-shadow">
    <form method="GET" action="{{ route('activities.index') }}" class="flex flex-wrap items-center gap-3">
        <select name="project_id" id="filter-project"
                class="bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
            <option value="">All Projects</option>
            @foreach($projects as $p)
            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>

        <select name="source" id="filter-source"
                class="bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
            <option value="">All Sources</option>
            <option value="github" {{ request('source') === 'github' ? 'selected' : '' }}>GitHub</option>
            <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>Manual</option>
        </select>

        <select name="event_type" id="filter-event-type"
                class="bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
            <option value="">All Types</option>
            <option value="push"         {{ request('event_type') === 'push'         ? 'selected' : '' }}>Push</option>
            <option value="pull_request" {{ request('event_type') === 'pull_request' ? 'selected' : '' }}>Pull Request</option>
            <option value="issue"        {{ request('event_type') === 'issue'        ? 'selected' : '' }}>Issue</option>
            <option value="error_log"    {{ request('event_type') === 'error_log'    ? 'selected' : '' }}>Error Log</option>
        </select>

        <select name="risk_level" id="filter-risk-level"
                class="bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
            <option value="">All Risk Levels</option>
            <option value="low"      {{ request('risk_level') === 'low'      ? 'selected' : '' }}>Low</option>
            <option value="medium"   {{ request('risk_level') === 'medium'   ? 'selected' : '' }}>Medium</option>
            <option value="high"     {{ request('risk_level') === 'high'     ? 'selected' : '' }}>High</option>
            <option value="critical" {{ request('risk_level') === 'critical' ? 'selected' : '' }}>Critical</option>
        </select>

        <button type="submit" id="btn-apply-filters"
                class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-4 py-2 text-body-sm">
            Filter
        </button>
        @if(request()->hasAny(['project_id', 'source', 'event_type', 'risk_level']))
        <a wire:navigate href="{{ route('activities.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors ml-2">Reset</a>
        @endif
    </form>
</div>

{{-- Activity Table --}}
<div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant/20">
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-6 py-4">Tanggal</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">Project</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">Source</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">Type</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">Title</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">Actor</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">Risk</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4 py-4">AI Summary</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($activities as $activity)
                <tr class="hover:bg-surface-bright transition-colors">
                    <td class="px-6 py-4 text-xs text-on-surface-variant whitespace-nowrap">
                        <div class="font-bold text-on-surface">{{ $activity->created_at->format('d M Y') }}</div>
                        <div class="text-[10px]">{{ $activity->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-4 py-4">
                        <a wire:navigate href="{{ route('projects.show', $activity->project) }}"
                           class="text-xs text-secondary hover:underline font-bold truncate max-w-[120px] block">
                            {{ $activity->project->name }}
                        </a>
                    </td>
                    <td class="px-4 py-4">
                        @php
                            $sourceColor = $activity->source === 'github' ? 'bg-[#ECEFF1] text-[#37474F]' : 'bg-[#F3E5F5] text-[#6A1B9A]';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-label-caps uppercase {{ $sourceColor }}">
                            {{ $activity->sourceLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @php
                            $typeColor = match($activity->event_type) {
                                'push'         => 'bg-[#E3F2FD] text-[#0D47A1]',
                                'pull_request' => 'bg-[#F3E5F5] text-[#6A1B9A]',
                                'issue'        => 'bg-[#FFF3E0] text-[#E65100]',
                                'error_log'    => 'bg-[#FFEBEE] text-[#C62828]',
                                default        => 'bg-surface-container text-on-surface-variant'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-label-caps uppercase {{ $typeColor }}">
                            {{ $activity->eventTypeLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <a wire:navigate href="{{ route('activities.show', $activity) }}"
                           class="text-sm text-on-surface hover:text-secondary transition-colors font-bold line-clamp-1 max-w-[200px] block">
                            {{ $activity->title }}
                        </a>
                    </td>
                    <td class="px-4 py-4 text-xs text-on-surface-variant font-label-code">{{ $activity->actor ?? '—' }}</td>
                    <td class="px-4 py-4">
                        @if($activity->aiSummary)
                            @php
                                $risk = $activity->aiSummary->risk_level;
                                $riskColor = match($risk) {
                                    'low'      => 'bg-[#E8F5E9] text-[#2E7D32]',
                                    'medium'   => 'bg-[#FFF3E0] text-[#E65100]',
                                    'high'     => 'bg-[#FBE9E7] text-[#D84315]',
                                    'critical' => 'bg-[#FFEBEE] text-[#C62828]',
                                    default    => 'bg-surface-container text-on-surface-variant'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-label-caps uppercase {{ $riskColor }}">
                                {{ $risk }}
                            </span>
                        @else
                            <span class="text-xs text-on-surface-variant font-semibold">Pending...</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        @if($activity->aiSummary)
                        <p class="text-xs text-on-surface-variant line-clamp-2 max-w-[220px]">{{ $activity->aiSummary->client_friendly_summary }}</p>
                        @else
                        <span class="text-xs text-on-surface-variant italic">Processing...</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[48px] text-outline mb-2" data-icon="timeline">timeline</span>
                        <p class="text-sm font-semibold">Tidak ada aktivitas ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $activities->links() }}
</div>
@endsection
