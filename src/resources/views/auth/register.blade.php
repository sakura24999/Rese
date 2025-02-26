@extends('layouts.auth')

@section('title', 'Registration')

@section('card-title', 'Registration')

@section('content')
    <form action="{{route('register')}}" method="POST">
        @csrf
        <div class="form-group">
            <span class="material-icons form-icon">person</span>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Username"
                value="{{old('name')}}" required>
            @error('name')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <span class="material-icons form-icon">email</span>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                value="{{old('email')}}" required>
            @error('email')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <span class="material-icons form-icon">lock</span>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Password" required>
            @error('password')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>

        <button class="btn" type="submit">登録</button>
    </form>

@endsection