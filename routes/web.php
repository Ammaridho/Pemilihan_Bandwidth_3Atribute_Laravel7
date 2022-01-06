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

// signup
Route::post('/signup','authController@signup')->name('signup');

// signin
Route::post('/signin','authController@signin')->name('signin');




//Testing Bagan
Route::get('/treeDiagramTest','homeController@treeDiagramTest')->name('treeDiagramTest');

Route::get('/resultTree','homeController@resultTree')->name('resultTree');




Route::group(['middleware' => 'ceksession'], function(){

    //lihat hasil decision tree
    Route::get('/hasilDecisiontree','perhitunganController@hasilDecisiontree')->name('hasilDecisiontree');
    
    // Search
    Route::get('/search','homeContoller@search')->name('search');
    
    // Hapus Hasil Decision Tree
    Route::delete('/hapusHasilDecisionTree/{id}','homeController@hapusHasilDecisiontree')->name('hapusHasilDecisionTree');
    
    //Import Export Excel
    Route::post('/importExcel','ImportExportExcelController@import_excel')->name('importExcel');
    
    // Pengecekan
    Route::get('/prediksi','prediksiController@prosesCek')->name('prosesCek');
    
    // Form Tambahan
    Route::get('/penghuni','penggunaanController@penghuni')->name('penghuni');
    
    Route::get('/penggunaangadget','penggunaanController@gadget')->name('gadget');

    // signout
    Route::get('/signout','authController@signout')->name('signout');

    //downloadcontohExcel
    Route::get('/downloadcontoh','ImportExportExcelController@contohExcel')->name('downloadcontoh');
});
