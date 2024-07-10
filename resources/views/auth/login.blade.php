@extends('layout.main')

@section('content')
    <section aria-label="Login Form" class="d-flex justify-content-center align-items-center flex-column mt-5">
        @if(Session::has('success'))
            <div class="alert alert-success text-center">
                {{Session::get('success')}}
            </div>
        @endif
        <form method="post" action="{{route('login')}}" enctype="multipart/form-data" class="card shadow-sm w-50 p-4 mx-auto">
            @csrf
            <h1 class="mb-4 text-center">Index | Login</h1>
            <div class="mb-2">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror">
                @error('username')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            <div class="mb-2 d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary">Login</button>
                <button type="reset" class="btn btn-outline-primary">Reset</button>
            </div>
        </form>
    </section>
@endsection
