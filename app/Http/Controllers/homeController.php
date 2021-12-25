<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\data_penghuni;
use App\Models\detail_gadget;
use App\Models\internet_keluarga;
use App\Models\hasilDecisiontree;

class homeController extends Controller
{
    public function index(Request $request)
    {
        // yang belum bagian Memilih data yang telah dilakukan prediksi
        // dd(isset($request->idData));

        if(!isset($request->idData)){    //INI BELUM BENARRRR>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $data = hasilDecisiontree::latest()->first(); //Tidak di set maka ambil data paling terakhir
            $idData = $data->id;
        }else{
            $idData = $request->idData;
            $data = hasilDecisiontree::find($idData); //jika di set maka ambil data sesuai set
        }

        $namaData       = $data->namaHasilDecisionTree;
        $jmlKel         = $data->jmlKel;
        $jml            = unserialize($data->jml);
        $totEntropykel  = $data->totEntropykel;
        $hasil          = unserialize($data->hasil);
        $akar           = unserialize($data->serializeAkar);

        $semuaData = hasilDecisiontree::orderBy('created_at', 'desc')->get();

        return view('content.home',compact('idData','namaData','jmlKel','jml','totEntropykel','hasil','akar','semuaData'));
    }

    public function search(Request $request)
    {
        $data = hasilDecisiontree::all();

        $request->searchHasilDecisionTree;
    }

    public function hapusHasilDecisiontree(Request $request)
    {

        // dd($request);
        hasilDecisiontree::find($request->id)->delete();

        return redirect('/')->with('message','Berhasil menghapus hasil decision tree');
    }
  
}
