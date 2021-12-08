<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('content.home');
// });

Route::get('/','homeController@index')->name('home');

Route::get('/tree','homeController@CreateTree')->name('createTree');

Route::get('/resultTree','homeController@resultTree')->name('resultTree');

Route::get('/treeDiagramTest','homeController@treeDiagramTest')->name('treeDiagramTest');

Route::get('/penghuni','penggunaanController@penghuni')->name('penghuni');

Route::get('/penggunaangadget','penggunaanController@gadget')->name('gadget');

Route::post('/prediksi','prediksiController@prosesCek')->name('prosesCek');