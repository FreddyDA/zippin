<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Orders;
use App\Models\Customers;
use App\Models\Addresses;
use App\Models\Payments;
use App\Models\Shippings;
use App\Models\Products;
use App\Models\OrdersProducts;

class GenerateOrderRepository
{


    public function varifyClient(Array $condition, Array $data)
    {
        try {

            $customer = Customers::firstOrCreate(
                $condition, // Condición de búsqueda
                $data // Datos para crear un nuevo registro si no se encuentra uno existente
       
            );

            return $customer->id;

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al verificar o crear cliente, en la tabla customer: " . $e->getMessage());
            return NULL;
            
        }
    }

    public function insertAddress(Array $condition, Array $data)
    {
        try {

            $address = Addresses::firstOrCreate(
                $condition, // Condición de búsqueda
                $data // Datos para crear un nuevo registro si no se encuentra uno existente
       
            );

            #$address = Addresses::create($data);

            return $address->id;

        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error insertar la direccion: ' . $e->getMessage());

            return null;
        }
    }

    public function insertOrder(Array $data)
    {
        // 4. Crear la orden
        try {

            $order = Orders::create($data);

            return $order->id;

        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error insertar la orden: ' . $e->getMessage());

            return null;
        }
    }

    public function insertOrderItems(Array $data)
    {
        // 5. Crear los items de la orden

        try {

            $result = OrdersProducts::create($data);
            
            return $result->id;
           
        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error insertar los productos que conforman la orden: ' . $e->getMessage());

            return null;
        }
    }

    public function decrementProduct(Int $idProduct, Int $quantity)
    {
        // 5. Crear los items de la orden

        try {

            Products::where('id', $idProduct)->update(['quantity' => $quantity]);

            return TRUE;

        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error modificar un producto de la orden: ' . $e->getMessage());

            return null;
        }
    }

    public function insertPayment(Array $data)
    {
        // 6. Crear el pago
        try {

            $result = Payments::create($data);

            return $result->id;

        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error al crear el pago de la orden: ' . $e->getMessage());

            return null;
        }
    }

    public function insertShipping(Array $data)
    {
        // 7. Crear el envío
        try {

            $result = Shippings::create($data);

            return $result->id;

        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error al crear el envio de la orden: ' . $e->getMessage());

            return null;
        }
    }

    private function invoice()
    {
        // 8. Crear la factura
    }
}