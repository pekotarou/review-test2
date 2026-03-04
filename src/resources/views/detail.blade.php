@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')

<form class="edit-wrapper"
      action="{{ route('products.update', $product->id) }}"
      method="POST"
      enctype="multipart/form-data">
  @csrf
  @method('PUT')

  {{-- 上段：2カラム --}}
  <div class="top-row">
    {{-- 左：画像 --}}
    <div class="left">
      <div class="breadcrumb">
        <a href="{{ route('products.index') }}">商品一覧</a>
        <span class="sep">›</span>
        <span>{{ $product->name }}</span>
      </div>

      <div class="image-box">
        <img id="preview" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
      </div>

      <div class="file-row">
        <label class="file-btn" for="imageInput">ファイルを選択</label>
        <span class="file-name" id="fileName">{{ basename($product->image) }}</span>
        <input id="imageInput" class="file-input" type="file" name="image" accept="image/png,image/jpeg">
      </div>

      @foreach ($errors->get('image') as $message)
        <p class="error-message">{{ $message }}</p>
      @endforeach
    </div>

    {{-- 右：フォーム --}}
    <div class="right">
      <div class="field">
        <label class="field-label">商品名</label>
        <input class="input" type="text" name="name" value="{{ old('name', $product->name) }}">
        @foreach ($errors->get('name') as $message)
          <p class="error-message">{{ $message }}</p>
        @endforeach
      </div>

      <div class="field">
        <label class="field-label">値段</label>
        <input class="input" type="number" name="price" value="{{ old('price', $product->price) }}">
        @foreach ($errors->get('price') as $message)
          <p class="error-message">{{ $message }}</p>
        @endforeach
      </div>

      <div class="field">
        <label class="field-label">季節</label>
        <div class="season-row">
          @php $checkedIds = old('seasons', $product->seasons->pluck('id')->toArray()); @endphp
          @foreach($seasons as $season)
            <label class="season-item">
              <input class="season-check" type="checkbox" name="seasons[]" value="{{ $season->id }}"
                     {{ in_array($season->id, $checkedIds) ? 'checked' : '' }}>
              <span class="season-ui"></span>
              <span class="season-text">{{ $season->name }}</span>
            </label>
          @endforeach
        </div>

        @foreach ($errors->get('seasons') as $message)
          <p class="error-message">{{ $message }}</p>
        @endforeach
        @foreach ($errors->get('seasons.*') as $message)
          <p class="error-message">{{ $message }}</p>
        @endforeach
      </div>
    </div>
  </div>

  {{-- 下段：商品説明（横いっぱい） --}}
  <div class="bottom-row">
    <div class="desc-block">
      <label class="field-label">商品説明</label>
      <textarea name="description" class="textarea">{{ old('description', $product->description) }}</textarea>
      @foreach ($errors->get('description') as $message)
        <p class="error-message">{{ $message }}</p>
      @endforeach
    </div>

    {{-- ボタン（商品説明の下） --}}
   
    <div class="actions">
  <a class="btn-back" href="{{ route('products.index') }}">戻る</a>

  <div class="actions-right">
    <button class="btn-save" type="submit">変更を保存</button>

    {{--  ゴミ箱：クリックで削除フォームを送信 --}}
   
    <button type="button"
        class="trash-btn"
        aria-label="削除"
        onclick="document.getElementById('deleteForm').submit();">
            <img src="{{ asset('ごみ箱のフリーアイコン.png') }}" alt="削除">
</button>
  </div>
</div>
  </div>
</form>

<form id="deleteForm"
      action="{{ route('products.delete', $product->id) }}"
      method="POST"
      style="display:none;">
  @csrf
  @method('DELETE')
</form>





{{-- 画像プレビュー --}}
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
  const file = e.target.files[0];
  const preview = document.getElementById('preview');
  const fileName = document.getElementById('fileName');

  if (!file) return;

  fileName.textContent = file.name;

  const reader = new FileReader();
  reader.onload = (ev) => { preview.src = ev.target.result; };
  reader.readAsDataURL(file);
});
</script>

@endsection