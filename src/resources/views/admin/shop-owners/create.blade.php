@extends('layouts.admin')

@section('title', '店舗代表者登録')

@section('content')
<div class="shop-owner-create">
    <h1 class="page-title">店舗代表者登録</h1>

    <div class="form-container">
        <form action="{{route('admin.shop-owners.store')}}" class="create-form" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">名前</label>
                <div class="input-with-icon">
                    <span class="material-icons form-icon">person</span>
                    <input type="text" id="name" name="name" class="form-input" value="{{old('name')}}" placeholder="Representative name" required>
                </div>
                @error('name')
                <span class="error-message">{{ $message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">メールアドレス</label>
                <div class="input-with-icon">
                    <span class="material-icons form-icon">email</span>
                    <input type="email" id="email" name="email" class="form-input" value="{{old('email')}}" placeholder="Email" required>
                </div>
                @error('email')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <div class="input-with-icon">
                    <span class="material-icons form-icon">lock</span>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                </div>
                @error('password')
                <span class="error-message">{{ $message}}</span>
                @enderror
            </div>

            @if (count($shops)> 0)
            <div class="form-group">
                <label class="store_charge">担当店舗</label>
                <div class="shop-list">
                    @foreach ($shops as $shop)
                    <div class="shop-item">
                        <input type="checkbox" id="shop-{{ $shop->id }}" name="shops[]" value="{{ $shop->id }}" {{ in_array($shop->id, old('shops', [])) ? 'checked' : ''}}>
                        <label for="shop-{{ $shop->id }}">{{ $shop->name }}</label>
                    </div>

                    @endforeach
                </div>
            </div>

            @endif

            <div class="form-actions">
                <button class="submit-button" type="submit">登録</button>
                <a href="{{route('admin.shop-owners.index')}} }}" class="cancel-button">キャンセル</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shopCheckboxes = document.querySelectorAll('input[name="shops[]"]');

        shopCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    shopCheckboxes.forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
