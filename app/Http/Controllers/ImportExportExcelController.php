<?php

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;

use App\Models\internet_keluarga;
use App\Models\hasildecisiontree;

use App\Imports\internet_keluarga_import;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportExcelController extends Controller
{
    public function import_excel(Request $request) 
	{
		set_time_limit(1000000000);
		// Empty database
		// foreach (internet_keluarga::all() as $e) { $e->delete(); }		

		internet_keluarga::query()->delete();

		// dd('tahan');

		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_Excel di dalam folder public
		$file->move('file_Excel',$nama_file);

		// import data
		Excel::import(new internet_keluarga_import, public_path('/file_Excel/'.$nama_file));

		$namaData = $request->namaData;
		$deskripsiData = $request->deskripsiData;

		// notifikasi dengan session
		Session::flash('sukses','Data Siswa Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect()->route('hasilDecisiontree')->with(['namaData' => $namaData, 'deskripsiData' => $deskripsiData]);
	}
}
