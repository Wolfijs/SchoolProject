@extends('layouts.app')

@section('title',' Home')
@section('content')
@include('partials.header')

@include('partials.sidebar')

<section id="home" class="home-section">
    <video autoplay muted loop class="background-video">
        <source src="{{ asset('mp4/video.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <div class="home-content">
            <h1 class="home-title">Find Your Perfect Teammates</h1>
            <p class="home-subtitle">Personalized player matching for your favorite games.</p>
            <a href="#" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</section>

<section id="features" class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Features</h2>
        </div>
        <div class="features-grid">
            <div class="feature-item" data-aos="fade-right">
                <h3 class="feature-title">Personalized Player Matching</h3>
                <p>Create a profile, list your favorite games, skill level, and preferred playstyle, and let
                    GameMate match you with ideal teammates.</p>
            </div>
            <div class="feature-item" data-aos="fade-left">
                <h3 class="feature-title">Game-Specific Communities</h3>
                <p>Join dedicated groups for the games you love and meet others who share your passion.</p>
            </div>
            <div class="feature-item" data-aos="fade-right">
                <h3 class="feature-title">Instant Matchmaking</h3>
                <p>Looking for a quick gaming session? Use our real-time matchmaking feature to find players online
                    right now.</p>
            </div>
            <div class="feature-item" data-aos="fade-right">
                <h3 class="feature-title">Events & Tournaments</h3>
                <p>Participate in or host gaming events, tournaments, and friendly matches to enhance your gaming
                    experience.</p>
            </div>
        </div>
    </div>
    <div id="stars"></div>
</section>

<section id="events" class="events-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Join Exciting Events & Tournaments</h2>
        </div>
        <div class="events-wrapper">
            <div class="events-content">
                <div>
                    <div class="event-top">
                        <p>Take part in competitive gaming events and friendly matches or host your own tournaments to build a reputation in the community.</p>
                    </div>
                    <div class="event-bottom">
                        <a href="#" class="btn btn-secondary">View Upcoming Events</a>
                    </div>
                </div>
                <div class="events-image">
                    <img src="{{ asset('img/tourny.jpg') }}" alt="Events and Tournaments">
                </div>
            </div>
        </div>
    </div>
</section>
@include('partials.footer')
@section('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
@endsection
