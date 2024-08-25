<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WholesaleUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'companyName' => ['required', 'string'],
            'companySize' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'mediaId' => ['required', 'exists:media,id'],
            'firstName' => ['required', 'string', 'min:3', 'max:255'],
            'lastName' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'required_with:confirmPassword', 'same:confirmPassword'],
            'confirmPassword' => ['required', 'min:8'],
        ];
    }
}
