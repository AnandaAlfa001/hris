<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\HeaderPEModel;
use App\Models\ProjectExModel;
use App\Models\LemburModel;
use App\Models\EmployeeModel;
use Barryvdh\DomPDF\Facade as PDF;

class PdfController extends Controller
{
    public function FUNC_CVLELANG($id_head) {

    	$head = HeaderPEModel::select('tb_headproj.posisi','tb_headproj.didikan','tb_headproj.didikannf','tb_headproj.bhs','tb_datapribadi.Nama as nama','tb_datapribadi.Alamat as alamat','tb_datapribadi.TempatLahir as tempatlahir','tb_datapribadi.TanggalLahir as tgl_lahir','tb_datapribadi.photo','tb_projectexp.nik','tb_projectexp.nama as nama_project','tb_projectexp.lokasi as lokasi_project','tb_projectexp.pengguna','tb_projectexp.perusahaan')
    			->leftjoin('tb_datapribadi','tb_headproj.nik','=','tb_datapribadi.NIK')
                ->leftjoin('tb_projectexp','tb_headproj.id','=','tb_projectexp.id_head')
    			->where('tb_headproj.id',$id_head)
    			->first();

        $projectex = ProjectExModel::select('tb_projectexp.nik','tb_projectexp.nama as nama_project','tb_projectexp.lokasi as lokasi_project','tb_projectexp.pengguna','tb_projectexp.perusahaan','tb_projectexp.uraian_tugas','tb_projectexp.waktu_pelaksanaan','tb_projectexp.posisi as posisipenugasan','tb_statuskar.status_kar as statuskep')
                ->leftjoin('tb_statuskar','tb_projectexp.status','=','tb_statuskar.id')
                ->where('tb_projectexp.id_head',$id_head)
                ->orderBy('tb_projectexp.waktu_pelaksanaan','DESC')
                ->get();

        // dd($head);

	    $pdf = PDF::loadView('employee.cvpdf',compact('head','projectex'))
	                ->setPaper('a4')->setOrientation('potrait');

	    return $pdf->stream();
    }

    public function FUNC_PDFLEMBUR(Request $request)
    {
        $tahun = $request['tahun'];
        $bulan = $request['bulan'];
        $nik = Session::get('nik');

        $statuskar = EmployeeModel::select('statuskar')->where('NIK',$nik)->first();
        $statuskar = $statuskar->statuskar;

        if (($tahun != NULL or $tahun != "") and ($bulan != NULL or $bulan != ""))
        {
            if(!Session::get('login') or Session::get('login')==false)
              {
                return redirect('login');
              }
            $data = LemburModel::select('tb_lembur.*',DB::raw('DATE_FORMAT(tb_lembur.TanggalMulaiLembur,"%d-%m-%Y") as tgl_lembur'),
                                DB::raw('CONCAT("Rp. ", FORMAT(tb_lembur.TotalUpah,0,"de_DE")) as total_upah')
                                )
                                ->whereRaw(DB::raw('YEAR(TanggalMulaiLembur) = "'.$tahun.'" AND MONTH(TanggalMulaiLembur) = "'.$bulan.'"
                                    AND (NIK = "'.$nik.'" OR (select old_nik from tb_datapribadi where tb_datapribadi.NIK = "'.$nik.'"))'))
                                ->where('status','S')
                                ->get();

            if (count($data) == 0) {
                return redirect('historylembur')->with('error','Data Lembur Tidak Ada');
            }

            $x=0;
            $ta_upah = 0;
            foreach ($data as $key => $datas) {
                $x+=$datas->SelisihJamLembur;
                $ta_upah+=$datas->TotalUpah;
            }
            $total = $x;
            $ta_upahmen = 'Rp. '.number_format($ta_upah,0,',','.');
            // dd($ta_upahmen);
            // return view('lembur.pdflembur')->with('data',$data)->with('total',$total)->with('ta_upahmen',$ta_upahmen)->with('statuskar',$statuskar);
            $pdf = PDF::loadView('lembur.pdflembur',compact('data','total','ta_upahmen','statuskar'))
                        ->setPaper('a4')->setOrientation('potrait');

            return $pdf->stream();
        } else {
            return redirect('historylembur')->with('error','anda belum memilih tahun atau bulan');
        }
    }
}
