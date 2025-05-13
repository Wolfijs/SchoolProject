<header class="header">
    <div class="container header-container">
        <a href="{{ route('home') }}" class="logo">GameMate</a>
        <nav class="nav" id="nav">
            <ul class="nav-list">
                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Mājaslapa</a></li>
                <li><a href="{{ route('fyt') }}"><i class="fas fa-users"></i> Atrodi biedrus</a></li>

                @auth
                    <li><a href="{{ route('chat') }}"><i class="fas fa-comments"></i> Globāla tērzēšana</a></li>
                    <li><a href="{{ route('tournaments.index') }}"><i class="fas fa-trophy"></i> Turnīri</a></li>
                    
                @endauth
            </ul>
        </nav>
        <div class="auth-buttons">
            @guest
                <a href="{{ route('login') }}" class="btn login-btn"><i class="fas fa-sign-in-alt"></i> Ieiet</a>
                <a href="{{ route('signup') }}" class="btn signup-btn"><i class="fas fa-user-plus"></i> Reģistrējies</a>
            @else
                <a href="{{ route('profile.edit') }}" class="btn edit-btn">
                    <i class="fas fa-user-edit"></i> Redigēšana
                </a>
                <a href="{{ route('logout') }}" class="btn logout-btn" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Izrakstīties
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
        <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
    </div>
</header>
