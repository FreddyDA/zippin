<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Gate;
use App\Models\Users;
use App\Services\User;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     method="get",
     *     security={{"bearerToken": {}}},
     *     summary="Obtener todos los usuarios",
     *     tags={"Usuarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Usuarios obtenidos satisfactoriamente",
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
     *                         example="Usuarios obtenidos satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="test"),
     *                             @OA\Property(property="email", type="string", example="test@gmail.com"),
     *                             @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example=null),
     *                             @OA\Property(property="password", type="string", example="$2y$10$3Z"),
     *                             @OA\Property(property="role", type="string", example="admin"),
     *                             @OA\Property(property="remember_token", type="string", nullable=true, example=null),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $result = $this->user->findAll();

        if (is_string($result)) {
            $message = 'Ocurrio un error al consultar los usuarios';
            $status = 500;
        }elseif($result === false){
            $message = 'No se encontraron usuarios';
            $status = 204;
        }else{
            $message = 'Usuarios obtenidos satisfactoriamente';
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
     * @OA\Post(
     *     path="/register",
     *     method="post",
     *     security={{"bearerToken": {}}},
     *     summary="Registrar un nuevo usuario",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="test"),
     *             @OA\Property(property="email", type="string", example="test@zippin.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="test12345678"),
     *             @OA\Property(property="password_confirmation", type="string", minLength=8, example="test12345678"),
     *             @OA\Property(property="role", type="string", example="user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario creado satisfactoriamente",
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
     *                         example="Usuario creado satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="integer",
     *                         example=3
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
    public function register(RegisterUserRequest $request)
    {

        $this->authorize('create', Users::class);

        $result = $this->user->create($request->validated());

        if (is_string($result)) {
            $message = 'Ocurrio un error al crear el usuario';
            $status = 500;
        }else{
            $message = 'Usuario creado satisfactoriamente';
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
     *     path="/users/{id}",
     *    method="get",
     *    security={{"bearerToken": {}}},
     *     summary="Obtener un usuario por ID",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario obtenido satisfactoriamente",
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
     *                         example="Usuario obtenido satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="Freddy Diaz"),
     *                         @OA\Property(property="email", type="string", example="diazfa6@gmail.com"),
     *                         @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example=null),
     *                         @OA\Property(property="password", type="string", example="$2y$12$RJiBvCAX4aMJpGSbt5W8T.Dh4KE5i0QIx/MhP5CeEywe8PqjdvXmm"),
     *                         @OA\Property(property="role", type="string", example="admin"),
     *                         @OA\Property(property="remember_token", type="string", nullable=true, example=null),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T20:48:45.000000Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function show(Int $id)
    {
        $result = $this->user->findById($id);

        if (is_string($result)) {
            $message = 'Ocurrio un error al consultar el usuario';
            $status = 500;
        }elseif($result === false){
            $message = 'No se encontro el usuario';
            $status = 204;
        }else{
            $message = 'Consulta de usuario satisfactoria';
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
     *     path="/users/{id}",
     *    method="patch",
     *   security={{"bearerToken": {}}},
     *     summary="Actualizar un usuario",
     *     tags={"Usuarios"},
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
     *             @OA\Property(property="name", type="string", example="test upddate"),
     *             @OA\Property(property="email", type="string", example="test@zippin.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="test1234567"),
     *             @OA\Property(property="password_confirmation", type="string", minLength=8, example="test1234567"),
     *             @OA\Property(property="role", type="string", example="user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado satisfactoriamente",
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
     *                         example="Usuario actualizado satisfactoriamente"
     *                     ),
     *                     @OA\Property(
     *                         property="body",
     *                         type="integer",
     *                         example=3
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
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $user = $this->user->validate($id);
        
        $response = Gate::inspect('update', $user);
 
        if ($response->allowed()) {
            //Autorizado
            $result = $this->user->update($request->all(), $id);
        } else { 
            //No autorizado
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (is_string($result)) {
            $message = 'Ocurrio un error al modificar el usuario';
            $status = 500;
        }else{
            $message = 'Usuario modificado satisfactoriamente';
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
     *     path="/users/{id}",
     *     method="delete",
     *    security={{"bearerToken": {}}},
     *     summary="Eliminar un usuario",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado satisfactoriamente",
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
     *                         example="Usuario eliminado satisfactoriamente"
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
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $user = $this->user->validate($id);

        $response = Gate::inspect('delete', $user);
 
        if ($response->allowed()) {
            //Autorizado
            $result = $this->user->delete($id);
        } else { 
            //No autorizado
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (is_string($result)) {
            $message = 'Ocurrio un error al eliminar el usuario';
            $status = 500;
        }else{
            $message = 'Usuario eliminado satisfactoriamente';
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
