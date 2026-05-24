@extends('layouts.app')

@section('title', $report->title)
@section('page-title', 'AI Project Report')
@section('page-subtitle', $project->name . ' · ' . $report->created_at->format('d M Y'))

@section('header-actions')
    <a wire:navigate href="{{ route('reports.index', $project) }}"
       class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-4 py-2 rounded-lg hover:bg-surface-container transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        All Reports
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    {{-- Report Header --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-[#dce9ff] rounded-lg flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                    </div>
                    <span class="text-xs font-bold text-secondary uppercase tracking-widest font-label-caps">BridgeOps AI Report</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface">{{ $report->title }}</h2>
                <p class="text-on-surface-variant text-sm mt-1">{{ $project->name }} · {{ $project->client_name }}</p>
            </div>
            @if(isset($report->content['overall_risk_level']))
                @php
                    $risk = $report->content['overall_risk_level'];
                    $riskColor = match($risk) {
                        'low'      => 'bg-[#E8F5E9] text-[#2E7D32]',
                        'medium'   => 'bg-[#FFF3E0] text-[#E65100]',
                        'high'     => 'bg-[#FBE9E7] text-[#D84315]',
                        'critical' => 'bg-[#FFEBEE] text-[#C62828]',
                        default    => 'bg-surface-container text-on-surface-variant'
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1.5 rounded text-xs font-bold font-label-caps uppercase {{ $riskColor }}">Overall: {{ $risk }} Risk</span>
            @endif
        </div>
        <div class="flex items-center gap-4 text-xs text-on-surface-variant mt-4 pt-4 border-t border-outline-variant/10">
            <span>Generated: {{ $report->created_at->format('d M Y, H:i') }}</span>
            <span class="text-outline-variant">·</span>
            <span>By {{ $report->generatedBy->name }}</span>
        </div>
    </div>

    {{-- Progress Summary --}}
    @if(isset($report->content['progress_summary']))
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <h3 class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
            <div class="w-5 h-5 bg-[#dce9ff] rounded flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
            </div>
            Ringkasan Progress
        </h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">{{ $report->content['progress_summary'] }}</p>
    </div>
    @endif

    {{-- Completed Work --}}
    @if(isset($report->content['completed_work']) && count($report->content['completed_work']) > 0)
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <h3 class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
            <div class="w-5 h-5 bg-[#E8F5E9] rounded flex items-center justify-center text-[#2E7D32]">
                <span class="material-symbols-outlined text-[14px]">task_alt</span>
            </div>
            Pekerjaan Selesai
        </h3>
        <ul class="space-y-2">
            @foreach($report->content['completed_work'] as $item)
            <li class="flex items-start gap-3 text-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-[#2E7D32] text-[18px] mt-0.5 flex-shrink-0">check_circle</span>
                <span class="text-on-surface">{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Issues & Risks --}}
    @if(isset($report->content['issues_and_risks']) && count($report->content['issues_and_risks']) > 0)
    <div class="bg-surface-container-lowest border border-amber-900/10 rounded-2xl p-6 ambient-shadow">
        <h3 class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
            <div class="w-5 h-5 bg-[#FFF3E0] rounded flex items-center justify-center text-[#E65100]">
                <span class="material-symbols-outlined text-[14px]">warning</span>
            </div>
            Masalah & Risiko
        </h3>
        <ul class="space-y-2">
            @foreach($report->content['issues_and_risks'] as $item)
            <li class="flex items-start gap-3 text-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-[#E65100] text-[18px] mt-0.5 flex-shrink-0">error</span>
                <span class="text-on-surface">{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Business Impact --}}
    @if(isset($report->content['business_impact']))
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <h3 class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
            <div class="w-5 h-5 bg-[#F3E5F5] rounded flex items-center justify-center text-[#6A1B9A]">
                <span class="material-symbols-outlined text-[14px]">insights</span>
            </div>
            Dampak Bisnis
        </h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">{{ $report->content['business_impact'] }}</p>
    </div>
    @endif

    {{-- Recommended Actions --}}
    @if(isset($report->content['recommended_actions']) && count($report->content['recommended_actions']) > 0)
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow">
        <h3 class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
            <div class="w-5 h-5 bg-[#dce9ff] rounded flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-[14px]">recommend</span>
            </div>
            Rekomendasi Tindakan
        </h3>
        <ul class="space-y-2">
            @foreach($report->content['recommended_actions'] as $index => $item)
            <li class="flex items-start gap-3 text-sm text-on-surface-variant">
                <span class="w-5 h-5 rounded-full bg-[#dce9ff] border border-secondary/20 flex items-center justify-center flex-shrink-0 text-[10px] font-bold text-secondary mt-0.5">{{ $index + 1 }}</span>
                <span class="text-on-surface">{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
