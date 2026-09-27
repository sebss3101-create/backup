@extends('layouts.app')

@section('title', trans('messages.home'))

@section('content')
<style>
.category-item {
    background: rgba(20, 18, 31, 0.7) !important;
    border: 1px solid rgba(168, 85, 247, 0.15) !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer;
    text-decoration: none;
}
.category-item:hover {
    background: rgba(147, 51, 234, 0.15) !important;
    border-color: rgba(168, 85, 247, 0.4) !important;
    transform: translateX(4px);
}
.category-item:hover .category-arrow {
    color: #FFFFFF !important;
    transform: translateX(3px);
}
.category-item.active {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.25) 0%, rgba(88, 28, 135, 0.2) 100%) !important;
    border: 1px solid rgba(192, 132, 252, 0.5) !important;
    box-shadow: 0 0 15px rgba(147, 51, 234, 0.2) !important;
}
.category-item.active .category-arrow {
    color: #C084FC !important;
    transform: translateX(2px);
}
.category-item.active .category-title {
    color: #FFFFFF !important;
    text-shadow: 0 0 10px rgba(192, 132, 252, 0.5);
}
</style>
@php
    $shopPackages = collect();
    if (class_exists('\Azuriom\Plugin\Shop\Models\Package')) {
        try {
            $shopPackages = \Azuriom\Plugin\Shop\Models\Package::all();
        } catch (\Exception $e) {
            $shopPackages = collect();
        }
    }

    $findPkgId = function($name, $isPerm, $fallbackId) use ($shopPackages) {
        if ($shopPackages->isEmpty()) {
            return $fallbackId;
        }
        $match = $shopPackages->first(function($p) use ($name, $isPerm) {
            $n = mb_strtolower($p->name);
            $hasName = str_contains($n, mb_strtolower($name));
            if (!$hasName) return false;
            $hasPerm = str_contains($n, 'permanente') || str_contains($n, 'perma') || str_contains($n, 'perm');
            $hasTemp = str_contains($n, 'temporal') || str_contains($n, '30') || str_contains($n, 'temp') || str_contains($n, 'dias') || str_contains($n, 'días') || str_contains($n, 'mes');
            if ($isPerm) {
                return $hasPerm || !$hasTemp;
            } else {
                return $hasTemp;
            }
        });
        return $match ? $match->id : $fallbackId;
    };

    // Mapeo dinámico con fallback a los IDs creados por el usuario
    $rankPackages = [
        'nova' => [
            'permId' => $findPkgId('nova', true, 1),
            'tempId' => $findPkgId('nova', false, 2),
        ],
        'vanguard' => [
            'permId' => $findPkgId('vanguard', true, 3),
            'tempId' => $findPkgId('vanguard', false, 4),
        ],
        'ascendant' => [
            'permId' => $findPkgId('ascendant', true, 5),
            'tempId' => $findPkgId('ascendant', false, 6),
        ],
        'eternal' => [
            'permId' => $findPkgId('eternal', true, 7),
            'tempId' => $findPkgId('eternal', false, 8),
        ],
        'onyxs' => [
            'permId' => $findPkgId('onyx', true, 9),
            'tempId' => $findPkgId('onyx', false, 10),
        ],
    ];

    $findCratePkg = function($name) use ($shopPackages) {
        if ($shopPackages->isEmpty()) {
            return null;
        }
        return $shopPackages->first(function($p) use ($name) {
            $n = mb_strtolower($p->name);
            $catSlug = isset($p->category) ? mb_strtolower($p->category->slug ?? '') : '';
            $isCrate = str_contains($n, 'llave') || str_contains($n, 'crate') || str_contains($n, 'key') || $catSlug === 'keysmp';
            return $isCrate && str_contains($n, mb_strtolower($name));
        });
    };

    $cratePackages = [
        'nova' => $findCratePkg('nova'),
        'sovereign' => $findCratePkg('sovereign'),
        'ascendant' => $findCratePkg('ascendant'),
        'eternal' => $findCratePkg('eternal'),
        'onyxs' => $findCratePkg('onyx'),
        'obsidian' => $findCratePkg('obsidian'),
    ];

    $pvpCratePackages = [
        'comun' => $findCratePkg('comun') ?? $findCratePkg('común'),
        'poco_comun' => $findCratePkg('poco') ?? $findCratePkg('uncommon'),
        'rara' => $findCratePkg('rara') ?? $findCratePkg('rare'),
        'epica' => $findCratePkg('epica') ?? $findCratePkg('épica') ?? $findCratePkg('epic'),
        'legendaria' => $findCratePkg('legendaria') ?? $findCratePkg('legendary'),
        'mitica' => $findCratePkg('mitica') ?? $findCratePkg('mítica') ?? $findCratePkg('mythic'),
    ];

    $findShardPkg = function($amount) use ($shopPackages) {
        if ($shopPackages->isEmpty()) {
            return null;
        }
        return $shopPackages->first(function($p) use ($amount) {
            $n = mb_strtolower($p->name);
            return (str_contains($n, 'shard') || str_contains($n, 'moneda')) 
                && str_contains($n, (string)$amount);
        });
    };

    $shardPackages = [
        25 => $findShardPkg(25),
        50 => $findShardPkg(50),
        100 => $findShardPkg(100),
        200 => $findShardPkg(200),
    ];
@endphp

<!-- Hero Banner -->
<div class="hero-banner mb-4">
    <div class="hero-overlay"></div>
    <img src="https://images.unsplash.com/photo-1607513837946-86db89eeb020?q=80&w=1000&auto=format&fit=crop" alt="Minecraft Banner" class="hero-img">
    <div class="hero-content">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge" style="background: rgba(168, 85, 247, 0.2); border: 1px solid rgba(168, 85, 247, 0.4); color: #C084FC; font-weight: 700; letter-spacing: 0.15em; font-size: 0.75rem; text-transform: uppercase;">
                TIENDA OFICIAL ONYXS
            </span>
            <span class="badge bg-dark border border-secondary text-secondary" style="font-size: 0.75rem;">SMP 1.20+</span>
        </div>
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.8rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; line-height: 1.15;">
            Apoya a la <span class="gradient-text">Network</span>
        </h1>
        <p style="color: #d1d5db; max-width: 32rem; font-size: 0.95rem; margin-bottom: 1.25rem;">
            Mejora tu experiencia con rangos exclusivos, llaves de crates y Shards para la economía de nuestro SMP.
        </p>
        <div class="d-flex flex-wrap gap-2">
            <a href="#catalogo" class="btn btn-primary btn-sm px-3 py-2">
                <i class="bi bi-bag-check me-1"></i> Explorar Catálogo
            </a>
            <a href="https://discord.gg/onyxsmc" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm px-3 py-2">
                <i class="bi bi-discord me-1"></i> Discord Oficial
            </a>
        </div>
    </div>
</div>

