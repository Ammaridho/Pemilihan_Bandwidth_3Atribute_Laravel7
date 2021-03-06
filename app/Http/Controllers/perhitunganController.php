<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\data_penghuni;
use App\Models\detail_gadget;
use App\Models\internet_keluarga;
use App\Models\hasilDecisiontree;

use App\Models\best_provider;

use Session;

class perhitunganController extends Controller
{
// Perhitungan Decision Tree =====================================================
    public function Algoritma($dataPatokan)
    {
        //Oper Data Patokan dari selection
        if($dataPatokan == ''){

            //Pengecekan masukkan kebawah =========================================

                $dataPatokan = $this->ChooseData('','semua','');
                
                // $dataPatokan = $this->selection($dataPatokan)['psedikit'];
                // $dataPatokan = $this->selection($dataPatokan)['brendah'];
                // $dataPatokan = $this->selection($dataPatokan)['dataSedangq'];
                // $dataPatokan = $this->selection($dataPatokan)['gbanyak'];                    

                // dd($dataPatokan);

            // ====================================================================

            $jmlKel   = count($dataPatokan);
            $jml[0]   = count($dataPatokan->where('kesimpulan','kurang'));
            $jml[1]   = count($dataPatokan->where('kesimpulan','cukup'));
            $jml[2]   = count($dataPatokan->where('kesimpulan','lebih'));

            // Semua data di Entropy
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

        }else{
            // dd($dataPatokan);

            $jmlKel   = count($dataPatokan);
            $jml[0]   = count($dataPatokan->where('kesimpulan','kurang'));
            $jml[1]   = count($dataPatokan->where('kesimpulan','cukup'));
            $jml[2]   = count($dataPatokan->where('kesimpulan','lebih'));

            // Semua data di Entropy
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
        }

        return compact('dataPatokan','jmlKel','jml','totEntropykel','hasil');
    }

    public function ChooseData($dataItterasi,$macamAtribut,$arrayNamaBagianAttribut)
    {
        //disini yang diutak atik ================================================================================
        // dd($dataItterasi);
        // catatan: komentari yang datanya tidak ingin digunakan, hanya bisa menggunakan satu data.

            // dd($dataPatokan = $this->selection($dataItterasi));

            //Itterasi pertama pasti menggunakan semua data
            if($macamAtribut == 'semua'){
                $dataPatokan = $this->selection('')['dataPatokan'];
            }

            //Sorting Bandwidth
            if ($macamAtribut == 'Bandwidth') {
                if ($arrayNamaBagianAttribut == 'Rendah') {
                    $dataPatokan = $this->selection($dataItterasi)['brendah'];
                }elseif($arrayNamaBagianAttribut == 'Sedang'){
                    $dataPatokan = $this->selection($dataItterasi)['bsedang'];
                }else{
                    $dataPatokan = $this->selection($dataItterasi)['btinggi'];

                }
            }

            //Sorting jumlah penghuni
            if ($macamAtribut == 'Jumlah Penghuni') {
                if ($arrayNamaBagianAttribut == 'Sedikit') {
                    $dataPatokan = $this->selection($dataItterasi)['psedikit'];
                }elseif($arrayNamaBagianAttribut == 'Normal'){
                    $dataPatokan = $this->selection($dataItterasi)['pnormal'];
                }else{
                    $dataPatokan = $this->selection($dataItterasi)['pbanyak'];
                }
            }

            //sorting banyak gadget
            if ($macamAtribut == 'Banyak Gadget') {
                if ($arrayNamaBagianAttribut == 'Sedikit') {
                    $dataPatokan = $this->selection($dataItterasi)['gsedikit'];
                }elseif($arrayNamaBagianAttribut == 'Sedang'){
                    $dataPatokan = $this->selection($dataItterasi)['gsedang'];
                }else{
                    $dataPatokan = $this->selection($dataItterasi)['gbanyak'];
                }
            }

            // Sorting Range
            if ($macamAtribut == 'Range Penggunaan') {
                if ($arrayNamaBagianAttribut == 'Ringan') {
                    $dataPatokan = $this->selection($dataItterasi)['dataRinganq']; 
                }elseif($arrayNamaBagianAttribut == 'Sedang'){
                    $dataPatokan = $this->selection($dataItterasi)['dataSedangq']; 
                }else{
                    $dataPatokan = $this->selection($dataItterasi)['dataBeratq'];
                }
            }                    


        //======================================================================================================== 

        return $dataPatokan;
    }

