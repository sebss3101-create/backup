@extends('layouts.base')

@section('app')
    <main class="container py-4">
        @include('elements.session-alerts')

        @yield('content')
    </main>
@endsection
