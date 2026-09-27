@extends('layouts.app')

@section('title', $page->title)
@section('description', $page->description)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Breadcrumb & Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>
            <h1 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 2.2rem; color: #FFFFFF;" class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-check" style="color: #A855F7;"></i> {{ $page->title }}
            </h1>
            <p style="color: #9CA3AF; font-size: 0.85rem;">Última actualización: {{ date('d/m/Y') }} &bull; Onyxs Network Oficial</p>
        </div>

        <!-- Page Content Card -->
        <div class="card shadow-lg" style="border: 1px solid rgba(168, 85, 247, 0.25); background: #14121F;">
            <div class="card-body p-4 p-md-5" style="line-height: 1.8; color: #E5E7EB; font-size: 0.95rem;">
                {!! $page->content !!}
            </div>
        </div>

        <!-- Support notice footer -->
        <div class="mt-4 p-3 rounded d-flex align-items-center justify-content-between" style="background: rgba(147, 51, 234, 0.08); border: 1px solid rgba(168, 85, 247, 0.2);">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-discord fs-3" style="color: #A855F7;"></i>
                <div>
                    <div style="font-weight: 700; color: #FFFFFF; font-size: 0.9rem;">¿Tienes dudas sobre nuestras políticas?</div>
                    <div style="color: #9CA3AF; font-size: 0.8rem;">Abre un ticket en nuestro Discord oficial para asistencia directa.</div>
                </div>
            </div>
            <a href="https://discord.gg/onyxsmc" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm px-3">
                Unirse a Discord
            </a>
        </div>
    </div>
</div>
@endsection
