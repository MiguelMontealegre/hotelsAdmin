<?php
declare(strict_types=1);

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
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
		$titleValidation = [
			'required',
            'string',
        ];
		$contentValidation = [
			'required',
            'string',
        ];
		$valorationValidation = [
			'required',
            'integer',
        ];
		$userIdValidation = [
			'required',
			'uuid',
			'exists:users,id',
		];
		if (request()->isMethod('put')) {
            $titleValidation = [
                'string',
                'nullable',
            ];
			$contentValidation = [
				'nullable',
				'string',
			];
			$valorationValidation = [
				'nullable',
				'integer',
			];
			$userIdValidation = [
				'nullable',
				'uuid',
				'exists:users,id',
			];
        }

        return [
			'title' => $titleValidation,
			'content' => $contentValidation,
			'valoration' => $valorationValidation,
			'pin' => ['nullable', 'boolean'],
			'userId' => $userIdValidation,
        ];

    }//end rules()


}//end class
