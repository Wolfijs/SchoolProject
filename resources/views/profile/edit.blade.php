@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <div class="form-wrapper">
        <video autoplay muted loop class="form-bg-video">
            <source src="{{ asset('mp4/video.mp4') }}" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>

        <div class="form-container">
            <section id="edit-profile" class="form-section">
                <div class="container">
                    <h2 class="form-title">Edit Profile</h2>
                    
  


                    <form action="{{ route('profile.update') }}" method="post" class="form">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" id="editName" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="editPassword">Password</label>
                            <input type="password" id="editPassword" name="password" placeholder="Leave blank if you do not want to change">
                        </div>

                        <div class="form-group">
                            <label for="editPasswordConfirmation">Confirm Password</label>
                            <input type="password" id="editPasswordConfirmation" name="password_confirmation" placeholder="Leave blank if you do not want to change">
                        </div>

                        <button type="submit" class="btn btn-primarys">Update Profile</button>
                        @if (session('status'))
                        <div class="alert alert-success" class="red">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    @if ($errors->any())
                        <div class="alert alert-danger" >
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li style="red">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    </form>
                </div>
            </section>
        </div>
    </div>

    @include('partials.footer')
@endsection
