@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
    @include('partials.header-login')
    @include('partials.sidebar-login')

    <div class="form-wrapper">
        <video autoplay muted loop class="form-bg-video">
            <source src="{{ asset('mp4/video.mp4') }}" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>

        <div class="form-container">
            <section id="signup" class="form-section">
                <div class="container">
                    <h2 class="form-title">Sign Up</h2>
                    <form action="{{ route('register') }}" method="post" class="form">
                        @csrf
                        <div class="form-group">
                            <label for="signupName">Username</label>
                            <input type="text" id="signupName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="signupEmail">Email</label>
                            <input type="email" id="signupEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="signupPassword">Password</label>
                            <input type="password" id="signupPassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="signupPasswordConfirmation">Confirm Password</label>
                            <input type="password" id="signupPasswordConfirmation" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primarys">Register</button>
                    </form>
                    <div>   
                        <p class="switch-link">Already have an account?</p>
                    </div>
                    <div class="sign">
                        <a href="{{ route('login') }}" class="btn btn-secondary">Registere</a>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('partials.footer-login')
@endsection
