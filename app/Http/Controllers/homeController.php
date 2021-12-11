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
        $data           = hasilDecisiontree::latest()->first();

        $jmlKel         = $data->jmlKel;
        $jml            = unserialize($data->jml);
        $totEntropykel  = $data->totEntropykel;
        $hasil          = unserialize($data->hasil);
        $akar           = unserialize($data->serializeAkar);

        return view('content.home',compact('jmlKel','jml','totEntropykel','hasil','akar'));
    }
  
}
