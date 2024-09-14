<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $table = 'addresses';
    protected $fillable = [
        'address_1',
        'address_2', 
        'city', 
        'state',
        'postcode',
        'country',
        'type',
        'customer_id'
    ];

    // Relaciones, métodos y lógica adicional aquí

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(Orders::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->hasOne(Orders::class, 'shipping_address_id');
    }
    


}