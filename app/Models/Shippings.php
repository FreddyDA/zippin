<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippings extends Model
{
    use HasFactory;

    protected $table = 'shippings';
    public $timestamps = false;
    
    protected $fillable = [
        'method', 
        'total',
        'create_at',
        'update_at',
        'id_order'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'id_order');
    }

}