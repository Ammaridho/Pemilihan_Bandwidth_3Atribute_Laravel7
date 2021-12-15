<?php

namespace App\Imports;

use App\Models\data_penghuni;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class second_sheet_data_penghuni_import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new data_penghuni([
            'id'                    => $row['id'],
            'internet_keluarga_id'  => $row['internet_keluarga_id'],
            'nama'                  => $row['nama'],
            'banyakGadget'          => $row['banyakgadget']
        ]);
    }
}
