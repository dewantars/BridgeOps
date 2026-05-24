@extends('layouts.app')

@section('title', 'Reports — ' . $project->name)
@section('page-title', 'AI Reports')
@section('page-subtitle', $project->name)

@section('header-actions')
    <div class="flex items-center gap-3">
        <a wire:navigate href="{{ route('projects.show', $project) }}"
           class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-4 py-2 rounded-lg hover:bg-surface-container transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Project
        </a>
        @can('manage-projects')
        <form method="POST" action="{{ route('reports.generate', $project) }}">
            @csrf
            <button type="submit" id="btn-generate-new-report"
                    class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-4 py-2 text-body-sm">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Generate New Report
            </button>
        </form>
        @endcan
    </div>
@endsection

@section('content')
<div class="space-y-4">
    @forelse($reports as $report)
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden hover:border-secondary-container transition-all duration-200 ambient-shadow">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#dce9ff] rounded-xl flex items-center justify-center flex-shrink-0 text-secondary">
                    <span class="material-symbols-outlined text-[22px]">description</span>
                </div>
                <div>
                    <h3 class="text-on-surface font-bold text-sm">{{ $report->title }}</h3>
                    <div class="flex items-center gap-2 mt-0.5 text-xs text-on-surface-variant">
                        <span>{{ $report->created_at->format('d M Y H:i') }}</span>
                        <span class="text-outline-variant">·</span>
                        <span>By {{ $report->generatedBy->name }}</span>
                        @if(isset($report->content['overall_risk_level']))
                        <span class="text-outline-variant">·</span>
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
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-label-caps uppercase {{ $riskColor }}">
                            {{ $risk }} Risk
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <a wire:navigate href="{{ route('reports.show', [$project, $report]) }}"
               class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-4 py-2 rounded-lg hover:bg-surface-container transition-colors font-semibold">
                View Report →
            </a>
        </div>
    </div>
    @empty
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-12 text-center ambient-shadow">
        <div class="w-12 h-12 bg-surface-container rounded-xl flex items-center justify-center mx-auto mb-3 text-secondary">
            <span class="material-symbols-outlined text-[32px]">description</span>
        </div>
        <p class="text-on-surface font-semibold text-sm mb-1">Belum ada report</p>
        <p class="text-on-surface-variant text-xs">Klik "Generate New Report" untuk membuat laporan AI pertama</p>
    </div>
    @endforelse
    
    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection
