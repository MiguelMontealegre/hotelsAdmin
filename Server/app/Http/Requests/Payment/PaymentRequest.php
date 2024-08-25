<?php
declare(strict_types=1);

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
			'userId'             => [
                'required',
                'uuid',
                'exists:users,id',
            ],
			'billingDetails.firstName' => ['required', 'string'],
			'billingDetails.lastName' => ['required', 'string'],
			'billingDetails.address' => ['required', 'string'],
			'billingDetails.addressOptional' => ['nullable', 'string'],
			'billingDetails.city' => ['required', 'string'],
			'billingDetails.country' => ['required', 'string'],
			'billingDetails.postalCode' => ['required', 'string'],
			'billingDetails.optional' => ['nullable', 'string'],
        ];

    }//end rules()


}//end class
