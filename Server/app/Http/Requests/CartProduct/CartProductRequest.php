<?php
declare(strict_types=1);

namespace App\Http\Requests\CartProduct;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartProductRequest extends FormRequest
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
			'quantity' => ['required', 'integer'],
			'productId' => ['required', 'string', 'exists:products,id'],
			'userId' => ['required', 'string', 'exists:users,id'],
			'sizeId' => ['nullable', 'string'],
			'colorId' => ['nullable', 'string'],
        ];

    }//end rules()


}//end class
