<x-guest-layout>
    <!-- Header section -->
    <div class="mb-stack-lg text-center">
        <span class="font-label-caps text-label-caps text-surface-tint block mb-stack-sm tracking-widest uppercase" data-translate-key="register_badge">Akses Platform</span>
        <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg font-bold text-on-surface" data-translate-key="register_title">
            Daftar Akun
        </h1>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-stack-sm" data-translate-key="register_subtitle">
            Silakan lengkapi formulir di bawah ini untuk membuat akun baru.
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-stack-md">
        @csrf

        <!-- Name -->
        <div>
            <label class="block font-body-sm text-body-sm font-bold text-on-surface mb-2" for="name" data-translate-key="register_label_name">Nama Lengkap</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-variation-settings: 'FILL' 0;">person</span>
                <input class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-transparent rounded-lg font-body-sm text-body-sm text-on-surface focus:bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" id="name" name="name" placeholder="John Doe" required="" type="text" value="{{ old('name') }}" autofocus autocomplete="name" />
            </div>
            @error('name')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label class="block font-body-sm text-body-sm font-bold text-on-surface mb-2" for="email" data-translate-key="register_label_email">Email Bisnis</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-variation-settings: 'FILL' 0;">mail</span>
                <input class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-transparent rounded-lg font-body-sm text-body-sm text-on-surface focus:bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" id="email" name="email" placeholder="nama@perusahaan.com" required="" type="email" value="{{ old('email') }}" autocomplete="username" data-translate-key="register_placeholder_email" />
            </div>
            @error('email')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block font-body-sm text-body-sm font-bold text-on-surface mb-2" for="password" data-translate-key="register_label_password">Kata Sandi</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-variation-settings: 'FILL' 0;">lock</span>
                <input class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-transparent rounded-lg font-body-sm text-body-sm text-on-surface focus:bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" id="password" name="password" placeholder="••••••••" required="" type="password" autocomplete="new-password" />
            </div>
            @error('password')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block font-body-sm text-body-sm font-bold text-on-surface mb-2" for="password_confirmation" data-translate-key="register_label_password_confirm">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-variation-settings: 'FILL' 0;">lock</span>
                <input class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-transparent rounded-lg font-body-sm text-body-sm text-on-surface focus:bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required="" type="password" autocomplete="new-password" />
            </div>
            @error('password_confirmation')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-stack-md">
            <button class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md text-title-md hover:-translate-y-0.5 shadow-sm hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2" type="submit">
                <span data-translate-key="register_btn_submit">Daftar Akun</span>
                <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
            </button>
        </div>
    </form>

    <!-- Login Link -->
    <div class="mt-stack-lg text-center font-body-sm text-body-sm text-on-surface-variant border-t border-surface-container-high pt-stack-lg">
        <span data-translate-key="register_login_prompt">Sudah memiliki akun?</span> 
        <a class="font-semibold text-primary hover:underline hover:text-surface-tint transition-colors" href="{{ route('login') }}" data-translate-key="register_login_link">Masuk sekarang</a>
    </div>
</x-guest-layout>
