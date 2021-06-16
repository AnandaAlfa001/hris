<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class EmployeeExport implements FromView
{
    private $param;

    public function __construct(array $param)
    {
        $this->param = $param;
    }

    public function view(): View
    {
        $employee = DB::table('tb_datapribadi AS A')
            ->select(
                'A.NIK',
                'A.sqc_nik AS SEQUENCE_NIK',
                'A.Nama as NAMA',
                'A.jk AS JENIS_KELAMIN',
                'A.Alamat AS ALAMAT',
                'A.NoHP as NOMOR_HP',
                'A.email AS EMAIL',
                DB::raw("CONCAT(B.nama_div_ext, ' - ', C.subdivisi) AS UNIT_KERJA"),
                DB::raw("CONCAT(D.pangkat, ' - ', E.jabatan) AS JABATAN"),
                DB::raw('(SELECT Nama FROM tb_datapribadi dp WHERE dp.NIK = dp.atasan1) as ATASAN_1'),
                DB::raw('(SELECT Nama FROM tb_datapribadi dp WHERE dp.NIK = dp.atasan2) as ATASAN_2'),
                DB::raw("DATE_FORMAT(A.TglKontrak, '%d-%m-%Y') as AWAL_KONTRAK"),
                DB::raw("DATE_FORMAT(A.TglKontrakEnd, '%d-%m-%Y') as AKHIR_KONTRAK"),
            )
            ->leftJoin('tbldivmaster AS B', 'A.Divisi', '=', 'B.id')
            ->leftJoin('tb_subdivisi AS C', 'A.SubDivisi', '=', 'C.id')
            ->leftJoin('tb_pangkat AS D', 'A.idpangkat', '=', 'D.id')
            ->leftJoin('tb_jabatan AS E', 'A.idjabatan', '=', 'E.id')
            ->where('A.resign', '<>', 'Y')
            ->get();

        return view('exports.employee', [
            'employee' => $employee
        ]);
    }
}
