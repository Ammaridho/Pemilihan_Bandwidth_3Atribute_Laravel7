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

// signin
Route::post('/signin','authController@signin')->name('signin');

// signup
Route::post('/signup','authController@signup')->name('signup');



//Testing Bagan
Route::get('/treeDiagramTest','homeController@treeDiagramTest')->name('treeDiagramTest');

Route::get('/resultTree','homeController@resultTree')->name('resultTree');

// Form Tambahan
Route::get('/penghuni','penggunaanController@penghuni')->name('penghuni');

Route::get('/penggunaangadget','penggunaanController@gadget')->name('gadget');

// Pengecekan
Route::get('/prediksi','prediksiController@prosesCek')->name('prosesCek');


Route::group(['middleware' => 'ceksession'], function(){

    //jadikan pola utama
    Route::get('/jadikanpolautama/{id}','homeController@jadikanpolautama')->name('jadikanpolautama');
    
    //lihat hasil decision tree
    Route::get('/hasilDecisiontree','perhitunganController@hasilDecisiontree')->name('hasilDecisiontree');
    
    // Search
    Route::get('/search','homeContoller@search')->name('search');
    
    // Hapus Hasil Decision Tree
    Route::delete('/hapusHasilDecisionTree/{id}','homeController@hapusHasilDecisiontree')->name('hapusHasilDecisionTree');

    // Hapus Akun
    Route::delete('/hapusAdmin/{id}','homeController@hapusAkun')->name('hapusAkun');
    
    //Import Export Excel
    Route::post('/importExcel','ImportExportExcelController@import_excel')->name('importExcel');
    
    // signout
    Route::get('/signout','authController@signout')->name('signout');
    
    //downloadcontohExcel
    Route::get('/downloadcontoh','ImportExportExcelController@contohExcel')->name('downloadcontoh');
});
