<?php

namespace App\Imports;

use App\Models\internet_keluarga;

use Maatwebsite\Excel\Concerns\ToModel;

class internet_keluarga_import implements ToModel
{

    // public function model(array $row)
    // {
    //     return new internet_keluarga([
    //         'namaKeluarga'  => $row[1],
    //         'noTelp'        => $row[2],
    //         'provider'      => $row[3],
    //         'bandwidth'     => $row[4],
    //         'biayaBulanan'  => $row[5],
    //         'jumlahPenghuni'=> $row[6],
    //         'jumlahGadget'  => $row[7],
    //         'kesimpulan'    => $row[8],
    //     ]);
    // }

    //TERAKHIR DISINI BISA TAPI HANYA SATU SHEET ================

    public function sheets(): array
    {
        return [
            new first_sheet_internet_keluarga_import(),
            new second_sheet_data_penghuni_import(),
            new last_sheet_detail_gadget_import()
        ];
    }
}
