<?php

namespace App\Imports;

use App\Models\internet_keluarga;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class internet_keluarga_import implements WithMultipleSheets 
{
    public function sheets(): array
    {
        return [
            'internet_keluarga' => new first_sheet_internet_keluarga_import(),  // Untuk sheet pertama benar
            'data_penghuni'     => new second_sheet_data_penghuni_import(),  // Sheet ke 2 masi salah
            'detail_gadget'     =>new last_sheet_detail_gadget_import()
        ];
    }

    // use WithConditionalSheets;

    // public function conditionalSheets(): array
    // {
    //     return [
    //         'Sheet1' => new first_sheet_internet_keluarga_import(),
    //         'Sheet2' => new second_sheet_data_penghuni_import(),
    //         'Sheet3' => new last_sheet_detail_gadget_import()
    //     ];
    // }
}
