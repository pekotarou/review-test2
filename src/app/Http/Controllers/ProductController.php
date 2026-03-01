<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;  

class ProductController extends Controller
{

    //商品一覧ページ（最初）
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->sort;
        $products;
        $data;

        //キーワードもソートも無しの場合
        if(empty($keyword)&&empty($sort)) {
            $products = Product::paginate(6);
        }elseif($keyword&&empty($sort)){
            $data = Product::query();
            $data->where('name', 'LIKE', "%{$keyword}%")->get();
            $products = $data->paginate(6)->appends($request->all());
        }elseif($keyword&&$sort){
            $data = Product::query();
            $data->where('name', 'LIKE', "%{$keyword}%")->get();
            if ($request->sort === 'high') {
                $data->orderBy('price', 'desc');
            }elseif ($request->sort === 'low') {
                $data->orderBy('price', 'asc');
            }
            $products = $data->paginate(6)->appends($request->all());
        }elseif(empty($keyword)&&$sort){
            $data = Product::query();
            if ($request->sort === 'high') {
                $data->orderBy('price', 'desc');
            }elseif ($request->sort === 'low') {
                $data->orderBy('price', 'asc');
            }
            $products = $data->paginate(6)->appends($request->all());
        }

        //return view('index', compact('products'));
        return view('index')->with('products',$products)
        ->with('keyword',$keyword)
        ->with('sort',$sort);


    }
    public function add()
    {
        $products = Product::paginate(6); //エラー回避のため仮置き
        return view('add', compact('products'));
    }

    public function store(TodoRequest $request)
    {
        $todo = $request->only(['content']);
        Todo::create($todo);
        return redirect('/')->with('message', 'Todoを作成しました');
    }


    public function search(Request $request)
{
   /* テーブルから全てのレコードを取得する */
        $data = Product::query();
        /* キーワードから検索処理 */
        $keyword = $request->input('keyword');
        $sort = $request->sort;
        if(!empty($keyword)) {//$keyword　が空ではない場合、検索処理を実行します
            $data->where('name', 'LIKE', "%{$keyword}%")->get();
            if ( $sort === 'high') {
                $data->orderBy('price', 'desc');
            }elseif ( $sort === 'low') {
                $data->orderBy('price', 'asc');
            }
         $products = $data->paginate(6)->appends($request->all());
        return view('index')->with('products',$products)
        ->with('keyword',$keyword)
        ->with('sort',$sort);
        }
}
}
