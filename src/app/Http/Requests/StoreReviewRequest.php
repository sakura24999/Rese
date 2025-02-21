<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:300'
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => '評価を選択してください',
            'rating.integer' => '評価は整数で入力してください',
            'rating.between' => '評価は1から5の間で選択してください',
            'comment.required' => 'コメントを入力してください',
            'comment.string' => 'コメントは文字列で入力してください',
            'comment.max' => 'コメントは300文字以内で入力してください'
        ];
    }
}
