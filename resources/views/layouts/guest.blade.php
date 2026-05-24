<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BridgeOps') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "error-container": "#ffdad6",
                    "inverse-surface": "#213145",
                    "primary-fixed-dim": "#bec6e0",
                    "on-tertiary": "#ffffff",
                    "on-error": "#ffffff",
                    "on-primary-container": "#7c839b",
                    "on-primary-fixed-variant": "#3f465c",
                    "outline": "#76777d",
                    "on-surface": "#0b1c30",
                    "tertiary-container": "#191c1e",
                    "error": "#ba1a1a",
                    "surface-bright": "#f8f9ff",
                    "surface-container-high": "#dce9ff",
                    "on-primary-fixed": "#131b2e",
                    "on-primary": "#ffffff",
                    "inverse-primary": "#bec6e0",
                    "on-tertiary-fixed-variant": "#444749",
                    "on-tertiary-fixed": "#191c1e",
                    "primary-container": "#131b2e",
                    "surface-tint": "#565e74",
                    "on-secondary-fixed-variant": "#004395",
                    "surface-dim": "#cbdbf5",
                    "surface-container": "#e5eeff",
                    "on-tertiary-container": "#818486",
                    "outline-variant": "#c6c6cd",
                    "primary": "#000000",
                    "on-background": "#0b1c30",
                    "tertiary": "#000000",
                    "surface-container-highest": "#d3e4fe",
                    "secondary-fixed": "#d8e2ff",
                    "secondary-container": "#2170e4",
                    "surface-variant": "#d3e4fe",
                    "on-secondary-fixed": "#001a42",
                    "secondary-fixed-dim": "#adc6ff",
                    "on-error-container": "#93000a",
                    "inverse-on-surface": "#eaf1ff",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-low": "#eff4ff",
                    "tertiary-fixed": "#e0e3e5",
                    "secondary": "#0058be",
                    "on-surface-variant": "#45464d",
                    "on-secondary-container": "#fefcff",
                    "surface": "#f8f9ff",
                    "tertiary-fixed-dim": "#c4c7c9",
                    "background": "#f8f9ff",
                    "primary-fixed": "#dae2fd",
                    "on-secondary": "#ffffff"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "stack-md": "12px",
                    "margin-desktop": "40px",
                    "gutter": "24px",
                    "margin-mobile": "16px",
                    "container-max": "1440px",
                    "stack-sm": "4px",
                    "base": "8px",
                    "stack-lg": "24px"
            },
            "fontFamily": {
                    "label-caps": ["Inter"],
                    "body-sm": ["Inter"],
                    "headline-lg": ["Inter"],
                    "display-lg": ["Inter"],
                    "body-lg": ["Inter"],
                    "label-code": ["JetBrains Mono"],
                    "headline-lg-mobile": ["Inter"],
                    "title-md": ["Inter"]
            },
            "fontSize": {
                    "label-caps": ["11px", { "lineHeight": "12px", "letterSpacing": "0.08em", "fontWeight": "700" }],
                    "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                    "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                    "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                    "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                    "label-code": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500" }],
                    "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                    "title-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }]
            }
          },
        },
      }
    </script>
    
    <!-- Google Fonts & Material Icons -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <style>
        .material-symbols-outlined {
          font-family: 'Material Symbols Outlined';
          font-weight: normal;
          font-style: normal;
          font-size: 24px;
          line-height: 1;
          letter-spacing: normal;
          text-transform: none;
          display: inline-block;
          white-space: nowrap;
          word-wrap: normal;
          direction: ltr;
          -webkit-font-feature-settings: 'liga';
          -webkit-font-smoothing: antialiased;
        }
        
        /* Ultra-soft ambient shadow for cards */
        .shadow-ambient {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.06);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface flex flex-col font-body-sm text-body-sm text-on-surface antialiased">

