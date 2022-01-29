<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\data_penghuni;
use App\Models\detail_gadget;
use App\Models\internet_keluarga;
use App\Models\hasilDecisiontree;
use App\Models\User;
use App\Models\best_provider;

use session;

// use App\Http\controllers\prediksiController;


class homeController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->idData);

        if (session('data')) {

            $idAkunLogin = User::where('username',session('data')['username'])->value('id');

            if($request->idData != null){
                $idData = $request->idData; 
            }

            if(session()->get( 'idData' ) != null){
                $idData = session()->get( 'idData' );
            }

            if(!isset($idData)){
                // dd($idData);
                $idData = null;
                $semuaData = hasilDecisiontree::where('user_id',$idAkunLogin)->orderBy('created_at', 'desc')->get();
                $listAkun = User::all();
                return view('content.home',compact('idData','semuaData','listAkun'));
            }else{
                // dd($idData);
                // $idData = $request->idData;
                $data = hasilDecisiontree::find($idData); //jika di set maka ambil data sesuai set

                $idData = $data->id;
    
                $namaData       = $data->namaHasilDecisionTree;
                $jmlKel         = $data->jmlKel;
                $jml            = unserialize($data->jml);
                $totEntropykel  = $data->totEntropykel;
                $hasil          = unserialize($data->hasil);
                $akar           = unserialize($data->serializeAkar);

                $hasilPrediksi = session()->get('hasilPrediksi'); //MASALAH DISINI PROSES HILANG SAAT DI RELOAD

                // dd(session()->get('hasilPrediksi'));
    
                $semuaData = hasilDecisiontree::where('user_id',$idAkunLogin)->orderBy('created_at', 'desc')->get();

                $best_provider = best_provider::where('hasildecisiontree_id',$idData)->get();

                $listAkun = User::all();

                // dd($listAkun);

                return view('content.home',compact('idData','namaData','jmlKel','jml','totEntropykel','hasil','akar','semuaData','hasilPrediksi','best_provider','listAkun'));
            }


        }else{
            $data = hasilDecisiontree::where('status','utama')->first(); //jika di set maka ambil data sesuai set

            $idData = $data->id;

            $namaData       = $data->namaHasilDecisionTree;
            $tanggalData    = $data->created_at;
            $deskripsiData  = $data->deskripsi;
            $jmlKel         = $data->jmlKel;
            $jml            = unserialize($data->jml);
            $totEntropykel  = $data->totEntropykel;
            $hasil          = unserialize($data->hasil);
            $akar           = unserialize($data->serializeAkar);

            $hasilPrediksi = session()->get('hasilPrediksi'); //MASALAH DISINI PROSES HILANG SAAT DI RELOAD

            $best_provider = best_provider::where('hasildecisiontree_id',$idData)->get();

            return view('content.home',compact('idData','namaData','tanggalData','deskripsiData','jmlKel','jml','totEntropykel','hasil','akar','hasilPrediksi','best_provider'));
        }
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

        return redirect('/')->with(['success' => 'Berhasil menghapus hasil decision tree!']);
    }

    public function jadikanpolautama(Request $request)
    {
        $utamasebelumnya = hasildecisiontree::where('status','utama')->first();
        $utamasebelumnya->status = '';
        $utamasebelumnya->save();

        $jadikanutama = hasildecisiontree::find($request->id);
        $jadikanutama->status = 'utama';
        $jadikanutama->save();

        return redirect('/')->with(['success' => 'Berhasil mengganti pola utama!']);
    }

    public function hapusAkun(Request $request)
    {
        User::find($request->id)->delete();
        return redirect('/')->with(['success' => 'Berhasil menghapus Akun!']);
    }
  
}
