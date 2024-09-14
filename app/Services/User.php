<?php

namespace App\Services;

use App\Repositories\UserRepository;

class User
{

    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function findAll()
    {
        return $this->userRepository->getAll();
    }

    public function findById(Int $id)
    {
        return $this->userRepository->getById($id);
    }

    public function create(Array $data)
    {
        return $this->userRepository->insert($data);
    }

    public function update(Array $data, Int $id)
    {
        return $this->userRepository->update($id, $data);
    }

    public function delete(Int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function validate(Int $id)
    {
        return $this->userRepository->validate($id);
    }

}