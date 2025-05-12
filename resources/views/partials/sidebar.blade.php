<aside class="sidebar" id="sidebar">
    <button class="close-btn" id="closeSidebar"><i class="fas fa-times"></i></button>
    <nav class="sidebar-nav">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Mājaslapa</a>
        <a href="{{ route('fyt') }}"><i class="fas fa-users"></i> Atrodi Biedrus</a>
        <a href="{{ route('chat') }}"><i class="fas fa-comments"></i> Globāla tērzēšana</a>
        <a href="{{ route('fyt') }}"><i class="fas fa-trophy"></i> Turnīri</a>

        <div class="auth-links">
            @guest
                <a href="{{ route('login') }}" class="login-btn"><i class="fas fa-sign-in-alt"></i> Ienākt</a>
                <a href="{{ route('signup') }}" class="signup-btn"><i class="fas fa-user-plus"></i> Reģistrācija</a>
            @else
                <a href="{{ route('profile.edit') }}" class="edit-btn">
                    <i class="fas fa-user-edit"></i> Profila rediģēšana
                </a>
                <a href="{{ route('logout') }}" class="logout-btn" 
                   onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt"></i> Redigēšana
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
    </nav>
</aside>
