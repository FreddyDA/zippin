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

    public function find($id)
    {
        $result = $this->orderRepository->getById($id);
        
        if (!isset($result['id'])) {
            
            $result = "Orden no encontrada con ID: $id";
        }

        return $result;
    }

    public function changeStatus($status, $id)
    {
        $param = array(
            'status' => $status
        );

        return $this->orderRepository->update($id, $param);

    }

    public function validate($id)
    {
        return $this->orderRepository->validate($id);
    }

}