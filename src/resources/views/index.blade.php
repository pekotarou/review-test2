@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="page-header">
    @if(request()->filled('keyword'))
        <h1>“{{ request('keyword') }}”の商品一覧</h1>
    @else
        <h1>商品一覧</h1>
    @endif

    <a href="/products/register"  class="add-button">
        + 商品を追加
    </a>
</div>

<div class="content-wrapper">

    <!-- サイドバー -->
    <aside class="sidebar">

        <!-- 検索フォーム -->
        <form class="search-form"
              action="{{ route('products.search') }}"
              method="GET">

            <!-- 並び替えを保持 -->
            <input type="hidden"
                   name="sort"
                   value="{{ request('sort') }}">

            <div class="search-form__item">
                <input class="search-input"
                       type="text"
                       name="keyword"
                       placeholder="商品名で検索"
                       value="{{ request('keyword') }}">
            </div>

            <div class="search-form__button">
                <button class="search-button" type="submit">
                    検索
                </button>
            </div>
        </form>

        <!-- 並び替え -->
        <div class="sort-area">
            <label>価格順で表示</label>

            <form method="GET"
                  action="{{ route('products.index') }}">

                <!-- キーワードを保持 -->
                <input type="hidden"
                       name="keyword"
                       value="{{ request('keyword') }}">

                <select name="sort"
                        onchange="this.form.submit()"
                        class="sort-select" required >

                    <option value="">価格で並べ替え</option>

                    <option value="high"
                        {{ request('sort') == 'high' ? 'selected' : '' }}>
                        高い順に表示
                    </option>

                    <option value="low"
                        {{ request('sort') == 'low' ? 'selected' : '' }}>
                        低い順に表示
                    </option>

                </select>
            </form>

            <!-- ✖ 並び替えタグ -->
            @if(request('sort'))
                <div class="sort-tag">
                    <span class="tag-text">
                        {{ request('sort') == 'high'
                            ? '高い順に表示'
                            : '低い順に表示' }}
                    </span>

                    <a href="{{ route('products.index',
                        request()->except('sort')) }}"
                       class="tag-close">
                        ×
                    </a>
                </div>
            @endif
        </div>

        <div class="line"></div>

    </aside>

    <!-- 商品一覧 -->
    <div class="product-grid">
        @foreach($products as $product)
         <a href="{{ url('/products/detail/' . $product->id) }}"
           class="product-card-link">
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}">
                <div class="product-info">
                    <span>{{ $product->name }}</span>
                    <span>¥{{ number_format($product->price) }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

</div>

<!-- ページネーション -->
<div class="pagination">
     {{ $products->appends(request()->all())->links('pagination::bootstrap-4') }}
</div>

@endsection