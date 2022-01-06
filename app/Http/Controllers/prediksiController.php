<?php

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;

use App\Models\hasilDecisiontree;

class prediksiController extends Controller
{
    public function prosesCek(Request $request)
    {
        $idData = $request->idData;
        $bandwidth      = 30;
        $jumlahPenghuni = $request->jumlahPenghuni;
        
        $k = 0;
            
        // total gadget
        for ($i = 1; $i <= $jumlahPenghuni; $i++) { 
            $banyakGadget   = "banyakGadget"."$i";
            $banyakGadgetTotal[$i] = $request->$banyakGadget;
        }

        // rapihkan dan store nama penghuni dan jumlah gadget
        for($i = 1; $i <= $jumlahPenghuni; $i++ ){

            $banyakGadget   = "banyakGadget"."$i";

            $jumlahGadget[$i] = $request->$banyakGadget;

            //rapihkan data penggunaan
            for ($j = 0; $j < $jumlahGadget[$i]; $j++) { 

                $a = $i; 
                $a -= 1;
                $rangePenggunaan = "range"."$a"."$j";

                $semuaRange[$k] = $request->$rangePenggunaan;
                $k++;
            }
        
        }         

        // step-step:
        // 1. Menyimpulkan Masing masing data

            // Note: nilai kode kiri,tengah,kanan (0,1,2)

            //Sorting Bandwidth
            if($bandwidth <= 20){
                $bandwidth = 0;         //rendah
            }elseif($bandwidth <= 40){
                $bandwidth = 1;         //sedang
            }else{
                $bandwidth = 2;         //tinggi
            }

            //Sorting jumlahPenghuni
            if($jumlahPenghuni <= 3 ){
                $jumlahPenghuni = 0;    //sedikit
            }elseif($jumlahPenghuni <= 5 ){
                $jumlahPenghuni = 1;    //normal
            }else{
                $jumlahPenghuni = 2;    //banyak
            }
            
            //Sorting JumlahGadget
            $jumlahGadget = array_sum($jumlahGadget);

            if($jumlahGadget <= 5){
                $jumlahGadget = 0;      //sedikit
            }elseif($jumlahGadget <= 7){
                $jumlahGadget = 1;      //sedang
            }else{
                $jumlahGadget = 2;      //banyak
            }

            //Soring Range
            for ($i=0; $i < count($semuaRange); $i++) {
                if($semuaRange[$i] == 'ringan'){
                    $semuaRange[$i] = 1;
                }elseif($semuaRange[$i] == 'sedang'){
                    $semuaRange[$i] = 2;
                }else{
                    $semuaRange[$i] = 3;
                }
            }

            $totalRange = array_sum($semuaRange);

            if($totalRange < 10 ){
                $totalRange = 0;        //ringan
            }elseif($totalRange <= 15){
                $totalRange = 1;        //sedang
            }else{
                $totalRange = 2;        //berat
            }

            // dd($bandwidth .' '. $jumlahPenghuni .' '. $jumlahGadget .' '. $totalRange);

        //proses prediksi
        $hasilPrediksi[0] = $this->prosesPrediksi($idData,0,$jumlahPenghuni,$jumlahGadget,$totalRange);
        $hasilPrediksi[1] = $this->prosesPrediksi($idData,1,$jumlahPenghuni,$jumlahGadget,$totalRange);
        $hasilPrediksi[2] = $this->prosesPrediksi($idData,2,$jumlahPenghuni,$jumlahGadget,$totalRange);

        return redirect()->route( 'home' )->with( [ 'idData' => $idData, 'hasilPrediksi' => $hasilPrediksi ] );
    }

    public function prosesPrediksi($idData,$bandwidth,$jumlahPenghuni,$jumlahGadget,$totalRange)
    {
        // 2. lakukan prediksi
            // $DecisionData = app('App\Http\Controllers\perhitunganController')->CreateTree();

            $DecisionData = hasilDecisiontree::find($idData);                
            $akar = unserialize($DecisionData->serializeAkar);
            $arrayNamaBagianAttribut = unserialize($DecisionData->serializeArrayNamaBagianAttribut);
            
            //proses pengecekan data

            $i = 1;

            // dd($akar);
            
            //cek akar 1
            do{
                // $index = 99;
                if($i == 1){
                    $namaAkar = $akar[$i];
                }elseif($i == 2){
                    $j = $index;
                    $namaAkar = $akar[$i][$j];
                }elseif($i == 3){
                    $k = $index;
                    $namaAkar = $akar[$i][$j][$k];    //[itterasi 3][jumlah penghuni 0][bandwidth 2]
                }elseif($i == 4){
                    $l = $index;
                    $namaAkar = $akar[$i][$j][$k][$l];
                }elseif($i == 5){
                    $m = $index;
                    $namaAkar = $akar[$i][$j][$k][$l][$m];
                }
                
                if($namaAkar == 'Bandwidth' ){
                    $index = $bandwidth;
                }elseif($namaAkar == 'Jumlah Penghuni'){
                    $index = $jumlahPenghuni;
                }elseif($namaAkar == 'Banyak Gadget'){
                    $index = $jumlahGadget;
                }else{
                    $index = $totalRange;
                }
                // $index = 99;
                $i++;

            }while($namaAkar == 'Bandwidth' || $namaAkar == 'Jumlah Penghuni' || $namaAkar == 'Banyak Gadget' || $namaAkar == 'Range Penggunaan');

            $hasilPrediksi = $namaAkar;

            return $hasilPrediksi;
    }
}
