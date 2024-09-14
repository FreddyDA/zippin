<?php

namespace App\Services;

class Customer
{
    public function find($id)
    {
        return Customer::find($id);
    }

    public function create($request)
    {
        return Customer::create($request->all());
    }

    public function update($request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return $customer;
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();

        return response()->json(['message' => 'Customer deleted']);
    }

    public function changeStatus()
    {
        return Customer::all();
    }
}