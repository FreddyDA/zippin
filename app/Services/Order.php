<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Services\GenerateOrderProccess;

class Order
{

    private $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function create(Array $request)
    {
        $process = new GenerateOrderProccess($request);
        $result = $process->init();
        
        return $result;
    }

    public function find(Int $id)
    {
        $result = $this->orderRepository->getById($id);
        
        if (!isset($result['id'])) {
            
            $result = "Orden no encontrada con ID: $id";
        }

        return $result;
    }

    public function changeStatus(String $status, Int $id)
    {
        $param = array(
            'status' => $status
        );

        return $this->orderRepository->update($id, $param);

    }

    public function validate(Int $id)
    {
        return $this->orderRepository->validate($id);
    }

    public function findProducts(Int $id)
    {
        $result = $this->orderRepository->getProductsById($id);
        
        if (!isset($result['id'])) {
            
            $result = "Orden no encontrada con ID: $id";
        }

        return $result;
    }

    public function findShipping(Int $id)
    {
        $result = $this->orderRepository->getShippingById($id);
        
        if (!isset($result['id'])) {
            
            $result = "Orden no encontrada con ID: $id";
        }

        return $result;
    }

    public function findPayment($id)
    {
        $result = $this->orderRepository->getPaymentById($id);
        
        if (!isset($result['id'])) {
            
            $result = "Orden no encontrada con ID: $id";
        }

        return $result;
    }

}