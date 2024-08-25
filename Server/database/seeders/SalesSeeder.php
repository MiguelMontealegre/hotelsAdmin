<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use App\Models\CartProduct;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInfoSaleUser;
use App\Mail\SendInfoSale;
class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener el último usuario registrado
        $lastUser = User::latest()->first();

        // Verificar si hay algún usuario registrado
        if ($lastUser) {
            // Crear un pago asociado al último usuario
            $payment = Payment::create([
                'userId'   => $lastUser->id,
                'value'    => 120, // Aquí reemplaza por el valor que desees
                'provider' => 'paypal',
                // 'createdAt' => '2024-05-5    10:10:10',


            ]);

            // Crear una orden asociada al último usuario
         $order =   Order::create([
                'paymentId'       => $payment->id,
                'firstName'       => 'Nombre', // Ejemplo, reemplaza con los datos deseados
                'lastName'        => 'Apellido', // Ejemplo, reemplaza con los datos deseados
                'address'         => 'Dirección', // Ejemplo, reemplaza con los datos deseados
                'addressOptional' => 'Dirección Opcional', // Ejemplo, reemplaza con los datos deseados
                'city'            => 'Ciudad', // Ejemplo, reemplaza con los datos deseados
                'country'         => 'País', // Ejemplo, reemplaza con los datos deseados
                'postalCode'      => 13444, // Ejemplo, reemplaza con los datos deseados
                'optional'        => 'Opcional', // Ejemplo, reemplaza con los datos deseados
                'status'         => 'STORE',
            ]);

            $cartProducts = CartProduct::where('userId',$lastUser->id,)->get();
			foreach($cartProducts as $cartProduct){
				OrderProduct::create(
					[
						'quantity' => $cartProduct->quantity,
						'sizeId' => $cartProduct->sizeId,
						'colorId' => $cartProduct->colorId,
						'productId' => $cartProduct->productId,
						'orderId' => $order->id
					]);
			}
            $recipientEmails = User::whereHas('roles', function ($query) {
                $query->where('name', 'ADMIN')
                ->orWhere('name', 'SALE_USER');
            })->pluck('email')->toArray();
            Mail::to($recipientEmails)->send(new SendInfoSale( $lastUser,$order));
            Mail::to($lastUser->email)->send(new SendInfoSaleUser($lastUser, $order));
    
        } else {
            // Si no hay ningún usuario registrado, imprimir un mensaje de advertencia
            echo "No se encontraron usuarios registrados.";
        }
    }
}
