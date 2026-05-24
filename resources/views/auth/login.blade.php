<x-guest-layout>
    <!-- Header section -->
    <div class="mb-stack-lg text-center">
        <span class="font-label-caps text-label-caps text-surface-tint block mb-stack-sm tracking-widest uppercase">Akses Platform</span>
        <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg font-bold text-on-surface">
            Masuk
        </h1>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-stack-sm">
            Silakan masukkan kredensial Anda untuk melanjutkan ke dashboard analitik.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 font-semibold text-sm text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('login') }}" class="space-y-stack-md" method="POST">
        @csrf

        <!-- Email Field -->
        <div>
            <label class="block font-body-sm text-body-sm font-bold text-on-surface mb-2" for="email">Email Bisnis</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-variation-settings: 'FILL' 0;">mail</span>
                <input class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-transparent rounded-lg font-body-sm text-body-sm text-on-surface focus:bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" id="email" name="email" placeholder="nama@perusahaan.com" required="" type="email" value="{{ old('email') }}" autofocus autocomplete="username" />
            </div>
            @error('email')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <label class="block font-body-sm text-body-sm font-bold text-on-surface mb-2" for="password">Kata Sandi</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-variation-settings: 'FILL' 0;">lock</span>
                <input class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-transparent rounded-lg font-body-sm text-body-sm text-on-surface focus:bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" id="password" name="password" placeholder="••••••••" required="" type="password" autocomplete="current-password" />
            </div>
            @error('password')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Options Row -->
        <div class="flex items-center justify-between pt-2">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-surface-container-lowest transition-colors bg-surface-container-low group-hover:border-primary" type="checkbox" name="remember" id="remember" />
                <span class="font-body-sm text-body-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="font-body-sm text-body-sm font-semibold text-primary hover:text-surface-tint transition-colors" href="{{ route('password.request') }}">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-stack-md">
            <button class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md text-title-md hover:-translate-y-0.5 shadow-sm hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2" type="submit">
                Masuk
                <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
            </button>
        </div>
    </form>

    <!-- Registration Link -->
    <div class="mt-stack-lg text-center font-body-sm text-body-sm text-on-surface-variant border-t border-surface-container-high pt-stack-lg">
        Belum memiliki akun? 
        <a class="font-semibold text-primary hover:underline hover:text-surface-tint transition-colors" href="{{ route('register') }}">Daftar sekarang</a>
    </div>
</x-guest-layout>
