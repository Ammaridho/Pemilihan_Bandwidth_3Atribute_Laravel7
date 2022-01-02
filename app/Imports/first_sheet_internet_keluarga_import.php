<?php

namespace App\Imports;

use App\Models\internet_keluarga;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class first_sheet_internet_keluarga_import implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new internet_keluarga([
            'id'            => $row['id'],
            'namaKeluarga'  => $row['namakeluarga'],
            'noTelp'        => $row['notelp'],
            'provider'      => $row['provider'],
            'bandwidth'     => $row['bandwidth'],
            'biayaBulanan'  => $row['biayabulanan'],
            'jumlahPenghuni'=> $row['jumlahpenghuni'],
            'jumlahGadget'  => $row['jumlahgadget'],
            'kesimpulan'    => $row['kesimpulan'],
        ]);
    }
}
