@extends('layouts.app')

@section('title', 'Edit ' . $project->name)
@section('page-title', 'Edit Project')
@section('page-subtitle', $project->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
        <div class="px-6 py-4 border-b border-outline-variant/10 bg-[#f0f5ff]/50">
            <h2 class="text-sm font-bold text-on-surface">Edit Informasi Project</h2>
        </div>
        <form method="POST" action="{{ route('projects.update', $project) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-bold text-on-surface mb-1.5">Nama Project <span class="text-error">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all @error('name') border-error @enderror">
                    @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="client_name" class="block text-sm font-bold text-on-surface mb-1.5">Nama Client <span class="text-error">*</span></label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $project->client_name) }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all @error('client_name') border-error @enderror">
                    @error('client_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-on-surface mb-1.5">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="repository_url" class="block text-sm font-bold text-on-surface mb-1.5">Repository URL</label>
                    <input type="url" id="repository_url" name="repository_url" value="{{ old('repository_url', $project->repository_url) }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label for="github_repo_name" class="block text-sm font-bold text-on-surface mb-1.5">GitHub Repo Name</label>
                    <input type="text" id="github_repo_name" name="github_repo_name" value="{{ old('github_repo_name', $project->github_repo_name) }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-bold text-on-surface mb-1.5">Status <span class="text-error">*</span></label>
                <select id="status" name="status"
                        class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    <option value="on_track"  {{ old('status', $project->status) === 'on_track'  ? 'selected' : '' }}>On Track</option>
                    <option value="at_risk"   {{ old('status', $project->status) === 'at_risk'   ? 'selected' : '' }}>At Risk</option>
                    <option value="blocked"   {{ old('status', $project->status) === 'blocked'   ? 'selected' : '' }}>Blocked</option>
                    <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="start_date" class="block text-sm font-bold text-on-surface mb-1.5">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-bold text-on-surface mb-1.5">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/10">
                <button type="submit" id="btn-update-project"
                        class="bg-primary text-on-primary rounded-lg font-title-md hover:bg-on-background transition-colors flex items-center gap-2 shadow-sm px-5 py-2.5 text-body-sm font-bold">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
                <a wire:navigate href="{{ route('projects.show', $project) }}"
                   class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-5 py-2.5 rounded-lg hover:bg-surface-container transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
