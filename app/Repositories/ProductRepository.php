<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Products;

class ProductRepository
{

    public function getAll()
    {
        try {

            $result = Products::get()->toArray();

            if(isset($result[0]['id'])) {
                return $result;
            } else {
                return FALSE;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al obtener todos los producto: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    public function getById(Int $id)
    {
        try {

            $result = Products::find($id);

            if(isset($result['id'])) {
                return $result;
            } else {
                return FALSE;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar un producto: " . $e->getMessage());
            return NULL;
            
        }
    }

    public function insert(Array $data)
    {
       
        DB::beginTransaction();
        try {

            $result = Products::create($data);

            DB::commit();

            return $result->id;

        } catch (\Exception $e) {
            // Log the error
            DB::rollBack();
            Log::channel('database')->error('Error crear un producto: ' . $e->getMessage());

            return null;
        }
    }


    public function update(Int $id, Array $data)
    {
       
        DB::beginTransaction();
        try {

            $product = Products::findOrFail($id);
            $result = $product->update($data);

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            // Log the error
            DB::rollBack();
            Log::channel('database')->error('Error modificar producto: ' . $e->getMessage());

            return null;
        }
    }

    public function delete(Int $id)
    {
       
        DB::beginTransaction();
        try {

            
            $result = Products::findOrFail($id)->delete();

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            // Log the error
            DB::rollBack();
            Log::channel('database')->error('Error eliminar producto: ' . $e->getMessage());

            return null;
        }
    }

    public function validate(Int $id)
    {
        try {

            $order = Products::findOrFail($id);

            return $order;

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al validar la existencia de un producto: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    
}