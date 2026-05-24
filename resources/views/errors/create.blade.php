@extends('layouts.app')

@section('title', 'Log Error Manual')
@section('page-title', 'Log Error Manual')
@section('page-subtitle', 'Input error log untuk diproses oleh AI')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
        <div class="px-6 py-4 border-b border-outline-variant/10 bg-[#f0f5ff]/50">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-error-container/50 rounded-lg flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                </div>
                <h2 class="text-sm font-bold text-on-surface">Detail Error Log</h2>
            </div>
        </div>
        <form method="POST" action="{{ route('manual-errors.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <label for="project_id" class="block text-sm font-bold text-on-surface mb-1.5">Project <span class="text-error">*</span></label>
                <select id="project_id" name="project_id"
                        class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all @error('project_id') border-error @enderror">
                    <option value="">Pilih Project...</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="title" class="block text-sm font-bold text-on-surface mb-1.5">Judul Error <span class="text-error">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           placeholder="e.g. TypeError: Cannot read properties..."
                           class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all @error('title') border-error @enderror">
                    @error('title') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="environment" class="block text-sm font-bold text-on-surface mb-1.5">Environment</label>
                    <select id="environment" name="environment"
                            class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Pilih Environment...</option>
                        <option value="production"  {{ old('environment') === 'production'  ? 'selected' : '' }}>Production</option>
                        <option value="staging"     {{ old('environment') === 'staging'     ? 'selected' : '' }}>Staging</option>
                        <option value="development" {{ old('environment') === 'development' ? 'selected' : '' }}>Development</option>
                        <option value="local"       {{ old('environment') === 'local'       ? 'selected' : '' }}>Local</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="severity" class="block text-sm font-bold text-on-surface mb-1.5">Severity <span class="text-error">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach([
                        'low' => ['Low', 'border-[#388E3C] bg-[#E8F5E9] text-[#2E7D32]'], 
                        'medium' => ['Medium', 'border-[#F57C00] bg-[#FFF3E0] text-[#E65100]'], 
                        'high' => ['High', 'border-[#E64A19] bg-[#FBE9E7] text-[#D84315]'], 
                        'critical' => ['Critical', 'border-[#D32F2F] bg-[#FFEBEE] text-[#C62828]']
                    ] as $val => [$label, $peerClasses])
                    <label class="cursor-pointer">
                        <input type="radio" name="severity" value="{{ $val }}" class="sr-only peer" {{ old('severity', 'medium') === $val ? 'checked' : '' }}>
                        <div class="flex items-center justify-center px-3 py-2 rounded-lg border border-outline-variant/30 text-xs font-semibold text-on-surface-variant bg-surface transition-all
                            peer-checked:{{ explode(' ', $peerClasses)[0] }} peer-checked:{{ explode(' ', $peerClasses)[1] }} peer-checked:{{ explode(' ', $peerClasses)[2] }}
                            hover:border-outline hover:text-on-surface">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('severity') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="error_message" class="block text-sm font-bold text-on-surface mb-1.5">Pesan Error <span class="text-error">*</span></label>
                <textarea id="error_message" name="error_message" rows="3"
                          placeholder="TypeError: Cannot read properties of undefined at PaymentService.validate()"
                          class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none font-label-code @error('error_message') border-error @enderror">{{ old('error_message') }}</textarea>
                @error('error_message') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stack_trace" class="block text-sm font-bold text-on-surface mb-1.5">Stack Trace / Detail Error</label>
                <textarea id="stack_trace" name="stack_trace" rows="5"
                          placeholder="at PaymentService.validate (/app/services/payment.js:45:12)&#10;at async processPayment (/app/controllers/payment.js:23:5)"
                          class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none font-label-code">{{ old('stack_trace') }}</textarea>
            </div>

            <div>
                <label for="notes" class="block text-sm font-bold text-on-surface mb-1.5">Catatan Tambahan</label>
                <textarea id="notes" name="notes" rows="2"
                          placeholder="Konteks tambahan, kondisi repro, atau informasi relevan lainnya..."
                          class="w-full bg-surface-container-low border border-outline-variant/20 text-on-surface text-sm rounded-lg px-4 py-2.5 placeholder-on-surface-variant/40 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/10">
                <button type="submit" id="btn-submit-error-log"
                        class="bg-error hover:bg-red-800 text-white rounded-lg font-title-md shadow-sm px-5 py-2.5 text-body-sm font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    Simpan & Proses AI
                </button>
                <a wire:navigate href="{{ route('dashboard') }}"
                   class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-sm px-5 py-2.5 rounded-lg hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <p class="text-xs text-on-surface-variant ml-auto italic">AI akan memproses error log ini secara otomatis</p>
            </div>
        </form>
    </div>
</div>
@endsection
