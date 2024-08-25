<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Cambia esto según tus reglas de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companyName' => 'required|string',
            'contactName' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'productName' => 'required|string',
            'sellingPrice' => 'required|numeric',
            'wholesalePrice' => 'required|numeric',
            'minQuantity' => 'required|integer',
            'productDescription' => 'nullable|string',
            'media' => 'required',
  ];
    }   
}
