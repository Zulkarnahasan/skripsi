@extends('layouts.app')

@section('content')
@php
    $adminNav = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'M3 13h8V3H3v10Zm10 8h8V3h-8v18ZM3 21h8v-6H3v6Z'],
        ['label' => 'Pendaftaran', 'route' => 'admin.registration', 'active' => 'admin.registration', 'icon' => 'M9 12h6M12 9v6M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z'],
        ['label' => 'User', 'route' => 'admin.users', 'active' => 'admin.users', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
        ['label' => 'Pendaftar', 'route' => 'applicants.index', 'active' => 'applicants.*', 'icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 11l-3 3-2-2'],
        ['label' => 'Kriteria', 'route' => 'criteria.index', 'active' => 'criteria.*', 'icon' => 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01'],
        ['label' => 'Soal Tes', 'route' => 'test-questions.index', 'active' => 'test-questions.*', 'icon' => 'M9 12h6M9 16h6M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h2ZM16 2v5h5'],
        ['label' => 'Jawaban Tes', 'route' => 'test-answers.index', 'active' => 'test-answers.*', 'icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11'],
        ['label' => 'Baca Quran', 'route' => 'quran-scores.index', 'active' => 'quran-scores.*', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15Z'],
        ['label' => 'Wawancara', 'route' => 'interview-scores.index', 'active' => 'interview-scores.*', 'icon' => 'M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8ZM8 9h8M8 13h5'],
        ['label' => 'Kuota Lulus', 'route' => 'admin.quota', 'active' => 'admin.quota', 'icon' => 'M16 21v-2a4 4 0 0 0-8 0v2M12 3l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 15.4 7.2 18l.9-5.4-3.9-3.8 5.4-.8L12 3Z'],
        ['label' => 'Nilai Terhitung', 'route' => 'saw.normalization', 'active' => 'saw.normalization', 'icon' => 'M3 3v18h18M7 15l4-4 3 3 5-7'],
        ['label' => 'Hasil SAW', 'route' => 'saw.results', 'active' => 'saw.results', 'icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11'],
        ['label' => 'Ranking', 'route' => 'saw.ranking', 'active' => 'saw.ranking', 'icon' => 'M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0V4ZM5 4H3v3a3 3 0 0 0 3 3M19 4h2v3a3 3 0 0 1-3 3'],
        ['label' => 'Laporan', 'route' => 'reports.index', 'active' => 'reports.*', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6ZM14 2v6h6M8 13h8M8 17h5'],
    ];
@endphp

<div class="user-shell">
    <div class="sidebar-backdrop" data-close-sidebar></div>
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-brand">
            <span class="navbar-logo-group">
                <img src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Logo-umt.png" alt="Logo Universitas Muhammadiyah Tangerang">
                <img src="{{ asset('images/permakip-logo.png') }}" alt="Logo PERMAKIP Unit Indonesia">
            </span>
            <div>
                <span>Portal Admin</span>
                <small>KIP-K UMT</small>
            </div>
        </div>

        <nav class="sidebar-nav" aria-label="Menu admin">
            @foreach($adminNav as $item)
                <a class="{{ request()->routeIs($item['active']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="{{ $item['icon'] }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="sidebar-note">
            <small>Kelola pendaftar, kriteria SAW, ranking, dan laporan seleksi KIP-K.</small>
        </div>
    </aside>

    <section class="user-main">
        <div class="user-mobilebar">
            <button class="icon-button" type="button" data-toggle-sidebar aria-label="Buka menu">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <span>Menu Admin</span>
        </div>
        @yield('admin-content')
    </section>
</div>
@endsection
