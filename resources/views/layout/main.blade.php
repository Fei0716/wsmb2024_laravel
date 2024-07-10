<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Photo Albums</title>
{{--    link bootstrap css--}}
    <link rel="stylesheet" href="{{asset('storage/bootstrap/css/bootstrap.min.css')}}">

{{--    custom css--}}
    @yield('styles')
</head>
<body>
    @include('layout.nav')
   <div class="container mt-3">
       @yield('content')
   </div>
{{--link bootstrap js--}}
<script src="{{asset('storage/bootstrap/js/bootstrap.min.js')}}"></script>
{{--link jquery--}}
<script src="{{asset('storage/jquery/jquery.min.js')}}"></script>

    {{--    custom js--}}
    @yield('scripts')
</body>
</html>
