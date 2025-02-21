@extends('layouts.auth')

@section('title', 'Login')

@section('card-title','Login')

@section('content')
<form action="{{route('login')}}" method="POST">
    @csrf
    <div class="form-group">
        <span class="material-icons form-icon">email</span>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" placeholder="Email" required>
        @error('email')
        <span class="error-message">{{$message}}</span>
        @enderror
    </div>

    <div class="form-group">
        <span class="material-icons form-icon">lock</span>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
        @error('password')
        <span class="error-message">{{$message}}</span>
        @enderror
    </div>

    <button class="btn" type="submit">ログイン</button>
</form>

@endsection
