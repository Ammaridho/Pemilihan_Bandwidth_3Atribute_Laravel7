<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\data_penghuni;
use App\Models\detail_gadget;
use App\Models\internet_keluarga;

class homeController extends Controller
{
    public function index()
    {
        return view('content.home',$this->Algoritma());
    }

    public function Algoritma()
    {
        // TOTAL
            //Itterasi pertama pasti menggunakan semua data
            $dataKel = internet_keluarga::all();

            //Sorting Bandwidth
            $brendah = where('bandwidth','<=',20);
            $bsedang = where('bandwidth','<=',40)->where('bandwidth','>',20);
            $btinggi = where('bandwidth','>',40);

            //Sorting jumlah penghuni
            $psedikit = where('jumlahPenghuni','<=',3);
            $pnormal  = where('jumlahPenghuni','<=',5)->where('jumlahPenghuni','>',3);
            $pbanyak  = where('jumlahPenghuni','>',5); //coba ini akan devided by zero karena datanya kosong

            //sorting banyak gadget
            $gsedikit  = where('jumlahGadget','<=',5);
            $gsedang   = where('jumlahGadget','>',5)->where('jumlahGadget','<=',7);
            $gbanyak   = where('jumlahGadget','>',7);

            //Sorting Range (Belum Benar)
            $keluargaKe = 0;
            $banyakData = 0;
                do{
                    if(internet_keluarga::find($keluargaKe)){
                        // Sorting
                        $ringan = internet_keluarga::
                                    join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                                    join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                                    select('internet_keluarga.id','detail_gadget.range')->
                                    where('range','ringan')->
                                    where('internet_keluarga.id',$keluargaKe)->
                                    get();
                        $sedang = internet_keluarga::
                                    find($keluargaKe)->
                                    join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                                    join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                                    select('internet_keluarga.id','detail_gadget.range')->
                                    where('range','sedang')->
                                    where('internet_keluarga.id',$keluargaKe)->
                                    get();
                        $berat  = internet_keluarga::
                                    find($keluargaKe)->
                                    join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                                    join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                                    select('internet_keluarga.id','detail_gadget.range')->
                                    where('range','berat')->
                                    where('internet_keluarga.id',$keluargaKe)->
                                    get();

                        
                        // Jumlah mentah gadget
                        $mentahValue[$keluargaKe][0] = count($ringan);
                        $mentahValue[$keluargaKe][1] = count($sedang);
                        $mentahValue[$keluargaKe][2] = count($berat);          

                        // jumlah dengan point
                        $pointValue[$keluargaKe][0] = count($ringan)*1;
                        $pointValue[$keluargaKe][1] = count($sedang)*2;
                        $pointValue[$keluargaKe][2] = count($berat)*3;

                        //total point perkeluarga
                        $totalPoint[$keluargaKe] = count($ringan)*1 + count($sedang)*2 + count($berat)*3;
                        $banyakData += 1;

                        // sorting kesimpulan range perkeluarga
                        if($totalPoint[$keluargaKe] < 10 ){
                            $kesimpulanRange[$keluargaKe] = 'ringan';

                        }elseif($totalPoint[$keluargaKe] <= 15) {
                            $kesimpulanRange[$keluargaKe] = 'sedang';

                        }else{
                            $kesimpulanRange[$keluargaKe] = 'berat';
                        }
                    }
                    $keluargaKe++;
                }while($banyakData < count(internet_keluarga::all()));

            $dataKasusBandwidthRendah = $dataKel->where('bandwidth','<=',20);

            //disini yang diutak atik ================================================================================


                $dataPatokan = $brendah->$gsedikit;


            //======================================================================================================== 

            $jmlKel   = count($dataPatokan);
            $jml[0]   = count($dataPatokan->where('kesimpulan','kurang'));
            $jml[1]   = count($dataPatokan->where('kesimpulan','cukup'));
            $jml[2]   = count($dataPatokan->where('kesimpulan','lebih'));

            // Entropy
            $totEntropykel = $this->Entropy($jml[0],$jml[1],$jml[2],$jmlKel);

            // ========================================================================================================

            // Bandwidth
                $hasil[0] = $this->Bandwidth($jmlKel,$totEntropykel,$dataPatokan);

            // Jumlah Penghuni
                $hasil[1] = $this->JumlahPenghuni($jmlKel,$totEntropykel,$dataPatokan);

            // Banyak Gadget
                $hasil[2] = $this->BanyakGadget($jmlKel,$totEntropykel,$dataPatokan);

            // Nilai Range
                $hasil[3] = $this->NilaiRange($jmlKel,$totEntropykel,$dataPatokan);

        return compact('jmlKel','jml','totEntropykel','hasil');
    }

