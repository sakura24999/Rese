<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Log::info('RegisterRequest: Checking authorization');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Log::info('RegisterRequest: Getting validation rules');
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|max:191',
        ];
    }

    public function messages()
    {
        Log::info('RegisterRequest: Getting custom messages');
        return [
            'name.required' => '名前は必須です',
            'name.max' => '名前は191文字以内で入力してください',
            'email.required' => 'メールアドレス必須です',
            'email.email' => '正しいメールアドレス形式で入力してください',
            'email.max' => 'メールアドレスは191文字以内で入力してください',
            'email.unique' => 'このメールアドレスは既に使用されています',
            'password.required' => 'パスワードは必須です',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは191文字以内で入力してください',
        ];
    }
}
