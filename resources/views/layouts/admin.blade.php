<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Hub') — Mikael On Shop Atelier</title>
    
    <!-- Editorial Haute Couture Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js Core (Untuk interaktivitas UI admin & clipboard) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        rosepet: {
                            base: '#FFF9FA',
                            glow: '#FDF0F3',
                            soft: '#FCE7EC',
                            fresh: '#E87A90',
                            vibrant: '#F06292',
                            deep: '#C46D82',
                            dark: '#241419',
                            muted: '#857077',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .sidebar-expanded {
            width: 17rem; /* 272px */
        }
        .sidebar-collapsed {
            width: 5rem; /* 80px */
        }
        .transition-width {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pearl-glass-nav {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(240, 98, 146, 0.12);
        }
    </style>
</head>
<body class="bg-[#FFF9FA] text-rosepet-dark font-sans antialiased selection:bg-rosepet-fresh selection:text-white">

    <div class="min-h-screen flex">
        
        <!-- BACKDROP OVERLAY FOR MOBILE -->
        <div id="sidebar-backdrop" onclick="toggleSidebarMobile()" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <!-- BESPOKE COLLAPSIBLE SIDEBAR -->
        <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-50 bg-white border-r border-rosepet-soft/80 flex flex-col justify-between transition-width duration-300 shadow-sm sidebar-expanded -translate-x-full md:translate-x-0">
            
            <!-- Sidebar Header & Brand -->
            <div>
                <div class="h-20 flex items-center justify-between px-5 border-b border-rosepet-soft/60">
                    <a href="/" class="flex items-center gap-3 overflow-hidden group">
                        <img src="{{ asset('assets/logo.png') }}" alt="Mikael On Shop" class="w-10 h-10 object-contain drop-shadow-md flex-shrink-0 group-hover:scale-105 transition-transform">
                        <div class="sidebar-text transition-opacity duration-200">
                            <span class="font-serif font-bold text-sm tracking-wider uppercase text-rosepet-dark">Mikael On Shop</span>
                            <span class="text-[9px] uppercase tracking-widest text-rosepet-muted font-bold">HQ</span>
                        </div>
                    </a>

                    <!-- Desktop Collapse Toggle Button -->
                    <button onclick="window.toggleSidebar()" class="hidden md:flex p-1.5 rounded-lg text-rosepet-muted hover:text-rosepet-dark hover:bg-rosepet-soft/40 transition-colors" title="Toggle Sidebar">
                        <svg class="w-5 h-5 sidebar-icon-toggle transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    </button>
                    <!-- Mobile Close Button -->
                    <button onclick="toggleSidebarMobile()" class="md:hidden p-1.5 rounded-lg text-rosepet-muted hover:text-rosepet-dark">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-3.5 space-y-1.5 text-xs font-semibold">
                    
                    <!-- Dashboard Orders -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-rosepet-soft/60 text-rosepet-fresh border border-rosepet-fresh/30 font-bold shadow-sm' : 'text-rosepet-muted hover:text-rosepet-dark hover:bg-rosepet-soft/30' }}" title="Semua Pesanan Tas">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span class="sidebar-text whitespace-nowrap">Semua Pesanan</span>
                    </a>

                    <!-- Category Management -->
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-rosepet-soft/60 text-rosepet-fresh border border-rosepet-fresh/30 font-bold shadow-sm' : 'text-rosepet-muted hover:text-rosepet-dark hover:bg-rosepet-soft/30' }}" title="Kategori Jenis Tas">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span class="sidebar-text whitespace-nowrap">Kategori Jenis</span>
                    </a>

                    <!-- Brands Management -->
                    <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.brands.*') ? 'bg-rosepet-soft/60 text-rosepet-fresh border border-rosepet-fresh/30 font-bold shadow-sm' : 'text-rosepet-muted hover:text-rosepet-dark hover:bg-rosepet-soft/30' }}" title="Merk / Brand Tas">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span class="sidebar-text whitespace-nowrap">Merk / Brand</span>
                    </a>

                    <!-- Products Catalog -->
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-rosepet-soft/60 text-rosepet-fresh border border-rosepet-fresh/30 font-bold shadow-sm' : 'text-rosepet-muted hover:text-rosepet-dark hover:bg-rosepet-soft/30' }}" title="Katalog Produk & Varian">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span class="sidebar-text whitespace-nowrap">Katalog & Varian</span>
                    </a>

                    <div class="pt-4 pb-1 border-t border-rosepet-soft/60 my-2"></div>

                    <!-- Front Store Link -->
                    <a href="/" target="_blank" class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-rosepet-muted hover:text-rosepet-dark hover:bg-rosepet-soft/30 transition-all" title="Buka Toko 3D">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span class="sidebar-text whitespace-nowrap">Lihat Toko 3D</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar User Profile Footer -->
            <div class="p-4 border-t border-rosepet-soft/80 flex items-center gap-3 overflow-hidden">
                <span class="w-8 h-8 rounded-full bg-rosepet-soft text-rosepet-deep font-bold text-xs flex-shrink-0 flex items-center justify-center">F</span>
                <div class="sidebar-text transition-opacity duration-200">
                    <p class="text-[11px] font-bold text-rosepet-dark truncate">Fariz (Admin)</p>
                    <p class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Gudang Aktif
                    </p>
                </div>
            </div>
        </aside>

        <!-- MAIN VIEWPORT CONTENT (Dynamically padded for desktop sidebar) -->
        <div id="content-wrapper" class="flex-1 flex flex-col transition-all duration-300 md:ml-[17rem]">
            
            <!-- Top Mobile Bar with Hamburger -->
            <header class="h-16 px-6 pearl-glass-nav flex items-center justify-between md:hidden sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebarMobile()" class="p-2 rounded-xl bg-white border border-rosepet-soft text-rosepet-dark shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <img src="{{ asset('assets/logo.png') }}" alt="Mikael On Shop" class="w-9 h-9 object-contain drop-shadow-md">
                    <span class="font-serif font-bold text-sm tracking-wider uppercase text-rosepet-dark">Mikael On Shop</span>
                </div>
                <a href="/" target="_blank" class="text-xs font-bold text-rosepet-fresh">Toko 3D →</a>
            </header>

            <!-- Page Content Injection -->
            <main class="flex-1 p-6 md:p-10 overflow-y-auto">
                @yield('content')
            </main>
        </div>

    </div>

    <!-- SIDEBAR INTERACTIVITY SCRIPT -->
    <script>
        const sidebar = document.getElementById('main-sidebar');
        const contentWrapper = document.getElementById('content-wrapper');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggleIcon = document.querySelector('.sidebar-icon-toggle');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        // Check Local Storage for Desktop Sidebar State
        let isExpanded = localStorage.getItem('sidebar_expanded') !== 'false';

        function applySidebarState() {
            if (window.innerWidth >= 768) {
                if (isExpanded) {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                    contentWrapper.style.marginLeft = '17rem';
                    sidebarTexts.forEach(el => el.classList.remove('hidden'));
                    if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
                } else {
                    sidebar.classList.remove('sidebar-expanded');
                    sidebar.classList.add('sidebar-collapsed');
                    contentWrapper.style.marginLeft = '5rem';
                    sidebarTexts.forEach(el => el.classList.add('hidden'));
                    if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
                }
            } else {
                contentWrapper.style.marginLeft = '0px';
                sidebarTexts.forEach(el => el.classList.remove('hidden'));
            }
        }

        // Global function for toggle button
        window.toggleSidebar = function() {
            isExpanded = !isExpanded;
            localStorage.setItem('sidebar_expanded', isExpanded);
            applySidebarState();
        };

        // Mobile drawer toggle
        function toggleSidebarMobile() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        window.addEventListener('resize', applySidebarState);
        document.addEventListener('DOMContentLoaded', applySidebarState);
    </script>
</body>
</html>
