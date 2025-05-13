@extends('layouts.app')

@section('title', $tournament->name)

@section('content')
@include('partials.header')
@include('partials.sidebar')

<div class="tournament-details-wrapper">
    <div class="tournament-details-container">
        <section class="tournament-details-section">
            <div class="container">
                <div class="tournament-details-header">
                    <h2 class="tournament-details-title">{{ $tournament->name }}</h2>
                    <a href="{{ route('tournaments.index') }}" class="tournament-back-btn">
                        <i class="fas fa-arrow-left"></i> Atpakaļ uz turnīriem
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

                <div class="tournament-details-grid">
                    <div class="tournament-info-card">
                        <h3 class="tournament-info-title">Turnīra informācija</h3>
                        <div class="tournament-info-content">
                            <div class="tournament-info-item">
                                <p class="tournament-info-label">Apraksts</p>
                                <p class="tournament-info-value">{{ $tournament->description }}</p>
                            </div>
                            <div class="tournament-info-item">
                                <p class="tournament-info-label">Spēle</p>
                                <p class="tournament-info-value">{{ $tournament->game }}</p>
                            </div>
                            <div class="tournament-info-item">
                                <p class="tournament-info-label">Platforma</p>
                                <p class="tournament-info-value">{{ $tournament->platform }}</p>
                            </div>
                            @if($tournament->external_link)
                                <div class="tournament-info-item">
                                    <p class="tournament-info-label">Ārējā saite</p>
                                    <a href="{{ $tournament->external_link }}" target="_blank" class="tournament-external-link">
                                        {{ $tournament->external_link }}
                                    </a>
                                </div>
                            @endif
                            <div class="tournament-info-item">
                                <p class="tournament-info-label">Laiks</p>
                                <p class="tournament-info-value">
                                    Sākums: {{ $tournament->start_time->format('d.m.Y H:i') }}<br>
                                    Beigas: {{ $tournament->end_time->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tournament-participants-card">
                        <h3 class="tournament-participants-title">
                            Dalībnieki ({{ $tournament->current_players }}/{{ $tournament->max_players }})
                        </h3>
                        <div class="tournament-participants-list">
                            @if($tournament->participants->isEmpty())
                                <p class="tournament-no-participants">Vēl nav dalībnieku</p>
                            @else
                                <ul class="participants-list">
                                    @foreach($tournament->participants as $participant)
                                        <li class="participant-item">
                                            <span class="participant-name">{{ $participant->name }}</span>
                                            @if($participant->id === $tournament->user_id)
                                                <span class="participant-badge">Organizators</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="tournament-join-section">
                            @if($tournament->hasParticipant(auth()->user()) && auth()->id() !== $tournament->user_id)
                                <form action="{{ route('tournaments.leave', $tournament) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="tournament-leave-tournament-btn">
                                        <i class="fas fa-sign-out-alt"></i> Pamest turnīru
                                    </button>
                                </form>
                            @elseif(!$tournament->hasParticipant(auth()->user()))
                                <form action="{{ route('tournaments.join', $tournament) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="tournament-join-tournament-btn" {{ $tournament->isFull() ? 'disabled' : '' }}>
                                        <i class="fas fa-sign-in-alt"></i> Pievienoties turnīram
                                    </button>
                                </form>
                            @endif
                        </div>
                        @if(auth()->id() === $tournament->user_id)
                        <div class="tournament-delete-section" style="margin-top: 1rem;">
                            <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST" onsubmit="return confirm('Vai tiešām dzēst šo turnīru?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Dzēst turnīru
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@include('partials.footer')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tournaments.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteTournamentForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Vai tiešām dzēst šo turnīru?')) {
                fetch(this.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.href = '{{ route('tournaments.index') }}';
                });
            }
        });
    }
});
</script>
@endpush 