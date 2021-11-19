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
        // TOTAL
            $datakel = internet_keluarga::all();

            $jmlKel     = count($datakel);
            $jmlKurang  = count($datakel->where('kesimpulan','kurang'));
            $jmlCukup   = count($datakel->where('kesimpulan','cukup'));
            $jmlLebih   = count($datakel->where('kesimpulan','lebih'));

            // Entropy
                $totEntropykel = (-$jmlKurang/$jmlKel)*log($jmlKurang/$jmlKel,2) + 
                                 (-$jmlCukup/$jmlKel)*log($jmlCukup/$jmlKel,2) + 
                                 (-$jmlLebih/$jmlKel)*log($jmlLebih/$jmlKel,2);

        // Macam" Attribut
            $macamAtribut = ['Bandwidth','Jumlah Penghuni','Banyak Gadget','Range Penggunaan'];
            $arrayNamaBagianAttribut = [
                                        ['Rendah','Sedang','Tinggi'],
                                        ['Sedikit','Normal','Banyak'],
                                        ['Sedikit','Sedang','Banyak'],
                                        ['Ringan','Sedang','Berat']
                                       ];
            $allGain = [0,0,0,0];

            // dd(count($arrayNamaBagianAttribut[0]));

        // BANDWIDTH

            // sorting kelompok bandwidth
                $rendah = $datakel->where('bandwidth','<=',20);
                $sedang = $datakel->where('bandwidth','<=',40)->where('bandwidth','>',20);
                $tinggi = $datakel->where('bandwidth','>',40);

                //jumlah total
                    $totalValueAttribute[0][0] = count($rendah);
                    $totalValueAttribute[0][1] = count($sedang);
                    $totalValueAttribute[0][2] = count($tinggi);

                //sorting berdasar target
                    $sortingTarget[0][0] = $this->sorting($rendah);
                    $sortingTarget[0][1] = $this->sorting($sedang);
                    $sortingTarget[0][2] = $this->sorting($tinggi);

                                       //[macamAtribut][macamsorting][isisorting]
                    //Entropy
                        for ($i=0; $i < count($totalValueAttribute[0]) ; $i++) {
                            $allEntropy[0][$i] = $this->entropy($sortingTarget[0][$i][0],$sortingTarget[0][$i][1],$sortingTarget[0][$i][2],$totalValueAttribute[0][$i]);
                        }

                        // dd($entropyBandwidth[0]);

                    //Gain
                    $allGain[0] = $totEntropykel - ($totalValueAttribute[0][0]/$jmlKel*$allEntropy[0][0]) + ($totalValueAttribute[0][1]/$jmlKel*$allEntropy[0][1]) + ($totalValueAttribute[0][2]/$jmlKel*$allEntropy[0][2]);

        // Jumlah Penghuni
            // Sorting jumlah penghuni
                $sedikit = $datakel->where('jumlahPenghuni','<=',3);
                $normal  = $datakel->where('jumlahPenghuni','<=',5)->where('jumlahPenghuni','>',3);
                $banyak  = $datakel->where('jumlahPenghuni','>',5);

                // jumlah Total
                    $totalValueAttribute[1][0] = count($sedikit);
                    $totalValueAttribute[1][1] = count($normal);
                    $totalValueAttribute[1][2] = count($banyak);

                // sorting berdasar target
                    $sortingTarget[1][0] = $this->sorting($sedikit);
                    $sortingTarget[1][1] = $this->sorting($normal);
                    $sortingTarget[1][2] = $this->sorting($banyak);                    

                    // entropy
                        for ($i=0; $i < count($totalValueAttribute[1]); $i++) { 
                            $allEntropy[1][$i] = $this->entropy($sortingTarget[1][$i][0],$sortingTarget[1][$i][1],$sortingTarget[1][$i][2],$totalValueAttribute[1][$i]);
                        }

                    //Gain
                        $allGain[1] = $totEntropykel - ($totalValueAttribute[1][0]/$jmlKel*$allEntropy[1][0]) + ($totalValueAttribute[1][1]/$jmlKel*$allEntropy[1][1]) + ($totalValueAttribute[1][2]/$jmlKel*$allEntropy[1][2]);

        // Banyak Gadget
            // sorting banyak gadget
                $sedikit        = $datakel->where('jumlahGadget','<=',5);
                $sedang         = $datakel->where('jumlahGadget','>',5)->where('jumlahGadget','<=',7);
                $banyak         = $datakel->where('jumlahGadget','>',7);

                // jumlah Total
                    $totalValueAttribute[2][0] = count($sedikit);
                    $totalValueAttribute[2][1] = count($sedang);
                    $totalValueAttribute[2][2] = count($banyak);

                // sorting berdasarkan target
                    $sortingTarget[2][0] = $this->sorting($sedikit);
                    $sortingTarget[2][1] = $this->sorting($sedang);
                    $sortingTarget[2][2] = $this->sorting($banyak); 
                
                // entropy
                for ($i=0; $i < count($totalValueAttribute[2]); $i++) { 
                    $allEntropy[2][$i] = $this->entropy($sortingTarget[2][$i][0],$sortingTarget[2][$i][1],$sortingTarget[2][$i][2],$totalValueAttribute[2][$i]);
                }

                //Gain
                    $allGain[2] = $totEntropykel - ($totalValueAttribute[2][0]/$jmlKel*$allEntropy[2][0]) + ($totalValueAttribute[2][1]/$jmlKel*$allEntropy[2][1]) + ($totalValueAttribute[2][2]/$jmlKel*$allEntropy[2][2]);


        // Nilai Range
            //sorting range
                // $querySortRange = internet_keluarga::
                //                 join('data_penghuni','internet_keluarga.id','=','data_penghuni.internet_keluarga_id')->
                //                 join('detail_gadget','data_penghuni.id','=','detail_gadget.data_penghuni_id')->
                //                 select('internet_keluarga.id','detail_gadget.range');

                // INI BELUM BENAR.... HARUSNYA SIMPULAN PERKELUARGA
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

                        // jumlah dengan pint
                        $pointValue[3][$keluargaKe][0] = count($ringan)*1;
                        $pointValue[3][$keluargaKe][1] = count($sedang)*2;
                        $pointValue[3][$keluargaKe][2] = count($berat)*3;

                        //total point perkeluarga
                        $totalPoint[3][$keluargaKe] = count($ringan)*1 + count($sedang)*2 + count($berat)*3;
                        $banyakData += 1;

                        // sorting kesimpulan range perkeluarga
                        if($totalPoint[3][$keluargaKe] < 10 ){
                            $kesimpulanRange[$keluargaKe] = 'ringan';

                        }elseif($totalPoint[3][$keluargaKe] <= 15) {
                            $kesimpulanRange[$keluargaKe] = 'sedang';

                        }else{
                            $kesimpulanRange[$keluargaKe] = 'berat';
                        }
                    }
                    $keluargaKe++;
                }while($banyakData < count(internet_keluarga::all()));
                
                // jumlah Total
                    $allCountArray = array_count_values($kesimpulanRange);
                    $totalValueAttribute[3][0] = $allCountArray['ringan'];
                    $totalValueAttribute[3][1] = $allCountArray['sedang'];
                    $totalValueAttribute[3][2] = $allCountArray['berat'];

                  $arrayindex = array_search('ringan', $kesimpulanRange);

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


                    // dd($kesimpulanRange);
                    // dd($indexData);

                    // Keterangan
                    // $indexData[0] = 'ringan'
                    // $indexData[1] = 'sedang'
                    // $indexData[2] = 'berat'
                    // $indexData[3] = 'sangatBerat'

                //sorting target berdasarkan index
                    //sorting ringan
                        for ($i=0; $i < count($indexData[0]); $i++) { 
                            $ringanq[$indexData[0][$i]] = $datakel->where('id',$indexData[0][$i])->first();
                        }
                    //sorting sedang
                        for ($i=0; $i < count($indexData[1]); $i++) { 
                            $sedangq[$indexData[1][$i]] = $datakel->where('id',$indexData[1][$i])->first();
                        }
                    //sorting berat
                        for ($i=0; $i < count($indexData[2]); $i++) { 
                            $beratq[$indexData[2][$i]] = $datakel->where('id',$indexData[2][$i])->first();
                        }
                    
                        
                // sorting berdasarkan target
                    $sortingTarget[3][0] = $this->sortingArray($ringanq);
                    $sortingTarget[3][1] = $this->sortingArray($sedangq);
                    $sortingTarget[3][2] = $this->sortingArray($beratq);
                    
                // entropy
                    for ($i=0; $i < count($totalValueAttribute[3]); $i++) { 
                        $allEntropy[3][$i] = $this->entropy($sortingTarget[3][$i][0],$sortingTarget[3][$i][1],$sortingTarget[3][$i][2],$totalValueAttribute[3][$i]);
                    }

                //Gain
                    $allGain[3] = $totEntropykel - ($totalValueAttribute[3][0]/$jmlKel*$allEntropy[3][0]) + ($totalValueAttribute[3][1]/$jmlKel*$allEntropy[3][1]) + ($totalValueAttribute[3][2]/$jmlKel*$allEntropy[3][2]);

        return view('content.home', compact(

        'macamAtribut','arrayNamaBagianAttribut','totalValueAttribute','sortingTarget',
        
        'datakel','jmlKel','jmlKurang','jmlCukup','jmlLebih','totEntropykel',

        'allEntropy','allGain',

        ));
    }

    public function sorting($data)
    {
        $sorting[0] = count($data->where('kesimpulan','kurang'));
        $sorting[1] = count($data->where('kesimpulan','cukup'));
        $sorting[2] = count($data->where('kesimpulan','lebih'));

        return $sorting;
    }

    public function sortingArray($data)
    {
        $sorting = array(0,0,0);
        $masuk = $dataKe = 0;
        do{
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
            $dataKe++;
        }while($masuk < count($data));

        return $sorting;
    }

    public function entropy($a,$b,$c,$total)
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
    
}
