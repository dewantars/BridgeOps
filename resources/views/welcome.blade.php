<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>BridgeOps - Engineering Data, Business Insights</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-primary": "#bec6e0",
                        "secondary-fixed": "#d8e2ff",
                        "on-tertiary-fixed": "#191c1e",
                        "surface-variant": "#d3e4fe",
                        "surface-container": "#e5eeff",
                        "on-error-container": "#93000a",
                        "surface-bright": "#f8f9ff",
                        "on-tertiary-container": "#818486",
                        "background": "#f8f9ff",
                        "outline": "#76777d",
                        "on-secondary-fixed-variant": "#004395",
                        "on-background": "#0b1c30",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "outline-variant": "#c6c6cd",
                        "on-primary-container": "#7c839b",
                        "primary": "#000000",
                        "on-primary-fixed": "#131b2e",
                        "tertiary": "#000000",
                        "primary-container": "#131b2e",
                        "secondary": "#0058be",
                        "on-tertiary": "#ffffff",
                        "error": "#ba1a1a",
                        "on-surface-variant": "#45464d",
                        "on-surface": "#0b1c30",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-tint": "#565e74",
                        "surface": "#f8f9ff",
                        "inverse-surface": "#213145",
                        "primary-fixed": "#dae2fd",
                        "on-secondary-fixed": "#001a42",
                        "on-primary": "#ffffff",
                        "primary-fixed-dim": "#bec6e0",
                        "secondary-fixed-dim": "#adc6ff",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed-variant": "#3f465c",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-high": "#dce9ff",
                        "on-tertiary-fixed-variant": "#444749",
                        "tertiary-fixed": "#e0e3e5",
                        "on-secondary-container": "#fefcff",
                        "on-secondary": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "tertiary-container": "#191c1e",
                        "secondary-container": "#2170e4",
                        "surface-dim": "#cbdbf5",
                        "tertiary-fixed-dim": "#c4c7c9"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px",
                        "2xl": "1rem"
                    },
                    "spacing": {
                        "stack-sm": "4px",
                        "base": "8px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "container-max": "1440px",
                        "margin-desktop": "40px",
                        "stack-sm": "4px",
                        "stack-md": "12px",
                        "stack-lg": "24px"
                    },
                    "fontFamily": {
                        "label-code": ["JetBrains Mono"],
                        "body-lg": ["Inter"],
                        "label-caps": ["Inter"],
                        "display-lg": ["Inter"],
                        "title-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-sm": ["Inter"],
                        "headline-lg": ["Inter"]
                    },
                    "fontSize": {
                        "label-code": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-caps": ["11px", { "lineHeight": "12px", "letterSpacing": "0.08em", "fontWeight": "700" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "title-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }]
                    },
                    boxShadow: {
                        'ambient': '0 20px 40px -10px rgba(0, 0, 0, 0.05)',
                        'ambient-hover': '0 30px 50px -15px rgba(0, 0, 0, 0.08)'
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-lg antialiased">

<!-- TopNavBar -->
<nav class="bg-surface shadow-sm docked full-width top-0 sticky z-50">
    <div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto h-20">
        <div class="font-headline-lg text-headline-lg-mobile md:font-headline-lg md:text-headline-lg font-bold text-primary tracking-tight">
            BridgeOps
        </div>
        <div class="hidden md:flex space-x-gutter items-center">
            <a class="text-on-surface-variant font-label-caps text-label-caps uppercase hover:text-secondary transition-colors duration-200" href="#features" data-translate-key="nav_features">Features</a>
            <a class="text-on-surface-variant font-label-caps text-label-caps uppercase hover:text-secondary transition-colors duration-200" href="#how-it-works" data-translate-key="nav_how_it_works">How it Works</a>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Language Toggle Button -->
            <button id="lang-toggle" class="flex items-center space-x-2 text-on-surface-variant hover:text-secondary hover:bg-surface-container px-3 py-2 rounded-full transition-all duration-200 border border-outline-variant/30 focus:outline-none" aria-label="Change Language">
                <span class="material-symbols-outlined text-[20px] transition-transform duration-300">g_translate</span>
                <span id="lang-label" class="font-label-caps text-label-caps font-bold">EN</span>
            </button>
            
            @auth
                <a href="{{ route('dashboard') }}" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-3 rounded-full hover:bg-inverse-surface hover:-translate-y-0.5 transition-all shadow-ambient text-center" data-translate-key="nav_dashboard">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden md:block font-label-caps text-label-caps text-on-surface-variant hover:text-secondary px-4 py-2 transition-colors text-center" data-translate-key="nav_login">Log In</a>
                <a href="{{ route('register') }}" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-3 rounded-full hover:bg-inverse-surface hover:-translate-y-0.5 transition-all shadow-ambient text-center" data-translate-key="nav_register">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<main>
    <!-- Hero Section -->
    <section class="px-margin-mobile md:px-margin-desktop py-24 md:py-32 max-w-container-max mx-auto overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter items-center">
            <div class="md:col-span-6 space-y-stack-lg z-10">
                <span class="inline-flex items-center space-x-2 bg-surface-container text-secondary font-label-caps text-label-caps px-4 py-2 rounded-full uppercase">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span data-translate-key="hero_tagline">Powered by Google Gemini</span>
                </span>
                <h1 class="font-display-lg text-display-lg text-primary tracking-tight" data-translate-key="hero_title">
                    Translate Engineering Work<br>Into Business Updates.<br><span class="text-secondary">Bridged by AI.</span>
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg" data-translate-key="hero_desc">
                    Stop manually reading PRs and commits. BridgeOps AI translates technical activities into professional business updates for stakeholders.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 pt-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-primary text-on-primary font-body-lg text-body-lg px-8 py-4 rounded-full shadow-ambient hover:-translate-y-1 transition-transform flex items-center justify-center space-x-2">
                            <span data-translate-key="hero_cta_dashboard">Go to Dashboard</span>
                            <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-primary text-on-primary font-body-lg text-body-lg px-8 py-4 rounded-full shadow-ambient hover:-translate-y-1 transition-transform flex items-center justify-center space-x-2">
                            <span data-translate-key="hero_cta_register">Get Started for Free</span>
                            <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
                        </a>
                        <a href="#how-it-works" class="bg-surface border border-outline-variant text-on-surface font-body-lg text-body-lg px-8 py-4 rounded-full hover:bg-surface-container transition-colors flex items-center justify-center space-x-2">
                            <span class="material-symbols-outlined" data-icon="play_circle">play_circle</span>
                            <span data-translate-key="hero_cta_demo">Watch Demo</span>
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Hero Visual -->
            <div class="md:col-span-6 relative mt-16 md:mt-0">
                <div class="absolute inset-0 bg-gradient-to-tr from-surface-container to-surface-bright rounded-3xl transform rotate-3 scale-105 -z-10"></div>
                <div class="bg-surface border border-outline-variant rounded-2xl shadow-ambient p-6 relative overflow-hidden">
                    <!-- Simulated UI Header -->
                    <div class="flex justify-between items-center mb-6 border-b border-surface-variant pb-4">
                        <div class="font-label-caps text-label-caps text-on-surface-variant uppercase" data-translate-key="feed_title">Translation Feed</div>
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 rounded-full bg-surface-variant"></div>
                            <div class="w-3 h-3 rounded-full bg-surface-variant"></div>
                            <div class="w-3 h-3 rounded-full bg-surface-variant"></div>
                        </div>
                    </div>
                    <!-- Bridge Metric Item -->
                    <div class="flex flex-col md:flex-row items-stretch bg-surface-bright border border-surface-variant rounded-xl p-4 mb-4 gap-4">
                        <div class="flex-1 space-y-2">
                            <div class="font-label-caps text-label-caps text-on-surface-variant" data-translate-key="feed_tech_event">TECHNICAL EVENT</div>
                            <div class="font-label-code text-label-code text-on-surface bg-surface-container p-2 rounded" data-translate-key="feed_event_1_tech">
                                fix: memory leak in auth middleware<br>
                                ref: PR #4092, feat/oauth-flow
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined" data-icon="arrow_right_alt">arrow_right_alt</span>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="font-label-caps text-label-caps text-secondary text-right" data-translate-key="feed_business_impact">BUSINESS IMPACT</div>
                            <div class="font-body-sm text-body-sm text-on-surface text-right border-l-2 md:border-l-0 md:border-r-2 border-secondary pl-2 md:pl-0 md:pr-2" data-translate-key="feed_event_1_biz">
                                Resolved login stability issue, improving overall system performance during peak traffic.
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row items-stretch bg-surface-bright border border-surface-variant rounded-xl p-4 gap-4 opacity-50">
                        <div class="flex-1 space-y-2">
                            <div class="font-label-caps text-label-caps text-on-surface-variant" data-translate-key="feed_tech_event">TECHNICAL EVENT</div>
                            <div class="font-label-code text-label-code text-on-surface bg-surface-container p-2 rounded" data-translate-key="feed_event_2_tech">
                                feat: integrate stripe payment intent API
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined" data-icon="arrow_right_alt">arrow_right_alt</span>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="font-label-caps text-label-caps text-secondary text-right" data-translate-key="feed_business_impact">BUSINESS IMPACT</div>
                            <div class="font-body-sm text-body-sm text-on-surface text-right border-l-2 md:border-l-0 md:border-r-2 border-secondary pl-2 md:pl-0 md:pr-2" data-translate-key="feed_event_2_biz">
                                New checkout flow implementation started.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Proposition -->
    <section class="bg-surface-container-low py-24 md:py-32" id="how-it-works">
        <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="font-headline-lg text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-primary" data-translate-key="how_title">The End of Manual Reporting</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant" data-translate-key="how_subtitle">Bridge the gap between engineering activities and business-level reporting automatically.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <!-- Step 1 -->
                <div class="bg-surface rounded-2xl p-8 shadow-ambient hover:shadow-ambient-hover transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-5 rounded-bl-full transform translate-x-16 -translate-y-16 group-hover:scale-110 transition-transform"></div>
                    <div class="w-12 h-12 bg-surface-container text-secondary rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined" data-icon="webhook">webhook</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-primary mb-4" data-translate-key="step_1_title">1. Ingest</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant" data-translate-key="step_1_desc">
                        Connect your technical sources. We capture GitHub webhooks, JIRA updates, and manual developer logs in real-time.
                    </p>
                </div>
                <!-- Step 2 -->
                <div class="bg-surface rounded-2xl p-8 shadow-ambient hover:shadow-ambient-hover transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-5 rounded-bl-full transform translate-x-16 -translate-y-16 group-hover:scale-110 transition-transform"></div>
                    <div class="w-12 h-12 bg-secondary-fixed text-on-secondary-fixed-variant rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined" data-icon="translate">translate</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-primary mb-4" data-translate-key="step_2_title">2. Translate</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant" data-translate-key="step_2_desc">
                        Google Gemini AI analyzes the technical payload, stripping jargon and synthesizing the core business value and intent.
                    </p>
                </div>
                <!-- Step 3 -->
                <div class="bg-surface rounded-2xl p-8 shadow-ambient hover:shadow-ambient-hover transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-5 rounded-bl-full transform translate-x-16 -translate-y-16 group-hover:scale-110 transition-transform"></div>
                    <div class="w-12 h-12 bg-surface-container text-secondary rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-primary mb-4" data-translate-key="step_3_title">3. Report</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant" data-translate-key="step_3_desc">
                        Access clean, dashboard-ready summaries. PMs and Clients see structured updates without needing to parse code.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bento Grid -->
    <section class="py-24 md:py-32 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto" id="features">
        <div class="mb-16 space-y-4">
            <span class="font-label-caps text-label-caps text-secondary uppercase tracking-widest" data-translate-key="feat_tagline">Capabilities</span>
            <h2 class="font-headline-lg text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-primary" data-translate-key="feat_title">Everything you need to align teams.</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter auto-rows-[minmax(200px,auto)]">
            <!-- Large Feature -->
            <div class="md:col-span-8 bg-surface border border-outline-variant rounded-2xl p-8 shadow-ambient flex flex-col justify-between overflow-hidden relative">
                <div class="z-10 max-w-md">
                    <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined" data-icon="language">language</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-primary mb-2" data-translate-key="feat_1_title">Gemini AI Summaries</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant" data-translate-key="feat_1_desc">Automated professional summaries in Indonesian or English, tailored to your stakeholder's language preferences.</p>
                </div>
                <!-- Decorative Graphic -->
                <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-1/4 translate-y-1/4">
                    <span class="material-symbols-outlined text-[200px]" data-icon="forum">forum</span>
                </div>
            </div>
            <!-- Small Feature -->
            <div class="md:col-span-4 bg-surface border border-outline-variant rounded-2xl p-8 shadow-ambient flex flex-col">
                <div class="w-10 h-10 bg-surface-container text-secondary rounded-lg flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined" data-icon="code">code</span>
                </div>
                <h3 class="font-title-md text-title-md text-primary mb-2" data-translate-key="feat_2_title">GitHub Integration</h3>
                <p class="font-body-sm text-body-sm text-on-surface-variant" data-translate-key="feat_2_desc">Native webhooks track push events, merged PRs, and resolved issues in real-time.</p>
            </div>
            <!-- Small Feature -->
            <div class="md:col-span-4 bg-surface border border-outline-variant rounded-2xl p-8 shadow-ambient flex flex-col">
                <div class="w-10 h-10 bg-error-container text-on-error-container rounded-lg flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined" data-icon="warning">warning</span>
                </div>
                <h3 class="font-title-md text-title-md text-primary mb-2" data-translate-key="feat_3_title">Risk Detection</h3>
                <p class="font-body-sm text-body-sm text-on-surface-variant" data-translate-key="feat_3_desc">Automatically identify high-risk technical debt activities and flag potential deployment blockers.</p>
            </div>
            <!-- Large Feature -->
            <div class="md:col-span-8 bg-surface-bright border border-surface-variant rounded-2xl p-8 shadow-ambient flex flex-col justify-between relative overflow-hidden">
                <div class="z-10">
                    <div class="w-10 h-10 bg-surface-container text-secondary rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined" data-icon="space_dashboard">space_dashboard</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-primary mb-2" data-translate-key="feat_4_title">Stakeholder Dashboard</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-md" data-translate-key="feat_4_desc">Clean interface with access rights designed specifically for Project Managers, Admins, and External Clients. No terminal needed.</p>
                </div>
                <div class="mt-8 bg-white border border-outline-variant rounded-t-xl h-32 p-4 flex gap-4 overflow-hidden relative shadow-inner">
                    <div class="w-1/3 bg-surface-container rounded-lg h-full animate-pulse"></div>
                    <div class="w-2/3 bg-surface-container rounded-lg h-full animate-pulse opacity-75"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="border-y border-surface-variant bg-surface py-16 overflow-hidden">
        <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto text-center">
            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-8" data-translate-key="tech_tagline">Built on Modern Infrastructure</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="font-title-md text-title-md font-bold text-on-surface">Laravel 11</div>
                <div class="font-title-md text-title-md font-bold text-on-surface">PostgreSQL 16</div>
                <div class="font-title-md text-title-md font-bold text-secondary">Google Gemini</div>
                <div class="font-title-md text-title-md font-bold text-on-surface">GitHub</div>
                <div class="font-title-md text-title-md font-bold text-on-surface">Docker</div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-surface-container w-full">
    <div class="w-full py-6 px-margin-desktop max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <div class="font-title-md text-title-md font-bold text-primary">
            BridgeOps AI
        </div>
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
            <a class="font-label-caps text-label-caps text-on-surface-variant hover:text-secondary transition-colors" href="#" data-translate-key="footer_privacy">Privacy Policy</a>
            <a class="font-label-caps text-label-caps text-on-surface-variant hover:text-secondary transition-colors" href="#" data-translate-key="footer_terms">Terms of Service</a>
            <a class="font-label-caps text-label-caps text-on-surface-variant hover:text-secondary transition-colors" href="#" data-translate-key="footer_security">Security</a>
            <a class="font-label-caps text-label-caps text-on-surface-variant hover:text-secondary transition-colors" href="#" data-translate-key="footer_contact">Contact</a>
        </div>
        <div class="font-body-sm text-body-sm text-on-surface" data-translate-key="footer_rights">
            © 2026 BridgeOps AI. All rights reserved.
        </div>
    </div>
</footer>

<script>
    const translations = {
        en: {
            nav_features: "Features",
            nav_how_it_works: "How it Works",
            nav_login: "Log In",
            nav_register: "Get Started",
            nav_dashboard: "Dashboard",
            
            hero_tagline: "Powered by Google Gemini",
            hero_title: "Translate Engineering Work<br>Into Business Updates.<br><span class=\"text-secondary\">Bridged by AI.</span>",
            hero_desc: "Stop manually reading PRs and commits. BridgeOps AI translates technical activities into professional business updates for stakeholders.",
            hero_cta_dashboard: "Go to Dashboard",
            hero_cta_register: "Get Started for Free",
            hero_cta_demo: "Watch Demo",
            
            feed_title: "Translation Feed",
            feed_tech_event: "TECHNICAL EVENT",
            feed_business_impact: "BUSINESS IMPACT",
            feed_event_1_tech: "fix: memory leak in auth middleware<br>ref: PR #4092, feat/oauth-flow",
            feed_event_1_biz: "Resolved login stability issue, improving overall system performance during peak traffic.",
            feed_event_2_tech: "feat: integrate stripe payment intent API",
            feed_event_2_biz: "New checkout flow implementation started.",
            
            how_title: "The End of Manual Reporting",
            how_subtitle: "Bridge the gap between engineering activities and business-level reporting automatically.",
            step_1_title: "1. Ingest",
            step_1_desc: "Connect your technical sources. We capture GitHub webhooks, JIRA updates, and manual developer logs in real-time.",
            step_2_title: "2. Translate",
            step_2_desc: "Google Gemini AI analyzes the technical payload, stripping jargon and synthesizing the core business value and intent.",
            step_3_title: "3. Report",
            step_3_desc: "Access clean, dashboard-ready summaries. PMs and Clients see structured updates without needing to parse code.",
            
            feat_tagline: "Capabilities",
            feat_title: "Everything you need to align teams.",
            feat_1_title: "Gemini AI Summaries",
            feat_1_desc: "Automated professional summaries in Indonesian or English, tailored to your stakeholder's language preferences.",
            feat_2_title: "GitHub Integration",
            feat_2_desc: "Native webhooks track push events, merged PRs, and resolved issues in real-time.",
            feat_3_title: "Risk Detection",
            feat_3_desc: "Automatically identify high-risk technical debt activities and flag potential deployment blockers.",
            feat_4_title: "Stakeholder Dashboard",
            feat_4_desc: "Clean interface with access rights designed specifically for Project Managers, Admins, and External Clients. No terminal needed.",
            
            tech_tagline: "Built on Modern Infrastructure",
            
            footer_privacy: "Privacy Policy",
            footer_terms: "Terms of Service",
            footer_security: "Security",
            footer_contact: "Contact",
            footer_rights: "© 2026 BridgeOps AI. All rights reserved."
        },
        id: {
            nav_features: "Fitur",
            nav_how_it_works: "Cara Kerja",
            nav_login: "Masuk",
            nav_register: "Mulai",
            nav_dashboard: "Dasbor",
            
            hero_tagline: "Didukung oleh Google Gemini",
            hero_title: "Terjemahkan Pekerjaan Rekayasa<br>Menjadi Pembaruan Bisnis.<br><span class=\"text-secondary\">Dijembatani oleh AI.</span>",
            hero_desc: "Berhenti membaca PR dan commit secara manual. BridgeOps AI menerjemahkan aktivitas teknis menjadi pembaruan bisnis profesional untuk para pemangku kepentingan.",
            hero_cta_dashboard: "Ke Dasbor",
            hero_cta_register: "Mulai secara Gratis",
            hero_cta_demo: "Tonton Demo",
            
            feed_title: "Aliran Terjemahan",
            feed_tech_event: "AKTIVITAS TEKNIS",
            feed_business_impact: "DAMPAK BISNIS",
            feed_event_1_tech: "perbaikan: kebocoran memori pada middleware autentikasi<br>ref: PR #4092, feat/oauth-flow",
            feed_event_1_biz: "Menyelesaikan masalah stabilitas login, meningkatkan kinerja sistem secara keseluruhan selama lalu lintas puncak.",
            feed_event_2_tech: "fitur: integrasi API stripe payment intent",
            feed_event_2_biz: "Implementasi alur pembayaran baru dimulai.",
            
            how_title: "Akhir dari Pelaporan Manual",
            how_subtitle: "Jembatani kesenjangan antara aktivitas rekayasa dan pelaporan tingkat bisnis secara otomatis.",
            step_1_title: "1. Impor",
            step_1_desc: "Hubunakan sumber teknis Anda. Kami menangkap webhook GitHub, pembaruan JIRA, dan log developer manual secara real-time.",
            step_2_title: "2. Terjemahkan",
            step_2_desc: "Google Gemini AI menganalisis payload teknis, menghapus jargon, dan menyintesis nilai serta maksud bisnis inti.",
            step_3_title: "3. Laporkan",
            step_3_desc: "Akses ringkasan bersih yang siap untuk dasbor. PM dan Klien melihat pembaruan terstruktur tanpa perlu mengurai kode.",
            
            feat_tagline: "Kemampuan",
            feat_title: "Semua yang Anda butuhkan untuk menyelaraskan tim.",
            feat_1_title: "Ringkasan Gemini AI",
            feat_1_desc: "Ringkasan profesional yang dibuat otomatis dalam Bahasa Indonesia atau Inggris, disesuaikan dengan preferensi bahasa stakeholder Anda.",
            feat_2_title: "Integrasi GitHub",
            feat_2_desc: "Webhook asli melacak push event, merge PR, dan penyelesaian issue secara real-time.",
            feat_3_title: "Deteksi Risiko",
            feat_3_desc: "Identifikasi otomatis aktivitas utang teknis berisiko tinggi dan tandai potensi penghambat deployment.",
            feat_4_title: "Dashboard Stakeholder",
            feat_4_desc: "Interface bersih dengan hak akses yang dirancang khusus untuk Project Manager, Admin, dan Klien Eksternal. Tidak perlu terminal.",
            
            tech_tagline: "Dibangun di atas Infrastruktur Modern",
            
            footer_privacy: "Kebijakan Privasi",
            footer_terms: "Ketentuan Layanan",
            footer_security: "Keamanan",
            footer_contact: "Kontak",
            footer_rights: "© 2026 BridgeOps AI. Hak cipta dilindungi undang-undang."
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

        // Animate the main content container during translation
        const main = document.querySelector('main');
        if (main) {
            main.style.opacity = '0';
            main.style.transform = 'translateY(8px)';
            main.style.transition = 'opacity 200ms cubic-bezier(0.4, 0, 0.2, 1), transform 200ms cubic-bezier(0.4, 0, 0.2, 1)';
        }

        setTimeout(() => {
            document.querySelectorAll('[data-translate-key]').forEach(el => {
                const key = el.getAttribute('data-translate-key');
                if (translations[lang] && translations[lang][key] !== undefined) {
                    const translationVal = translations[lang][key];
                    if (translationVal.includes('<') || el.tagName === 'H1') {
                        el.innerHTML = translationVal;
                    } else {
                        el.textContent = translationVal;
                    }
                }
            });

            if (main) {
                main.style.opacity = '1';
                main.style.transform = 'translateY(0)';
            }
        }, 200);
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
                if (translationVal.includes('<') || el.tagName === 'H1') {
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
