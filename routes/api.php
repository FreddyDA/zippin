<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [AuthController::class, 'login']);

/* Route::get('/send-test-email', function () {
    Mail::raw('Este es un correo de prueba.', function ($message) {
        $message->to('test@gmail.com')
                ->subject('Correo de Prueba');
    });

    return 'Correo de prueba enviado!';
}); */


Route::group(['middleware' => ['auth.jwt']], function () {
    /***************USER*********************/
    // Ruta para consultar todos los usuarios
    Route::get('/users', [UserController::class, 'index']);
    // Ruta para consultar un usuarios
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('check.numeric.id');
    // Ruta para crear un nuevo usuario
    Route::post('/users', [UserController::class, 'register']);
    // Ruta para actualizar un usuario existente
    Route::patch('/users/{id}', [UserController::class, 'update'])->middleware('check.numeric.id');
    // Ruta para eliminar un usuario existente
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('check.numeric.id');

    /***************ORDER*********************/
    // Ruta para obtener una orden específica por ID
    Route::get('/orders/{id}', [OrderController::class, 'show'])->middleware('check.numeric.id');
    // Ruta para crear una nueva orden
    Route::post('/orders/register', [OrderController::class, 'register']);
    // Ruta para actualizar una orden existente
    Route::patch('/orders/{id}', [OrderController::class, 'updateStatus'])->middleware('check.numeric.id');

    /***************PRODUCT*********************/
    // Ruta para consultar todos los productos
    Route::get('/product', [ProductController::class, 'index']);
    // Ruta para consultar un productos
    Route::get('/product/{id}', [ProductController::class, 'show'])->middleware('check.numeric.id');
    // Ruta para actualizar una producto existente
    Route::patch('/product/{id}', [ProductController::class, 'update'])->middleware('check.numeric.id');
    // Ruta para crear un nuevo producto
    Route::post('/product', [ProductController::class, 'store']);
    // Ruta para eliminar una orden existente
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->middleware('check.numeric.id');
});
