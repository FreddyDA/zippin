<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Orders;

class OrderRepository
{


    public function getById(Int $id)
    {
        try {

            $result = Orders::with([
                'customer',
                'billingAddress',
                'shippingAddress',
                'orderItems.product',
                'payment',
                'shipping'

            ])->find($id);

            if(isset($result['id'])) {
                return $result;
            } else {
                return NULL;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar una orden: " . $e->getMessage());
            return NULL;
            
        }
    }

    public function update(Int $id, Array $data)
    {
       

        try {

            $result = Orders::where('id', $id)->update($data);

            return $result;

        } catch (\Exception $e) {
            // Log the error
            Log::channel('database')->error('Error modificar la orden: ' . $e->getMessage());

            return $e->getMessage();
        }
    }

    public function validate(Int $id)
    {
        try {

            $order = Orders::findOrFail($id);

            return $order;

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar una orden: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    public function getProductsById(Int $id)
    {
        try {

            $result = Orders::with(['orderItems.product'])->find($id);

            if(isset($result['id'])) {
                return $result;
            } else {
                return NULL;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar los productos de una orden: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    public function getShippingById(Int $id)
    {
        try {

            $result = Orders::with([
                'customer',
                'shippingAddress',
                'shipping'
            ])->find($id);

            if(isset($result['id'])) {
                return $result;
            } else {
                return NULL;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar el detalle del envio de una orden: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    public function getPaymentById(Int $id)
    {
        try {

            $result = Orders::with([
                'customer',
                'billingAddress',
                'payment'
            ])->find($id);

            if(isset($result['id'])) {
                return $result;
            } else {
                return NULL;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar el detalle de una orden: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    
}