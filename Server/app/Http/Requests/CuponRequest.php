<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuponRequest extends FormRequest
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

		$codeValidation = [
			'required',
            'string',
			'unique:cupons,code'
        ];
        if (request()->isMethod('put')) {
            $codeValidation = [
                'string',
                'nullable',
            ];
        }

        return [
            'code' => $codeValidation,
            'discount' => ['required', 'numeric'],
            'availableQuantity' => ['required', 'numeric'],
            'expirationDate' => ['nullable', 'date'],
            'categories' => ['nullable', 'array', 'exists:categories,id'],
            'products' => ['nullable', 'array', 'exists:products,id'],
        ];
        
    }
}