    public function Selection($dataItterasi)
    {
        // Semua Sorting Sorting:
        //Itterasi pertama pasti menggunakan semua data
        
        if($dataItterasi != ''){
            $dataPatokan = $dataItterasi;
        }else{
            $dataKel = $dataPatokan = internet_keluarga::all();
        }
        
        //Sorting Bandwidth
        $brendah = $dataPatokan->where('bandwidth','<=',20);
        $bsedang = $dataPatokan->where('bandwidth','<=',40)->where('bandwidth','>',20);
        $btinggi = $dataPatokan->where('bandwidth','>',40);
        
        //Sorting jumlah penghuni
        $psedikit = $dataPatokan->where('jumlahPenghuni','<=',3);
        $pnormal  = $dataPatokan->where('jumlahPenghuni','<=',5)->where('jumlahPenghuni','>',3);
        $pbanyak  = $dataPatokan->where('jumlahPenghuni','>',5);
        
        //sorting banyak gadget
        $gsedikit = $dataPatokan->where('jumlahGadget','<=',5);
        $gsedang  = $dataPatokan->where('jumlahGadget','>',5)->where('jumlahGadget','<=',7);
        $gbanyak  = $dataPatokan->where('jumlahGadget','>',7);
        
        
        //Sorting Range
        $keluargaKe = 0;
        $banyakData = 0;
        do{
            if(internet_keluarga::find($keluargaKe)){       //Ambil semua data

                $datainternetkeluarga = internet_keluarga::
                join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                select('internet_keluarga.id','detail_gadget.range')->
                where('internet_keluarga.id',$keluargaKe)->get();

                $sedang = $datainternetkeluarga->where('range','sedang');
                $ringan = $datainternetkeluarga->where('range','ringan');
                $berat  = $datainternetkeluarga->where('range','berat');

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

        // dd($kesimpulanRange);   //simpulan perkeluarga
        
        //  search index masing masing
        $dataKe = 0;
        $dataMasuk = 0;
        $a = $b = $c = $d =0;
        do{
            if(internet_keluarga::find($dataKe)){
                $dataMasuk += 1;
                if($kesimpulanRange[$dataKe] == 'ringan'){              //mengelompokkan id keluarga berdasarkan range
                    $indexData['ringan'][$a] = $dataKe;
                    $a++;
                }elseif($kesimpulanRange[$dataKe] == 'sedang'){
                    $indexData['sedang'][$b] = $dataKe;
                    $b++;
                }else{
                    $indexData['berat'][$c] = $dataKe;
                    $c++;
                }
            }
            $dataKe++;
        }while($dataMasuk < count($kesimpulanRange));

        $dataRinganq = isset($indexData['ringan']) ? $dataPatokan->find($indexData['ringan']) : []; 
        $dataSedangq = isset($indexData['sedang']) ? $dataPatokan->find($indexData['sedang']) : []; 
        $dataBeratq  = isset($indexData['berat'])  ? $dataPatokan->find($indexData['berat'])  : []; 
        
        return compact('dataPatokan','brendah','bsedang','btinggi','psedikit','pnormal','pbanyak','gsedikit','gsedang','gbanyak','dataRinganq','dataSedangq','dataBeratq');
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


        if (count($dataPatokan) != 0) {
        
            //sorting range
            $keluargaKe = 0;
            $banyakData = 0;
            
            do{
                if($dataPatokan->find($keluargaKe) != Null){
                    $banyakData += 1;

                    // Sorting
                    $datainternetkeluarga = internet_keluarga::
                                            join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                                            join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                                            select('internet_keluarga.id','detail_gadget.range')->
                                            where('internet_keluarga.id',$keluargaKe)->get();

                    $sedang = $datainternetkeluarga->where('range','sedang');
                    $ringan = $datainternetkeluarga->where('range','ringan');
                    $berat  = $datainternetkeluarga->where('range','berat');

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
            }while($banyakData < count($dataPatokan)); //masalahnya disini, pembatas loopingnya.
            
            // jumlah Total
                $totalValueAttribute = array(0,0,0);

                $allCountArray = array_count_values($kesimpulanRange);

                if(isset($allCountArray['ringan'])){
                    $totalValueAttribute[0] = $allCountArray['ringan'];
                }else{
                    $totalValueAttribute[0] = 0;
                }

                if (isset($allCountArray['sedang'])) {
                    $totalValueAttribute[1] = $allCountArray['sedang'];
                }else{
                    $totalValueAttribute[1] = 0;
                }
                
                if (isset($allCountArray['berat'])) {
                    $totalValueAttribute[2] = $allCountArray['berat'];
                }else{
                    $totalValueAttribute[2] = 0;
                }
                

            //  search index masing masing
                $dataKe = 0;
                $dataMasuk = 0;
                $a=$b=$c=$d=0;
                do{
                    if($dataPatokan->find($dataKe)){
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
                if(isset($indexData[0])){
                    for ($i=0; $i < count($indexData[0]); $i++) { 
                        if(($dataPatokan->where('id',$indexData[0][$i])->first()) != NULL){
                        $ringanq[$indexData[0][$i]] = $dataPatokan->where('id',$indexData[0][$i])->first();
                        }
                    }
                }else{
                    $ringanq = 0;
                }

                //sorting sedang
                if(isset($indexData[1])){
                    for ($i=0; $i < count($indexData[1]); $i++) { 
                        if(($dataPatokan->where('id',$indexData[1][$i])->first()) != NULL){
                            $sedangq[$indexData[1][$i]] = $dataPatokan->where('id',$indexData[1][$i])->first();
                        }
                    }
                }else{
                    $sedangq = 0;
                }
                    
                //sorting berat
                if(isset($indexData[2])){
                    for ($i=0; $i < count($indexData[2]); $i++) { 
                        if(($dataPatokan->where('id',$indexData[2][$i])->first()) != NULL){
                        $beratq[$indexData[2][$i]] = $dataPatokan->where('id',$indexData[2][$i])->first();
                        }
                    }
                }else{
                    $beratq = 0;
                }


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
        }
        else{
            $totalValueAttribute = array(0,0,0);
            $sortingTarget       = array([0,0,0],[0,0,0],[0,0,0]);
            $entropy             = array(0,0,0);
            $gain                = 0;
        }


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

        if ($data != 0) {        
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
        }

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

        return $gain = $totEntropykel - array_sum($nilai);

    }


// Membuat Tree ===================================================================
    public function GainTertinggi($macamAtribut,$itterasi)
    {
        
        $gainTeringgi = max(array_column($itterasi['hasil'],'gain'));    //Membandingkan masing masing gain atribut

        if($gainTeringgi > 0){    //cek apakah gain tertinggi lebih dari nol

            for($i = 0 ; $i < count($itterasi['hasil']); $i++){
                if($itterasi['hasil'][$i]['gain'] == $gainTeringgi){
                    $indexAtrributGainTertinggi = $i;                       //ambil index array yang memiliki gain tertinggi
                }
            }

            $macamAtribut = $itterasi['hasil'][$indexAtrributGainTertinggi]['macamAtribut'];
            
            $arrayNamaBagianAttribut = $itterasi['hasil'][$indexAtrributGainTertinggi]['arrayNamaBagianAttribut'];

        }else{              //kalau tidak maka proses decision di stop

            $kurang  = $itterasi['jml'][0];

            $cukup  = $itterasi['jml'][1];

            $lebih  = $itterasi['jml'][2];

            $array = [$kurang,$cukup,$lebih];

            //persentasenya
            $jumlahTerbesar = max([$kurang,$cukup,$lebih]);
            $indexKe        = array_search($jumlahTerbesar,$array);
            $jumlahTotal    = array_sum($itterasi['jml']);

            if($jumlahTotal > 0 ){
                $persentase = $jumlahTerbesar / $jumlahTotal * 100;

                if($indexKe == 0){
                    $macam = 'Kurang';
                }elseif($indexKe == 1){
                    $macam = 'Cukup';
                }else{
                    $macam = 'Lebih';
                }

                $macamAtribut = $macam . ' ' . round($persentase,2) . '%';
            }else{
                $macamAtribut = 'Tidak Ada';
            }

            $arrayNamaBagianAttribut = [];

        }

        return compact('macamAtribut','arrayNamaBagianAttribut');
    }

    public function CreateTree()
    {

        //Itterasi ke 1 ==================================================================
        $itterasi[1] = $this->Algoritma(''); //array data awal (semua data)

        // mengambil nilai yang ada sebanyak
        // bandingkan gainnya
        // ambil atribute dengan gain tertinggi
        // Buat Cabang

        $akar[1] = $macamAtribut[1] = $this->GainTertinggi('',$itterasi[1])['macamAtribut'];                        //akar pertama
        
        $arrayNamaBagianAttribut[1] =  $this->GainTertinggi($macamAtribut[1],$itterasi[1])['arrayNamaBagianAttribut'];

        // Iterasi ke 2 ======================================================================================
        for ($i=0; $i < count($arrayNamaBagianAttribut[1]); $i++) { 

            //membagi data menjadi 3 sesuai atribut
            $hasilAlgoritma[2][$i] = $this->ChooseData($itterasi[1]['dataPatokan'],$macamAtribut[1],$arrayNamaBagianAttribut[1][$i]);

            //dari masing masing data yang sudah dibagi, dipecah datanya sesuai sifatnya masing"
            $itterasi[2][$i] = $this->Algoritma($hasilAlgoritma[2][$i]);
            
            $akar[2][$i] = $macamAtribut[2][$i] = $this->GainTertinggi($macamAtribut[1],$itterasi[2][$i])['macamAtribut'];
        
            $arrayNamaBagianAttribut[2][$i] =  $this->GainTertinggi($macamAtribut[1],$itterasi[2][$i])['arrayNamaBagianAttribut'];


            // Iterasi ke 3 ======================================================================================
            for ($j=0; $j < count($arrayNamaBagianAttribut[2][$i]); $j++) { 

                $hasilAlgoritma[3][$i][$j] = $this->ChooseData($itterasi[2][$i]['dataPatokan'],$macamAtribut[2][$i],$arrayNamaBagianAttribut[2][$i][$j]);
        
                $itterasi[3][$i][$j] = $this->Algoritma($hasilAlgoritma[3][$i][$j]);

                $akar[3][$i][$j] = $macamAtribut[3][$i][$j] = $this->GainTertinggi($macamAtribut[2],$itterasi[3][$i][$j])['macamAtribut'];
        
                $arrayNamaBagianAttribut[3][$i][$j] =  $this->GainTertinggi($macamAtribut[2],$itterasi[3][$i][$j])['arrayNamaBagianAttribut'];
                

                // Iterasi ke 4 ======================================================================================
                for ($k=0; $k < count($arrayNamaBagianAttribut[3][$i][$j]); $k++) { 
    
                    $hasilAlgoritma[4][$i][$j][$k] = $this->ChooseData($itterasi[3][$i][$j]['dataPatokan'],$macamAtribut[3][$i][$j],$arrayNamaBagianAttribut[3][$i][$j][$k]); 
                    
                    $itterasi[4][$i][$j][$k] = $this->Algoritma($hasilAlgoritma[4][$i][$j][$k]);
    
                    $akar[4][$i][$j][$k] = $macamAtribut[4][$i][$j][$k] = $this->GainTertinggi($macamAtribut[3],$itterasi[4][$i][$j][$k])['macamAtribut'];
            
                    $arrayNamaBagianAttribut[4][$i][$j][$k] =  $this->GainTertinggi($macamAtribut[3],$itterasi[4][$i][$j][$k])['arrayNamaBagianAttribut'];


                    // Iterasi ke 5 ======================================================================================
                    for ($l=0; $l < count($arrayNamaBagianAttribut[4][$i][$j][$k]); $l++) { 
    
                        $hasilAlgoritma[5][$i][$j][$k][$l] = $this->ChooseData($itterasi[4][$i][$j][$k]['dataPatokan'],$macamAtribut[4][$i][$j][$k],$arrayNamaBagianAttribut[4][$i][$j][$k][$l]); 
                        
                        $itterasi[5][$i][$j][$k][$l] = $this->Algoritma($hasilAlgoritma[5][$i][$j][$k][$l]);
        
                        $akar[5][$i][$j][$k][$l] = $macamAtribut[5][$i][$j][$k][$l] = $this->GainTertinggi($macamAtribut[4],$itterasi[5][$i][$j][$k][$l])['macamAtribut'];
                
                        $arrayNamaBagianAttribut[5][$i][$j][$k][$l] =  $this->GainTertinggi($macamAtribut[4],$itterasi[5][$i][$j][$k][$l])['arrayNamaBagianAttribut'];
                        
                    }
                }
            }
        }

        return compact('itterasi','akar','arrayNamaBagianAttribut');
    }

// Provider terbaik ==============================================================
    public function bestProvider($hasilDecisiontree)
    {
        // ambil semua data, di pisah berdasar kategari
            $pbrendah = internet_keluarga::where('bandwidth','<=',20)->get();
            $pbsedang = internet_keluarga::where('bandwidth','<=',40)->where('bandwidth','>',20)->get();
            $pbtinggi = internet_keluarga::where('bandwidth','>',40)->get();

        //rendah terbaik

            $providerTerbaik[0] = $this->tigaTerbaik($pbrendah);

        //sedang terbaik

            $providerTerbaik[1] = $this->tigaTerbaik($pbsedang);

        //tinggi terbaik
            
            $providerTerbaik[2] = $this->tigaTerbaik($pbtinggi);

        // dd($providerTerbaik);

        // dd('asda');

        //insert kedalam database
            for ($i=0; $i < count($providerTerbaik); $i++) { 
                for ($j=0; $j < count($providerTerbaik[$i]); $j++) { 
                    $best_provider = new best_provider;
                    $best_provider->namaprovider    = $providerTerbaik[$i][$j]['provider'];
                    
                    if ($i == 1) {
                        $best_provider->kategori = 'sedang';
                    } else if($i == 2){
                        $best_provider->kategori = 'tinggi';
                    }else {
                        $best_provider->kategori = 'rendah';
                    }
                    
                    $best_provider->bandwidth       = $providerTerbaik[$i][$j]['bandwidth'];
                    $best_provider->harga           = $providerTerbaik[$i][$j]['biayaBulanan'];
                    $best_provider->hasilDecisiontree()->associate($hasilDecisiontree);
                    $best_provider->save();
                }
            }

    }
    
    public function tigaTerbaik($dpb)
    {
        foreach ($dpb as $key => $value) {
            $a[$value['id']] = [$value['biayaBulanan'] / $value['bandwidth'], $value['id']];
        }

        asort($a); //sort value tapi index tetap sesuai sebelumnya

        $best3 = array_slice($a,0,3); //ambil 3 terbaik

        for ($i=0; $i < 3; $i++) { 
            $bestdpb[$i] = internet_keluarga::where('id',$best3[$i][1])->first();
        }

        return $bestdpb;
    }

// Save Ke Database
    public function hasilDecisiontree(Request $request)
    {
        
		$namaData = session()->get( 'namaData' );
        $deskripsiData = session()->get( 'deskripsiData' );
        
        set_time_limit(999999999999);
        $dataAlgoritma = $this->Algoritma('');
        $dataCreateTree = $this->CreateTree();

        $hasilDecisiontree = new hasilDecisiontree;

        //untuk table
        $hasilDecisiontree->jmlKel          = $dataAlgoritma['jmlKel'];
        $hasilDecisiontree->jml             = serialize($dataAlgoritma['jml']);
        $hasilDecisiontree->totEntropykel   = $dataAlgoritma['totEntropykel'];
        $hasilDecisiontree->hasil           = serialize($dataAlgoritma['hasil']);

        //untuk bagan
        $hasilDecisiontree->serializeAkar = serialize($dataCreateTree['akar']);
        $hasilDecisiontree->serializeArrayNamaBagianAttribut = serialize($dataCreateTree['arrayNamaBagianAttribut']);
        
		$hasilDecisiontree->namaHasilDecisionTree = $namaData;
        $hasilDecisiontree->deskripsi = $deskripsiData;

        $hasilDecisiontree->user_id = Session::get('user_id');;   //Tangkap id user dengan session
        
        $hasilDecisiontree->save();

        // provider terbaik
        $this->bestProvider($hasilDecisiontree);      //ambil provider terbaik buat list

        // akurasi dari pola

        $idPola = hasilDecisiontree::latest()->first()->id;

        // banyak data excel
        $banyakData = internet_keluarga::count();

        for ($i=1; $i <= $banyakData; $i++) { 
            
            // Ambil semua data
            $dataKeluarga = internet_keluarga::join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->where('internet_keluarga.id',$i);
            
            // 1. Jumlah Penghuni
            $jumlahPenghuni = $dataKeluarga->first()->jumlahPenghuni;

            // 2. Banyak Gadget Perpenghuni
                // ambil id pertama
                $jp = $dataKeluarga->first()->data_penghuni_id;

                for ($j=0; $j < $jumlahPenghuni; $j++) { 
                    // array banyak gadget perorang
                    $jumlahGadget[] = internet_keluarga::join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->where('internet_keluarga.id',$i)->where('detail_gadget.data_penghuni_id',$jp+$j)->first()->banyakGadget;
                }

            // 3. Range Penggunaan
            $rangePenggunaan = $dataKeluarga->select('detail_gadget.range')->pluck('range')->toarray();

            $this->prosesCekHasil($idPola,$i,$jumlahPenghuni,$jumlahGadget,$rangePenggunaan);

            $this->cekAkurasi($idPola);
        }
        
		// Empty database
		foreach (internet_keluarga::all() as $e) { $e->delete(); }

        return redirect('/')->with(['success' => 'berhasil Membuat Pola Prediksi']);
    }

    public function cekAkurasi($idPola)
    {
        $hasilPrediksi = internet_keluarga::all();

        $sama = 0;
        $beda = 0;

        foreach ($hasilPrediksi as $value) {
            if($value->kesimpulan == $value->hasilPrediksi){
                $sama++;
            }else{
                $beda++;
            }
        }

        $akurasi = $sama / ($sama+$beda);

        $hasilDecisiontree = hasilDecisiontree::find($idPola);
        $hasilDecisiontree->akurasi = $akurasi;
        $hasilDecisiontree->hasilSama = $sama;
        $hasilDecisiontree->hasilBeda = $beda;
        $hasilDecisiontree->save();

    }

    public function prosesCekHasil($idPola,$idData,$jumlahPenghuni,$jumlahGadget,$rangePenggunaan)
    {

        // $jumlahPenghuni = $jumlahPenghuni;                             // Jumlah Penghuni
        
        $k = 0;

        // rapihkan dan store nama penghuni dan jumlah gadget
        // for($i = 0; $i < $jumlahPenghuni; $i++ ){

        //     //rapihkan data penggunaan
        //     for ($j = 0; $j < $jumlahGadget[0]; $j++) { 

        //         $a = $i; 
        //         $a -= 1;
        //         $rangePenggunaan = "range"."$a"."$j";

        //         $semuaRange[$k] = $request->$rangePenggunaan;                  //range penggunaan masung masing gadget
        //         $k++;
        //     }
        
        // }         

        // step-step:
        // 1. Menyimpulkan Masing masing data

            // Note: nilai kode kiri,tengah,kanan (0,1,2)

            //Sorting Bandwidth
            // if($bandwidth <= 20){
            //     $bandwidth = 0;         //rendah
            // }elseif($bandwidth <= 40){
            //     $bandwidth = 1;         //sedang
            // }else{
            //     $bandwidth = 2;         //tinggi
            // }

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
            for ($i=0; $i < count($rangePenggunaan); $i++) {
                if($rangePenggunaan[$i] == 'ringan'){
                    $rangePenggunaan[$i] = 1;
                }elseif($rangePenggunaan[$i] == 'sedang'){
                    $rangePenggunaan[$i] = 2;
                }else{
                    $rangePenggunaan[$i] = 3;
                }
            }

            $totalRange = array_sum($rangePenggunaan);

            if($totalRange < 10 ){
                $totalRange = 0;        //ringan
            }elseif($totalRange <= 15){
                $totalRange = 1;        //sedang
            }else{
                $totalRange = 2;        //berat
            }

            // dd($bandwidth .' '. $jumlahPenghuni .' '. $jumlahGadget .' '. $totalRange);

        //proses prediksi
        $hasilPrediksi[0] = $this->prosesPrediksi($idPola,0,$jumlahPenghuni,$jumlahGadget,$totalRange);
        $hasilPrediksi[1] = $this->prosesPrediksi($idPola,1,$jumlahPenghuni,$jumlahGadget,$totalRange);
        $hasilPrediksi[2] = $this->prosesPrediksi($idPola,2,$jumlahPenghuni,$jumlahGadget,$totalRange);

        // ambil yang terbaik
        if (isset($hasilPrediksi)) {
            if (substr($hasilPrediksi[0],0,6) == 'Cukup ' || substr($hasilPrediksi[0],0,6) == 'Lebih ') {
                $simpulanprediksi = substr($hasilPrediksi[0],0,5);
                
            }elseif (substr($hasilPrediksi[1],0,6) == 'Cukup ' || substr($hasilPrediksi[1],0,6) == 'Lebih ') {
                $simpulanprediksi = substr($hasilPrediksi[1],0,5);

            }elseif (substr($hasilPrediksi[2],0,6) == 'Cukup ' || substr($hasilPrediksi[2],0,6) == 'Lebih ') {
                $simpulanprediksi = substr($hasilPrediksi[2],0,5);

            }elseif (substr($hasilPrediksi[2],0,6) == 'Kurang') {
                $simpulanprediksi = substr($hasilPrediksi[2],0,6);

            }

            $akurasi = internet_keluarga::find($idData);
            $akurasi->hasilPrediksi = lcfirst($simpulanprediksi);
            $akurasi->save();
        }


        // SELESAI DISINI PROSES CEK HASIL MASUK TABLE
    }

    public function prosesPrediksi($idPola,$bandwidth,$jumlahPenghuni,$jumlahGadget,$totalRange)
    {
        // 2. lakukan prediksi
            // $DecisionData = app('App\Http\Controllers\perhitunganController')->CreateTree();

            $DecisionData = hasilDecisiontree::find($idPola);                
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
