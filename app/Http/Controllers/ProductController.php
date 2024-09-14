<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Products;
use App\Services\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->middleware('auth.jwt', ['except' => ['show']]);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     method="post",
     * security={{"bearerToken": {}}},
     *     summary="Crear un nuevo producto",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="test"),
     *             @OA\Property(property="price", type="number", format="float", example=23.4),
     *             @OA\Property(property="category", type="string", example="shoe"),
     *             @OA\Property(property="quantity", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto creado satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example=200
     *                 ),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Producto creado satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="integer",
     *                         example=319
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud inválida"
     *     )
     * )
     */
    public function store(CreateProductRequest $request)
    {
        $this->authorize('create', Products::class);

        $result = $this->product->create($request->validated());

        if (is_string($result)) {
            $message = 'Ocurrio un error al crear el producto';
            $status = 500;
        }else{
            $message = 'Producto creado satisfactoriamente';
            $status = 200;
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
     * @OA\Get(
     *     path="/products",
     *     method="get",
     * security={{"bearerToken": {}}},
     *     summary="Obtener todos los productos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example=200
     *                 ),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Productos obtenidos satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Producto 1"),
     *                             @OA\Property(property="price", type="number", format="float", example=23.4),
     *                             @OA\Property(property="category", type="string", example="shoe"),
     *                             @OA\Property(property="quantity", type="integer", example=5),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud inválida"
     *     )
     * )
     */
    public function index()
    {
        $result = $this->product->findAll();

        if (is_string($result)) {
            $message = 'Ocurrio un error al consultar los productos';
            $status = 500;
        }elseif($result === false){
            $message = 'No se encontraron productos';
            $status = 204;
        }else{
            $message = 'Productos obtenidos satisfactoriamente';
            $status = 200;
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
     * @OA\Get(
     *     path="/products/{id}",
     *     method="get",
     * security={{"bearerToken": {}}},
     *     summary="Obtener un producto por ID",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto obtenido satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example=200
     *                 ),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Producto obtenido satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Producto 1"),
     *                         @OA\Property(property="price", type="number", format="float", example=23.4),
     *                         @OA\Property(property="category", type="string", example="shoe"),
     *                         @OA\Property(property="quantity", type="integer", example=5),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function show(Int $id)
    {
        $result = $this->product->findById($id);

        if (is_string($result)) {
            $message = 'Ocurrio un error al consultar el producto';
            $status = 500;
        }elseif($result === false){
            $message = 'No se encontro el producto';
            $status = 204;
        }else{
            $message = 'Consulta de producto satisfactoria';
            $status = 200;
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
     * @OA\Patch(
     *     path="/products/{id}",
     *     method="patch",
     * security={{"bearerToken": {}}},
     *     summary="Actualizar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Probando de nuevo"),
     *             @OA\Property(property="price", type="number", format="float", example=23.4),
     *             @OA\Property(property="category", type="string", example="shoe"),
     *             @OA\Property(property="quantity", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto modificado satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example=200
     *                 ),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Producto modificado satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="integer",
     *                         example=319
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud inválida"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, Int $id)
    {
        $product = $this->product->validate($id);
        
        $response = Gate::inspect('update', $product);
 
        if ($response->allowed()) {
            //Autorizado
            $result = $this->product->update($request->all(), $id);
        } else { 
            //No autorizado
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (is_string($result)) {
            $message = 'Ocurrio un error al modificar el producto';
            $status = 500;
        }else{
            $message = 'Producto modificado satisfactoriamente';
            $status = 200;
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
     * @OA\Delete(
     *     path="/products/{id}",
     *     method="delete",
     * security={{"bearerToken": {}}},
     *     summary="Eliminar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example=200
     *                 ),
     *                 @OA\Property(
     *                     property="response",
     *                     type="object",
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Producto eliminado satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="boolean",
     *                         example=true
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function destroy(Int $id)
    {
        $product = $this->product->validate($id);

        $response = Gate::inspect('delete', $product);
 
        if ($response->allowed()) {
            //Autorizado
            $result = $this->product->delete($id);
        } else { 
            //No autorizado
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (is_string($result)) {
            $message = 'Ocurrio un error al eliminar el producto';
            $status = 500;
        }else{
            $message = 'Producto eliminado satisfactoriamente';
            $status = 200;
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
}