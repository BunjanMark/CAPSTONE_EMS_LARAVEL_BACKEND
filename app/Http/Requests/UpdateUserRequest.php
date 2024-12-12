<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {   
        // get the currently authenticated user
        $currentUser = Auth::user();
        // console.log($currentUser);
        printf($currentUser);
        return $currentUser != null;
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // test
    // testing lang for github rule
    public function rules(): array
    {
        //get the currently authenticated user
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|int',
        ];
    }
}
