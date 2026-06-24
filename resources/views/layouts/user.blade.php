@extends('layouts.app')

@section('content')
@php
    $userNav = [
        ['label' => 'Dashboard', 'route' => 'user.dashboard', 'icon' => 'M3 13h8V3H3v10Zm10 8h8V3h-8v18ZM3 21h8v-6H3v6Z'],
        ['label' => 'Dokumen', 'route' => 'user.documents', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6ZM14 2v6h6M8 13h8M8 17h5'],
        ['label' => 'Status', 'route' => 'user.status', 'icon' => 'M20 6 9 17l-5-5'],
        ['label' => 'Isi Tes', 'route' => 'user.test', 'icon' => 'M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z'],
        ['label' => 'Hasil', 'route' => 'user.result', 'icon' => 'M3 3v18h18M7 15l4-4 3 3 5-7'],
    ];
@endphp

<div class="user-shell">
    <div class="sidebar-backdrop" data-close-sidebar></div>
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-brand sidebar-brand-text">
            <div>
                <span>Portal KIP-K</span>
                <small>Pendaftar</small>
            </div>
        </div>

        <nav class="sidebar-nav" aria-label="Menu pendaftar">
            @foreach($userNav as $item)
                <a class="{{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="{{ $item['icon'] }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="sidebar-note">
            <small>Lengkapi profil dan dokumen agar proses seleksi berjalan lancar.</small>
        </div>
    </aside>

    <section class="user-main">
        <div class="user-mobilebar">
            <button class="icon-button" type="button" data-toggle-sidebar aria-label="Buka menu">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <span>Menu Pendaftar</span>
        </div>
        @yield('user-content')
    </section>
</div>
@endsection
