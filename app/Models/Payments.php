<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';
    public $timestamps = false;
    
    protected $fillable = [
        'method',
        'transaction_id', 
        'date_paid', 
        'order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

}