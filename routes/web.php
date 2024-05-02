<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ComentariosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect('/login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

    // categorias
    Route::get('/categorias', [App\Http\Controllers\CategoriasController::class, 'index']);
    Route::get('/categorias/registrar', [App\Http\Controllers\CategoriasController::class, 'create']);
    Route::post('/categorias/registrar', [App\Http\Controllers\CategoriasController::class, 'store']);
    Route::get('/categorias/actualizar/{id}', [App\Http\Controllers\CategoriasController::class, 'edit']);
    Route::put('/categorias/actualizar/{id}', [App\Http\Controllers\CategoriasController::class, 'update']);
    Route::get('/categorias/estado/{id}', [App\Http\Controllers\CategoriasController::class, 'estado']);


    //tags
    Route::get('/tags', [App\Http\Controllers\TagsController::class, 'index']);
    Route::get('/tags/registrar', [App\Http\Controllers\TagsController::class, 'create']);
    Route::post('/tags/registrar', [App\Http\Controllers\TagsController::class, 'store']);
    Route::get('/tags/actualizar/{id}', [App\Http\Controllers\TagsController::class, 'edit']);
    Route::put('/tags/actualizar/{id}', [App\Http\Controllers\TagsController::class, 'update']);
    Route::get('/tags/estado/{id}', [App\Http\Controllers\TagsController::class, 'estado']);

    //posts
    Route::get('/posts', [App\Http\Controllers\PostsController::class, 'index']);
    Route::get('/posts/registrar', [App\Http\Controllers\PostsController::class, 'create']);
    Route::post('/posts/registrar', [App\Http\Controllers\PostsController::class, 'store']);
    Route::get('/posts/ver/{id}', [App\Http\Controllers\PostsController::class, 'show']);
    Route::get('/posts/actualizar/{id}', [App\Http\Controllers\PostsController::class, 'edit']);
    Route::put('/posts/actualizar/{id}', [App\Http\Controllers\PostsController::class, 'update']);
    Route::get('/posts/estado/{id}', [App\Http\Controllers\PostsController::class, 'estado']);

    //comentarios
    Route::get('/comentarios', [App\Http\Controllers\ComentariosController::class, 'index']);
    Route::get('/comentarios/registrar/{post_id}', [App\Http\Controllers\ComentariosController::class, 'create']);
    Route::post('/comentarios/registrar/{post_id}', [App\Http\Controllers\ComentariosController::class, 'store']);
    Route::get('/comentarios/actualizar/{id}', [App\Http\Controllers\ComentariosController::class, 'edit']);
    Route::put('/comentarios/actualizar/{id}', [App\Http\Controllers\ComentariosController::class, 'update']);
    Route::get('/comentarios/estado/{id}', [App\Http\Controllers\ComentariosController::class, 'estado']);

    //usuarios
    Route::get('/usuarios', [App\Http\Controllers\UsuariosController::class, 'index']);
});
