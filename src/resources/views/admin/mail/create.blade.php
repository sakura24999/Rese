@extends('layouts.admin')

@section('title', 'メール送信 | Rese 管理画面')
@section('content')
    <div class="admin-content">
        <h1 class="page-title">メール送信</h1>

        <div class="form-container">
            <form action="{{route('admin.mail.send')}}" method="POST" id="mailForm">
                @csrf

                <div class="form-group">
                    <label for="user_type" class="form-label">送信対象</label>
                    <select name="user_type" id="user_type" class="form-input">
                        @foreach ($userTypes as $value => $label)
                            <option value="{{$value}}" {{old('user_type') === $value ? 'selected' : ''}}>{{$label}}</option>
                        @endforeach
                    </select>
                    @error('user_type')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">件名</label>
                    <input type="text" name="subject" id="subject" class="form-input" value="{{old('subject')}}">
                    @error('subject')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">メッセージ</label>
                    <textarea name="message" id="message" rows="10" class="form-input">{{old('message')}}</textarea>
                    @error('message')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{route('admin.dashboard')}}" class="cancel-button">キャンセル</a>
                    <button class="submit-button" type="submit" id="submitButton">送信する</button>
                </div>

                <div class="mail-loading" id="loadingIndicator">
                    <div class="spinner"></div>
                    <p class="mail-loading-text">メール送信中...</p>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('mailForm').addEventListener('submit', function () {
            document.getElementById('submitButton').disabled = true;
            document.getElementById('loadingIndicator').style.display = 'block';
        });
    </script>
@endsection
