@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Kelola informasi akun dan keamanan Anda')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ─── Profile Header Card ─────────────────────────────── --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 ambient-shadow flex items-center gap-5">
        {{-- Avatar --}}
        <div class="w-16 h-16 rounded-2xl bg-secondary flex items-center justify-center shrink-0">
            <span class="text-white text-2xl font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
        <div>
            <h2 class="text-lg font-bold text-on-surface">{{ auth()->user()->name }}</h2>
            <p class="text-sm text-on-surface-variant">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full
                {{ auth()->user()->role === 'client' ? 'bg-green-100 text-green-700' : 'bg-[#dce9ff] text-secondary' }}">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>

    {{-- ─── Update Name & Email ─────────────────────────────── --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
        <div class="flex items-center gap-2 px-6 py-4 border-b border-outline-variant/10">
            <span class="material-symbols-outlined text-[18px] text-secondary">person</span>
            <h3 class="text-sm font-semibold text-on-surface">Informasi Akun</h3>
        </div>
        <div class="p-6">
            @if(session('status') === 'profile-updated')
            <div class="flex items-center gap-2 mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                Profil berhasil diperbarui.
            </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full rounded-xl border px-4 py-3 text-sm text-on-surface bg-[#f0f5ff] placeholder-on-surface-variant/50
                               focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all
                               {{ $errors->get('name') ? 'border-error ring-1 ring-error/30' : 'border-outline-variant/40 focus:border-secondary' }}"
                        placeholder="Masukkan nama lengkap"
                    >
                    @error('name')
                    <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="username"
                        class="w-full rounded-xl border px-4 py-3 text-sm text-on-surface bg-[#f0f5ff] placeholder-on-surface-variant/50
                               focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all
                               {{ $errors->get('email') ? 'border-error ring-1 ring-error/30' : 'border-outline-variant/40 focus:border-secondary' }}"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                    <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-secondary/80 active:scale-[.98] transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Change Password ──────────────────────────────────── --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl overflow-hidden ambient-shadow">
        <div class="flex items-center gap-2 px-6 py-4 border-b border-outline-variant/10">
            <span class="material-symbols-outlined text-[18px] text-secondary">lock</span>
            <h3 class="text-sm font-semibold text-on-surface">Ubah Password</h3>
        </div>
        <div class="p-6">
            @if(session('status') === 'password-updated')
            <div class="flex items-center gap-2 mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                Password berhasil diperbarui.
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div>
                    <label for="current_password" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Password Saat Ini
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            class="w-full rounded-xl border px-4 py-3 pr-11 text-sm text-on-surface bg-[#f0f5ff] placeholder-on-surface-variant/50
                                   focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all
                                   {{ $errors->updatePassword->get('current_password') ? 'border-error ring-1 ring-error/30' : 'border-outline-variant/40 focus:border-secondary' }}"
                            placeholder="••••••••"
                        >
                        <button type="button" onclick="togglePwd('current_password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 hover:text-secondary">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword')
                    <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border px-4 py-3 pr-11 text-sm text-on-surface bg-[#f0f5ff] placeholder-on-surface-variant/50
                                   focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all
                                   {{ $errors->updatePassword->get('password') ? 'border-error ring-1 ring-error/30' : 'border-outline-variant/40 focus:border-secondary' }}"
                            placeholder="Min. 8 karakter"
                        >
                        <button type="button" onclick="togglePwd('password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 hover:text-secondary">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                    @error('password', 'updatePassword')
                    <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-xl border px-4 py-3 pr-11 text-sm text-on-surface bg-[#f0f5ff] placeholder-on-surface-variant/50
                                   focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all
                                   {{ $errors->updatePassword->get('password_confirmation') ? 'border-error ring-1 ring-error/30' : 'border-outline-variant/40 focus:border-secondary' }}"
                            placeholder="Ulangi password baru"
                        >
                        <button type="button" onclick="togglePwd('password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 hover:text-secondary">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                    @error('password_confirmation', 'updatePassword')
                    <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-secondary/80 active:scale-[.98] transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Danger Zone: Delete Account ─────────────────────── --}}
    <div class="bg-surface-container-lowest border border-error/20 rounded-2xl overflow-hidden ambient-shadow">
        <div class="flex items-center gap-2 px-6 py-4 border-b border-error/10">
            <span class="material-symbols-outlined text-[18px] text-error">delete_forever</span>
            <h3 class="text-sm font-semibold text-error">Hapus Akun</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-on-surface-variant mb-5">
                Setelah akun dihapus, semua data akan dihapus permanen dan tidak dapat dikembalikan.
                Pastikan Anda sudah mengunduh semua data yang dibutuhkan sebelum melanjutkan.
            </p>

            {{-- Toggle Button --}}
            <button id="show-delete-form"
                    class="inline-flex items-center gap-2 border border-error text-error text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-error/5 active:scale-[.98] transition-all">
                <span class="material-symbols-outlined text-[16px]">delete</span>
                Hapus Akun Saya
            </button>

            {{-- Confirm Form (hidden by default) --}}
            <div id="delete-confirm-form" class="hidden mt-5 bg-error-container/30 border border-error/20 rounded-xl p-5">
                <p class="text-sm font-semibold text-error mb-4">
                    ⚠️ Masukkan password Anda untuk konfirmasi penghapusan akun.
                </p>
                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="delete_password" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="delete_password"
                                name="password"
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-error/30 bg-white px-4 py-3 pr-11 text-sm text-on-surface
                                       focus:outline-none focus:ring-2 focus:ring-error/30 focus:border-error transition-all"
                                placeholder="Masukkan password Anda"
                            >
                            <button type="button" onclick="togglePwd('delete_password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 hover:text-error">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                        @error('password', 'userDeletion')
                        <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">error</span>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-error text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-error/80 active:scale-[.98] transition-all">
                            <span class="material-symbols-outlined text-[16px]">delete_forever</span>
                            Ya, Hapus Akun
                        </button>
                        <button type="button" id="cancel-delete"
                                class="text-sm text-on-surface-variant hover:text-on-surface transition-colors px-3 py-2.5">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Toggle password visibility
    function togglePwd(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('.material-symbols-outlined');
        if (input && input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else if (input) {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function initProfilePage() {
        const showDeleteBtn = document.getElementById('show-delete-form');
        const cancelDeleteBtn = document.getElementById('cancel-delete');
        const deleteConfirmForm = document.getElementById('delete-confirm-form');

        if (!showDeleteBtn) return;
        if (showDeleteBtn.dataset.init) return;
        showDeleteBtn.dataset.init = "true";

        showDeleteBtn.addEventListener('click', () => {
            if (deleteConfirmForm) deleteConfirmForm.classList.remove('hidden');
            showDeleteBtn.classList.add('hidden');
        });

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', () => {
                if (deleteConfirmForm) deleteConfirmForm.classList.add('hidden');
                showDeleteBtn.classList.remove('hidden');
            });
        }

        // Auto-open delete form if there were errors
        @if($errors->userDeletion->isNotEmpty())
            if (deleteConfirmForm) deleteConfirmForm.classList.remove('hidden');
            showDeleteBtn.classList.add('hidden');
        @endif
    }

    document.addEventListener('DOMContentLoaded', initProfilePage);
    document.addEventListener('livewire:navigated', initProfilePage);
</script>
@endsection