<div class="row" id="catalogo">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
        <!-- Navegación de Categorías -->
        <div class="card mb-3 shadow-sm" style="border: 1px solid rgba(168, 85, 247, 0.2); background: #12101D;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                    <span style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #9CA3AF; font-size: 0.75rem; letter-spacing: 0.12em; text-transform: uppercase;">
                        Categorías
                    </span>
                    <i class="bi bi-grid-fill" style="color: #A855F7; font-size: 0.85rem;"></i>
                </div>
                <div class="nav flex-column gap-2" role="tablist">
                    <!-- 1. RANGOS -->
                    <button class="nav-link active category-item d-flex align-items-center justify-content-between py-2 px-3 rounded text-start w-100" id="tab-ranks-btn" type="button">
                        <div class="d-flex align-items-center gap-3">
                            <div class="category-icon-box d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; background: rgba(168, 85, 247, 0.12); border: 1px solid rgba(168, 85, 247, 0.25);">
                                <i class="bi bi-shield-shaded" style="color: #C084FC; font-size: 1rem;"></i>
                            </div>
                            <div>
                                <div class="category-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 0.92rem; letter-spacing: 0.06em; text-transform: uppercase; color: #FFFFFF;">
                                    RANGOS
                                </div>
                                <span class="category-desc" style="font-size: 0.68rem; color: #9CA3AF; display: block; line-height: 1.1;">Suscripciones & Perma</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background: rgba(168, 85, 247, 0.2); color: #C084FC; font-size: 0.62rem; font-weight: 700;">5</span>
                            <i class="bi bi-caret-right-fill category-arrow" style="font-size: 0.75rem; color: #C084FC; transition: transform 0.2s ease;"></i>
                        </div>
                    </button>

                    <!-- 2. SMP (Acordeón desplegable: Crates & Shards) -->
                    <div class="category-group">
                        <button class="category-item category-toggle collapsed d-flex align-items-center justify-content-between py-2 px-3 rounded text-start w-100" id="smp-group-toggle" aria-expanded="false" type="button">
                            <div class="d-flex align-items-center gap-3">
                                <div class="category-icon-box d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; background: rgba(96, 165, 250, 0.12); border: 1px solid rgba(96, 165, 250, 0.25);">
                                    <i class="bi bi-box-seam" style="color: #60A5FA; font-size: 1rem;"></i>
                                </div>
                                <div>
                                    <div class="category-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 0.92rem; letter-spacing: 0.06em; text-transform: uppercase; color: #FFFFFF;">
                                        SMP
                                    </div>
                                    <span class="category-desc" style="font-size: 0.68rem; color: #9CA3AF; display: block; line-height: 1.1;">Crates & Shards</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background: rgba(96, 165, 250, 0.2); color: #60A5FA; font-size: 0.62rem; font-weight: 700;">10</span>
                                <i class="bi bi-chevron-down collapse-chevron" style="font-size: 0.75rem; color: #9CA3AF;"></i>
                            </div>
                        </button>
                        <div class="collapse mt-1" id="menu-smp">
                            <div class="subcategory-list d-flex flex-column gap-1 pt-1 pb-1">
                                <!-- Opción 1: Crates SMP -->
                                <button class="nav-link subcategory-item d-flex align-items-center justify-content-between py-2 px-3 rounded text-start w-100" id="tab-smp-crates-btn" type="button">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-key-fill" style="color: #60A5FA; font-size: 0.88rem;"></i>
                                        <div>
                                            <span class="subcategory-title d-block" style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.84rem; color: #FFFFFF;">Crates SMP</span>
                                            <span style="font-size: 0.65rem; color: #9CA3AF; display: block; line-height: 1.1;">6 Cajas de Recompensas</span>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: rgba(96, 165, 250, 0.15); color: #60A5FA; font-size: 0.62rem; font-weight: 600;">6 Cajas</span>
                                </button>

                                <!-- Opción 2: Shards SMP -->
                                <button class="nav-link subcategory-item d-flex align-items-center justify-content-between py-2 px-3 rounded text-start w-100" id="tab-smp-shards-btn" type="button">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-gem" style="color: #C084FC; font-size: 0.88rem;"></i>
                                        <div>
                                            <span class="subcategory-title d-block" style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.84rem; color: #FFFFFF;">Shards SMP</span>
                                            <span style="font-size: 0.65rem; color: #9CA3AF; display: block; line-height: 1.1;">Monedas de Economía</span>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: rgba(192, 132, 252, 0.15); color: #C084FC; font-size: 0.62rem; font-weight: 600;">4 Packs</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. FULLPVP (Acordeón desplegable: Crates FullPvP) -->
                    <div class="category-group">
                        <button class="category-item category-toggle collapsed d-flex align-items-center justify-content-between py-2 px-3 rounded text-start w-100" id="fullpvp-group-toggle" aria-expanded="false" type="button">
                            <div class="d-flex align-items-center gap-3">
                                <div class="category-icon-box d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);">
                                    <i class="bi bi-crosshair" style="color: #F87171; font-size: 1rem;"></i>
                                </div>
                                <div>
                                    <div class="category-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 0.92rem; letter-spacing: 0.06em; text-transform: uppercase; color: #FFFFFF;">
                                        FULLPVP
                                    </div>
                                    <span class="category-desc" style="font-size: 0.68rem; color: #9CA3AF; display: block; line-height: 1.1;">Crates de Combate</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background: rgba(239, 68, 68, 0.2); color: #F87171; font-size: 0.62rem; font-weight: 700;">6</span>
                                <i class="bi bi-chevron-down collapse-chevron" style="font-size: 0.75rem; color: #9CA3AF;"></i>
                            </div>
                        </button>
                        <div class="collapse mt-1" id="menu-fullpvp">
                            <div class="subcategory-list d-flex flex-column gap-1 pt-1 pb-1" style="border-left-color: rgba(239, 68, 68, 0.35);">
                                <!-- Opción: Crates FullPvP -->
                                <button class="nav-link subcategory-item d-flex align-items-center justify-content-between py-2 px-3 rounded text-start w-100" id="tab-fullpvp-crates-btn" type="button">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-box-seam" style="color: #F87171; font-size: 0.88rem;"></i>
                                        <div>
                                            <span class="subcategory-title d-block" style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.84rem; color: #FFFFFF;">Crates FullPvP</span>
                                            <span style="font-size: 0.65rem; color: #9CA3AF; display: block; line-height: 1.1;">6 Cajas de Combate</span>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #F87171; font-size: 0.62rem; font-weight: 600;">6 Cajas</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Mensual Dinámica -->
        @php
            $goalTarget = 100; // Objetivo mensual en USD
            $baseAmount = 52;  // Base acumulada: $34 iniciales + $7 USD y $11 USD ya adquiridos ($52 USD)

            $onlineRevenue = 0;
            $recentPayments = collect();

            if (class_exists('Azuriom\Plugin\Shop\Models\Payment')) {
                try {
                    $onlineRevenue = (float) \Azuriom\Plugin\Shop\Models\Payment::whereIn('status', ['completed', 'COMPLETED', 'paid', 'success'])
                        ->where('created_at', '>=', now()->startOfMonth())
                        ->sum('price');

                    $recentPayments = \Azuriom\Plugin\Shop\Models\Payment::with('user')
                        ->whereIn('status', ['completed', 'COMPLETED', 'paid', 'success'])
                        ->latest()
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    $onlineRevenue = 0;
                    $recentPayments = collect();
                }
            }

            $totalRecaudado = $baseAmount + $onlineRevenue;
            $goalPercent = min(100, round(($totalRecaudado / $goalTarget) * 100));

            $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
            $nombreMes = $meses[now()->month] ?? 'Septiembre';
        @endphp

        <div class="card mb-3 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #fff; margin: 0; font-size: 0.9rem;">
                        <i class="bi bi-trophy text-warning me-1"></i> Meta de {{ $nombreMes }}
                    </h6>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #C084FC;">{{ $goalPercent }}%</span>
                </div>
                <div class="goal-bar-bg mb-2">
                    <div class="goal-bar-fill" style="width: {{ $goalPercent }}%"></div>
                </div>
                <div class="d-flex justify-content-between" style="font-size: 0.75rem; color: #9CA3AF;">
                    <span>Recaudado: <strong class="text-white">${{ number_format($totalRecaudado, 0) }} USD</strong></span>
                    <span>Objetivo: <strong class="text-white">${{ number_format($goalTarget, 0) }} USD</strong></span>
                </div>
            </div>
        </div>

        <!-- Donadores Recientes -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-3">
                <h6 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #fff; margin-bottom: 0.75rem; font-size: 0.9rem;" class="d-flex align-items-center gap-2">
                    <i class="bi bi-heart-fill text-danger"></i> Donadores Recientes
                </h6>
                @if(!empty($recentPayments) && count($recentPayments) > 0)
                    <div class="d-flex flex-column gap-2">
                        @foreach($recentPayments as $p)
                            @php
                                $donorName = $p->user->name ?? ($p->player_name ?? 'Anónimo');
                            @endphp
                            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(147, 51, 234, 0.08); border: 1px solid rgba(168, 85, 247, 0.15);">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://mc-heads.net/avatar/{{ $donorName }}/32" alt="{{ $donorName }}" width="24" height="24" style="border-radius: 4px; image-rendering: pixelated;" onerror="this.src='https://mc-heads.net/avatar/Steve/32'">
                                    <span style="font-size: 0.8rem; font-weight: 600; color: #fff;">{{ $donorName }}</span>
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 700; color: #4ADE80;">+${{ number_format($p->price, 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center rounded" style="background: rgba(168, 85, 247, 0.04); border: 1px dashed rgba(168, 85, 247, 0.2);">
                        <p style="font-size: 0.75rem; color: #9CA3AF; margin: 0;">¡Sé el primero en apoyar la Network este mes!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Garantía de Entrega Inmediata -->
        <div class="card shadow-sm" style="border: 1px solid rgba(74, 222, 128, 0.2); background: rgba(74, 222, 128, 0.03);">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-lightning-charge-fill text-success"></i>
                    <span style="font-weight: 700; font-size: 0.85rem; color: #FFFFFF;">Entrega Automática</span>
                </div>
                <p style="font-size: 0.72rem; color: #9CA3AF; margin: 0; line-height: 1.4;">
                    Los rangos y llaves se acreditan en tu cuenta de Minecraft al instante mediante AzuriomLink.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area with Tabs -->
    <div class="col-lg-9">
        <div class="tab-content">
            <!-- ========================================== -->
            <!-- TAB 1: RANGOS SMP                          -->
            <!-- ========================================== -->
            <div class="tab-pane fade show active" id="tab-ranks" role="tabpanel">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 p-3 rounded" style="background: rgba(168, 85, 247, 0.04); border: 1px solid rgba(168, 85, 247, 0.15);">
                    <div>
                        <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin: 0; font-size: 1.6rem;" class="d-flex align-items-center gap-2">
                            <i class="bi bi-gem" style="color: #A855F7;"></i> Rangos del SMP
                        </h2>
                        <p style="color: #9CA3AF; font-size: 0.85rem; margin: 0;">Selecciona si deseas un rango de 30 días o permanente.</p>
                    </div>

                    <!-- SELECTOR GLOBAL REDISEÑADO CON ESTILO PREMIUM -->
                    <div class="duration-toggle-wrapper p-1 rounded-pill d-inline-flex align-items-center" style="background: #0D0B14; border: 1px solid rgba(168, 85, 247, 0.4); box-shadow: 0 0 15px rgba(147, 51, 234, 0.2);">
                        <button type="button" class="btn btn-sm rounded-pill px-3 py-2 border-0 fw-bold d-flex align-items-center gap-2" id="global-btn-temp" onclick="switchGlobalBilling('temp')" style="background: transparent; color: #9CA3AF; transition: all 0.3s ease; font-size: 0.82rem;">
                            <i class="bi bi-clock-history"></i>
                            <span>30 Días</span>
                        </button>
                        <button type="button" class="btn btn-sm rounded-pill px-3 py-2 border-0 fw-bold d-flex align-items-center gap-2" id="global-btn-perm" onclick="switchGlobalBilling('perm')" style="background: linear-gradient(135deg, #9333EA 0%, #7E22CE 100%); color: #FFFFFF; box-shadow: 0 0 12px rgba(168, 85, 247, 0.6); transition: all 0.3s ease; font-size: 0.82rem;">
                            <i class="bi bi-infinity"></i>
                            <span>Permanente</span>
                            <span class="badge rounded-pill text-uppercase" style="background: rgba(255, 255, 255, 0.2); font-size: 0.6rem; letter-spacing: 0.05em;">Popular</span>
                        </button>
                    </div>
                </div>

                <!-- 5 Tarjetas de Rangos con Selector Individual -->
                @php
                $ranks = [
                    [
                        'id' => 'nova',
                        'name' => 'Nova',
                        'icon' => 'diamond_sword.png',
                        'color' => '#60A5FA',
                        'priceTemp' => 3,
                        'pricePerm' => 5,
                        'permId' => $rankPackages['nova']['permId'],
                        'tempId' => $rankPackages['nova']['tempId'],
                        'features' => ['3 Player Vaults (/pv)', 'Acceso a comando /feed', 'Acceso al Chat VIP', 'Kit Nova diario']
                    ],
                    [
                        'id' => 'vanguard',
                        'name' => 'Vanguard',
                        'icon' => 'emerald.png',
                        'color' => '#4ADE80',
                        'priceTemp' => 5,
                        'pricePerm' => 9,
                        'permId' => $rankPackages['vanguard']['permId'],
                        'tempId' => $rankPackages['vanguard']['tempId'],
                        'features' => ['5 Player Vaults (/pv)', 'Acceso a comando /tpahere', 'Comandos con colores', 'Kit Vanguard diario']
                    ],
                    [
                        'id' => 'ascendant',
                        'name' => 'Ascendant',
                        'icon' => 'netherite_sword.png',
                        'color' => '#F87171',
                        'priceTemp' => 7,
                        'pricePerm' => 15,
                        'permId' => $rankPackages['ascendant']['permId'],
                        'tempId' => $rankPackages['ascendant']['tempId'],
                        'features' => ['10 Player Vaults (/pv)', 'Acceso a comando /repair', 'Entrar a servidores llenos', 'Kit Ascendant diario']
                    ],
                    [
                        'id' => 'eternal',
                        'name' => 'Eternal',
                        'icon' => 'totem.png',
                        'color' => '#FBBF24',
                        'priceTemp' => 11,
                        'pricePerm' => 20,
                        'permId' => $rankPackages['eternal']['permId'],
                        'tempId' => $rankPackages['eternal']['tempId'],
                        'features' => ['15 Player Vaults (/pv)', 'Acceso a comando /fly', '1 Revive por temporada', 'Kit Eternal diario']
                    ],
                    [
                        'id' => 'onyxs',
                        'name' => 'Onyxs',
                        'icon' => 'nether_star.png',
                        'color' => '#C084FC',
                        'priceTemp' => 14,
                        'pricePerm' => 34,
                        'permId' => $rankPackages['onyxs']['permId'],
                        'tempId' => $rankPackages['onyxs']['tempId'],
                        'features' => ['25 Player Vaults (/pv)', 'Acceso a /repair all', 'x2 Key All en eventos', 'Todos los Kits + Acceso Beta']
                    ],
                ];
                @endphp

                <div class="row g-4 mb-5 justify-content-center">
                    @foreach($ranks as $rank)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3 position-relative" style="border: 1px solid {{ $rank['color'] }}35; background: #14121F;">
                            @if($rank['id'] === 'onyxs')
                            <span class="badge position-absolute top-0 end-0 m-2 fw-bold text-uppercase" style="background: linear-gradient(135deg, #7E22CE, #C084FC); color: #fff; font-size: 0.65rem; letter-spacing: 0.08em; border: 1px solid rgba(255,255,255,0.2);">
                                Rango Supremo
                            </span>
                            @endif
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/' . $rank['icon']) }}" alt="{{ $rank['name'] }}" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 10px {{ $rank['color'] }}80); image-rendering: pixelated;">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: {{ $rank['color'] }}; margin-top: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                    {{ $rank['name'] }}
                                </h5>

                                <div class="my-2">
                                    <span class="badge duration-badge-{{ $rank['id'] }}" style="font-size: 0.72rem; color: #C084FC; background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.35); font-weight: 600; padding: 4px 12px; border-radius: 20px; display: inline-block;">
                                        Permanente
                                    </span>
                                </div>

                                <div style="font-size: 1.4rem; font-weight: 700; color: #FFFFFF; margin-bottom: 0.25rem;">
                                    $<span class="price-display-{{ $rank['id'] }}">{{ $rank['pricePerm'] }}.00</span> <span style="font-size: 0.8rem; color: #9CA3AF; font-weight: 500;">USD</span>
                                </div>
                                <div class="price-subtitle-{{ $rank['id'] }}" style="font-size: 0.72rem; color: #9CA3AF; margin-bottom: 1rem;">
                                    <i class="bi bi-shield-lock me-1"></i> Pago único &bull; De por vida
                                </div>

                                <div class="mt-auto">
                                    @php
                                        $defaultPkgUrl = $rank['permId'] ? url('/shop/packages/' . $rank['permId']) : url('/shop/categories/ranks');
                                    @endphp
                                    <a href="{{ $defaultPkgUrl }}" @if($rank['permId']) data-package-url="{{ $defaultPkgUrl }}" @endif class="btn btn-primary btn-sm w-100 fw-bold buy-btn-{{ $rank['id'] }}" style="background: linear-gradient(135deg, #9333EA 0%, #7E22CE 100%); border: 1px solid rgba(192, 132, 252, 0.4);">
                                        <i class="bi bi-cart3 me-1"></i> Adquirir Rango
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Tabla Comparativa -->
                <div class="card mb-5 shadow">
                    <div class="card-header py-3" style="background: rgba(147, 51, 234, 0.06); border-bottom: 1px solid rgba(168, 85, 247, 0.2);">
                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #fff; margin: 0;">Comparar Beneficios</h4>
                        <p style="font-size: 0.82rem; color: #9ca3af; margin: 0;">Compara detalladamente los comandos y ventajas de cada rango.</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th style="color: #9ca3af; font-weight: 600; padding: 1rem;">Beneficio</th>
                                        <th class="text-center" style="color: #60A5FA; font-family: 'Outfit', sans-serif; font-weight: 700;">Nova</th>
                                        <th class="text-center" style="color: #4ADE80; font-family: 'Outfit', sans-serif; font-weight: 700;">Vanguard</th>
                                        <th class="text-center" style="color: #F87171; font-family: 'Outfit', sans-serif; font-weight: 700;">Ascendant</th>
                                        <th class="text-center" style="color: #FBBF24; font-family: 'Outfit', sans-serif; font-weight: 700;">Eternal</th>
                                        <th class="text-center" style="color: #C084FC; font-family: 'Outfit', sans-serif; font-weight: 700;">Onyxs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 0.85rem 1rem; font-weight: 600;">Player Vaults (/pv)</td>
                                        <td class="text-center fw-bold text-white">3</td>
                                        <td class="text-center fw-bold text-white">5</td>
                                        <td class="text-center fw-bold text-white">10</td>
                                        <td class="text-center fw-bold text-white">15</td>
                                        <td class="text-center fw-bold text-white">25</td>
                                    </tr>
                                    @php
                                    $tableFeatures = [
                                        ['name' => 'Acceso a Chat VIP', 'checks' => [true, true, true, true, true]],
                                        ['name' => 'Comando /feed', 'checks' => [true, true, true, true, true]],
                                        ['name' => 'Comando /tpahere', 'checks' => [false, true, true, true, true]],
                                        ['name' => 'Acceso a Comandos de Colores', 'checks' => [false, true, true, true, true]],
                                        ['name' => 'Entrar a Servidores Llenos', 'checks' => [false, false, true, true, true]],
                                        ['name' => 'Comando /repair', 'checks' => [false, false, true, true, true]],
                                        ['name' => 'Comando /fly', 'checks' => [false, false, false, true, true]],
                                        ['name' => '1 Revive por Temporada', 'checks' => [false, false, false, true, true]],
                                        ['name' => 'Comando /repair all', 'checks' => [false, false, false, false, true]],
                                        ['name' => 'Acceso a Todos los Kits + Reclaims', 'checks' => [false, false, false, false, true]],
                                        ['name' => 'x2 Key All en Eventos', 'checks' => [false, false, false, false, true]],
                                        ['name' => 'Acceso a Beta Test Exclusivo', 'checks' => [false, false, false, false, true]],
                                    ];
                                    @endphp
                                    @foreach($tableFeatures as $feat)
                                    <tr>
                                        <td style="padding: 0.85rem 1rem; font-size: 0.9rem;">{{ $feat['name'] }}</td>
                                        @foreach($feat['checks'] as $check)
                                        <td class="text-center">
                                            @if($check)
                                                <i class="bi bi-check-lg text-success fs-5"></i>
                                            @else
                                                <i class="bi bi-dash-lg text-secondary opacity-25"></i>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2A: CRATES SMP                         -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="tab-smp-crates" role="tabpanel">
                <div class="mb-4">
                    <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin: 0;" class="d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam" style="color: #60A5FA;"></i> Cajas de Recompensas (Crates SMP)
                    </h2>
                    <p style="color: #9CA3AF; font-size: 0.85rem; margin: 0;">Adquiere llaves mágicas para abrir Cajas de Recompensas y conseguir ítems legendarios, kits y armaduras en Onyxs SMP.</p>
                </div>

                <!-- Banner Informativo Llaves -->
                <div class="p-3 rounded mb-4 d-flex align-items-center justify-content-between" style="background: rgba(96, 165, 250, 0.08); border: 1px solid rgba(96, 165, 250, 0.25);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(96, 165, 250, 0.15); border: 1px solid rgba(96, 165, 250, 0.3);">
                            <i class="bi bi-key-fill" style="color: #60A5FA; font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #FFFFFF; font-size: 0.88rem;">Llaves Virtuales Directas al Inventario</div>
                            <div style="font-size: 0.75rem; color: #9CA3AF;">Usa tus llaves en la zona de Spawn (/spawn o /warp crates) para abrir tus cajas mágicas.</div>
                        </div>
                    </div>
                    <span class="badge" style="background: rgba(96, 165, 250, 0.15); color: #60A5FA; border: 1px solid rgba(96, 165, 250, 0.3); font-size: 0.7rem;">6 Cajas</span>
                </div>

                <div class="row g-4 mb-5">
                    <!-- 1. Nova Crate -->
                    @php
                        $pkgNova = $cratePackages['nova'] ?? null;
                        $urlNova = $pkgNova ? url('/shop/packages/' . $pkgNova->id) : url('/shop/categories/keysmp');
                        $priceNova = $pkgNova ? '$' . number_format($pkgNova->price, 2) . ' USD' : '$1.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3" style="border: 1px solid rgba(96, 165, 250, 0.25); background: #14121F;">
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/tripwire_hook.png') }}" alt="Nova Crate" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.6));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Nova Crate</h5>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #60A5FA; margin: 0.5rem 0;">Tier I</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Llave SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceNova }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlNova }}" @if($pkgNova) data-package-url="{{ $urlNova }}" @endif class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Llaves
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Sovereign Crate -->
                    @php
                        $pkgSov = $cratePackages['sovereign'] ?? null;
                        $urlSov = $pkgSov ? url('/shop/packages/' . $pkgSov->id) : url('/shop/categories/keysmp');
                        $priceSov = $pkgSov ? '$' . number_format($pkgSov->price, 2) . ' USD' : '$3.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3" style="border: 1px solid rgba(74, 222, 128, 0.25); background: #14121F;">
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/chest.png') }}" alt="Sovereign Crate" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(74, 222, 128, 0.6));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Sovereign Crate</h5>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #4ADE80; margin: 0.5rem 0;">Tier II</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Llave SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceSov }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlSov }}" @if($pkgSov) data-package-url="{{ $urlSov }}" @endif class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Llaves
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Ascendant Crate -->
                    @php
                        $pkgAsc = $cratePackages['ascendant'] ?? null;
                        $urlAsc = $pkgAsc ? url('/shop/packages/' . $pkgAsc->id) : url('/shop/categories/keysmp');
                        $priceAsc = $pkgAsc ? '$' . number_format($pkgAsc->price, 2) . ' USD' : '$3.50 USD';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3" style="border: 1px solid rgba(248, 113, 113, 0.25); background: #14121F;">
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/netherite_sword.png') }}" alt="Ascendant Crate" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(248, 113, 113, 0.6));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Ascendant Crate</h5>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #F87171; margin: 0.5rem 0;">Tier III</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Llave SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceAsc }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlAsc }}" @if($pkgAsc) data-package-url="{{ $urlAsc }}" @endif class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Llaves
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Eternal Crate -->
                    @php
                        $pkgEte = $cratePackages['eternal'] ?? null;
                        $urlEte = $pkgEte ? url('/shop/packages/' . $pkgEte->id) : url('/shop/categories/keysmp');
                        $priceEte = $pkgEte ? '$' . number_format($pkgEte->price, 2) . ' USD' : '$4.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3" style="border: 1px solid rgba(245, 158, 11, 0.25); background: #14121F;">
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/totem.png') }}" alt="Eternal Crate" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.6));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Eternal Crate</h5>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #F59E0B; margin: 0.5rem 0;">Tier IV</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Llave SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceEte }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlEte }}" @if($pkgEte) data-package-url="{{ $urlEte }}" @endif class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Llaves
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Onyxs Crate -->
                    @php
                        $pkgOny = $cratePackages['onyxs'] ?? null;
                        $urlOny = $pkgOny ? url('/shop/packages/' . $pkgOny->id) : url('/shop/categories/keysmp');
                        $priceOny = $pkgOny ? '$' . number_format($pkgOny->price, 2) . ' USD' : '$8.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3 position-relative" style="border: 1px solid rgba(192, 132, 252, 0.35); background: #14121F;">
                            <span class="badge position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; background: #9333EA !important;">Tier Máximo</span>
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/ender_chest.png') }}" alt="Onyxs Crate" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 10px rgba(192, 132, 252, 0.7));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Onyxs Crate</h5>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #C084FC; margin: 0.5rem 0;">Tier V</div>
                                <span style="font-size: 0.75rem; color: #C084FC; font-weight: 600; display: block; margin-bottom: 1rem;">Llave Mítica SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceOny }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlOny }}" @if($pkgOny) data-package-url="{{ $urlOny }}" @endif class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Llaves
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Obsidian Crate (LA CAJA ESPECIAL / DEFINITIVA) -->
                    @php
                        $pkgObs = $cratePackages['obsidian'] ?? null;
                        $urlObs = $pkgObs ? url('/shop/packages/' . $pkgObs->id) : url('/shop/categories/keysmp');
                        $priceObs = $pkgObs ? '$' . number_format($pkgObs->price, 2) . ' USD' : '$10.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow text-center p-3 position-relative" style="border: 2px solid #7E22CE; background: linear-gradient(180deg, #161224 0%, #0B0A12 100%); box-shadow: 0 0 25px rgba(126, 34, 206, 0.25) !important;">
                            <span class="badge position-absolute top-0 end-0 m-2 fw-bold text-uppercase" style="background: linear-gradient(135deg, #7E22CE, #C084FC); color: #fff; font-size: 0.65rem; letter-spacing: 0.08em; border: 1px solid rgba(255,255,255,0.2);">
                                Bóveda Secreta
                            </span>
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/crying_obsidian.png') }}" alt="Obsidian Crate" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 12px rgba(168, 85, 247, 0.8));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Obsidian Crate</h5>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #C084FC; margin: 0.5rem 0;">Secreta</div>
                                <span style="font-size: 0.75rem; color: #C084FC; font-weight: 600; display: block; margin-bottom: 1rem;">Llave Definitiva</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceObs }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlObs }}" @if($pkgObs) data-package-url="{{ $urlObs }}" @endif class="btn btn-primary btn-sm w-100 fw-bold" style="background: linear-gradient(135deg, #7E22CE 0%, #9333EA 100%); border: 1px solid rgba(192, 132, 252, 0.4);">
                                        <i class="bi bi-cart3 me-1"></i> Adquirir Llave
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2B: SHARDS SMP                         -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="tab-smp-shards" role="tabpanel">
                <div class="mb-4">
                    <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin: 0;" class="d-flex align-items-center gap-2">
                        <i class="bi bi-gem" style="color: #C084FC;"></i> Shards & Economía del SMP
                    </h2>
                    <p style="color: #9CA3AF; font-size: 0.85rem; margin: 0;">Consigue Shards oficiales para comerciar en la casa de subastas, mejorar tus protecciones y dominar la economía de Onyxs SMP.</p>
                </div>

                <!-- Banner Informativo Shards -->
                <div class="p-3 rounded mb-4 d-flex align-items-center justify-content-between" style="background: rgba(168, 85, 247, 0.08); border: 1px solid rgba(168, 85, 247, 0.25);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3);">
                            <i class="bi bi-shield-check" style="color: #C084FC; font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #FFFFFF; font-size: 0.88rem;">Moneda Oficial del Servidor</div>
                            <div style="font-size: 0.75rem; color: #9CA3AF;">Los Shards se acreditan automáticamente en tu saldo virtual (/shards balance) al instante.</div>
                        </div>
                    </div>
                    <span class="badge" style="background: rgba(74, 222, 128, 0.15); color: #4ADE80; border: 1px solid rgba(74, 222, 128, 0.3); font-size: 0.7rem;">Entrega Instantánea</span>
                </div>

                <div class="row g-4 mb-5">
                    <!-- Pack 1: 25 Shards -->
                    @php
                        $pkgS25 = $shardPackages[25] ?? null;
                        $urlS25 = $pkgS25 ? url('/shop/packages/' . $pkgS25->id) : url('/shop/categories/shards');
                        $priceS25 = $pkgS25 ? '$' . number_format($pkgS25->price, 2) . ' USD' : '$8.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100 shadow text-center p-3" style="border: 1px solid rgba(168, 85, 247, 0.2); background: #14121F;">
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/amethyst_shard.png') }}" alt="25 Shards" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.5));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Bolsa de Shards</h5>
                                <div style="font-size: 1.8rem; font-weight: 800; color: #FBBF24; margin: 0.5rem 0;">25</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Shards SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceS25 }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlS25 }}" @if($pkgS25) data-package-url="{{ $urlS25 }}" @endif class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Pack
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pack 2: 50 Shards -->
                    @php
                        $pkgS50 = $shardPackages[50] ?? null;
                        $urlS50 = $pkgS50 ? url('/shop/packages/' . $pkgS50->id) : url('/shop/categories/shards');
                        $priceS50 = $pkgS50 ? '$' . number_format($pkgS50->price, 2) . ' USD' : '$18.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100 shadow text-center p-3" style="border: 1px solid rgba(168, 85, 247, 0.2); background: #14121F;">
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/bundle.png') }}" alt="50 Shards" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.5));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Saco de Shards</h5>
                                <div style="font-size: 1.8rem; font-weight: 800; color: #60A5FA; margin: 0.5rem 0;">50</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Shards SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceS50 }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlS50 }}" @if($pkgS50) data-package-url="{{ $urlS50 }}" @endif class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Pack
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pack 3: 100 Shards -->
                    @php
                        $pkgS100 = $shardPackages[100] ?? null;
                        $urlS100 = $pkgS100 ? url('/shop/packages/' . $pkgS100->id) : url('/shop/categories/shards');
                        $priceS100 = $pkgS100 ? '$' . number_format($pkgS100->price, 2) . ' USD' : '$30.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100 shadow text-center p-3 position-relative" style="border: 1px solid rgba(74, 222, 128, 0.35); background: #14121F;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-2" style="font-size: 0.65rem;">+15% Extra</span>
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/ender_chest.png') }}" alt="100 Shards" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 10px rgba(74, 222, 128, 0.5));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Cofre de Shards</h5>
                                <div style="font-size: 1.8rem; font-weight: 800; color: #4ADE80; margin: 0.5rem 0;">100</div>
                                <span style="font-size: 0.75rem; color: #9CA3AF; display: block; margin-bottom: 1rem;">Shards SMP</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceS100 }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlS100 }}" @if($pkgS100) data-package-url="{{ $urlS100 }}" @endif class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Pack
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pack 4: 200 Shards (MÁXIMO PERMITIDO) -->
                    @php
                        $pkgS200 = $shardPackages[200] ?? null;
                        $urlS200 = $pkgS200 ? url('/shop/packages/' . $pkgS200->id) : url('/shop/categories/shards');
                        $priceS200 = $pkgS200 ? '$' . number_format($pkgS200->price, 2) . ' USD' : '$50.00 USD';
                    @endphp
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100 shadow text-center p-3 position-relative" style="border: 2px solid #9333EA; background: linear-gradient(180deg, #181329 0%, #100D1B 100%); box-shadow: 0 0 20px rgba(147, 51, 234, 0.25) !important;">
                            <span class="badge position-absolute top-0 end-0 m-2 fw-bold text-uppercase" style="background: linear-gradient(135deg, #9333EA, #C084FC); color: #fff; font-size: 0.65rem;">
                                Pack Máximo
                            </span>
                            <div class="p-3 d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-center" style="height: 52px;">
                                    <img src="{{ theme_asset('images/shulker_box.png') }}" alt="200 Shards" style="width: 44px; height: 44px; object-fit: contain; filter: drop-shadow(0 0 12px rgba(192, 132, 252, 0.6));">
                                </div>
                                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-top: 0.75rem;">Bóveda de Shards</h5>
                                <div style="font-size: 1.8rem; font-weight: 800; color: #C084FC; margin: 0.5rem 0;">200</div>
                                <span style="font-size: 0.75rem; color: #C084FC; font-weight: 600; display: block; margin-bottom: 1rem;">Shards SMP (Límite Máximo)</span>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF; margin-bottom: 1rem;">{{ $priceS200 }}</div>
                                <div class="mt-auto">
                                    <a href="{{ $urlS200 }}" @if($pkgS200) data-package-url="{{ $urlS200 }}" @endif class="btn btn-primary btn-sm w-100 fw-bold" style="background: linear-gradient(135deg, #9333EA 0%, #7E22CE 100%); border: 1px solid rgba(192, 132, 252, 0.4);">
                                        <i class="bi bi-cart3 me-1"></i> Comprar Pack
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: FULL PVP (CRATES)                   -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="tab-fullpvp" role="tabpanel">
                <div class="mb-4">
                    <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin: 0;" class="d-flex align-items-center gap-2">
                        <i class="bi bi-crosshair text-danger"></i> Cajas de Recompensas (Full PvP)
                    </h2>
                    <p style="color: #9CA3AF; font-size: 0.85rem; margin: 0;">Abre cajas en la arena de Onyxs Full PvP con tus llaves y equipa a tu guerrero con armaduras, armas y tótems.</p>
                </div>

                <div class="row g-4 mb-5">
                    <!-- 1. Crate Común -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow" style="border: 1px solid rgba(156, 163, 175, 0.3); background: #14121F;">
                            <div style="height: 4px; background: #9CA3AF;"></div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #9CA3AF; margin: 0; font-size: 1.25rem;">Crate Común</h4>
                                        <span class="badge" style="background: rgba(156, 163, 175, 0.15); color: #9CA3AF; border: 1px solid rgba(156, 163, 175, 0.3);">Tier I</span>
                                    </div>
                                    <div class="py-4 my-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px dashed rgba(156, 163, 175, 0.25);">
                                        <i class="bi bi-box-seam fs-1 mb-2" style="color: #9CA3AF;"></i>
                                        <span style="font-size: 0.85rem; color: #D1D5DB; font-weight: 600;">Recompensas en preparación</span>
                                        <span style="font-size: 0.72rem; color: #9CA3AF;">Modalidad Full PvP</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.65; font-weight: 600; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #9CA3AF;">
                                        <i class="bi bi-clock me-1"></i> Próximamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Crate Poco Común -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow" style="border: 1px solid rgba(74, 222, 128, 0.3); background: #14121F;">
                            <div style="height: 4px; background: #4ADE80;"></div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #4ADE80; margin: 0; font-size: 1.25rem;">Crate Poco Común</h4>
                                        <span class="badge" style="background: rgba(74, 222, 128, 0.15); color: #4ADE80; border: 1px solid rgba(74, 222, 128, 0.3);">Tier II</span>
                                    </div>
                                    <div class="py-4 my-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px dashed rgba(74, 222, 128, 0.25);">
                                        <i class="bi bi-box-seam fs-1 mb-2" style="color: #4ADE80;"></i>
                                        <span style="font-size: 0.85rem; color: #D1D5DB; font-weight: 600;">Recompensas en preparación</span>
                                        <span style="font-size: 0.72rem; color: #9CA3AF;">Modalidad Full PvP</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.65; font-weight: 600; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #9CA3AF;">
                                        <i class="bi bi-clock me-1"></i> Próximamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Crate Rara -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow" style="border: 1px solid rgba(96, 165, 250, 0.3); background: #14121F;">
                            <div style="height: 4px; background: #60A5FA;"></div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #60A5FA; margin: 0; font-size: 1.25rem;">Crate Rara</h4>
                                        <span class="badge" style="background: rgba(96, 165, 250, 0.15); color: #60A5FA; border: 1px solid rgba(96, 165, 250, 0.3);">Tier III</span>
                                    </div>
                                    <div class="py-4 my-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px dashed rgba(96, 165, 250, 0.25);">
                                        <i class="bi bi-box-seam fs-1 mb-2" style="color: #60A5FA;"></i>
                                        <span style="font-size: 0.85rem; color: #D1D5DB; font-weight: 600;">Recompensas en preparación</span>
                                        <span style="font-size: 0.72rem; color: #9CA3AF;">Modalidad Full PvP</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.65; font-weight: 600; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #9CA3AF;">
                                        <i class="bi bi-clock me-1"></i> Próximamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Crate Épica -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow" style="border: 1px solid rgba(168, 85, 247, 0.3); background: #14121F;">
                            <div style="height: 4px; background: #A855F7;"></div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #C084FC; margin: 0; font-size: 1.25rem;">Crate Épica</h4>
                                        <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #C084FC; border: 1px solid rgba(168, 85, 247, 0.3);">Tier IV</span>
                                    </div>
                                    <div class="py-4 my-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px dashed rgba(168, 85, 247, 0.25);">
                                        <i class="bi bi-box-seam fs-1 mb-2" style="color: #C084FC;"></i>
                                        <span style="font-size: 0.85rem; color: #D1D5DB; font-weight: 600;">Recompensas en preparación</span>
                                        <span style="font-size: 0.72rem; color: #9CA3AF;">Modalidad Full PvP</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.65; font-weight: 600; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #9CA3AF;">
                                        <i class="bi bi-clock me-1"></i> Próximamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Crate Legendaria -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow" style="border: 1px solid rgba(245, 158, 11, 0.3); background: #14121F;">
                            <div style="height: 4px; background: #F59E0B;"></div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #F59E0B; margin: 0; font-size: 1.25rem;">Crate Legendaria</h4>
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3);">Tier V</span>
                                    </div>
                                    <div class="py-4 my-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px dashed rgba(245, 158, 11, 0.25);">
                                        <i class="bi bi-box-seam fs-1 mb-2" style="color: #F59E0B;"></i>
                                        <span style="font-size: 0.85rem; color: #D1D5DB; font-weight: 600;">Recompensas en preparación</span>
                                        <span style="font-size: 0.72rem; color: #9CA3AF;">Modalidad Full PvP</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.65; font-weight: 600; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #9CA3AF;">
                                        <i class="bi bi-clock me-1"></i> Próximamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Crate Mítica -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow position-relative" style="border: 2px solid #EF4444; background: linear-gradient(180deg, #1C1014 0%, #0D080A 100%); box-shadow: 0 0 25px rgba(239, 68, 68, 0.25) !important;">
                            <span class="badge position-absolute top-0 end-0 m-2 fw-bold text-uppercase" style="background: linear-gradient(135deg, #DC2626, #EF4444); color: #fff; font-size: 0.65rem; letter-spacing: 0.08em; border: 1px solid rgba(255,255,255,0.2);">
                                PvP Supremo
                            </span>
                            <div style="height: 4px; background: linear-gradient(90deg, #7F1D1D 0%, #DC2626 50%, #EF4444 100%);"></div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #FFFFFF; margin: 0; font-size: 1.25rem;">
                                            <i class="bi bi-fire me-1" style="color: #EF4444;"></i> Crate Mítica
                                        </h4>
                                        <span class="badge" style="background: #DC2626; color: #fff;">Tier VI &bull; Máxima</span>
                                    </div>
                                    <div class="py-4 my-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(239, 68, 68, 0.04); border-radius: 8px; border: 1px dashed rgba(239, 68, 68, 0.3);">
                                        <i class="bi bi-fire fs-1 mb-2" style="color: #EF4444;"></i>
                                        <span style="font-size: 0.85rem; color: #FCA5A5; font-weight: 600;">Recompensas en preparación</span>
                                        <span style="font-size: 0.72rem; color: #9CA3AF;">Modalidad Full PvP</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.65; font-weight: 600; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #9CA3AF;">
                                        <i class="bi bi-clock me-1"></i> Próximamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Noticias Recientes -->
        @if(isset($posts) && count($posts) > 0)
        <div class="mt-4">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #fff; margin-bottom: 1.25rem;" class="d-flex align-items-center">
                <i class="bi bi-newspaper me-2" style="color: #A855F7;"></i> Noticias de la Comunidad
            </h3>
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm" style="overflow: hidden; border: 1px solid rgba(168, 85, 247, 0.2); background: #14121F;">
                        @if($post->hasImage())
                            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="card-img-top" style="height: 11rem; object-fit: cover;">
                        @endif
                        <div class="card-body p-4">
                            <h5 class="card-title text-white" style="font-family: 'Outfit', sans-serif; font-weight: 700;">{{ $post->title }}</h5>
                            <p style="font-size: 0.85rem; color: #9ca3af; margin-bottom: 1rem;">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                            <a href="{{ route('posts.show', $post->slug) }}" style="color: #C084FC; font-weight: 600; font-size: 0.85rem;" class="text-decoration-none">
                                Leer artículo completo &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Nativo de Compra de Azuriom Shop -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true"></div>

