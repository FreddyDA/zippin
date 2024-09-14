<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'customer_id',
        'shipping_address_id', 
        'billing_address_id', 
        'total',
        'status',
        'currency',
        'date_created',
        'date_modified'
    ];

    // Relaciones, métodos y lógica adicional aquí

    public function orderItems()
    {
        return $this->hasMany(OrdersProducts::class, 'order_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Addresses::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Addresses::class, 'shipping_address_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function shipping()
    {
        return $this->hasOne(Shippings::class, 'id_order');
    }

    public function payment()
    {
        return $this->hasOne(Payments::class, 'order_id');
    }


}