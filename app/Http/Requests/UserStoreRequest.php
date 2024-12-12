<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|int',
            'password' => 'required|string|min:8',
            'role_id' => 'required|int',
            'gender'=> 'required|string',
            'email' => 'required|email|unique:users,email',
            'date_of_birth'=> 'string',
            'valid_ID_number'=> 'string',
            // 'first_name'=> 'required|string',
            // 'last_name'=> 'required|string',
            // 'user_name'=> 'required|string|unique:users,user_name',
            // 'password' => 'required|string|min:6',
            // 'mobile_number'=> 'required|string',
            // 'country'=> 'required|string',
        ];
    }
}
