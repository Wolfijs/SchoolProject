@extends('layouts.app')

@section('title',' Sākums')
@section('content')
@include('partials.header')

@include('partials.sidebar')

<section id="home" class="home-section">
    <video autoplay muted loop class="background-video">
        <source src="{{ asset('mp4/video.mp4') }}" type="video/mp4">
        Jūsu pārlūks neatbalsta video tagu.
    </video>
    <div class="container">
        <div class="home-content">
            <h1 class="home-title">Atrodi Savus Ideālos Komandas Biedrus</h1>
            <p class="home-subtitle">Personalizēta spēlētāju saderība jūsu iecienītākajām spēlēm.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Sākt</a>
        </div>
    </div>
</section>

<section id="features" class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Mūsu Iespējas</h2>
        </div>
        <div class="features-grid">
            <div class="feature-item" data-aos="fade-right">
                <h3 class="feature-title">Personalizēta Spēlētāju Saderība</h3>
                <p>Izveidojiet profilu, uzskaitiet savas iecienītākās spēles, prasmju līmeni un vēlamo spēles stilu, un ļaujiet GameMate atrast jums ideālos komandas biedrus.</p>
            </div>
            <div class="feature-item" data-aos="fade-left">
                <h3 class="feature-title">Spēļu Kopienas</h3>
                <p>Pievienojieties spēļu grupām, kuras jūs mīlat, un satieciet citus, kuri dalās tajā pašā aizraušanās.</p>
            </div>
            <div class="feature-item" data-aos="fade-right">
                <h3 class="feature-title">Tūlītēja Saderība</h3>
                <p>Meklējat ātru spēles sesiju? Izmantojiet mūsu reāllaika saderības funkciju, lai atrastu tiešsaistē esošus spēlētājus.</p>
            </div>
            <div class="feature-item" data-aos="fade-right">
                <h3 class="feature-title">Pasākumi un Turnīri</h3>
                <p>Piedalieties vai rīkojiet spēļu pasākumus, turnīrus un draudzīgas spēles, lai uzlabotu savu spēļu pieredzi.</p>
            </div>
        </div>
    </div>
    <div id="stars"></div>
</section>

<section id="events" class="events-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Pievienojieties Aizraujošiem Vestibils un Turnīriem</h2>
        </div>
        <div class="events-wrapper">
            <div class="events-content">
                <div>
                    <div class="event-top">
                        <p>Piedalieties aizraujošiem spēļu pasākumos un draudzīgās spēlēs vai rīkojiet savus turnīrus, lai izveidotu reputāciju kopienā.</p>
                    </div>
                    <div class="event-bottom">
                        <a href="{{ route('login') }}" class="btn btn-secondary">Skatīt Gaidāmās Spēles</a>
                    </div>
                </div>
                <div class="events-image">
                    <img src="{{ asset('img/tourny.jpg') }}" alt="Pasākumi un Turnīri">
                </div>
            </div>
        </div>
    </div>
</section>
@include('partials.footer')
@section('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
@endsection
