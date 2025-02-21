<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_id' => 'required|exists:shops,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'number_of_people' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages()
    {
        return [
            'shop_id.required' => '店舗の指定は必須です',
            'shop_id.exists' => '指定された店舗が存在しません',
            'date.required' => '予約日は必須です',
            'date.date' => '予約日の形式が正しくありません',
            'date.after_or_equal' => '予約日は今日以降の日付を指定してください',
            'time.required' => '予約時間は必須です',
            'time.date_format' => '予約時間の形式が正しくありません',
            'number.required' => '予約人数は必須です',
            'number.integer' => '予約人数は整数で指定してください',
            'number.min' => '予約人数は1人以上で指定してください',
            'number.max' => '予約人数は10人までです',
        ];
    }
}
