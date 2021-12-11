<?php

namespace App\Imports;

use App\Models\detail_gadget;
use Maatwebsite\Excel\Concerns\ToModel;

class last_sheet_detail_gadget_import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new detail_gadget([
            'data_penghuni_id'  => $row[1],
            'namaGadget'        => $row[2],
            'range'             => $row[3]
        ]);
    }
}
