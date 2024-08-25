<?php
declare(strict_types=1);


namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Mail\SendInfoSale;

use App\Mail\SendInfoSaleUser;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    //

    public function store(Request $request)
    {

        $customerId = $request->input('customerId');
        $customer = User::find($customerId);
        
        if ($customer) {
            $newSale = Sale::create([
                'total' => $request->input('total'),
                'customerId' => $customerId,
                'address' => $request->input('address'),
                'status' => 'TIENDA',
            ]);
        
            $recipientEmails = User::whereHas('roles', function ($query) {
                $query->where('name', 'SALE_USER');
            })->pluck('email')->toArray();
            // $wholesaleUser = WholesaleUsers::where('userId', $user->id)->first();
            Mail::to($recipientEmails)->send(new SendInfoSale( $customer,$newSale));
            Mail::to($customer->email)->send(new SendInfoSaleUser($customer, $newSale));
        
            return response()->json(['message' => 'Venta creada correctamente'], 201);
        
    }
    else{
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
    
}


public function update(SaleRequest $request)
{
    $id = $request->input('id');  
    $sale = Sale::find($id);
    $sale->status = $request->input('status');
    $sale->save();

    $customer = User::find($sale->customerId);


    Mail::to($customer->email)->send(new SendInfoSaleUser($customer, $sale));

    return response()->json(['message' => 'Estado de venta actualizado correctamente'], 200);

}
}
