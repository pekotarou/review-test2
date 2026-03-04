<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'price' => ['required', 'integer', 'between:0,10000'],
            // 更新は画像必須にしない（選ばれたらチェック）
            'image' => ['nullable', 'file', 'mimes:jpeg,png'],
            'seasons' => ['required', 'array'],
            'seasons.*' => ['exists:seasons,id'],
            'description' => ['required', 'string', 'max:120'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.integer' => '数値で入力してください',
            'price.between' => '0~10000円以内で入力してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'seasons.required' => '季節を選択してください',
            'seasons.*.exists' => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '120文字以内で入力してください',
        ];
    }
}