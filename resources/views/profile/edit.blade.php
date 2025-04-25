@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
@include('partials.header')
@include('partials.sidebar')

<div class="profile-editor-wrapper">
    <div class="profile-editor-container">
        <section id="edit-profile" class="profile-editor-section">
            <div class="container">
                <h2 class="profile-editor-title">Profila redigēšana</h2>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
    class="profile-editor-form">
    @csrf
    @method('PUT')

    <div class="profile-editor-photo-section">
        <div class="profile-editor-current-photo">
            @if($user->photo && $user->photo !== 'public/img/default.jpg')
                <img src="{{ Storage::url($user->photo) }}" alt="Profile Photo" id="profile-photo-preview">
            @else
                <img src="{{ asset('img/default.jpg') }}" alt="Default Profile Photo" id="profile-photo-preview">
            @endif
        </div>

        <div class="profile-editor-upload-controls">
            <label for="profile_photo" class="profile-editor-upload-btn">
                <i class="fa fa-camera"></i> Samaini attēlu
            </label>
            <input type="file" id="profile_photo" name="photo" class="profile-editor-photo-input" accept="image/*">
            <span class="profile-editor-help-text">JPG, PNG or GIF, max 2 MB</span>
            @error('photo')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="profile-editor-info">
        <div class="profile-editor-form-group">
            <label for="editName" class="profile-editor-form-label">Lietotājvārds</label>
            <input type="text" id="editName" name="name" value="{{ old('name', $user->name) }}"
                class="profile-editor-form-input" required>
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="profile-editor-form-group">
            <label for="editEmail" class="profile-editor-form-label">Epasta adrese</label>
            <input type="email" id="editEmail" name="email" value="{{ old('email', $user->email) }}"
                class="profile-editor-form-input" required>
            @error('email')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="profile-editor-form-group">
            <label for="editPassword" class="profile-editor-form-label">Parole</label>
            <input type="password" id="editPassword" name="password"
                placeholder="Atstāj parole tukšu, lai saglabātu esošo"
                class="profile-editor-form-input">
            @error('password')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="profile-editor-form-group">
            <label for="editPasswordConfirmation" class="profile-editor-form-label">Atkārtota parole</label>
            <input type="password" id="editPasswordConfirmation" name="password_confirmation"
                placeholder="Atkārtojiet paroli"
                class="profile-editor-form-input">
        </div>
    </div>

    <div class="profile-editor-form-actions">
        <button type="submit" class="profile-editor-btn profile-editor-btn-primary">
            Atjaunināt
        </button>
    </div>

    @if (session('status'))
        <div class="profile-editor-alert profile-editor-alert-success">
            {{ session('status') }}
        </div>
    @endif
</form>

            </div>
        </section>
    </div>
</div>

@include('partials.footer')
@endsection

@push('scripts')
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = ({ target }) => {
        const preview = document.getElementById('profile-photo-preview');
        preview.src = target.result;
    };
    reader.readAsDataURL(file);
});

// VALIDATION SCRIPT

</script>
@endpush
