<?php
declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;

    }//end authorize()


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {

        return [
			'status' => ['required', 'string'],
			'firstName' => ['required', 'string'],
			'lastName' => ['required', 'string'],
			'address' => ['required', 'string'],
			'addressOptional' => ['nullable', 'string'],
			'city' => ['required', 'string'],
			'country' => ['required', 'string'],
			'postalCode' => ['required', 'string'],
			'optional' => ['nullable', 'string'],
			'paymentId'             => [
                'required',
                'uuid',
                'exists:payments,id',
            ],
        ];

    }//end rules()


}//end class
