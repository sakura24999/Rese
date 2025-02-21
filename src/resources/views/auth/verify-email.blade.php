@extends('layouts.auth')

@section('title','メール認証')

@section('content')
<div class="verify-email-container">
    <h2>メール認証が必要です</h2>
    <p>
        ご登録いただいたメールアドレスに認証メールを送信しました。<br>
        メール内のリンクをクリックして、認証を完了してください。
    </p>

    @if(session('message'))
    <div class="alert alert-success">
        {{session('message')}}
    </div>
    @endif

    <form action="{{route('verification.send')}}" method="POST">
        @csrf
        <button class="btn" type="submit">認証メールを再送信</button>
    </form>
</div>
@endsection