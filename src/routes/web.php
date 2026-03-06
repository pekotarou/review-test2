<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//商品一覧表示のルート
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');
//商品登録ページのルート
Route::get('/products/register', [ProductController::class, 'add']);
//商品登録ページで登録ボタン押下後の動き
Route::post('/products/register', [ProductController::class, 'store']);
//商品一覧でキーワード検索した際の動き
Route::get('/products/search', [ProductController::class, 'index'])
    ->name('products.search');
//商品一覧で商品カードを押下した時の動き
Route::get('/products/detail/{id}', [ProductController::class, 'detail'])
    ->name('products.detail')
    ->where('id', '[0-9]+');

    // 更新処理
Route::get('/products/{id}/update', [ProductController::class, 'edit'])
    ->name('products.edit')
    ->where('id', '[0-9]+');
Route::put('/products/{id}/update', [ProductController::class, 'update'])
    ->name('products.update')
    ->where('id', '[0-9]+');

// 削除
Route::delete('/products/{id}/delete', [ProductController::class, 'destroy'])
    ->name('products.delete')
    ->where('id', '[0-9]+');






