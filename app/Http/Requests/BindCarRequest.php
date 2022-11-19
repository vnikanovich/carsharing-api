<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BindCarRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'car_id' => ['required', 'exists:cars,id']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required' => 'USER_ID_NOT_PROVIDED',
            'user_id.exists' => 'USER_DOES_NOT_EXIST',
            'car_id.required' => 'CAR_ID_NOT_PROVIDED',
            'car_id.exists' => 'CAR_DOES_NOT_EXIST',
        ];
    }
}
