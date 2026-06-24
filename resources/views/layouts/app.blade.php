<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'KIP-K SAW' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #111827;
            --muted: #64748b;
            --line: #dbe3ee;
            --panel: rgba(255, 255, 255, .92);
            --blue: #2563eb;
            --teal: #0f766e;
            --amber: #b45309;
            --rose: #be123c;
            --shadow-soft: 0 10px 26px rgba(15, 23, 42, .08);
            --shadow-bar: 0 14px 32px rgba(15, 23, 42, .13);
        }
        html, body { min-height: 100%; }
        html { scroll-padding-top: 1rem; }
        body {
            color: var(--ink);
            background-color: #f5f9ff;
            background-image:
                linear-gradient(180deg, rgba(241, 247, 255, .86) 0%, rgba(255, 255, 255, .92) 46%, rgba(232, 242, 255, .78) 100%),
                url("data:image/svg+xml,%3Csvg width='1440' height='390' viewBox='0 0 1440 390' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h1440v192c-105 43-211 67-319 72-173 8-295-36-443-63-151-28-309-26-466 16C131 238 60 269 0 306V0Z' fill='%232563eb' fill-opacity='.16'/%3E%3Cpath d='M0 0h1440v116c-133 32-267 47-403 43-157-5-252-37-402-58C414 70 215 94 0 180V0Z' fill='%230f172a' fill-opacity='.08'/%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg width='96' height='96' viewBox='0 0 96 96' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%232563eb' fill-opacity='.08'%3E%3Ccircle cx='12' cy='12' r='2'/%3E%3Ccircle cx='60' cy='12' r='2'/%3E%3Ccircle cx='36' cy='36' r='2'/%3E%3Ccircle cx='84' cy='36' r='2'/%3E%3Ccircle cx='12' cy='60' r='2'/%3E%3Ccircle cx='60' cy='60' r='2'/%3E%3Ccircle cx='36' cy='84' r='2'/%3E%3Ccircle cx='84' cy='84' r='2'/%3E%3C/g%3E%3C/svg%3E");
            background-size: auto, 100% 390px, 96px 96px;
            background-position: center, top center, top left;
            background-repeat: no-repeat, no-repeat, repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            background:
                linear-gradient(135deg, rgba(37, 99, 235, .06) 0 1px, transparent 1px 38px),
                linear-gradient(45deg, rgba(15, 23, 42, .04) 0 1px, transparent 1px 42px);
        }
        body > * { position: relative; z-index: 1; }
        img, svg { max-width: 100%; }
        main { flex: 1 0 auto; }
        .top-nav { background: linear-gradient(90deg, #132033 0%, #0f172a 62%, #172554 100%) !important; color: #fff; border-color: rgba(255, 255, 255, .14) !important; backdrop-filter: blur(14px); box-shadow: var(--shadow-bar); }
        .top-nav .container { gap: .75rem; }
        .top-nav .navbar-brand,
        .top-nav .navbar-brand:hover,
        .top-nav .navbar-brand:focus,
        .top-nav .navbar-title { color: #fff; }
        .site-credit { flex-shrink: 0; }
        .navbar-logo-group { display: flex; align-items: center; gap: .45rem; }
        .navbar-logo { width: 40px; height: 40px; object-fit: contain; background: rgba(255, 255, 255, .94); border-radius: 6px; padding: .18rem; }
        .navbar-title { line-height: 1.2; overflow-wrap: anywhere; }
        .user-shell { display: grid; grid-template-columns: 270px minmax(0, 1fr); gap: 1.1rem; align-items: start; min-height: calc(100vh - 150px); }
        .sidebar { position: sticky; top: 1rem; min-height: calc(100vh - 140px); padding: 1rem; border: 1px solid rgba(255, 255, 255, .18); border-radius: 8px; background: linear-gradient(180deg, #132033 0%, #0f172a 64%, #172554 100%); box-shadow: 0 24px 58px rgba(15, 23, 42, .24); color: #fff; }
        .sidebar-brand { display: flex; align-items: center; gap: .75rem; padding: .35rem .25rem 1rem; border-bottom: 1px solid rgba(255, 255, 255, .12); margin-bottom: .85rem; }
        .sidebar-brand-text { padding-left: .1rem; }
        .sidebar-brand img { width: 44px; height: 44px; object-fit: contain; background: #fff; border-radius: 8px; padding: .25rem; }
        .sidebar-brand .navbar-logo-group { gap: .35rem; flex: 0 0 auto; }
        .sidebar-brand .navbar-logo-group img { width: 42px; height: 42px; }
        .sidebar-brand span { display: block; font-weight: 800; line-height: 1.1; }
        .sidebar-brand small { color: #a7f3d0; }
        .sidebar-nav { display: grid; gap: .35rem; }
        .sidebar-nav a { color: #cbd5e1; text-decoration: none; display: flex; align-items: center; gap: .75rem; padding: .78rem .85rem; border-radius: 8px; transition: transform .18s ease, background .18s ease, color .18s ease; }
        .sidebar-nav a svg { width: 19px; height: 19px; flex: 0 0 auto; }
        .sidebar-nav a:hover { background: rgba(255, 255, 255, .08); color: #fff; transform: translateX(2px); }
        .sidebar-nav a.active { background: #fff; color: #0f172a; box-shadow: 0 12px 30px rgba(0, 0, 0, .18); }
        .sidebar-note { margin-top: 1rem; padding: .85rem; border-radius: 8px; background: rgba(20, 184, 166, .12); color: #d1fae5; }
        .sidebar-backdrop { display: none; }
        .user-main { min-width: 0; }
        .user-mobilebar { display: none; align-items: center; gap: .7rem; margin-bottom: 1rem; font-weight: 700; }
        .content-card { background: var(--panel); border: 1px solid rgba(219, 227, 238, .92); border-radius: 8px; box-shadow: 0 18px 50px rgba(15, 23, 42, .08); backdrop-filter: blur(14px); min-width: 0; }
        .accordion-item,
        .accordion-button,
        .data-toolbar,
        .user-mobilebar,
        .input-group,
        .alert,
        .table-bordered,
        .border.rounded.bg-white,
        .border.rounded.bg-light,
        form.border.rounded {
            box-shadow: var(--shadow-soft);
        }
        .accordion-item { border-color: rgba(219, 227, 238, .92); overflow: hidden; }
        .accordion-button { position: relative; z-index: 1; }
        .accordion-button:not(.collapsed) { box-shadow: var(--shadow-bar); }
        .table-responsive { filter: drop-shadow(0 10px 18px rgba(15, 23, 42, .05)); }
        .interactive-card { transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; }
        .interactive-card:hover { transform: translateY(-3px); box-shadow: 0 18px 42px rgba(15, 23, 42, .12); border-color: rgba(37, 99, 235, .28); }
        .user-hero { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 1rem; align-items: center; padding: 1.25rem; overflow: hidden; position: relative; }
        .user-hero::after { content: ""; position: absolute; width: 230px; height: 230px; right: -70px; top: -85px; background: radial-gradient(circle, rgba(37, 99, 235, .2), transparent 62%); pointer-events: none; }
        .user-hero > * { position: relative; z-index: 1; }
        .hero-kicker { color: var(--blue); font-weight: 800; font-size: .78rem; text-transform: uppercase; letter-spacing: 0; margin-bottom: .35rem; }
        .quick-actions { display: flex; flex-wrap: wrap; gap: .5rem; }
        .soft-button { display: inline-flex; align-items: center; gap: .45rem; border: 1px solid var(--line); background: #fff; color: var(--ink); text-decoration: none; border-radius: 8px; padding: .56rem .78rem; font-weight: 700; }
        .soft-button:hover { border-color: #93c5fd; color: var(--blue); }
        .stat-card { position: relative; overflow: hidden; height: 100%; padding: 1rem; border: 1px solid var(--line); border-radius: 8px; background: #fff; box-shadow: var(--shadow-soft); }
        .stat-card::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 4px; background: var(--accent, var(--blue)); }
        .stat-card small { color: var(--muted); font-weight: 700; }
        .timeline { display: grid; gap: .75rem; }
        .timeline-item { display: grid; grid-template-columns: 34px minmax(0, 1fr); gap: .75rem; align-items: start; }
        .timeline-dot { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: #e0f2fe; color: #0369a1; font-weight: 800; }
        .timeline-item.done .timeline-dot { background: #dcfce7; color: #166534; }
        .data-toolbar { display: flex; flex-wrap: wrap; justify-content: space-between; gap: .75rem; align-items: center; margin-bottom: 1rem; }
        .search-input { max-width: 320px; }
        .table-modern { --bs-table-bg: transparent; }
        .table-modern thead th { color: var(--muted); font-size: .78rem; text-transform: uppercase; letter-spacing: 0; border-bottom: 1px solid var(--line); }
        .table-modern tbody tr { transition: background .18s ease; }
        .table-modern tbody tr:hover { background: #f8fafc; }
        .badge-soft { border: 1px solid var(--line); background: #f8fafc; color: var(--ink); }
        .documentation-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
        .documentation-card { overflow: hidden; border: 1px solid var(--line); border-radius: 8px; background: #fff; }
        .documentation-card img { width: 100%; aspect-ratio: 16 / 10; object-fit: cover; display: block; background: #e2e8f0; }
        .documentation-card figcaption { padding: .75rem; font-size: .86rem; color: var(--muted); }
        .form-control, .form-select { border-radius: 8px; border-color: #cbd5e1; min-height: 42px; }
        .btn { min-height: 40px; }
        .form-control:focus, .form-select:focus { border-color: #60a5fa; box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12); }
        .form-progress { height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden; }
        .form-progress span { display: block; width: 0; height: 100%; background: linear-gradient(90deg, var(--blue), var(--teal)); transition: width .2s ease; }
        .drop-zone { border: 1px dashed #94a3b8; border-radius: 8px; padding: 1rem; background: #f8fafc; transition: border-color .18s ease, background .18s ease; }
        .drop-zone.dragging { border-color: var(--blue); background: #eff6ff; }
        .icon-button { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--line); border-radius: 8px; color: #0f172a; background: #fff; }
        .icon-button svg { width: 20px; height: 20px; }
        [data-reveal] { opacity: 0; transform: translateY(12px); transition: opacity .45s ease, transform .45s ease; }
        [data-reveal].is-visible { opacity: 1; transform: none; }
        .notification-button { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; position: relative; border: 1px solid rgba(255, 255, 255, .38); border-radius: 50%; color: #fff; background: rgba(255, 255, 255, .14); }
        .notification-button:hover { background: rgba(255, 255, 255, .24); color: #fff; }
        .notification-alert { position: absolute; top: -3px; right: -3px; min-width: 18px; height: 18px; padding: 0 5px; border-radius: 999px; background: #dc2626; color: #fff; border: 2px solid #fff; font-size: 11px; line-height: 15px; text-align: center; font-weight: 700; }
        .notification-item.unread { background: #fff7f7; border-left: 3px solid #dc2626; }
        .profile-link { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, .38); border-radius: 50%; background: rgba(255, 255, 255, .14); color: #fff; text-decoration: none; }
        .profile-link:hover { background: rgba(255, 255, 255, .24); color: #fff; }
        .top-nav .btn-outline-danger { color: #fff; border-color: rgba(255, 255, 255, .65); }
        .top-nav .btn-outline-danger:hover,
        .top-nav .btn-outline-danger:focus { color: var(--blue); background: #fff; border-color: #fff; }
        .auth-shell { display: grid; grid-template-columns: minmax(280px, .95fr) minmax(320px, 1fr); overflow: hidden; }
        .auth-page { min-height: calc(100vh - 96px); padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .auth-visual { position: relative; min-height: 520px; background: linear-gradient(rgba(15, 23, 42, .16), rgba(15, 23, 42, .72)), url('{{ asset('images/umt-campus.jpg') }}') center/cover; color: #fff; display: flex; align-items: flex-end; padding: 2rem; }
        .auth-logo-group { position: absolute; top: 1.25rem; left: 1.25rem; display: flex; gap: .65rem; align-items: center; }
        .auth-logo { width: 86px; height: 86px; object-fit: contain; background: rgba(255, 255, 255, .92); border-radius: .5rem; padding: .45rem; }
        .auth-visual-title { max-width: 430px; }
        .auth-form-panel { padding: 2rem; display: flex; align-items: center; }
        .auth-form-panel > div { width: 100%; }
        .auth-form-title { margin-top: .5rem; margin-bottom: 2rem; line-height: 1.3; }
        .whatsapp-help { position: fixed; right: 1.25rem; bottom: 1.25rem; min-width: 126px; height: 56px; padding: 0 1rem; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; gap: .5rem; background: #25d366; color: #fff; box-shadow: 0 12px 26px rgba(15, 23, 42, .22); text-decoration: none; z-index: 20; font-weight: 700; }
        .whatsapp-help:hover { background: #1ebe5d; color: #fff; }
        @media (max-width: 1199px) {
            .documentation-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 991px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-visual { min-height: 260px; }
            .auth-form-panel { padding: 1.25rem; }
            .user-shell { display: block; }
            .sidebar { position: fixed; top: .75rem; bottom: .75rem; left: .75rem; z-index: 1041; width: min(300px, calc(100vw - 1.5rem)); min-height: 0; overflow-y: auto; transform: translateX(calc(-100% - 1rem)); transition: transform .22s ease; }
            .sidebar.show { transform: translateX(0); }
            .sidebar-backdrop.show { display: block; position: fixed; inset: 0; z-index: 1040; background: rgba(15, 23, 42, .42); backdrop-filter: blur(4px); }
            .user-mobilebar { display: flex; }
            .user-hero { grid-template-columns: 1fr; }
            .navbar-title { max-width: 58vw; font-size: .86rem; }
        }
        @media (max-width: 767px) {
            body { background-attachment: scroll; }
            .auth-page { min-height: auto; padding-top: .25rem; padding-bottom: .25rem; }
            .auth-visual { min-height: 220px; padding: 1rem; align-items: flex-end; }
            .auth-logo-group { top: .9rem; left: .9rem; right: .9rem; gap: .45rem; flex-wrap: wrap; }
            .auth-logo { width: 62px; height: 62px; padding: .35rem; }
            .auth-visual-title { padding-top: 4.25rem; }
            .auth-form-panel { padding: 1rem; }
            .auth-form-title { margin-bottom: 1.25rem; font-size: 1.05rem; }
            .data-toolbar { align-items: stretch; }
            .data-toolbar > * { width: 100%; }
            .search-input { max-width: none; }
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .table-responsive table { min-width: 620px; }
            .modal-dialog { margin: .75rem; }
            .row.g-3 > [class*="col-"] { min-width: 0; }
            .timeline-item { grid-template-columns: 30px minmax(0, 1fr); }
            .timeline-dot { width: 30px; height: 30px; font-size: .86rem; }
        }
        @media (max-width: 575px) {
            main.container { padding: 1rem .8rem; }
            .top-nav .container { flex-wrap: nowrap; align-items: flex-start; }
            .top-nav .navbar-brand { flex: 1 1 auto; min-width: 0; align-items: flex-start !important; }
            .navbar-logo-group { gap: .3rem; }
            .navbar-logo { width: 32px; height: 32px; border-radius: 5px; padding: .15rem; }
            .navbar-title { max-width: none; font-size: .72rem; line-height: 1.18; }
            .notification-button,
            .profile-link { width: 34px; height: 34px; flex: 0 0 auto; }
            .top-nav form .btn { min-height: 34px; padding: .25rem .45rem; font-size: .74rem; }
            .content-card, .user-hero { padding: 1rem !important; }
            .quick-actions { width: 100%; }
            .soft-button { flex: 1 1 calc(50% - .5rem); justify-content: center; min-height: 42px; text-align: center; }
            .documentation-grid { grid-template-columns: 1fr; }
            .user-mobilebar { position: sticky; top: .5rem; z-index: 15; padding: .65rem .75rem; border: 1px solid var(--line); border-radius: 8px; background: rgba(255, 255, 255, .95); box-shadow: var(--shadow-soft); }
            .content-card .d-flex.justify-content-between { align-items: flex-start !important; }
            .stat-card { padding: .9rem; }
            .whatsapp-help { right: .8rem; bottom: .8rem; min-width: 112px; height: 48px; padding: 0 .8rem; font-size: .9rem; }
            .whatsapp-help svg { width: 24px; height: 24px; }
        }
        @media (max-width: 380px) {
            main.container { padding-left: .65rem; padding-right: .65rem; }
            .soft-button { flex-basis: 100%; }
            .auth-logo { width: 54px; height: 54px; }
            .navbar-title { font-size: .68rem; }
            .top-nav .d-flex.align-items-center.gap-2 { gap: .25rem !important; }
        }
    </style>
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg border-bottom top-nav">
            <div class="container">
                @php
                    $isUserArea = auth()->user()->role === 'user';
                @endphp
                @if($isUserArea)
                    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('user.dashboard') }}">
                    <span class="navbar-logo-group">
                        <img class="navbar-logo" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Logo-umt.png" alt="Logo Universitas Muhammadiyah Tangerang">
                        <img class="navbar-logo" src="{{ asset('images/permakip-logo.png') }}" alt="Logo PERMAKIP Unit Indonesia">
                    </span>
                    <span class="navbar-title">Ujian Penerimaan Mahasiswa KIP-K Universitas Muhammadiyah Tangerang</span>
                    </a>
                @else
                    <div></div>
                @endif
                @php
                    $notificationRoute = auth()->user()->role === 'admin' ? route('admin.notifications') : route('user.notifications');
                    $notificationCount = auth()->user()->role === 'admin'
                        ? \App\Models\Notification::query()->where('is_read', false)->count()
                        : auth()->user()->notifications()->where('is_read', false)->count();
                    $notificationItems = auth()->user()->role === 'admin'
                        ? \App\Models\Notification::query()->with('user')->latest()->limit(5)->get()
                        : auth()->user()->notifications()->latest()->limit(5)->get();
                @endphp
                <div class="d-flex align-items-center gap-2">
                    <button class="notification-button" type="button" data-bs-toggle="modal" data-bs-target="#notificationModal" aria-label="Notifikasi">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        @if($notificationCount > 0)
                            <span class="notification-alert">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                        @endif
                    </button>
                    @if(auth()->user()->role === 'user')
                        <a class="profile-link" href="{{ route('user.profile') }}" aria-label="Profil" title="Profil">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 21a8 8 0 0 0-16 0M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endif
                    <form method="post" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-danger">Logout</button></form>
                </div>
            </div>
        </nav>
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="notificationModalLabel">Notifikasi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body p-0">
                        @forelse($notificationItems as $notification)
                            <div class="notification-item p-3 border-bottom {{ $notification->is_read ? '' : 'unread' }}">
                                <div class="d-flex justify-content-between gap-3">
                                    <strong>{{ $notification->title }}</strong>
                                    @if(! $notification->is_read)
                                        <span class="badge text-bg-danger">Baru</span>
                                    @endif
                                </div>
                                @if(auth()->user()->role === 'admin')
                                    <small class="text-secondary">{{ $notification->user?->name ?? 'Semua pendaftar' }}</small>
                                @endif
                                <p class="mb-0 mt-1 text-secondary">{{ $notification->message }}</p>
                            </div>
                        @empty
                            <div class="p-4 text-center text-secondary">Belum ada notifikasi.</div>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" href="{{ $notificationRoute }}">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    <main class="container py-4">
        @include('partials.flash')
        @yield('content')
    </main>
    <footer class="site-credit py-3">
        <div class="container text-center text-secondary small">
            &copy; Zul Karna Hasan Teknik Informatika 2026 | Universitas Muhammadiyah Tangerang
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
