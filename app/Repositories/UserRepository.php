<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\users;

class UserRepository
{

    public function getAll()
    {
        try {

            $result = Users::get()->toArray();

            if(isset($result[0]['id'])) {
                return $result;
            } else {
                return FALSE;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al obtener todos los usuarios: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    public function getById(Int $id)
    {
        try {

            $result = Users::find($id);

            if(isset($result['id'])) {
                return $result;
            } else {
                return FALSE;
            }

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al buscar un usuario: " . $e->getMessage());
            return NULL;
            
        }
    }

    public function insert(Array $data)
    {
       
        DB::beginTransaction();
        try {

            $result = Users::create($data);

            DB::commit();

            return $result->id;

        } catch (\Exception $e) {
            // Log the error
            DB::rollBack();
            Log::channel('database')->error('Error crear un usuario: ' . $e->getMessage());

            return null;
        }
    }


    public function update(Int $id, Array $data)
    {
       
        DB::beginTransaction();
        try {

            $product = Users::findOrFail($id);
            $result = $product->update($data);

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            // Log the error
            DB::rollBack();
            Log::channel('database')->error('Error modificar usuario: ' . $e->getMessage());

            return null;
        }
    }

    public function delete(Int $id)
    {
       
        DB::beginTransaction();
        try {

            
            $result = Users::findOrFail($id)->delete();

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            // Log the error
            DB::rollBack();
            Log::channel('database')->error('Error eliminar usuario: ' . $e->getMessage());

            return null;
        }
    }

    public function validate(Int $id)
    {
        try {

            $order = Users::findOrFail($id);

            return $order;

        } catch (\Exception $e) {

            Log::channel('database')->error("Error al validar la existencia de un usuario: " . $e->getMessage());
            return $e->getMessage();
            
        }
    }

    
}