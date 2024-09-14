<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class Product
{

    private $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function findAll()
    {
        return $this->productRepository->getAll();
    }

    public function findById(Int $id)
    {
        return $this->productRepository->getById($id);
    }

    public function create(Array $data)
    {
        return $this->productRepository->insert($data);
    }

    public function update(Array $data, Int $id)
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete(Int $id)
    {
        return $this->productRepository->delete($id);
    }

    public function validate(Int $id)
    {
        return $this->productRepository->validate($id);
    }

}