<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//principal
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/all', [HomeController::class, 'all'])->name('home.all');

//Articulos
/* Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy'); */

//Articulos en reemplazo de los 6 que estan arriba
Route::resource('articles', ArticleController::class)
    ->except('show')
    ->names('articles');

//Categorias en reemplazo de los 6 que podrian haber estado arriba
Route::resource('categories', CategoryController::class)
    ->except('show')
    ->names('categories');

//Comentarios
Route::resource('comments', CommentController::class)
    ->only('index', 'destroy') //funcion que esta unicamente
    ->names('comments');

//perfiles
Route::resource('profiles', ProfileController::class)
    ->only('edit', 'update') //funcion que esta unicamente
    ->names('profiles');    

//ver articulos
Route::get('article/{article}', [ArticleController::class, 'show'])->name('articles.show');

//ver articulos por categoria
Route::get('category/{category}', [CategoryController::class, 'detail'])->name('categories.detail');    

//guardar comentarios
Route::get('/comment', [CommentController::class, 'store'])->name('comments.store');

//se creo cuando ejecute los comandos de Autenticacion - siempre se pone al ultimo
Auth::routes();
