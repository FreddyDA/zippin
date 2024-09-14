<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     required={"name", "price"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID del producto"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nombre del producto"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Precio del producto"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="string",
 *         description="Categoria del producto"
 *     ),
 *      @OA\Property(
 *         property="quantity",
 *         type="string",
 *         description="Cantidad del producto"
 *     )
 * )
 */
class Products extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name', 
        'price',
        'category',
        'quantity',
        'created_at',
        'updated_at'
    ];

    public function orders()
    {
        return $this->belongsToMany(Orders::class, 'orders_products', 'product_id', 'order_id')->withPivot('quantity');
    }

}