<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $reservation = $this->route('reservation');
        return auth()->id() === $reservation->user_id;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date|after:today',
            'time' => ['required', 'regex:/^([0-1][0-9]|2[0-1]):(00|30)$/'],
            'number_of_people' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => '予約日は必須です',
            'date.date' => '正しい日付形式で入力してください',
            'date.after' => '明日以降の日付を選択してください',
            'time.required' => '予約時間は必須です',
            'time.regex' => '正しい時間形式で入力してください',
            'number_of_people.required' => '予約人数は必須です',
            'number_of_people.integer' => '予約人数は整数で入力してください',
            'number_of_people.min' => '予約人数は1人以上で入力してください',
            'number_of_people.max' => '予約人数は10人以下で入力してください',
        ];
    }
}
