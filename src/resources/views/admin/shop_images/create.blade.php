@extends('layouts.admin')

@section('title', '店舗画像アップロード')

@section('content')
    <div class="admin-content">
        <div class="header-content">
            <h1 class="page-title">店舗画像アップロード</h1>
        </div>

        <div class="form-container">
            <form action="{{route('admin.shop-images.store')}}" method="POST" enctype="multipart/form-data">@csrf

                <div class="form-group">
                    <label for="name" class="form-label">画像名（店舗名）</label>
                    <input type="text" id="name" name="name" class="form-input" required>
                    @error('name')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">画像ファイル</label>
                    <input type="file" id="image" name="image" class="form-input" accept="image/*" required>
                    <div class="image-preview" id="image-preview"></div>
                    @error('image')
                        <span class="error-message">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="submit-button" type="submit">アップロード</button>
                    <a href="{{route('admin.shop-images.index')}}" class="cancel-button">キャンセル</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const previewDiv = document.getElementById('image-preview');
            previewDiv.innerHTML = '';

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '300px';
                    img.style.maxHeight = '200px';
                    img.style.marginTop = '10px';
                    previewDiv.appendChild(img);
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
@endsection