<!-- SCRIPT INTERACTIVO CONECTADO A LOS PAQUETES REALES -->
<script>
    const rankData = {
        nova: {
            tempPrice: 3,
            permPrice: 5,
            tempId: {{ $rankPackages['nova']['tempId'] }},
            permId: {{ $rankPackages['nova']['permId'] }}
        },
        vanguard: {
            tempPrice: 5,
            permPrice: 9,
            tempId: {{ $rankPackages['vanguard']['tempId'] }},
            permId: {{ $rankPackages['vanguard']['permId'] }}
        },
        ascendant: {
            tempPrice: 7,
            permPrice: 15,
            tempId: {{ $rankPackages['ascendant']['tempId'] }},
            permId: {{ $rankPackages['ascendant']['permId'] }}
        },
        eternal: {
            tempPrice: 11,
            permPrice: 20,
            tempId: {{ $rankPackages['eternal']['tempId'] }},
            permId: {{ $rankPackages['eternal']['permId'] }}
        },
        onyxs: {
            tempPrice: 14,
            permPrice: 34,
            tempId: {{ $rankPackages['onyxs']['tempId'] }},
            permId: {{ $rankPackages['onyxs']['permId'] }}
        }
    };

    // Cambiar duración individual de una tarjeta y actualizar su botón de compra
    function setRankDuration(rankId, mode) {
        const data = rankData[rankId];
        if (!data) return;

        const priceEl = document.querySelector('.price-display-' + rankId);
        const subEl = document.querySelector('.price-subtitle-' + rankId);
        const badgeEl = document.querySelector('.duration-badge-' + rankId);
        const buyBtn = document.querySelector('.buy-btn-' + rankId);

        const isTemp = (mode === 'temp');
        const price = isTemp ? data.tempPrice : data.permPrice;
        const targetPackageId = isTemp ? data.tempId : data.permId;
        const targetPackageUrl = '/shop/packages/' + targetPackageId;

        // Actualizar precio
        if (priceEl) priceEl.textContent = price + '.00';

        // Actualizar URL del botón de compra del paquete
        if (buyBtn) {
            buyBtn.setAttribute('href', targetPackageUrl);
            buyBtn.setAttribute('data-package-url', targetPackageUrl);
        }

        if (isTemp) {
            if (subEl) subEl.innerHTML = '<i class="bi bi-clock-history me-1"></i> Duración: 30 Días (Renovable)';
            if (badgeEl) {
                badgeEl.textContent = '30 Días';
                badgeEl.style.background = 'rgba(96, 165, 250, 0.2)';
                badgeEl.style.color = '#60A5FA';
                badgeEl.style.borderColor = 'rgba(96, 165, 250, 0.4)';
            }
        } else {
            if (subEl) subEl.innerHTML = '<i class="bi bi-shield-lock me-1"></i> Pago único • De por vida';
            if (badgeEl) {
                badgeEl.textContent = 'Permanente';
                badgeEl.style.background = 'rgba(168, 85, 247, 0.2)';
                badgeEl.style.color = '#C084FC';
                badgeEl.style.borderColor = 'rgba(168, 85, 247, 0.4)';
            }
        }
    }

    // Cambiar duración global para todos los rangos simultáneamente
    function switchGlobalBilling(mode) {
        const btnTemp = document.getElementById('global-btn-temp');
        const btnPerm = document.getElementById('global-btn-perm');

        if (mode === 'temp') {
            btnTemp.style.background = 'linear-gradient(135deg, #9333EA 0%, #7E22CE 100%)';
            btnTemp.style.color = '#FFFFFF';
            btnTemp.style.boxShadow = '0 0 12px rgba(168, 85, 247, 0.6)';

            btnPerm.style.background = 'transparent';
            btnPerm.style.color = '#9CA3AF';
            btnPerm.style.boxShadow = 'none';

            for (const key in rankData) {
                setRankDuration(key, 'temp');
            }
        } else {
            btnPerm.style.background = 'linear-gradient(135deg, #9333EA 0%, #7E22CE 100%)';
            btnPerm.style.color = '#FFFFFF';
            btnPerm.style.boxShadow = '0 0 12px rgba(168, 85, 247, 0.6)';

            btnTemp.style.background = 'transparent';
            btnTemp.style.color = '#9CA3AF';
            btnTemp.style.boxShadow = 'none';

            for (const key in rankData) {
                setRankDuration(key, 'perm');
            }
        }
    }

    // Conectar eventos AJAX para abrir el modal nativo de Azuriom Shop al hacer clic en Comprar
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-package-url]').forEach(function (el) {
            el.addEventListener('click', function (ev) {
                var url = el.getAttribute('data-package-url');
                if (!url || url === '#' || url.startsWith('#')) return;

                ev.preventDefault();

                if (window.axios) {
                    axios.get(url).then(function (response) {
                        var itemModal = document.getElementById('itemModal');
                        if (itemModal) {
                            itemModal.innerHTML = response.data;
                            var modal = new bootstrap.Modal(itemModal);
                            modal.show();
                        } else {
                            window.location.href = url;
                        }
                    }).catch(function () {
                        window.location.href = url;
                    });
                } else {
                    window.location.href = url;
                }
            });
        });

        // Sistema universal y robusto de navegación de categorías y pestañas
        function selectCategoryTab(tabId, targetBtn) {
            // 1. Mostrar/ocultar tab-panes con animación
            const allPanes = document.querySelectorAll('.tab-content > .tab-pane');
            allPanes.forEach(function (pane) {
                pane.classList.remove('show', 'active');
            });

            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }

            // 2. Actualizar estados active en todos los botones de la navegación lateral
            document.querySelectorAll('.category-item, .subcategory-item').forEach(function (btn) {
                btn.classList.remove('active');
            });

            if (targetBtn) {
                targetBtn.classList.add('active');
            }

            // 3. Manejar acordeones y clases de estado padre
            const smpToggle = document.getElementById('smp-group-toggle');
            const fullpvpToggle = document.getElementById('fullpvp-group-toggle');
            const smpMenu = document.getElementById('menu-smp');
            const fullpvpMenu = document.getElementById('menu-fullpvp');

            if (tabId === 'tab-ranks') {
                if (smpToggle) smpToggle.classList.remove('is-active-parent');
                if (fullpvpToggle) fullpvpToggle.classList.remove('is-active-parent');
                if (smpMenu && smpMenu.classList.contains('show')) {
                    smpMenu.classList.remove('show');
                    if (smpToggle) {
                        smpToggle.classList.add('collapsed');
                        smpToggle.setAttribute('aria-expanded', 'false');
                    }
                }
                if (fullpvpMenu && fullpvpMenu.classList.contains('show')) {
                    fullpvpMenu.classList.remove('show');
                    if (fullpvpToggle) {
                        fullpvpToggle.classList.add('collapsed');
                        fullpvpToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            } else if (tabId === 'tab-smp-crates' || tabId === 'tab-smp-shards') {
                if (smpToggle) smpToggle.classList.add('is-active-parent');
                if (fullpvpToggle) fullpvpToggle.classList.remove('is-active-parent');
                if (fullpvpMenu && fullpvpMenu.classList.contains('show')) {
                    fullpvpMenu.classList.remove('show');
                    if (fullpvpToggle) {
                        fullpvpToggle.classList.add('collapsed');
                        fullpvpToggle.setAttribute('aria-expanded', 'false');
                    }
                }
                if (smpMenu && !smpMenu.classList.contains('show')) {
                    smpMenu.classList.add('show');
                    if (smpToggle) {
                        smpToggle.classList.remove('collapsed');
                        smpToggle.setAttribute('aria-expanded', 'true');
                    }
                }
            } else if (tabId === 'tab-fullpvp') {
                if (fullpvpToggle) fullpvpToggle.classList.add('is-active-parent');
                if (smpToggle) smpToggle.classList.remove('is-active-parent');
                if (smpMenu && smpMenu.classList.contains('show')) {
                    smpMenu.classList.remove('show');
                    if (smpToggle) {
                        smpToggle.classList.add('collapsed');
                        smpToggle.setAttribute('aria-expanded', 'false');
                    }
                }
                if (fullpvpMenu && !fullpvpMenu.classList.contains('show')) {
                    fullpvpMenu.classList.add('show');
                    if (fullpvpToggle) {
                        fullpvpToggle.classList.remove('collapsed');
                        fullpvpToggle.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        }

        // 1. Botón RANGOS
        const btnRanks = document.getElementById('tab-ranks-btn');
        if (btnRanks) {
            btnRanks.addEventListener('click', function (e) {
                e.preventDefault();
                selectCategoryTab('tab-ranks', btnRanks);
            });
        }

        // 2. Acordeón SMP
        const smpToggle = document.getElementById('smp-group-toggle');
        const smpMenu = document.getElementById('menu-smp');
        const btnCrates = document.getElementById('tab-smp-crates-btn');
        const btnShards = document.getElementById('tab-smp-shards-btn');

        if (smpToggle) {
            smpToggle.addEventListener('click', function (e) {
                e.preventDefault();
                const isOpen = smpMenu && smpMenu.classList.contains('show');
                if (isOpen) {
                    smpMenu.classList.remove('show');
                    smpToggle.classList.add('collapsed');
                    smpToggle.setAttribute('aria-expanded', 'false');
                } else {
                    const activeBtn = (btnShards && btnShards.classList.contains('active')) ? btnShards : btnCrates;
                    const targetTab = (activeBtn === btnShards) ? 'tab-smp-shards' : 'tab-smp-crates';
                    selectCategoryTab(targetTab, activeBtn);
                }
            });
        }

        if (btnCrates) {
            btnCrates.addEventListener('click', function (e) {
                e.preventDefault();
                selectCategoryTab('tab-smp-crates', btnCrates);
            });
        }

        if (btnShards) {
            btnShards.addEventListener('click', function (e) {
                e.preventDefault();
                selectCategoryTab('tab-smp-shards', btnShards);
            });
        }

        // 3. Acordeón FULLPVP
        const fullpvpToggle = document.getElementById('fullpvp-group-toggle');
        const fullpvpMenu = document.getElementById('menu-fullpvp');
        const btnFullpvpCrates = document.getElementById('tab-fullpvp-crates-btn');

        if (fullpvpToggle) {
            fullpvpToggle.addEventListener('click', function (e) {
                e.preventDefault();
                const isOpen = fullpvpMenu && fullpvpMenu.classList.contains('show');
                if (isOpen) {
                    fullpvpMenu.classList.remove('show');
                    fullpvpToggle.classList.add('collapsed');
                    fullpvpToggle.setAttribute('aria-expanded', 'false');
                } else {
                    selectCategoryTab('tab-fullpvp', btnFullpvpCrates);
                }
            });
        }

        if (btnFullpvpCrates) {
            btnFullpvpCrates.addEventListener('click', function (e) {
                e.preventDefault();
                selectCategoryTab('tab-fullpvp', btnFullpvpCrates);
            });
        }
    });
</script>
@endsection
