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

// Utama
Route::get('/','homeController@index')->name('home');

Route::get('/hasilDecisiontree','perhitunganController@hasilDecisiontree')->name('hasilDecisiontree');


// Pengecekan
Route::post('/prediksi','prediksiController@prosesCek')->name('prosesCek');


//Testing Bagan
Route::get('/treeDiagramTest','homeController@treeDiagramTest')->name('treeDiagramTest');

Route::get('/resultTree','homeController@resultTree')->name('resultTree');


// Form Tambahan
Route::get('/penghuni','penggunaanController@penghuni')->name('penghuni');

Route::get('/penggunaangadget','penggunaanController@gadget')->name('gadget');