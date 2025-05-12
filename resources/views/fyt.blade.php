@extends('layouts.app')

@section('title', 'Atrodi perfekto komandasbiedru')

@section('content')
@include('partials.header')
@include('partials.sidebar')

<section id="find-teammates" class="find-teammates-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Atrodi perfekto komandasbiedru</h2>
            <p>Sazinies ar spēlētājiem, kuri atbilst tavām spēles stilam, prasmju līmenim un iecienītākajām spēlēm.</p>
        </div>
    </div>
</section>

<section id="game-lobbies" class="game-lobbies-section">
    <div class="container">

        @auth
        <button id="createLobbyBtn" class="btn-primarys">Izveido vestibilu</button>
        @else
        <a href="{{ route('login') }}" class="btn-primarys">Pieslēdzies lai izveidotu vestibilu</a>
        @endauth

        <h2 class="section-title mt-6">Pieejamie spēlu vestibili</h2>

        @foreach (['success','error'] as $flash)
        @if (session($flash))
        <div class="alert alert-{{ $flash == 'error' ? 'danger' : 'success' }}">
            {{ session($flash) }}
        </div>
        @endif
        @endforeach

        <div class="lobby-list" id="lobbyList">
            @foreach ($lobbies as $lobby)
            <div class="lobby-box" id="lobby-{{ $lobby->id }}">
                {{-- Logo / image --}}
                <div class="lobby-image-container">
                    @php
                    $gameImage = $lobby->photo
                    ? Storage::url($lobby->photo)
                    : asset('img/default.jpg');
                    @endphp
                    <img src="{{ $gameImage }}" alt="{{ $lobby->game_name }} logo" class="lobby-game-img">
                </div>

                {{-- Basic info --}}
                <h3 class="game-name">
                    <a href="{{ route('lobby.show', $lobby->id) }}">{{ $lobby->game_name }}</a>
                </h3>

                <p><strong>Prasmju līmenis:</strong> {{ $lobby->skill_level }}</p>
                <p><strong>Spēles stils:</strong> {{ $lobby->playstyle }}</p>
                <p><strong>Reģions:</strong> {{ $lobby->region }}</p>
                <p><strong>Apraksts:</strong> {{ $lobby->description }}</p>
                <p><strong>Kad izveidots:</strong> {{ $lobby->created_at->format('F j. Y') }}</p>
                <p><strong>Spēlētāju skaits:</strong>
                    <span class="lobby-players-count">{{ $lobby->players_count }}</span>
                    / {{ $lobby->max_players }}
                </p>


                @auth
                @php $member = $lobby->players->contains(auth()->id()); @endphp

                {{-- Join --}}
                @if (!$member && $lobby->players_count < $lobby->max_players)
                    <form action="{{ route('lobby.join', $lobby->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn-primarys">Pievienojies vestibilam</button>
                    </form>

                    {{-- Full but user not inside --}}
                    @elseif(!$member && $lobby->players_count >= $lobby->max_players)
                    <p class="text-muted">Vestibils ir pilns.</p>

                    {{-- Member already inside --}}

                    @else
                    <div class="xd">
                        <a href="{{ route('lobby.show', $lobby->id) }}" class="btn-primary">Sazinies ar komandas
                            biedriem</a>
                    </div>

                    {{-- Show Leave button only if user is not the host --}}
                    @if (auth()->id() !== $lobby->user_id)
                    <form action="{{ route('lobby.leave', $lobby->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="delete-lobby">Iziet no vestibila</button>
                    </form>
                    @endif
                    @endif

                    {{-- Host-only delete --}}
                    @if (auth()->id() === $lobby->user_id)
                    <form class="delete-lobby-form" action="{{ route('lobby.destroy', $lobby->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="delete-lobby">Dzēst vestibilu</button>
                    </form>
                    @endif
                    @else
                    <p class="text-muted">Pieslēdzies lai pievienoties vestibilam</p>
                    @endauth

            </div>
            @endforeach
        </div>
    </div>
</section>


@auth
<!-- Your existing HTML code -->
<div id="createLobbyPopup" class="popup" style="display:none;">
    <div class="popup-content">
        <div class="max">
            <span class="close-popup">&times;</span>
            <h2>Izveido jaunu vestibilu</h2>

            <form id="createLobbyForm" action="{{ route('lobby.store') }}" method="POST" class="create-lobby-form">
                @csrf



                <div class="form-group">
                    <label for="game_name">Spēles nosaukums</label>
                    <input type="text" id="game_name" name="game_name" required class="form-control"
                        placeholder="Enter Game Name">
                </div>

                <div class="form-group">
                    <label for="photo">Augšup ielāde attēlu no spēles (Nav obligāti) </label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="form-control">
                </div>

                <div class="form-group">
                    <label for="skill_level">Prasmju līmenis</label>
                    <select id="skill_level" name="skill_level" class="form-control" required>
                        <option value="" disabled selected>Izvēlies prasmju līmeni</option>
                        <option>Iesācejs</option>
                        <option>Ar pieredzi</option>
                        <option>Profesionāls</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="playstyle">Spēles stills</label>
                    <input type="text" id="playstyle" name="playstyle" class="form-control" required
                        placeholder="Ievadi spēles stillu">
                </div>

                <div class="form-group">
                    <label for="region">Reģions</label>
                    <select id="region" name="region" class="form-control" required>
                        <option value="" disabled selected>Izvēlies reģionu</option>
                        <optgroup label="Amerika">
                            <option value="Ziemeļamerika–Austrumi">Ziemeļamerika – Austrumi</option>
                            <option value="Ziemeļamerika-Rietumi">Ziemeļamerika – Rietumi</option>
                            <option value="Dienvidamerika">Dienvidamerika</option>
                        </optgroup>
                        <optgroup label="Eiropa">
                            <option value="EU-Ziemeļi">Eiropa – Ziemeļi</option>
                            <option value="EU-Rietumi">Eiropa – Rietumi</option>
                            <option value="EU-Austrumi">Eiropa – Austrumi</option>
                        </optgroup>
                    </select>
                </div>

                <!-- ── Description ─────────────────────────────── -->
                <div class="form-group">
                    <label for="description">Apraksts</label>
                    <input type="text" id="description" name="description" class="form-control" required
                        placeholder="Ievadi nelielu aprakstu">
                </div>

                <!-- ── Max players ─────────────────────────────── -->
                <div class="form-group">
                    <label for="max_players">Maksimālais spēlētaju skaits</label>
                    <input type="number" id="max_players" name="max_players" class="form-control" required min="1"
                        max="10" placeholder="Ierakstiet maksimālo spēlētāju skaitu">
                </div>
                <div class="popup-poga">

                    <button type="submit" class="btn-primarys create-btn"> Izveido vestibilu</button>


                </div>

            </form>
        </div>
    </div>
</div>



@endauth


@include('partials.footer')

@endsection