    public function Bandwidth($jmlKel,$totEntropykel,$dataPatokan)
    {
        $macamAtribut = 'Bandwidth';

        $arrayNamaBagianAttribut = ['Rendah','Sedang','Tinggi'];

        // sorting kelompok bandwidth
        $rendah = $dataPatokan->where('bandwidth','<=',20);
        $sedang = $dataPatokan->where('bandwidth','<=',40)->where('bandwidth','>',20);
        $tinggi = $dataPatokan->where('bandwidth','>',40);

        return $this->kompilasi($macamAtribut,$arrayNamaBagianAttribut,$jmlKel,$totEntropykel,$rendah,$sedang,$tinggi);
    }

    public function JumlahPenghuni($jmlKel,$totEntropykel,$dataPatokan)
    {
        $macamAtribut = 'Jumlah Penghuni';

        $arrayNamaBagianAttribut = ['Sedikit','Normal','Banyak'];

        // Sorting jumlah penghuni
        $sedikit = $dataPatokan->where('jumlahPenghuni','<=',3);
        $normal  = $dataPatokan->where('jumlahPenghuni','<=',5)->where('jumlahPenghuni','>',3);
        $banyak  = $dataPatokan->where('jumlahPenghuni','>',5);

        return $this->kompilasi($macamAtribut,$arrayNamaBagianAttribut,$jmlKel,$totEntropykel,$sedikit,$normal,$banyak);
    }

    public function BanyakGadget($jmlKel,$totEntropykel,$dataPatokan)
    {
        $macamAtribut = 'Banyak Gadget';

        $arrayNamaBagianAttribut = ['Sedikit','Sedang','Banyak'];

        // sorting banyak gadget
        $sedikit        = $dataPatokan->where('jumlahGadget','<=',5);
        $sedang         = $dataPatokan->where('jumlahGadget','>',5)->where('jumlahGadget','<=',7);
        $banyak         = $dataPatokan->where('jumlahGadget','>',7);

        return $this->kompilasi($macamAtribut,$arrayNamaBagianAttribut,$jmlKel,$totEntropykel,$sedikit,$sedang,$banyak);

    }

    public function Kompilasi($macamAtribut,$arrayNamaBagianAttribut,$jmlKel,$totEntropykel,$a,$b,$c)
    {
        $macamAtribut = $macamAtribut;
        //jumlah total
            $totalValueAttribute[0] = count($a);
            $totalValueAttribute[1] = count($b);
            $totalValueAttribute[2] = count($c);

        //sorting berdasar target
            $sortingTarget[0] = $this->SortingBiasa($a);
            $sortingTarget[1] = $this->SortingBiasa($b);
            $sortingTarget[2] = $this->SortingBiasa($c);

                    //[macamsorting][isisorting]
        //Entropy
            for ($i=0; $i < count($totalValueAttribute) ; $i++) {
                $entropy[$i] = $this->Entropy($sortingTarget[$i][0],$sortingTarget[$i][1],$sortingTarget[$i][2],$totalValueAttribute[$i]);
            }

        //Gain
            $gain = $this->Gain($totEntropykel,$totalValueAttribute,$jmlKel,$entropy);

        return compact('macamAtribut','arrayNamaBagianAttribut','totalValueAttribute','sortingTarget','entropy','gain');
    }

