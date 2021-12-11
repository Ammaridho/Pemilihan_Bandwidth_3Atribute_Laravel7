<?php

namespace App\Imports;

use App\Models\internet_keluarga;
use Maatwebsite\Excel\Concerns\ToModel;

class first_sheet_internet_keluarga_import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new internet_keluarga([
            'namaKeluarga'  => $row[1],
            'noTelp'        => $row[2],
            'provider'      => $row[3],
            'bandwidth'     => $row[4],
            'biayaBulanan'  => $row[5],
            'jumlahPenghuni'=> $row[6],
            'jumlahGadget'  => $row[7],
            'kesimpulan'    => $row[8],
        ]);
    }
}
