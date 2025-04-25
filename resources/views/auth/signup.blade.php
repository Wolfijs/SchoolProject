@extends('layouts.app')

@section('title', 'Reģistrācija')	

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
                    <h2 class="form-title">Reģistrējies</h2>
                    <form action="{{ route('register') }}" method="post" class="form" id="signupForm">
                        @csrf
                        <div class="form-group">
                            <label for="signupName">Lietotājvārds</label>
                            <input type="text" id="signupName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="signupEmail">Epasts</label>
                            <input type="email" id="signupEmail" name="email" required>
                            <small id="emailHint" style="color: red; display: none;">Lūdzu, ievadiet derīgu e-pasta adresi ar '@'.</small>
                        </div>
                        <div class="form-group">
                            <label for="signupPassword">Parole</label>
                            <div class="password-container">
                                <input type="password" id="signupPassword" name="password" required>
                                <span id="togglePassword" class="eye-icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                            <small id="passwordHint" style="color: red; display: none;"> Parolei jābūt vismaz 8 rakstzīmēm, vienam lielajam burtam, vienam mazajam burtam, vienam skaitlim un vienai speciālajai rakstzīmei.</small>
                        </div>
                        <div class="form-group">
                            <label for="signupPasswordConfirmation">Atkārtota parole</label>
                            <div class="password-container">
                                <input type="password" id="signupPasswordConfirmation" name="password_confirmation" required>
                                <span id="togglePasswordConfirmation" class="eye-icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                            <small id="passwordMatchHint" style="color: red; display: none;">Paroles nav vienādas.</small>
                        </div>
                        <button type="submit" class="btn btn-primarys">Reģistrējies</button>
                    </form>
                    <div>   
                        <p class="switch-link">Vai jums jau ir konts?</p>
                    </div>
                    <div class="sign">
                        <a href="{{ route('login') }}" class="btn btn-secondary">Ieiet</a>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('partials.footer-login')

    <!-- Include FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script>
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var password = document.getElementById('signupPassword').value;
            var passwordConfirmation = document.getElementById('signupPasswordConfirmation').value;
            var email = document.getElementById('signupEmail').value;
            var passwordHint = document.getElementById('passwordHint');
            var passwordMatchHint = document.getElementById('passwordMatchHint');
            var emailHint = document.getElementById('emailHint');
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Reset error messages
            passwordHint.style.display = 'none';
            passwordMatchHint.style.display = 'none';
            emailHint.style.display = 'none';

            // Check password strength
            if (!passwordRegex.test(password)) {
                passwordHint.style.display = 'inline';
                event.preventDefault(); // Prevent form submission
            }

            // Check if passwords match
            if (password !== passwordConfirmation) {
                passwordMatchHint.style.display = 'inline';
                event.preventDefault(); // Prevent form submission
            }

            // Check if email is valid
            if (!emailRegex.test(email)) {
                emailHint.style.display = 'inline';
                event.preventDefault(); // Prevent form submission
            }
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordField = document.getElementById('signupPassword');
            var passwordType = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = passwordType;

            // Toggle the eye icon
            this.innerHTML = passwordType === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
        });

        document.getElementById('togglePasswordConfirmation').addEventListener('click', function () {
            var passwordConfirmationField = document.getElementById('signupPasswordConfirmation');
            var passwordConfirmationType = passwordConfirmationField.type === 'password' ? 'text' : 'password';
            passwordConfirmationField.type = passwordConfirmationType;

            // Toggle the eye icon
            this.innerHTML = passwordConfirmationType === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
        });
    </script>
@endsection
