<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\OrderRequest;
use App\Services\Order;
use App\Jobs\NotifyOrderStatus;
use App\Models\Users;
use App\Models\Orders;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *     title="API de Ordenes",
 *     version="1.0.0",
 *     description="Documentación de la API para gestionar órdenes."
 * )
 * 
 * @OA\Server(url="http://localhost:8000")
 */

class OrderController extends Controller
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->middleware('auth.jwt', ['except' => ['show']]);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     summary="Obtener una orden por ID",
     *     description="Retorna los detalles de una orden específica.",
     *     tags={"Ordenes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la orden",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=200),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Orden obtenida, orden numero: 22"),
     *                     @OA\Property(
     *                         property="body",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=22),
     *                         @OA\Property(property="total", type="string", example="29.35"),
     *                         @OA\Property(property="status", type="string", example="processing"),
     *                         @OA\Property(property="currency", type="string", example="USD"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T13:15:05.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T13:15:05.000000Z"),
     *                         @OA\Property(property="customer_id", type="integer", example=25),
     *                         @OA\Property(property="shipping_address_id", type="integer", example=44),
     *                         @OA\Property(property="billing_address_id", type="integer", example=43),
     *                         @OA\Property(
     *                             property="customer",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=25),
     *                             @OA\Property(property="first_name", type="string", example="John"),
     *                             @OA\Property(property="last_name", type="string", example="Doe"),
     *                             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                             @OA\Property(property="number_phone", type="string", example="(555) 555-5555"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z")
     *                         ),
     *                         @OA\Property(
     *                             property="billing_address",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=43),
     *                             @OA\Property(property="address_1", type="string", example="969 Market"),
     *                             @OA\Property(property="address_2", type="string", example=null),
     *                             @OA\Property(property="city", type="string", example="San Francisco"),
     *                             @OA\Property(property="state", type="string", example="CA"),
     *                             @OA\Property(property="postcode", type="string", example="94103"),
     *                             @OA\Property(property="country", type="string", example="US"),
     *                             @OA\Property(property="type", type="string", example="billing"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="customer_id", type="integer", example=25)
     *                         ),
     *                         @OA\Property(
     *                             property="shipping_address",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=44),
     *                             @OA\Property(property="address_1", type="string", example="969 Market"),
     *                             @OA\Property(property="address_2", type="string", example=null),
     *                             @OA\Property(property="city", type="string", example="San Francisco"),
     *                             @OA\Property(property="state", type="string", example="CA"),
     *                             @OA\Property(property="postcode", type="string", example="94103"),
     *                             @OA\Property(property="country", type="string", example="US"),
     *                             @OA\Property(property="type", type="string", example="shipping"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="customer_id", type="integer", example=25)
     *                         ),
     *                         @OA\Property(
     *                             property="order_items",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=14),
     *                                 @OA\Property(property="quantity", type="integer", example=2),
     *                                 @OA\Property(property="order_id", type="integer", example=22),
     *                                 @OA\Property(property="product_id", type="integer", example=93),
     *                                 @OA\Property(
     *                                     property="product",
     *                                     type="object",
     *                                     @OA\Property(property="id", type="integer", example=93),
     *                                     @OA\Property(property="name", type="string", example="Producto 1"),
     *                                     @OA\Property(property="price", type="string", example="100.00"),
     *                                     @OA\Property(property="category", type="string", example="Categoría 1"),
     *                                     @OA\Property(property="quantity", type="integer", example=2),
     *                                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-10T10:29:44.000000Z"),
     *                                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T13:15:05.000000Z")
     *                                 )
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="payment",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=5),
     *                             @OA\Property(property="method", type="string", example="bacs"),
     *                             @OA\Property(property="transaction_id", type="integer", example=123),
     *                             @OA\Property(property="date_paid", type="string", example="2017-03-22 16:28:08"),
     *                             @OA\Property(property="order_id", type="integer", example=22)
     *                         ),
     *                         @OA\Property(
     *                             property="shipping",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=4),
     *                             @OA\Property(property="method", type="string", example="Flat Rate"),
     *                             @OA\Property(property="total", type="string", example="10.00"),
     *                             @OA\Property(property="created_at", type="string", example=null),
     *                             @OA\Property(property="updated_at", type="string", example=null),
     *                             @OA\Property(property="id_order", type="integer", example=22)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la orden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=500),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Ocurrio un error al obtener la orden: error_message"),
     *                     @OA\Property(property="body", type="null", example=null)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function show(Int $id)
    {

        $result = $this->order->find($id);

        if (is_string($result)) {
            $message = 'Ocurrio un error al obtener la orden: '.$result;
            $status = 500;
        }else{
            $message = 'Orden obtenida, orden numero: '.$result['id'];
            $body = $result;
            $status = 200;
        }

        return response()->json([
            'data' => [
                'status' => $status,
                'response' => [
                    'message' => $message,
                    'body' => $body
                ]
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Registrar una nueva orden",
     *     description="Crea una nueva orden en el sistema.",
     *     tags={"Ordenes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=727),
     *             @OA\Property(property="number", type="string", example="727"),
     *             @OA\Property(property="order_key", type="string", example="order_58d2d042d1d"),
     *             @OA\Property(property="status", type="string", example="processing"),
     *             @OA\Property(property="currency", type="string", example="USD"),
     *             @OA\Property(property="date_created", type="string", format="date-time", example="2017-03-22T16:28:02"),
     *             @OA\Property(property="date_modified", type="string", format="date-time", example="2017-03-22T16:28:08"),
     *             @OA\Property(property="discount_total", type="string", example="0.00"),
     *             @OA\Property(property="discount_tax", type="string", example="0.00"),
     *             @OA\Property(property="shipping_total", type="string", example="10.00"),
     *             @OA\Property(property="shipping_tax", type="string", example="0.00"),
     *             @OA\Property(property="cart_tax", type="string", example="1.35"),
     *             @OA\Property(property="total", type="string", example="29.35"),
     *             @OA\Property(
     *                 property="billing",
     *                 type="object",
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="company", type="string", example=""),
     *                 @OA\Property(property="address_1", type="string", example="969 Market"),
     *                 @OA\Property(property="address_2", type="string", example=""),
     *                 @OA\Property(property="city", type="string", example="San Francisco"),
     *                 @OA\Property(property="state", type="string", example="CA"),
     *                 @OA\Property(property="postcode", type="string", example="94103"),
     *                 @OA\Property(property="country", type="string", example="US"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="phone", type="string", example="(555) 555-5555")
     *             ),
     *             @OA\Property(
     *                 property="shipping",
     *                 type="object",
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="company", type="string", example=""),
     *                 @OA\Property(property="address_1", type="string", example="969 Market"),
     *                 @OA\Property(property="address_2", type="string", example=""),
     *                 @OA\Property(property="city", type="string", example="San Francisco"),
     *                 @OA\Property(property="state", type="string", example="CA"),
     *                 @OA\Property(property="postcode", type="string", example="94103"),
     *                 @OA\Property(property="country", type="string", example="US")
     *             ),
     *             @OA\Property(property="payment_method", type="string", example="bacs"),
     *             @OA\Property(property="payment_method_title", type="string", example="Direct Bank Transfer"),
     *             @OA\Property(property="transaction_id", type="string", example="123"),
     *             @OA\Property(property="date_paid", type="string", format="date-time", example="2017-03-22T16:28:08"),
     *             @OA\Property(property="date_paid_gmt", type="string", format="date-time", example="2017-03-22T19:28:08"),
     *             @OA\Property(property="date_completed", type="string", example=null),
     *             @OA\Property(property="date_completed_gmt", type="string", example=null),
     *             @OA\Property(property="cart_hash", type="string", example=""),
     *             @OA\Property(
     *                 property="line_items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=315),
     *                     @OA\Property(property="name", type="string", example="Woo Single #1"),
     *                     @OA\Property(property="product_id", type="integer", example=93),
     *                     @OA\Property(property="variation_id", type="integer", example=0),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="tax_class", type="string", example=""),
     *                     @OA\Property(property="subtotal", type="string", example="6.00"),
     *                     @OA\Property(property="subtotal_tax", type="string", example="0.45"),
     *                     @OA\Property(property="total", type="string", example="6.00"),
     *                     @OA\Property(property="total_tax", type="string", example="0.45"),
     *                     @OA\Property(
     *                         property="taxes",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=75),
     *                             @OA\Property(property="total", type="string", example="0.45"),
     *                             @OA\Property(property="subtotal", type="string", example="0.45")
     *                         )
     *                     ),
     *                     @OA\Property(property="meta_data", type="array", @OA\Items(type="object")),
     *                     @OA\Property(property="sku", type="string", example=""),
     *                     @OA\Property(property="price", type="integer", example=3)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="shipping_lines",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=317),
     *                     @OA\Property(property="method_title", type="string", example="Flat Rate"),
     *                     @OA\Property(property="method_id", type="string", example="flat_rate"),
     *                     @OA\Property(property="total", type="string", example="10.00"),
     *                     @OA\Property(property="total_tax", type="string", example="0.00"),
     *                     @OA\Property(property="taxes", type="array", @OA\Items(type="object")),
     *                     @OA\Property(property="meta_data", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden creada satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=200),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Orden creada satisfactoriamente"),
     *                     @OA\Property(property="body", type="integer", example=27)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la orden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=500),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Ocurrio un error al crear la orden"),
     *                     @OA\Property(property="body", type="null", example=null)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(OrderRequest $request)
    {
        // Autorizar la acción usando la política
        $this->authorize('create', Orders::class);

        $result = $this->order->create($request->validated());

        if (is_string($result)) {
            $message = 'Ocurrio un error al crear la orden';
            $status = 500;
        }else{
            $message = 'Orden creada satisfactoriamente';
            $status = 200;
            $order = $this->order->validate($result);

            // Despachar el Job para notificar al cliente y al usuario de la empresa
            $user = Cache::get('user');
            NotifyOrderStatus::dispatch($order, 'created', $user->email);
        }

        return response()->json([
            'data' => [
                'status' => $status,
                'response' => [
                    'message' => $message,
                    'body' => $result
                ]
            ]
        ]);
    }

    /**
         * @OA\Put(
         *     path="/orders/{id}/status",
         *     summary="Actualizar el estado de una orden",
         *     tags={"Ordenes"},
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         description="ID de la orden",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"status"},
         *             @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"})
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Estado actualizado correctamente",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string"),
         *             @OA\Property(property="body", type="object")
         *         )
         *     ),
         *     @OA\Response(
         *         response=403,
         *         description="No autorizado",
         *         @OA\JsonContent(
         *             @OA\Property(property="error", type="string")
         *         )
         *     )
         * )
     */
    public function updateStatus(Request $request, Int $id)
    {
        // Definir los estados permitidos
        $allowedStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        // Validar el request
        $request->validate([
            'status' => ['required', 'in:' . implode(',', $allowedStatuses)]
        ]);

        $order = $this->order->validate($id);

        $user = Cache::get('user');
        
        $response = Gate::inspect('updateStatus', $order);
 
        if ($response->allowed()) {
            //Autorizado
            $result = $this->order->changeStatus($request->post('status'), $id);
            
        } else { 
            //No autorizado
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (is_string($result)) {

            $message = 'Ocurrio un error al actualizar la orden: '.$result;
            $status = 500;

        }else{

            $message = 'Orden actualizada';
            $body = $result;
            $status = 200;
            // Despachar el Job para notificar al cliente y al usuario de la empresa
            NotifyOrderStatus::dispatch($order, 'updated', $user->email);

        }

        return response()->json([
            'data' => [
                'status' => $status,
                'response' => [
                    'message' => $message,
                    'body' => $body
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}/products",
     *     tags={"Ordenes"},
     *     summary="Mostrar productos de una orden",
     *     description="Obtiene los productos de una orden por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la orden",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden obtenida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=200),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Orden obtenida, orden numero: 22"),
     *                     @OA\Property(
     *                         property="body",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=22),
     *                         @OA\Property(property="total", type="string", example="29.35"),
     *                         @OA\Property(property="status", type="string", example="pending"),
     *                         @OA\Property(property="currency", type="string", example="USD"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T13:15:05.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-13T13:13:33.000000Z"),
     *                         @OA\Property(property="customer_id", type="integer", example=25),
     *                         @OA\Property(property="shipping_address_id", type="integer", example=44),
     *                         @OA\Property(property="billing_address_id", type="integer", example=43),
     *                         @OA\Property(
     *                             property="order_items",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=14),
     *                                 @OA\Property(property="quantity", type="integer", example=2),
     *                                 @OA\Property(property="order_id", type="integer", example=22),
     *                                 @OA\Property(property="product_id", type="integer", example=93),
     *                                 @OA\Property(
     *                                     property="product",
     *                                     type="object",
     *                                     @OA\Property(property="id", type="integer", example=93),
     *                                     @OA\Property(property="name", type="string", example="Producto 1"),
     *                                     @OA\Property(property="price", type="string", example="100.00"),
     *                                     @OA\Property(property="category", type="string", example="Categoría 1"),
     *                                     @OA\Property(property="quantity", type="integer", example=-10),
     *                                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-10T10:29:44.000000Z"),
     *                                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-14T18:37:51.000000Z")
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la orden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=500),
     *                 @OA\Property(property="response", type="string", example="Ocurrio un error al obtener la orden: {error}")
     *             )
     *         )
     *     )
     * )
     */
    public function showProducts(Int $id)
    {

        $result = $this->order->findProducts($id);

        if (is_string($result)) {
            $message = 'Ocurrio un error al obtener la orden: '.$result;
            $status = 500;
        }else{
            $message = 'Orden obtenida, orden numero: '.$result['id'];
            $body = $result;
            $status = 200;
        }

        return response()->json([
            'data' => [
                'status' => $status,
                'response' => [
                    'message' => $message,
                    'body' => $body
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}/shipping",
     *     tags={"Ordenes"},
     *     summary="Mostrar información de envío de una orden",
     *     description="Obtiene la información de envío de una orden por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la orden",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden obtenida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=200),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Orden obtenida, orden numero: 22"),
     *                     @OA\Property(
     *                         property="body",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=22),
     *                         @OA\Property(property="total", type="string", example="29.35"),
     *                         @OA\Property(property="status", type="string", example="pending"),
     *                         @OA\Property(property="currency", type="string", example="USD"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T13:15:05.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-13T13:13:33.000000Z"),
     *                         @OA\Property(property="customer_id", type="integer", example=25),
     *                         @OA\Property(property="shipping_address_id", type="integer", example=44),
     *                         @OA\Property(property="billing_address_id", type="integer", example=43),
     *                         @OA\Property(
     *                             property="customer",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=25),
     *                             @OA\Property(property="first_name", type="string", example="John"),
     *                             @OA\Property(property="last_name", type="string", example="Doe"),
     *                             @OA\Property(property="email", type="string", example="f_d-16@hotmail.com"),
     *                             @OA\Property(property="number_phone", type="string", example="(555) 555-5555"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z")
     *                         ),
     *                         @OA\Property(
     *                             property="shipping_address",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=44),
     *                             @OA\Property(property="address_1", type="string", example="969 Market"),
     *                             @OA\Property(property="address_2", type="string", example=null),
     *                             @OA\Property(property="city", type="string", example="San Francisco"),
     *                             @OA\Property(property="state", type="string", example="CA"),
     *                             @OA\Property(property="postcode", type="string", example="94103"),
     *                             @OA\Property(property="country", type="string", example="US"),
     *                             @OA\Property(property="type", type="string", example="shipping"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="customer_id", type="integer", example=25)
     *                         ),
     *                         @OA\Property(
     *                             property="shipping",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=4),
     *                             @OA\Property(property="method", type="string", example="Flat Rate"),
     *                             @OA\Property(property="total", type="string", example="10.00"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example=null),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                             @OA\Property(property="id_order", type="integer", example=22)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la orden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=500),
     *                 @OA\Property(property="response", type="string", example="Ocurrio un error al obtener la orden: {error}")
     *             )
     *         )
     *     )
     * )
     */
    public function showShipping(Int $id)
    {

        $result = $this->order->findShipping($id);

        if (is_string($result)) {
            $message = 'Ocurrio un error al obtener la orden: '.$result;
            $status = 500;
        }else{
            $message = 'Orden obtenida, orden numero: '.$result['id'];
            $body = $result;
            $status = 200;
        }

        return response()->json([
            'data' => [
                'status' => $status,
                'response' => [
                    'message' => $message,
                    'body' => $body
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}/payment",
     *     tags={"Ordenes"},
     *     summary="Mostrar información de pago de una orden",
     *     description="Obtiene la información de pago de una orden por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la orden",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden obtenida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=200),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Orden obtenida, orden numero: 22"),
     *                     @OA\Property(
     *                         property="body",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=22),
     *                         @OA\Property(property="total", type="string", example="29.35"),
     *                         @OA\Property(property="status", type="string", example="pending"),
     *                         @OA\Property(property="currency", type="string", example="USD"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T13:15:05.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-13T13:13:33.000000Z"),
     *                         @OA\Property(property="customer_id", type="integer", example=25),
     *                         @OA\Property(property="shipping_address_id", type="integer", example=44),
     *                         @OA\Property(property="billing_address_id", type="integer", example=43),
     *                         @OA\Property(
     *                             property="customer",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=25),
     *                             @OA\Property(property="first_name", type="string", example="John"),
     *                             @OA\Property(property="last_name", type="string", example="Doe"),
     *                             @OA\Property(property="email", type="string", example="f_d-16@hotmail.com"),
     *                             @OA\Property(property="number_phone", type="string", example="(555) 555-5555"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z")
     *                         ),
     *                         @OA\Property(
     *                             property="shipping_address",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=44),
     *                             @OA\Property(property="address_1", type="string", example="969 Market"),
     *                             @OA\Property(property="address_2", type="string", example=null),
     *                             @OA\Property(property="city", type="string", example="San Francisco"),
     *                             @OA\Property(property="state", type="string", example="CA"),
     *                             @OA\Property(property="postcode", type="string", example="94103"),
     *                             @OA\Property(property="country", type="string", example="US"),
     *                             @OA\Property(property="type", type="string", example="shipping"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T12:47:37.000000Z"),
     *                             @OA\Property(property="customer_id", type="integer", example=25)
     *                         ),
     *                         @OA\Property(
     *                             property="shipping",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=4),
     *                             @OA\Property(property="method", type="string", example="Flat Rate"),
     *                             @OA\Property(property="total", type="string", example="10.00"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example=null),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                             @OA\Property(property="id_order", type="integer", example=22)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la orden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="status", type="integer", example=500),
     *                 @OA\Property(property="response", type="string", example="Ocurrio un error al obtener la orden: {error}")
     *             )
     *         )
     *     )
     * )
     */
    public function showPayment( Int $id)
    {

        $result = $this->order->findPayment($id);

        if (is_string($result)) {
            $message = 'Ocurrio un error al obtener la orden: '.$result;
            $status = 500;
        }else{
            $message = 'Orden obtenida, orden numero: '.$result['id'];
            $body = $result;
            $status = 200;
        }

        return response()->json([
            'data' => [
                'status' => $status,
                'response' => [
                    'message' => $message,
                    'body' => $body
                ]
            ]
        ]);
    }

}