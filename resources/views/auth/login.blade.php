@extends('layouts.app')

@section('title', 'Ieiet')

@section('content')
    @include('partials.header-login')
    @include('partials.sidebar-login')
    <div class="form-wrapper">
        <video autoplay muted loop class="form-bg-video">
            <source src="{{ asset('mp4/video.mp4') }}" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
        <div class="form-container">
            <section id="login" class="form-section">
                <div class="container">
                    <h2 class="form-title">Ieiet</h2>
                    <form action="{{ route('login') }}" method="post" class="form">
                        @csrf
                        <div class="form-group">
                            <label for="loginInput">Epasts vai Lietotājvārds</label>
                            <input type="text" id="loginInput" name="login" required value="{{ old('login') }}">
                            @if ($errors->has('login'))
                                <span style="color:red"  class="error">{{ $errors->first('login') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="loginPassword">Password</label>
                            <div class="password-container">
                                <input type="password" id="loginPassword" name="password" required>
                                <span id="toggleLoginPassword" class="eye-icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                            @if ($errors->has('password'))
                                <span  style="color:red" class="error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
        
                        <button type="submit" class="btn btn-primarys">Ieiet</button>
                    </form>

                    <div>
                        <p class="switch-link">Vai jums nav konts?</p>
                    </div>
                    <div class="sign">
                        <a href="{{ route('signup') }}" class="btn btn-secondary">Reģistrācija</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('partials.footer-login')

    <!-- Include FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script>
        // Toggle password visibility for login
        document.getElementById('toggleLoginPassword').addEventListener('click', function () {
            var passwordField = document.getElementById('loginPassword');
            var passwordType = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = passwordType;

            // Toggle the eye icon
            this.innerHTML = passwordType === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
        });
    </script>
@endsection