<!-- TopAppBar -->
<header class="w-full bg-surface">
    <div class="flex justify-between items-center w-full px-margin-desktop py-base max-w-container-max mx-auto md:px-margin-desktop px-margin-mobile">
        <div class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg font-bold text-on-surface">
            <a href="/">BridgeOps</a>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Language Toggle Button -->
            <button id="lang-toggle" class="flex items-center space-x-2 text-on-surface-variant hover:text-secondary hover:bg-surface-container px-3 py-2 rounded-full transition-all duration-200 border border-outline-variant/30 focus:outline-none" aria-label="Change Language">
                <span class="material-symbols-outlined text-[20px] transition-transform duration-300">g_translate</span>
                <span id="lang-label" class="font-label-caps text-label-caps font-bold">EN</span>
            </button>

            @if(request()->routeIs('login'))
                <a class="font-title-md text-title-md text-primary cursor-pointer transition-all active:scale-95 hover:text-surface-tint" href="{{ route('register') }}" data-translate-key="nav_signup">
                    Sign Up
                </a>
            @else
                <a class="font-title-md text-title-md text-primary cursor-pointer transition-all active:scale-95 hover:text-surface-tint" href="{{ route('login') }}" data-translate-key="nav_login">
                    Log In
                </a>
            @endif
        </div>
    </div>
</header>

<!-- Main Canvas -->
<main class="flex-grow flex items-center justify-center p-margin-mobile md:p-margin-desktop">
    <!-- Card Container -->
    <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl p-stack-lg md:p-margin-desktop shadow-ambient border border-outline-variant/30 transition-all">
        {{ $slot }}
    </div>
</main>

<!-- Footer -->
<footer class="w-full bg-surface-container-low mt-auto">
    <div class="w-full px-margin-desktop py-stack-lg flex flex-col md:flex-row justify-between items-center gap-gutter max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="font-label-caps text-label-caps text-on-surface text-center md:text-left" data-translate-key="footer_rights">
            © 2026 BridgeOps AI. All rights reserved.
        </div>
        <nav class="flex flex-wrap justify-center gap-gutter font-body-sm text-body-sm text-on-surface-variant">
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#" data-translate-key="footer_privacy">Privacy Policy</a>
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#" data-translate-key="footer_terms">Terms of Service</a>
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#" data-translate-key="footer_security">Security</a>
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#" data-translate-key="footer_contact">Contact Support</a>
        </nav>
    </div>
</footer>

