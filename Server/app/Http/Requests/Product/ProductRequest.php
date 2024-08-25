<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{


	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	} //end authorize()


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules(): array
	{

		return [
			'title' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'description' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'price' => request()->isMethod('put') ? ['integer', 'nullable'] : ['integer', 'required'],

			'hotelId' => ['string', 'required'],

			'discount' => ['integer', 'nullable'],
			'availableQuantity' => request()->isMethod('put') ? ['integer', 'nullable'] : ['integer', 'required'],
			'categories' => request()->isMethod('put') ? ['nullable', 'array', 'exists:categories,id'] : ['required', 'array', 'exists:categories,id'],
			'tags' => ['nullable', 'array', 'exists:tags,id'],
			'media' => request()->isMethod('put') ? ['nullable', 'array', 'exists:media,id'] : ['nullable', 'array', 'exists:media,id'],
			'pin' => ['nullable', 'boolean'],
		];
	} //end rules()


}//end class
