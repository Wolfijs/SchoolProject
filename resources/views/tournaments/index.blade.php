@extends('layouts.app')

@section('title', 'Turnīri')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tournaments.css') }}">
@endpush

@section('content')
@include('partials.header')
@include('partials.sidebar')

<div class="tournaments-wrapper">
    <div class="tournaments-container">
        <section class="tournaments-section">
            <div class="container">
                <div class="tournaments-header">
                    <h2 class="tournaments-title">Turnīri</h2>
                    <a href="{{ route('tournaments.create') }}" class="btn btn-primary tournaments-create-btn">
                        <i class="fas fa-plus"></i> Izveidot turnīru
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="tournaments-grid">
                    @foreach($tournaments as $tournament)
                        <div class="tournament-card">
                            <h3 class="tournament-name">{{ $tournament->name }}</h3>
                            <p class="tournament-description">{{ Str::limit($tournament->description, 100) }}</p>
                            <div class="tournament-details">
                                <p><i class="fas fa-gamepad"></i> Spēle: {{ $tournament->game }}</p>
                                <p><i class="fas fa-desktop"></i> Platforma: {{ $tournament->platform }}</p>
                                <p><i class="fas fa-users"></i> Spēlētāji: {{ $tournament->current_players }}/{{ $tournament->max_players }}</p>
                                <p><i class="fas fa-clock"></i> Sākums: {{ $tournament->start_time->format('d.m.Y H:i') }}</p>
                                @auth
                                    @if(auth()->user()->id === $tournament->user_id)
                                        <p class="tournament-creator"><i class="fas fa-crown"></i> Jūs esat turnīra organizātors</p>
                                    @endif
                                @endauth
                            </div>
                            <div class="tournament-actions">
                                <a href="{{ route('tournaments.show', $tournament) }}" class="tournament-view-details-btn">
                                    <i class="fas fa-eye"></i> Skatīt detaļas
                                </a>
                                @if($tournament->hasParticipant(auth()->user()) && auth()->id() !== $tournament->user_id)
                                    <form action="{{ route('tournaments.leave', $tournament) }}" method="POST" class="tournament-form">
                                        @csrf
                                        <button type="submit" class="tournament-leave-tournament-btn">
                                            <i class="fas fa-sign-out-alt"></i> Pamest
                                        </button>
                                    </form>
                                @elseif(!$tournament->hasParticipant(auth()->user()))
                                    <form action="{{ route('tournaments.join', $tournament) }}" method="POST" class="tournament-form">
                                        @csrf
                                        <button type="submit" class="tournament-join-tournament-btn" {{ $tournament->isFull() ? 'disabled' : '' }}>
                                            <i class="fas fa-sign-in-alt"></i> Pievienoties
                                        </button>
                                    </form>
                                @endif
                                @if(auth()->id() === $tournament->user_id)
                                   
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="tournaments-pagination">
                    {{ $tournaments->links() }}
                </div>
            </div>
        </section>
    </div>
</div>

@include('partials.footer')
@endsection

