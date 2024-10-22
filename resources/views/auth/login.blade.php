@extends('layouts.app')

@section('title', 'Login')

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
                    <h2 class="form-title">Login</h2>
                    <form action="{{ route('login') }}" method="post" class="form">
                        @csrf
                        <div class="form-group">
                            <label for="loginInput">Email or Username</label>
                            <input type="text" id="loginInput" name="login" required value="{{ old('login') }}">
                            @if ($errors->has('login'))
                                <span style="color:red"  class="error">{{ $errors->first('login') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">Password</label>
                            <input type="password" id="loginPassword" name="password" required>
                            @if ($errors->has('password'))
                                <span  style="color:red" class="error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </form>
                    <div>
                        <p class="switch-link">Don't have an account?</p>
                    </div>
                    <div class="sign">
                        <a href="{{ route('signup') }}" class="btn btn-secondary">Sign Up</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('partials.footer-login')
@endsection
