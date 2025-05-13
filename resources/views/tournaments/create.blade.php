@extends('layouts.app')

@section('title', 'Izveidot turnīru')

@section('content')
@include('partials.header')
@include('partials.sidebar')

<div class="tournament-create-wrapper">
    <div class="tournament-create-container">
        <section class="tournament-create-section">
            <div class="container">
                <div class="tournament-create-header">
                    <h2 class="tournament-create-title">Izveidot jaunu turnīru</h2>
                    <a href="{{ route('tournaments.index') }}" class="tournament-back-btn">
                        <i class="fas fa-arrow-left"></i> Atpakaļ uz turnīriem
                    </a>
                </div>

                <div class="tournament-form-card">
                    <form action="{{ route('tournaments.store') }}" method="POST" class="tournament-form">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="form-label">Turnīra nosaukums</label>
                            <input type="text" name="name" id="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Apraksts</label>
                            <input type="text" name="description" id="description" class="form-input @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
                            @error('description')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="game" class="form-label">Spēle</label>
                            <input type="text" name="game" id="game" class="form-input @error('game') is-invalid @enderror" value="{{ old('game') }}" required>
                            @error('game')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="platform" class="form-label">Platforma</label>
                            <input type="text" name="platform" id="platform" class="form-input @error('platform') is-invalid @enderror" value="{{ old('platform') }}" required>
                            @error('platform')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="external_link" class="form-label">Ārējā saite </label>
                            <input type="url" name="external_link" id="external_link" class="form-input @error('external_link') is-invalid @enderror" value="{{ old('external_link') }}">
                            @error('external_link')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="max_players" class="form-label">Maksimālais dalībnieku skaits</label>
                            <input type="number" name="max_players" id="max_players" class="form-input @error('max_players') is-invalid @enderror" value="{{ old('max_players', 2) }}" min="2" required>
                            @error('max_players')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="start_time" class="form-label">Sākuma laiks</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-input @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_time" class="form-label">Beigu laiks</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="form-input @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn">
                                <i class="fas fa-plus"></i> Izveidot turnīru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

@include('partials.footer')
@endsection

