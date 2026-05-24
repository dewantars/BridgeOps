<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BridgeOps AI') }}</title>
    
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
            <a href="/">BridgeOps AI</a>
        </div>
        <div>
            @if(request()->routeIs('login'))
                <a class="font-title-md text-title-md text-primary cursor-pointer transition-all active:scale-95 hover:text-surface-tint" href="{{ route('register') }}">
                    Sign Up
                </a>
            @else
                <a class="font-title-md text-title-md text-primary cursor-pointer transition-all active:scale-95 hover:text-surface-tint" href="{{ route('login') }}">
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
        <div class="font-label-caps text-label-caps text-on-surface text-center md:text-left">
            © 2026 BridgeOps AI. All rights reserved.
        </div>
        <nav class="flex flex-wrap justify-center gap-gutter font-body-sm text-body-sm text-on-surface-variant">
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#">Privacy Policy</a>
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#">Terms of Service</a>
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#">Security</a>
            <a class="hover:underline hover:text-secondary transition-opacity hover:opacity-80" href="#">Contact Support</a>
        </nav>
    </div>
</footer>

</body>
</html>
