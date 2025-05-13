<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - GameMate</title>
    <style> {{ file_get_contents(public_path('css/style.css')) }} </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @push('styles')
        <style>
            {!! file_get_contents(public_path('css/style.css')) !!}
            
        </style>
    @endpush

</head>
<body>
    @include('partials.sidebar')
    <main>
        @yield('content')
    </main>

    <script type="module" src="{{ asset('js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('scripts')
</body>
</html>
