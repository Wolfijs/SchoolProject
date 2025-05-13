<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Navigācija</h4>
                <ul class="footer-links">
                <li><a href="{{ route('home') }}">Mājaslapa</a></li>
                    <li><a href="{{ route('fyt') }}"> Atrodi savus komandas biedrus</a></li>
                    <li><a href="{{ route('chat') }}">Globāla tērzēšana</a></li>
                    <li><a href="{{ route('tournaments.index') }}"> Turnīri</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Pieseko mums</h4>
                <div class="footer-social">
                    <a href="https://twitter.com/manulewolfijs" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="https://instagram.com/ralfsvz" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com" target="_blank" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-section contact-section">
                <h4>Sazinies ar mums</h4>
                <p>Epasts: <a href="mailto:contact@gamemate.com">contact@gamemate.com</a></p>
                <!-- <button class="btn btn-contact">Talk with Us</button> -->
            </div>
        </div>
        <p class="footer-text">&copy; {{ date('Y') }} GameMate. All Rights Reserved.</p>
    </div>
</footer>
