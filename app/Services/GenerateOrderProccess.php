<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Repositories\GenerateOrderRepository;
use App\Services\Order;
/* use App\Services\Customer;
use App\Services\Address;
use App\Services\Payment;
use App\Services\Shipping; */
use App\Services\Product;

class GenerateOrderProccess
{

    private $request;
    private $generateOrderRepository;
    private $productClass;

    public function __construct(Array $request)
    {
        $this->request = $request;
        $this->generateOrderRepository = new GenerateOrderRepository();
        $this->productClass = new Product();
    }

    public function init()
    {
        DB::beginTransaction();

        try {
            Log::channel('newOrder')->info("////////////**********////////////");
            Log::channel('newOrder')->info("Iniciando proceso de creación de orden");

            $idClient = $this->client();
            $idAddress = $this->address($idClient);
            $idOrder = $this->order($idClient, $idAddress);
            $this->orderItems($idOrder);
            $this->payment($idOrder);
            $this->shipping($idOrder);

            DB::commit();

            return $idOrder;

        } catch (\Exception $e) {

            DB::rollBack();
            Log::channel('newOrder')->error("Erro: " . $e->getMessage());

            return $e->getMessage();

        }
    }

    private function client()
    {
        // 2. Crear o actualizar el cliente
        Log::channel('newOrder')->info("Creando o actualizando el cliente");

        $condition = ['email' => $this->request['billing']['email']]; // Condición de búsqueda

        $params = [
            'first_name' => $this->request['billing']['first_name'], // Datos para crear un nuevo registro si no se encuentra uno existente
            'last_name' => $this->request['billing']['last_name'],
            'email' => $this->request['billing']['email'],
            'number_phone' => $this->request['billing']['phone']
        ];

        Log::channel('newOrder')->info("Datos: " . json_encode($params));

        $result = $this->generateOrderRepository->varifyClient($condition, $params);

        if (empty($result)) {
            throw new \Exception('Error en la Creacion o validacion del cliente');
        }
        return $result;
    }

    private function address(Int $idClient)
    {
        // 3. Crear direcciones de facturación y envío
        Log::channel('newOrder')->info("Creando direcciones de facturación y envío");

        $conditionBi = ['address_1' => $this->request['billing']['address_1'], 'postcode' => $this->request['billing']['postcode'], 'type' => 'billing']; // Condición de búsqueda

        $billing = Array(

            'address_1' => $this->request['billing']['address_1'],
            'address_2' => $this->request['billing']['address_2'],
            'city' => $this->request['billing']['city'],
            'state' => $this->request['billing']['state'],
            'postcode' => $this->request['billing']['postcode'],
            'country' => $this->request['billing']['country'],
            'type' => 'billing',
            'customer_id' => $idClient
        );

        Log::channel('newOrder')->info("Datos de la direccion de facturación: " . json_encode($billing));

        $billingAddress = $this->generateOrderRepository->insertAddress($conditionBi, $billing);

        if (empty($billingAddress)) {
            throw new \Exception('Error al guardar la direccion de facturacion');
        }

        $conditionSh = ['address_1' => $this->request['shipping']['address_1'], 'postcode' => $this->request['shipping']['postcode'], 'type' => 'shipping']; // Condición de búsqueda

        $shippin = Array(

            'address_1' => $this->request['shipping']['address_1'],
            'address_2' => $this->request['shipping']['address_2'],
            'city' => $this->request['shipping']['city'],
            'state' => $this->request['shipping']['state'],
            'postcode' => $this->request['shipping']['postcode'],
            'country' => $this->request['shipping']['country'],
            'type' => 'shipping',
            'customer_id' => $idClient
        );
        $shippingAddress = $this->generateOrderRepository->insertAddress($conditionSh, $shippin);

        Log::channel('newOrder')->info("Datos de la direccion de envio: " . json_encode($shippin));

        if (empty($shippingAddress)) {
            throw new \Exception('Error al guardar la direccion de envio');
        }

        return [
            'billing' => $billingAddress,
            'shipping' => $shippingAddress
        ];
    }

    private function order(Int $idClient, Array $addresses)
    {
        // 4. Crear la orden
        Log::channel('newOrder')->info("Creando la orden");

        $order = array (
            'customer_id' => $idClient,
            'billing_address_id' => $addresses['billing'],
            'shipping_address_id' => $addresses['shipping'],
            'status' => $this->request['status'],
            'total' => $this->request['total'],
            'currency' => $this->request['currency'],
            'created_at' => $this->request['date_created'],
            'update_at' => $this->request['date_modified'],
        );

        Log::channel('newOrder')->info("Datos de la orden: " . json_encode($order));

        $result = $this->generateOrderRepository->insertOrder($order);

        if (empty($result)) {
            throw new \Exception('Error al crear la orden');
        }

        return $result;
    }

    private function orderItems(Int $idOrder)
    {
        // 5. Crear los items de la orden
        Log::channel('newOrder')->info("Creando los items de la orden");

        foreach ($this->request['line_items'] as $item) {
            
            $product = $this->productClass->findById($item['product_id']);
            
            if (!empty($product)) {
                $item = array(
                    'order_id' => $idOrder,
                    'product_id' => $product['id'],
                    'quantity' => $item['quantity']
                );
    
                $result = $this->generateOrderRepository->insertOrderItems($item);
    
                Log::channel('newOrder')->info("Datos del item de la orden: " . json_encode($item));
    
                if (empty($result)) {
                    throw new \Exception('Error al guardar uno de los productos de la orden');
                }
    
                // Actualizar el inventario
                $quantity = $product['quantity'] - $item['quantity'];
                Log::channel('newOrder')->info("Actualizando el inventario del producto: " . $product['id'] . " - Cantidad: " . $quantity);
    
                $resultQuantity = $this->generateOrderRepository->decrementProduct($product['id'], $quantity);
    
                if (empty($resultQuantity)) {
                    throw new \Exception('Error al actualizar el inventario de uno de los productos de la orden');
                }
            } else {
                Log::channel('newOrder')->info("El producto no existe: " . $item['product_id']);
            }
            
        }
    }

    private function payment(Int $idOrder)
    {
        // 6. Crear el pago
        Log::channel('newOrder')->info("Creando el pago");

        $params = array(
            'order_id' => $idOrder,
            'method' => $this->request['payment_method'],
            'transaction_id' => $this->request['transaction_id'],
            'date_paid' => $this->request['date_paid'],
        );

        Log::channel('newOrder')->info("Datos del pago: " . json_encode($params));

        $result = $this->generateOrderRepository->insertPayment($params);

        if (empty($result)) {
            throw new \Exception('Error al guardar el pago de la orden');
        }
    }

    private function shipping(Int $idOrder)
    {
        // 7. Crear el envío
        Log::channel('newOrder')->info("Creando el envío");

        $params = array(
            'id_order' => $idOrder,
            'method' => $this->request['shipping_lines'][0]['method_title'],
            'total' => $this->request['shipping_total'],
            'created_at' => Carbon::now(),
        );

        Log::channel('newOrder')->info("Datos del envío: " . json_encode($params));

        $result = $this->generateOrderRepository->insertShipping($params);

        if (empty($result)) {
            throw new \Exception('Error al guardar el envio de la orden');
        }
    }


}