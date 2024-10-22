<header class="header">
    <div class="container header-container">
        <a href="{{ route('home') }}" class="logo">GameMate</a>
        <nav class="nav" id="nav">
            <ul class="nav-list">
                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="{{ route('fyt') }}"><i class="fas fa-users"></i> Find Your Teammates</a></li>
                <li><a href="{{ route('events') }}"><i class="fas fa-calendar-alt"></i> Events</a></li>
                @auth
                    <li><a href="{{ route('chat') }}"><i class="fas fa-comments"></i> Global Chat</a></li>
                @endauth
            </ul>
        </nav>
        <div class="auth-buttons">
            @guest
                <a href="{{ route('login') }}" class="btn login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="{{ route('signup') }}" class="btn signup-btn"><i class="fas fa-user-plus"></i> Sign Up</a>
            @else
                <a href="{{ route('profile.edit') }}" class="btn edit-btn">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                <a href="{{ route('logout') }}" class="btn logout-btn" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
        <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
    </div>
</header>
