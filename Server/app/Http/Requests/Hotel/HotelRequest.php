<?php

declare(strict_types=1);

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRequest extends FormRequest
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
			'name' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'description' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'country' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'city' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'address' => request()->isMethod('put') ? ['string', 'nullable'] : ['required', 'string'],
			'media' => request()->isMethod('put') ? ['nullable', 'array', 'exists:media,id'] : ['nullable', 'array', 'exists:media,id'],
		];
	} //end rules()


}//end class
