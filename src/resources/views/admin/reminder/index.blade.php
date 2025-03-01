@extends('layouts.admin')

@section('title', 'リマインダー設定 - Rese 管理画面')

@section('content')
    <div class="reminder-settings">
        <h1 class="page-title">リマインダー設定</h1>

        <form action="{{route('admin.reminder.update')}}" method="POST" class="reminder-form">@csrf
            @method('PUT')

            <div class="settings-card">
                <h2 class="card-title">基本設定</h2>

                <div class="form-group">
                    <label class="form-label">リマインダー機能</label>
                    <div class="toggle-wrapper">
                        <input type="checkbox" id="is_enabled" name="is_enabled" value="1" class="toggle-input"
                            {{$settings->is_enabled ? 'checked' : ''}}>
                        <label for="is_enabled" class="toggle-label"></label>
                        <span class="toggle-text">{{$settings->is_enabled ? '有効' : '無効'}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="send_time" class="form-label">送信時間</label>
                    <input type="time" id="send_time" name="send_time" class="form-input"
                        value="{{$settings->send_time ? substr($settings->send_time, 0, 5) : '09:00'}}" required>
                    @error('send_time')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="days_before" class="form-label">送信タイミング</label>
                    <select name="days_before" id="days_before" class="form-input">
                        <option value="0" {{$settings->days_before == 0 ? 'selected' : ''}}>予約当日</option>
                        <option value="1" {{$settings->days_before == 1 ? 'selected' : ''}}>予約前日</option>
                        <option value="2" {{$settings->days_before == 2 ? 'selected' : ''}}>2日前</option>
                        <option value="3" {{$settings->days_before == 3 ? 'selected' : ''}}>3日前</option>
                        <option value="7" {{$settings->days_before == 7 ? 'selected' : ''}}>1週間前</option>
                    </select>
                    @error('days_before')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="settings-card">
                <h2 class="card-title">メール設定</h2>

                <div class="form-group">
                    <label for="email_subject" class="form-label">メール件名</label>
                    <input type="text" id="email_subject" name="email_subject" class="form-input"
                        value="{{$settings->email_subject}}" required>
                    @error('email_subject')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email_template" class="form-label">メールテンプレート</label>
                    <textarea name="email_template" id="email_template" class="form-input template-textarea"
                        required>{{$settings->email_template}}</textarea>
                    @error('email_template')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="template-help">
                    <p>以下のプレースホルダーを使用できます:</p>
                    <ul>
                        <li><code>@{{user_name}}</code> - 予約者名</li>
                        <li><code>@{{shop_name}}</code> - 店舗名</li>
                        <li><code>@{{Reservation_date}}</code> - 予約日</li>
                        <li><code>@{{Reservation_time}}</code> - 予約時間</li>
                        <li><code>@{{number_of_guests}}</code> - 予約人数</li>
                    </ul>
                </div>
            </div>

            <div class="form-actions">
                <button class="submit-button" class="submit">設定を保存</button>
            </div>
        </form>

        <div class="settings-card">
            <h2 class="card-title">テスト送信</h2>
            <form action="{{route('admin.reminder.test')}}" method="POST" class="test-form">
                @csrf
                <div class="form-group">
                    <label for="test-email" class="form-label">テスト送信先メールアドレス</label>
                    <input type="email" id="test_email" name="email" class="form-input" required>
                    @error('email')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-actions">
                    <button class="test-button" type="submit">テスト送信</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleInput = document.getElementById('is_enabled');
            const toggleText = document.querySelector('.toggle-text');

            toggleInput.addEventListener('change', function () {
                toggleText.textContent = this.checked ? '有効' : '無効';
            });
        });
    </script>
@endsection