    public function NilaiRange($jmlKel,$totEntropykel,$dataPatokan)
    {
        $macamAtribut = 'Range Penggunaan';

        $arrayNamaBagianAttribut = ['Ringan','Sedang','Berat'];

        //sorting range
        $keluargaKe = 0;
        $banyakData = 0;
        do{
            if(internet_keluarga::find($keluargaKe)){
                // Sorting
                $ringan = internet_keluarga::
                            join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                            join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                            select('internet_keluarga.id','detail_gadget.range')->
                            where('range','ringan')->
                            where('internet_keluarga.id',$keluargaKe)->
                            get();
                $sedang = internet_keluarga::
                            find($keluargaKe)->
                            join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                            join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                            select('internet_keluarga.id','detail_gadget.range')->
                            where('range','sedang')->
                            where('internet_keluarga.id',$keluargaKe)->
                            get();
                $berat  = internet_keluarga::
                            find($keluargaKe)->
                            join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                            join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                            select('internet_keluarga.id','detail_gadget.range')->
                            where('range','berat')->
                            where('internet_keluarga.id',$keluargaKe)->
                            get();

                
                // Jumlah mentah gadget
                $mentahValue[$keluargaKe][0] = count($ringan);
                $mentahValue[$keluargaKe][1] = count($sedang);
                $mentahValue[$keluargaKe][2] = count($berat);          

                // jumlah dengan point
                $pointValue[$keluargaKe][0] = count($ringan)*1;
                $pointValue[$keluargaKe][1] = count($sedang)*2;
                $pointValue[$keluargaKe][2] = count($berat)*3;

                //total point perkeluarga
                $totalPoint[$keluargaKe] = count($ringan)*1 + count($sedang)*2 + count($berat)*3;
                $banyakData += 1;

                // sorting kesimpulan range perkeluarga
                if($totalPoint[$keluargaKe] < 10 ){
                    $kesimpulanRange[$keluargaKe] = 'ringan';

                }elseif($totalPoint[$keluargaKe] <= 15) {
                    $kesimpulanRange[$keluargaKe] = 'sedang';

                }else{
                    $kesimpulanRange[$keluargaKe] = 'berat';
                }
            }
            $keluargaKe++;
        }while($banyakData < count(internet_keluarga::all()));
        
        // jumlah Total
            $allCountArray = array_count_values($kesimpulanRange);
            $totalValueAttribute[0] = $allCountArray['ringan'];
            $totalValueAttribute[1] = $allCountArray['sedang'];
            $totalValueAttribute[2] = $allCountArray['berat'];

        //  search index masing masing
            $dataKe = 0;
            $dataMasuk = 0;
            $a=$b=$c=$d=0;
            do{
                if(internet_keluarga::find($dataKe)){
                    $dataMasuk += 1;
                    if($kesimpulanRange[$dataKe] == 'ringan'){
                        $indexData[0][$a] = $dataKe;
                        $a++;
                    }elseif($kesimpulanRange[$dataKe] == 'sedang'){
                        $indexData[1][$b] = $dataKe;
                        $b++;
                    }else{
                        $indexData[2][$c] = $dataKe;
                        $c++;
                    }
                }
                $dataKe++;
            }while($dataMasuk < count($kesimpulanRange));

            // Keterangan
            // $indexData[0] = 'ringan'
            // $indexData[1] = 'sedang'
            // $indexData[2] = 'berat'

        //sorting target berdasarkan index
            $ringanq = [];
            $sedangq = [];
            $beratq = [];

            //sorting ringan
                for ($i=0; $i < count($indexData[0]); $i++) { 
                    if(($dataPatokan->where('id',$indexData[0][$i])->first()) != NULL){
                    $ringanq[$indexData[0][$i]] = $dataPatokan->where('id',$indexData[0][$i])->first();
                    }
                }
            //sorting sedang
                for ($i=0; $i < count($indexData[1]); $i++) { 
                    if(($dataPatokan->where('id',$indexData[1][$i])->first()) != NULL){
                        $sedangq[$indexData[1][$i]] = $dataPatokan->where('id',$indexData[1][$i])->first();
                    }
                }
            //sorting berat
                for ($i=0; $i < count($indexData[2]); $i++) { 
                    if(($dataPatokan->where('id',$indexData[2][$i])->first()) != NULL){
                    $beratq[$indexData[2][$i]] = $dataPatokan->where('id',$indexData[2][$i])->first();
                    }
                }
            
                // dd($beratq);
        // sorting berdasarkan target
            $sortingTarget[0] = $this->SortingArray($ringanq);
            $sortingTarget[1] = $this->SortingArray($sedangq);
            $sortingTarget[2] = $this->SortingArray($beratq);

        // entropy
            for ($i=0; $i < count($totalValueAttribute); $i++) { 
                $entropy[$i] = $this->Entropy($sortingTarget[$i][0],$sortingTarget[$i][1],$sortingTarget[$i][2],$totalValueAttribute[$i]);
            }
            

        //Gain
            $gain = $this->Gain($totEntropykel,$totalValueAttribute,$jmlKel,$entropy);

        return compact('macamAtribut','arrayNamaBagianAttribut','totalValueAttribute','sortingTarget','entropy','gain');
    }
    
    public function SortingBiasa($data)
    {
        $sorting[0] = count($data->where('kesimpulan','kurang'));
        $sorting[1] = count($data->where('kesimpulan','cukup'));
        $sorting[2] = count($data->where('kesimpulan','lebih'));

        return $sorting;
    }

    public function SortingArray($data)
    {
        $sorting = array(0,0,0);
        $masuk = $dataKe = 0;
        
        do{
            if($data != []){
                if((array_values($data)[$dataKe]['id']) != NULL ){
                    if($isi = internet_keluarga::find(array_values($data)[$dataKe]['id'])){
                        $masuk++;
                        if($isi['kesimpulan'] == 'kurang'){
                            $sorting[0] +=  1;
                        }elseif($isi['kesimpulan'] == 'cukup'){
                            $sorting[1] +=  1;
                        }elseif($isi['kesimpulan'] == 'lebih'){
                            $sorting[2] +=  1;
                        }
                    }
                }
            }
            $dataKe++;
        }while($masuk < count($data));

        return $sorting;
    }

    public function Entropy($a,$b,$c,$total)
    {        
        if($total == 0){
            return 0;
        }

        $entropy = (-$a/$total)*log($a/$total,2) + (-$b/$total)*log($b/$total,2) + (-$c/$total)*log($c/$total,2);

        if(is_nan($entropy)){
            return 0;
        }
        return $entropy;
    }

    public function Gain($totEntropykel,$totalValueAttribute,$jmlKel,$entropy)
    {
        for ($i=0; $i < 3; $i++) { 
            if ($jmlKel > 0) {
                $nilai[$i] = $totalValueAttribute[$i]/$jmlKel*$entropy[$i];
            }else{
                $nilai[$i] = 0;
            }
        }

        $gain = $totEntropykel - array_sum($nilai);
    }
}
