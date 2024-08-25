<?php
declare(strict_types=1);

namespace App\Http\Controllers\Order;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInfoSaleUser;

class OrderController extends Controller

{

    public function update(Request $request)
    {
        $id = $request->input('id');  
        $order = Order::find($id);
        $order->status = $request->input('status');
        $order->save();
        $user= User::find($order->payment->userId);
        Mail::to($user->email)->send(new SendInfoSaleUser($user, $order));
        return response()->json(['message' => 'Orden actualizada correctamente'], 200);
    }//end update(

}//end class