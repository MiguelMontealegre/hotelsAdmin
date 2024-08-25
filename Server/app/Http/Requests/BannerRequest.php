<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
            'title' => ['nullable', 'string'],
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date'],
            'mediaId' => ['required', 'exists:media,id'],
			'mediaSmId' => ['required', 'exists:media,id'],
            'link' => ['nullable', 'string'],
        ];
    }
}