<script>
    const translations = {
        en: {
            nav_login: "Log In",
            nav_signup: "Sign Up",
            footer_rights: "© 2026 BridgeOps AI. All rights reserved.",
            footer_privacy: "Privacy Policy",
            footer_terms: "Terms of Service",
            footer_security: "Security",
            footer_contact: "Contact Support",
            login_badge: "Platform Access",
            login_title: "Log In",
            login_subtitle: "Please enter your credentials to proceed to the analytical dashboard.",
            login_label_email: "Business Email",
            login_placeholder_email: "name@company.com",
            login_label_password: "Password",
            login_remember_me: "Remember Me",
            login_forgot_password: "Forgot Password?",
            login_btn_submit: "Log In",
            login_register_prompt: "Don't have an account?",
            login_register_link: "Sign up now",
            register_badge: "Platform Access",
            register_title: "Register Account",
            register_subtitle: "Please complete the form below to create a new account.",
            register_label_name: "Full Name",
            register_label_email: "Business Email",
            register_placeholder_email: "name@company.com",
            register_label_password: "Password",
            register_label_password_confirm: "Confirm Password",
            register_btn_submit: "Register Account",
            register_login_prompt: "Already have an account?",
            register_login_link: "Log in now"
        },
        id: {
            nav_login: "Masuk",
            nav_signup: "Daftar",
            footer_rights: "© 2026 BridgeOps AI. Hak cipta dilindungi undang-undang.",
            footer_privacy: "Kebijakan Privasi",
            footer_terms: "Ketentuan Layanan",
            footer_security: "Keamanan",
            footer_contact: "Hubungi Dukungan",
            login_badge: "Akses Platform",
            login_title: "Masuk",
            login_subtitle: "Silakan masukkan kredensial Anda untuk melanjutkan ke dashboard analitik.",
            login_label_email: "Email Bisnis",
            login_placeholder_email: "nama@perusahaan.com",
            login_label_password: "Kata Sandi",
            login_remember_me: "Ingat Saya",
            login_forgot_password: "Lupa Kata Sandi?",
            login_btn_submit: "Masuk",
            login_register_prompt: "Belum memiliki akun?",
            login_register_link: "Daftar sekarang",
            register_badge: "Akses Platform",
            register_title: "Daftar Akun",
            register_subtitle: "Silakan lengkapi formulir di bawah ini untuk membuat akun baru.",
            register_label_name: "Nama Lengkap",
            register_label_email: "Email Bisnis",
            register_placeholder_email: "nama@perusahaan.com",
            register_label_password: "Kata Sandi",
            register_label_password_confirm: "Konfirmasi Kata Sandi",
            register_btn_submit: "Daftar Akun",
            register_login_prompt: "Sudah memiliki akun?",
            register_login_link: "Masuk sekarang"
        }
    };

    let currentLang = localStorage.getItem('lang') || 'en';

    function setLanguage(lang) {
        currentLang = lang;
        localStorage.setItem('lang', lang);
        document.documentElement.lang = lang;
        
        // Update toggle button UI
        const label = document.getElementById('lang-label');
        if (label) label.textContent = lang.toUpperCase();

        // Animate the main card container during translation
        const card = document.querySelector('main > div');
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.98) translateY(4px)';
            card.style.transition = 'opacity 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1)';
        }

        setTimeout(() => {
            document.querySelectorAll('[data-translate-key]').forEach(el => {
                const key = el.getAttribute('data-translate-key');
                if (translations[lang] && translations[lang][key] !== undefined) {
                    const translationVal = translations[lang][key];
                    if (el.tagName === 'INPUT') {
                        el.placeholder = translationVal;
                    } else if (translationVal.includes('<') || el.tagName === 'H1') {
                        el.innerHTML = translationVal;
                    } else {
                        el.textContent = translationVal;
                    }
                }
            });

            if (card) {
                card.style.opacity = '1';
                card.style.transform = 'scale(1) translateY(0)';
            }
        }, 150);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Apply saved language instantly on load
        const savedLang = localStorage.getItem('lang') || 'en';
        document.documentElement.lang = savedLang;
        const label = document.getElementById('lang-label');
        if (label) label.textContent = savedLang.toUpperCase();
        
        // Perform initial translation without animation
        document.querySelectorAll('[data-translate-key]').forEach(el => {
            const key = el.getAttribute('data-translate-key');
            if (translations[savedLang] && translations[savedLang][key] !== undefined) {
                const translationVal = translations[savedLang][key];
                if (el.tagName === 'INPUT') {
                    el.placeholder = translationVal;
                } else if (translationVal.includes('<') || el.tagName === 'H1') {
                    el.innerHTML = translationVal;
                } else {
                    el.textContent = translationVal;
                }
            }
        });

        // Setup click handler
        const btn = document.getElementById('lang-toggle');
        if (btn) {
            btn.addEventListener('click', () => {
                // Toggle between EN and ID
                const nextLang = currentLang === 'en' ? 'id' : 'en';
                
                // Add micro-rotation animation to the icon
                const icon = btn.querySelector('.material-symbols-outlined');
                if (icon) {
                    icon.style.transition = 'transform 300ms cubic-bezier(0.4, 0, 0.2, 1)';
                    // Get current rotation
                    const currentRotation = icon.style.transform || 'rotate(0deg)';
                    const newRotation = currentRotation === 'rotate(360deg)' ? 'rotate(0deg)' : 'rotate(360deg)';
                    icon.style.transform = newRotation;
                }
                
                setLanguage(nextLang);
            });
        }
    });
</script>

</body>
</html>
