<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BridgeOps AI') }} — @yield('title', 'Dashboard')</title>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for custom config injection -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-background": "#0b1c30",
                        "error": "#ba1a1a",
                        "primary-fixed-dim": "#bec6e0",
                        "inverse-on-surface": "#eaf1ff",
                        "on-primary-fixed": "#131b2e",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#444749",
                        "surface-variant": "#d3e4fe",
                        "on-tertiary": "#ffffff",
                        "on-surface": "#0b1c30",
                        "tertiary-container": "#191c1e",
                        "on-secondary-container": "#fefcff",
                        "tertiary": "#000000",
                        "surface-bright": "#f8f9ff",
                        "on-tertiary-fixed": "#191c1e",
                        "secondary-container": "#2170e4",
                        "primary": "#000000",
                        "outline": "#76777d",
                        "surface": "#f8f9ff",
                        "surface-dim": "#cbdbf5",
                        "surface-container": "#e5eeff",
                        "on-primary-container": "#7c839b",
                        "inverse-surface": "#213145",
                        "on-tertiary-container": "#818486",
                        "surface-container-low": "#eff4ff",
                        "secondary-fixed": "#d8e2ff",
                        "secondary": "#0058be",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c6c6cd",
                        "surface-tint": "#565e74",
                        "on-error": "#ffffff",
                        "inverse-primary": "#bec6e0",
                        "on-error-container": "#93000a",
                        "primary-container": "#131b2e",
                        "on-secondary-fixed": "#001a42",
                        "surface-container-highest": "#d3e4fe",
                        "on-secondary-fixed-variant": "#004395",
                        "error-container": "#ffdad6",
                        "surface-container-high": "#dce9ff",
                        "on-surface-variant": "#45464d",
                        "background": "#f8f9ff",
                        "tertiary-fixed-dim": "#c4c7c9",
                        "on-primary-fixed-variant": "#3f465c",
                        "primary-fixed": "#dae2fd",
                        "tertiary-fixed": "#e0e3e5",
                        "secondary-fixed-dim": "#adc6ff",
                        "on-secondary": "#ffffff"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "margin-desktop": "40px",
                        "stack-sm": "4px",
                        "margin-mobile": "16px",
                        "container-max": "1440px",
                        "base": "8px",
                        "stack-lg": "24px",
                        "gutter": "24px",
                        "stack-md": "12px"
                    },
                    fontFamily: {
                        "headline-lg": ["Inter", "sans-serif"],
                        "body-sm": ["Inter", "sans-serif"],
                        "body-lg": ["Inter", "sans-serif"],
                        "display-lg": ["Inter", "sans-serif"],
                        "label-caps": ["Inter", "sans-serif"],
                        "headline-lg-mobile": ["Inter", "sans-serif"],
                        "title-md": ["Inter", "sans-serif"],
                        "label-code": ["JetBrains Mono", "monospace"]
                    },
                    fontSize: {
                        "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "display-lg": ["48px", { lineHeight: "56px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "label-caps": ["11px", { lineHeight: "12px", letterSpacing: "0.08em", fontWeight: "700" }],
                        "headline-lg-mobile": ["24px", { lineHeight: "32px", fontWeight: "600" }],
                        "title-md": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "label-code": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "500" }]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Soft Ambient Shadows */
        .ambient-shadow {
            box-shadow: 0 4px 20px -2px rgba(11, 28, 48, 0.05);
        }
        .shadow-ambient {
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-lg text-body-lg antialiased overflow-hidden">

<div class="flex flex-col md:flex-row h-screen w-full overflow-hidden">
    <!-- Desktop SideNavBar Component (Hidden on Mobile/Tablet) -->
    <nav class="hidden md:flex h-screen w-64 flex-col fixed left-0 top-0 py-8 px-4 bg-[#f0f5ff] shadow-sm z-30 border-r border-outline-variant/10">
        <!-- Header / Logo -->
        <div class="mb-10 px-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-secondary flex items-center justify-center text-on-secondary">
                <span class="material-symbols-outlined" style="font-size: 18px; color: white;">account_tree</span>
            </div>
            <div>
                <h1 class="font-headline-lg-mobile text-[22px] font-bold text-on-surface">BridgeOps</h1>
            </div>
        </div>
        
        <!-- Main Navigation -->
        <div class="flex-1 overflow-y-auto pr-2 space-y-1">
            <!-- Tab: Dashboard -->
            <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('dashboard') ? 'text-secondary' : 'text-on-surface-variant' }}" data-icon="grid_view">grid_view</span>
                <span class="text-sm">Dashboard</span>
            </a>
            
            <!-- Tab: Projects -->
            <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('projects.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('projects.index') }}">
                <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('projects.*') ? 'text-secondary' : 'text-on-surface-variant' }}" data-icon="account_tree">account_tree</span>
                <span class="text-sm">Projects</span>
            </a>
            
            <!-- Tab: Activity Timeline -->
            <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('activities.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('activities.index') }}">
                <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('activities.*') ? 'text-secondary' : 'text-on-surface-variant' }}" data-icon="show_chart">show_chart</span>
                <span class="text-sm">Activity Timeline</span>
            </a>
            
            <!-- Tab: Chat -->
            <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('chat.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('chat.index') }}">
                <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('chat.*') ? 'text-secondary' : 'text-on-surface-variant' }}" data-icon="chat">chat</span>
                <span class="text-sm flex-1">Chat</span>
                <span id="sidebar-unread-badge" class="hidden text-[11px] font-bold text-white bg-secondary rounded-full w-5 h-5 flex items-center justify-center shrink-0"></span>
            </a>

            <!-- Tab: Log Error Manual -->
            @can('manage-projects')
            <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('manual-errors.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('manual-errors.create') }}">
                <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('manual-errors.*') ? 'text-secondary' : 'text-on-surface-variant' }}" data-icon="info">info</span>
                <span class="text-sm">Log Error Manual</span>
            </a>
            @endcan
        </div>
        
        <!-- Footer Actions -->
        <div class="mt-auto pt-4 border-t border-outline-variant/20 space-y-1">
            <div class="flex items-center gap-3 px-4 py-3 bg-[#dce9ff] rounded-xl mb-4">
                <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-on-secondary shrink-0">
                    <span class="material-symbols-outlined text-white" data-icon="person">person</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-bold text-sm text-on-surface truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">{{ strtoupper(auth()->user()->role) }}</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-white/30 transition-colors rounded-lg text-sm text-left">
                    <span class="material-symbols-outlined text-[20px]" data-icon="logout">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Mobile Top Navigation Header -->
    <header class="md:hidden flex items-center justify-between px-margin-mobile bg-[#f0f5ff] border-b border-outline-variant/10 h-16 shrink-0 z-40 sticky top-0">
        <div class="flex items-center gap-3">
            <button id="mobile-menu-open" class="text-on-surface hover:text-secondary flex focus:outline-none">
                <span class="material-symbols-outlined text-[24px]">menu</span>
            </button>
            <h1 class="font-headline-lg-mobile text-lg font-bold text-on-surface">BridgeOps</h1>
        </div>
        <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-on-secondary text-xs font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </header>

    <!-- Mobile Sidebar Drawer Container -->
    <div id="mobile-sidebar-container" class="fixed inset-0 z-50 md:hidden hidden">
        <!-- Backdrop overlay -->
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-black/50 transition-opacity duration-300 opacity-0"></div>
        
        <!-- Drawer Menu Body -->
        <nav id="mobile-sidebar" class="fixed top-0 left-0 bottom-0 w-64 bg-[#f0f5ff] p-6 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out shadow-lg z-50 border-r border-outline-variant/10">
            <!-- Logo & Close Button -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-secondary flex items-center justify-center text-on-secondary">
                        <span class="material-symbols-outlined text-white" style="font-size: 18px;">account_tree</span>
                    </div>
                    <h1 class="font-headline-lg-mobile text-xl font-bold text-on-surface">BridgeOps</h1>
                </div>
                <button id="mobile-menu-close" class="text-on-surface-variant hover:text-error flex focus:outline-none">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto pr-2 space-y-1">
                <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('dashboard') ? 'text-secondary' : 'text-on-surface-variant' }}">grid_view</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                
                <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('projects.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('projects.index') }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('projects.*') ? 'text-secondary' : 'text-on-surface-variant' }}">account_tree</span>
                    <span class="text-sm">Projects</span>
                </a>
                
                <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('activities.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('activities.index') }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('activities.*') ? 'text-secondary' : 'text-on-surface-variant' }}">show_chart</span>
                    <span class="text-sm">Activity Timeline</span>
                </a>
                
                <!-- Tab: Chat -->
                <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('chat.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('chat.index') }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('chat.*') ? 'text-secondary' : 'text-on-surface-variant' }}">chat</span>
                    <span class="text-sm flex-1">Chat</span>
                    <span id="sidebar-unread-badge-mobile" class="hidden text-[11px] font-bold text-white bg-secondary rounded-full w-5 h-5 flex items-center justify-center shrink-0"></span>
                </a>

                @can('manage-projects')
                <a class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all duration-150 {{ request()->routeIs('manual-errors.*') ? 'bg-[#dce9ff] text-secondary border-r-4 border-secondary' : 'text-on-surface-variant hover:bg-white/30' }}" href="{{ route('manual-errors.create') }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('manual-errors.*') ? 'text-secondary' : 'text-on-surface-variant' }}">info</span>
                    <span class="text-sm">Log Error Manual</span>
                </a>
                @endcan
            </div>
            
            <!-- User Info & Logout -->
            <div class="mt-auto pt-4 border-t border-outline-variant/20 space-y-1">
                <div class="flex items-center gap-3 px-4 py-3 bg-[#dce9ff] rounded-xl mb-4">
                    <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-on-secondary shrink-0">
                        <span class="material-symbols-outlined text-white">person</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="font-bold text-sm text-on-surface truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">{{ strtoupper(auth()->user()->role) }}</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-white/30 transition-colors rounded-lg text-sm text-left">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content Canvas -->
    <main class="flex-1 ml-0 md:ml-64 flex flex-col h-screen overflow-hidden bg-background">
        <!-- Content Header -->
        <header class="flex items-center justify-between px-margin-mobile md:px-margin-desktop border-b border-outline-variant/20 bg-surface-container-lowest shrink-0 z-10 relative py-3">
            <div>
                <h2 class="font-headline-lg text-on-surface text-title-md">@yield('page-title')</h2>
                <p class="font-body-lg text-on-surface-variant text-body-sm">@yield('page-subtitle')</p>
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm" id="flash-success">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm" id="flash-error">
            <span class="material-symbols-outlined text-[18px]">error</span>
            {{ session('error') }}
        </div>
        @endif

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop">
            @yield('content')
        </div>
    </main>
</div>

<!-- Mobile Menu Drawer Javascript Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('mobile-menu-open');
        const closeBtn = document.getElementById('mobile-menu-close');
        const backdrop = document.getElementById('mobile-sidebar-backdrop');
        const container = document.getElementById('mobile-sidebar-container');
        const sidebar = document.getElementById('mobile-sidebar');

        function openMenu() {
            container.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                sidebar.classList.remove('-translate-x-full');
            }, 10);
        }

        function closeMenu() {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            sidebar.classList.add('-translate-x-full');
            setTimeout(() => {
                container.classList.add('hidden');
            }, 300);
        }

        if (openBtn) openBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);
        if (backdrop) backdrop.addEventListener('click', closeMenu);

        // ─── Unread Chat Badge Polling ────────────────────────────
        function updateUnreadBadge() {
            fetch('{{ route('chat.unread-count') }}')
                .then(r => r.json())
                .then(data => {
                    const badges = [
                        document.getElementById('sidebar-unread-badge'),
                        document.getElementById('sidebar-unread-badge-mobile'),
                    ];
                    badges.forEach(badge => {
                        if (!badge) return;
                        if (data.count > 0) {
                            badge.textContent = data.count > 9 ? '9+' : data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    });
                })
                .catch(() => {});
        }

        // Poll every 30 seconds
        updateUnreadBadge();
        setInterval(updateUnreadBadge, 30000);
    });
</script>

</body>
</html>
