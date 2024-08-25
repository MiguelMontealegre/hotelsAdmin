<?php
declare(strict_types=1);

namespace App\Http\Requests\Passenger;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PassengerRequest extends FormRequest
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
			'name' => ['required', 'string'],
        ];

    }//end rules()


}//end class
