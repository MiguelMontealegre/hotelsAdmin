<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserUpdateProfileRequest
 *
 * @category Request
 * @package  App\Http\Requests

 */
class ZipRecordingRequest extends FormRequest
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
    public function rules()
    {
        return [
            'recordingIds'   => ['array'],
            'recordingIds.*' => ['exists:recordings,id'],
        ];

    }//end rules()


}//end class
