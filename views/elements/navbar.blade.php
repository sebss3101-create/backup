<nav class="navbar navbar-expand-lg navbar-onyxs sticky-top">
    <div class="container">
        <!-- Logo de Onyxs Network -->
        <a class="navbar-brand d-flex align-items-center gap-2 py-1" href="{{ route('home') }}">
            <img src="{{ theme_asset('images/logo.png') }}" alt="Onyxs Network" width="40" height="40" style="object-fit: contain; border-radius: 8px;">
            <div class="d-flex flex-column" style="line-height: 1;">
                <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.25rem; color: #fff; letter-spacing: 0.04em;">ONYXS</span>
                <span style="font-size: 0.6rem; color: #C084FC; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase;">NETWORK</span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-2"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menú de Navegación -->
            <ul class="navbar-nav me-auto ps-lg-3 gap-1 align-items-lg-center">
                @foreach($navbar as $element)
                    @if($element->isDropdown())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{ $element->name }}</a>
                            <ul class="dropdown-menu">
                                @foreach($element->elements as $childElement)
                                    <li>
                                        <a class="dropdown-item" href="{{ $childElement->getLink() }}" @if($childElement->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                            {{ $childElement->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link @if($element->isCurrent()) active @endif" href="{{ $element->getLink() }}" @if($element->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $element->name }}
                            </a>
                        </li>
                    @endif
                @endforeach

                <!-- MENÚ DESPLEGABLE LEGAL EN LA NAVBAR -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-check" style="color: #A855F7;"></i> Legal
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="/terms">
                                <i class="bi bi-file-text"></i> Términos y Condiciones
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="/privacy">
                                <i class="bi bi-lock"></i> Política de Privacidad
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="/cookies">
                                <i class="bi bi-shield"></i> Política de Cookies
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: rgba(168,85,247,0.15);"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold" href="/refunds">
                                <i class="bi bi-x-circle-fill"></i> 0 Reembolsos (Estricto)
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Server Status (Pill Centrado con IP Copiable) -->
            <div class="d-none d-lg-flex align-items-center me-3">
                <div class="server-status" onclick="navigator.clipboard.writeText('play.onyxs.lat'); const t = document.getElementById('server-players'); const old = t.textContent; t.textContent = '¡IP copiada!'; setTimeout(() => t.textContent = old, 1500);" title="Haz clic para copiar la IP">
                    <div class="ping-dot">
                        <span class="ping-ring"></span>
                        <span class="ping-core"></span>
                    </div>
                    <div class="d-flex flex-column text-start" style="line-height: 1.15;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: #FFFFFF; letter-spacing: 0.02em;">play.onyxs.lat</span>
                        <span id="server-players" style="font-size: 0.68rem; color: #9CA3AF;">Consultando...</span>
                    </div>
                </div>
            </div>

            <!-- Carrito de Compras & Usuario / Autenticación -->
            <ul class="navbar-nav align-items-center gap-2">
                <!-- Botón de Carrito (Si el plugin Shop de Azuriom está activo) -->
                @if(Route::has('shop.cart.index'))
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('shop.cart.index') }}">
                            <i class="bi bi-cart3"></i>
                            <span>Carrito</span>
                        </a>
                    </li>
                @endif

                @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm px-3" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> Registro
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 py-1 px-2 rounded" style="background: rgba(168, 85, 247, 0.08); border: 1px solid rgba(168, 85, 247, 0.2);" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="https://mc-heads.net/avatar/{{ Auth::user()->name }}/32" alt="{{ Auth::user()->name }}" width="26" height="26" style="border-radius: 4px; image-rendering: pixelated;" onerror="this.src='https://mc-heads.net/avatar/Steve/32'">
                            <span style="font-weight: 600; font-size: 0.9rem; color: #FFFFFF;">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person-circle"></i> Perfil
                                </a>
                            </li>
                            @can('admin.access')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 text-warning" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-shield-lock"></i> Panel Admin
                                    </a>
                                </li>
                            @endcan
                            <li><hr class="dropdown-divider" style="border-color: rgba(168,85,247,0.15);"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<script>
    (function() {
        function checkPlayers() {
            fetch('https://api.minetools.eu/ping/play.onyxs.lat')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var online = (data && data.players && typeof data.players.online !== 'undefined') ? data.players.online : 0;
                    var el = document.getElementById('server-players');
                    if (el) el.textContent = online + ' jugadores en línea';
                })
                .catch(function() {
                    var el = document.getElementById('server-players');
                    if (el) el.textContent = 'En línea';
                });
        }
        checkPlayers();
        setInterval(checkPlayers, 45000);
    })();
</script>
