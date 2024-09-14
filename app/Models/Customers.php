<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customers extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'customers';
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'number_phone',
        'created_at',
        'updated_at'
    ];

    public function orders()
    {
        return $this->hasMany(Orders::class, 'customer_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(Addresses::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->hasOne(Addresses::class, 'shipping_address_id');
    }

}   