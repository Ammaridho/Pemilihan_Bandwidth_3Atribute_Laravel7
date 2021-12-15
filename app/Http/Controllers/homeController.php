<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\data_penghuni;
use App\Models\detail_gadget;
use App\Models\internet_keluarga;
use App\Models\hasilDecisiontree;

class homeController extends Controller
{
    public function index()
    {
        // yang belum bagian Memilih data yang telah dilakukan prediksi

        
        if(session()->get( 'idData' ) == null){
            $data = hasilDecisiontree::latest()->first(); //Tidak di set maka ambil data paling terakhir
            $idData = $data->id;
        }else{
            $idData = session()->get( 'idData' );
            $data = hasilDecisiontree::find($idData); //jika di set maka ambil data sesuai set
        }

        $namaData       = $data->namaHasilDecisionTree;
        $jmlKel         = $data->jmlKel;
        $jml            = unserialize($data->jml);
        $totEntropykel  = $data->totEntropykel;
        $hasil          = unserialize($data->hasil);
        $akar           = unserialize($data->serializeAkar);

        return view('content.home',compact('idData','namaData','jmlKel','jml','totEntropykel','hasil','akar'));
    }
  
}
