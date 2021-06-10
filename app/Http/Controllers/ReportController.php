<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\EmployeeModel;
use App\Models\KaryawanModel;
use App\Models\CutiModel;
use App\Models\TrainingModel;
use App\Models\StatusKarModel;
use App\Models\LokkerModel;
use App\Models\DivisiModel;
use App\Models\SubDivisiModel;
use App\Models\KesehatanModel;
use App\Models\LemburModel;
use Excel;
use Crypt;

class ReportController extends Controller
{

    public function FUNC_REPORTEMPLOYEE() {

        $atasan1 = EmployeeModel::select('nik','nama',
                              DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
                              )
                              ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                              ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7)'))
                              ->orderby('tb_datapribadi.idpangkat','ASC')
                              ->get();

        $atasan2 = EmployeeModel::select('nik','nama',
                              DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
                              )
                              ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                              ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5)'))
                              ->orderby('tb_datapribadi.idpangkat','ASC')
                              ->get();
        $statuskar = StatusKarModel::all();
        $lokasikerja = LokkerModel::all();


        return view('report/reportemployee')->with('atasan1',$atasan1)->with('atasan2',$atasan2)->with('statuskar',$statuskar)->with('lokasikerja',$lokasikerja);

    }

      public function FUNC_FILTEREMPLOYEE(Request $request) {

      // dd($request->all());

      // $status = $request->get('statuskaryawan');
      // $lokasi = $request->get('lokasiker');
      // $tgl_kontrak = $request->get('tgl_kontrak');
      // $tgl_akhir = $request->get('tgl_akhir');
      // $atasan1 = $request->get('atasan1');
      // $atasan2 = $request->get('atasan2');

      $status = $request['statuskaryawan'];
      $lokasi = $request['lokasiker'];
      $tgl_kontrak = $request['tgl_kontrak'];
      $tgl_akhir = $request['tgl_akhir'];
      $atasan1input = $request['atasan1'];
      $atasan2input = $request['atasan2'];


      // $snull = "";
      // $lnull = "";
      // $tanull = "";
      // $tknull = "";
      // $a1null = "";
      // $a2null = "";
      $snull = "";
      $lnull = "";
      $tanull = "";
      $tknull = "";
      $a1null = "";
      $a2null = "";
      if($status == null or $status == ""){
        $status = "";
        $snull = "or statuskar IS NULL";
        // dd($snull);
      }
      if($lokasi == NULL or $lokasi == ""){
        $lokasi = "";
        $lnull = "or LokasiKer IS NULL";
      }
      if($tgl_kontrak == NULL or $tgl_kontrak == ""){
        $tgl_kontrak = "";
        $tknull = "or TglKontrak IS NULL";
      }
      if($tgl_akhir == NULL or $tgl_akhir == ""){
        $tgl_akhir = "";
        $tanull = "or TglKontrakEnd IS NULL";
      }
      if($atasan2input == NULL or $atasan2input == ""){
        $atasan2input = "";
        $a2null = "or atasan2 IS NULL";
      }
      if($atasan1input == NULL or $atasan1input == ""){
        $atasan1input = "";
        $a1null = "or atasan1 IS NULL";
      }
      // DB::enableQueryLog();
      $Result = KaryawanModel::select('sqc_nik','NIK','Nama','Alamat','NoHp','email',
                                    DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
                                    DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
                                    DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
                                    DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol'),'tb_statuskar.status_kar as Status'
                                )
                            ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                            ->leftjoin('tb_subdivisi','tb_datapribadi.SubDivisi','=','tb_subdivisi.id')
                            ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                            ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                            ->leftjoin('tb_statuskar','tb_datapribadi.statuskar','=','tb_statuskar.id')
                            ->whereRaw(DB::raw('(statuskar LIKE "%'.$status.'%" '.$snull.') and (LokasiKer LIKE "%'.$lokasi.'%" '.$lnull.') and (atasan1 LIKE "%'.$atasan1input.'%" '.$a1null.') and (atasan2 LIKE "%'.$atasan2input.'%" '.$a2null.') and (TglKontrak LIKE "%'.$tgl_kontrak.'%" '.$tknull.') and (TglKontrakEnd LIKE "%'.$tgl_akhir.'%" '.$tanull.')'))
                            ->where('tb_datapribadi.resign','<>','Y')
                            // ->where('statuskar','LIKE','%' . $status . '%')
                            // ->where('LokasiKer','LIKE','%' . $lokasi  . '%')
                            // ->where('atasan1','LIKE','%' . $atasan1 . '%')
                            // ->where('atasan2','LIKE','%' . $atasan2 . '%')
                            // ->where('TglKontrak','LIKE','%' . $tgl_kontrak . '%')
                            // ->where('TglKontrakEnd','LIKE','%' . $tgl_akhir . '%')
                            ->orderby('tbldivmaster.nama_div_ext')
                            ->orderby('tb_subdivisi.subdivisi')
                            ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
                            // ->toSql();
                            ->get();
                            // dd(DB::getQueryLog());
                            //dd($Result);


      $atasan1 = EmployeeModel::select('nik','nama',
                              DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
                              )
                              ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                              ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7)'))
                              ->orderby('tb_datapribadi.idpangkat','ASC')
                              ->get();

        $atasan2 = EmployeeModel::select('nik','nama',
                              DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
                              )
                              ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                              ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5)'))
                              ->orderby('tb_datapribadi.idpangkat','ASC')
                              ->get();
        $statuskar = StatusKarModel::all();
        $lokasikerja = LokkerModel::all();

    return view('report/resultemployee')
            ->with('Result',$Result)
            ->with('atasan1',$atasan1)
            ->with('atasan2',$atasan2)
            ->with('statuskar',$statuskar)
            ->with('lokasikerja',$lokasikerja)
            ->with('status',$status)
            ->with('lokasi',$lokasi)
            ->with('atasan1input',$atasan1input)
            ->with('atasan2input',$atasan2input)
            ->with('tgl_kontrak',$tgl_kontrak)
            ->with('tgl_akhir',$tgl_akhir);
            // ,compact('Result','atasan1','atasan2','statuskar','lokasikerja','status','lokasi','atasan1input','atasan2input','tgl_kontrak','tgl_akhir'));

    }

    public function FUNC_GETEXPORT(Request $request) {

      $status = $request['statuskaryawans'];
      $lokasi = $request['lokasikers'];
      $tgl_kontrak = $request['tgl_kontraks'];
      $tgl_akhir = $request['tgl_akhirs'];
      $atasan1input = $request['atasan1s'];
      $atasan2input = $request['atasan2s'];

      // dd($request->all());

      $snull = "";
      $lnull = "";
      $tanull = "";
      $tknull = "";
      $a1null = "";
      $a2null = "";
      if($status == null or $status == ""){
        $status = "";
        $snull = "or statuskar IS NULL";
        // dd($snull);
      }
      if($lokasi == NULL or $lokasi == ""){
        $lokasi = "";
        $lnull = "or LokasiKer IS NULL";
      }
      if($tgl_kontrak == NULL or $tgl_kontrak == ""){
        $tgl_kontrak = "";
        $tknull = "or TglKontrak IS NULL";
      }
      if($tgl_akhir == NULL or $tgl_akhir == ""){
        $tgl_akhir = "";
        $tanull = "or TglKontrakEnd IS NULL";
      }
      if($atasan2input == NULL or $atasan2input == ""){
        $atasan2input = "";
        $a2null = "or atasan2 IS NULL";
      }
      if($atasan1input == NULL or $atasan1input == ""){
        $atasan1input = "";
        $a1null = "or atasan1 IS NULL";
      }

     $Result = KaryawanModel::select('sqc_nik','NIK','Nama','Alamat','NoHp','email',
                                    DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
                                    DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
                                    DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
                                    DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol'), 'tb_statuskar.status_kar as Status',
                                    DB::raw('(CASE WHEN tb_datapribadi.jk = "L" THEN "Laki-Laki" WHEN tb_datapribadi.jk = "P" THEN "Perempuan" ELSE "" END) as jk'),
                                    DB::raw('DATE_FORMAT(tb_datapribadi.TglKontrak, "%d-%m-%Y") as TglKontrak'),
                                    DB::raw('DATE_FORMAT(tb_datapribadi.TglKontrakEnd, "%d-%m-%Y") as TglKontrakEnd') ,
                                    DB::raw('(SELECT Nama FROM tb_datapribadi dp WHERE dp.NIK = tb_datapribadi.atasan1) as Atasan1'),
                                    DB::raw('(SELECT Nama FROM tb_datapribadi dp WHERE dp.NIK = tb_datapribadi.atasan2) as Atasan2'),
                                    'tb_lokasikerja.lokasi as LokasiKerja','Alamat_KTP','NoTelepon','TempatLahir',
                                    DB::raw('DATE_FORMAT(tb_datapribadi.TanggalLahir, "%d-%m-%Y") as TanggalLahir'),'tb_agama.nama_agama','NamaPasangan',
                                    DB::raw('DATE_FORMAT(tb_datapribadi.TanggalLahirPasangan, "%d-%m-%Y") as TanggalLahirPasangan'),'TmplLahirPasangan','jobpasangan',
                                    'pndkpasangan',DB::raw('(SELECT nama_agama FROM tb_agama ag WHERE ag.idagama = tb_datapribadi.agamapasangan) as agamapasangan'),
                                    'emailreg','ktp_sim','gol_darah','nama_bapak',
                                    DB::raw('(SELECT nama_agama FROM tb_agama ag WHERE ag.idagama = tb_datapribadi.agama_bapak) as agama_bapak'),
                                    'tmplhr_bapak', DB::raw('DATE_FORMAT(tb_datapribadi.tgllhr_bapak, "%d-%m-%Y") as tgllhr_bapak'),'nama_ibu',
                                    DB::raw('(SELECT nama_agama FROM tb_agama ag WHERE ag.idagama = tb_datapribadi.agama_ibu) as agama_ibu'),
                                    'tmplhr_ibu', DB::raw('DATE_FORMAT(tb_datapribadi.tgllhr_ibu, "%d-%m-%Y") as tgllhr_ibu'),'npwp','jamsostek','bpjs','norek',
                                    DB::raw('DATE_FORMAT(tb_datapribadi.tgl_sk_gol, "%d-%m-%Y") as TanggalGolongan'),
                                    DB::raw('(SELECT j.jenjang
                                            FROM tb_pendidikan pdkk
                                            LEFT JOIN tb_jenjang j ON j.id_j = pdkk.Jenjang
                                            WHERE pdkk.NIK = tb_datapribadi.NIK OR pdkk.NIK = tb_datapribadi.old_nik
                                            ORDER BY pdkk.Jenjang DESC LIMIT 1
                                            ) AS pendidikan_terakhir')
                                )
                            ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                            ->leftjoin('tb_subdivisi','tb_datapribadi.SubDivisi','=','tb_subdivisi.id')
                            ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                            ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                            ->leftjoin('tb_statuskar','tb_datapribadi.statuskar','=','tb_statuskar.id')
                            ->leftjoin('tb_lokasikerja','tb_datapribadi.LokasiKer','=','tb_lokasikerja.id')
                            ->leftjoin('tb_agama','tb_datapribadi.Agama','=','tb_agama.idagama')
                            ->whereRaw(DB::raw('(statuskar LIKE "%'.$status.'%" '.$snull.') and (LokasiKer LIKE "%'.$lokasi.'%" '.$lnull.') and (atasan1 LIKE "%'.$atasan1input.'%" '.$a1null.') and (atasan2 LIKE "%'.$atasan2input.'%" '.$a2null.') and (TglKontrak LIKE "%'.$tgl_kontrak.'%" '.$tknull.') and (TglKontrakEnd LIKE "%'.$tgl_akhir.'%" '.$tanull.')'))
                            ->where('tb_datapribadi.resign','<>','Y')
                            ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
                            ->orderby('tbldivmaster.nama_div_ext')
                            ->orderby('tb_subdivisi.subdivisi')

                            // ->toSql();
                            ->get();

        $atasan1 = EmployeeModel::select('nik','nama',
                              DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
                              )
                              ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                              ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7)'))
                              ->orderby('tb_datapribadi.idpangkat','ASC')
                              ->get();

        $atasan2 = EmployeeModel::select('nik','nama',
                              DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
                              )
                              ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                              ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5)'))
                              ->orderby('tb_datapribadi.idpangkat','ASC')
                              ->get();
        $statuskar = StatusKarModel::all();
        $lokasikerja = LokkerModel::all();

        $divisi = DivisiModel::where('type',NULL)->get();
        $subdivisi = SubDivisiModel::where('type',NULL)->get();
        Excel::create('Export Data', function($excel) use($Result, $atasan1, $atasan2, $statuskar, $lokasikerja) {
          $excel->sheet('Sheet 1', function($sheet) use($Result, $atasan1, $atasan2, $statuskar, $lokasikerja) {
            $sheet->loadView('report/resultemployeeexcel')
              ->with("Result",$Result)
              ->with("atasan1",$atasan1)
              ->with("atasan2",$atasan2)
              ->with("statuskar",$statuskar)
              ->with("lokasikerja",$lokasikerja)
              ->with("Status",$statuskar);
          });
        })->export('xls');

        // return view('report/resultemployeeexcel')
        //     ->with("Result",$Result)
        //     ->with("atasan1",$atasan1)
        //     ->with("atasan2",$atasan2)
        //     ->with("statuskar",$statuskar)
        //     ->with("lokasikerja",$lokasikerja)
        //     ->with("divisi",$divisi)
        //     ->with("Status",$statuskar);

    }



    // CUTIIIII

    public function FUNC_REPORTCUTI() {

      $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
      $tahundropdowns = DB::select($tahundropdown);

      return view('report/reportcuti')->with('tahundropdowns',$tahundropdowns);

    }

    public function FUNC_FILTERCUTI(Request $request) {

      $tahun = $request['tahun'];
      $bulan = $request['bulan'];

      if (($tahun != NULL or $tahun != "") and ($bulan != NULL or $bulan != "")) {
          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->whereRaw(DB::raw('YEAR(TanggalMulaiCuti) = "'.$tahun.'" AND MONTH(TanggalMulaiCuti) = "'.$bulan.'"'))
            // ->groupBy('tb_cuti.nik')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

      } elseif(($tahun != NULL or $tahun != "") and ($bulan == NULL or $bulan == "")) {

          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->whereRaw(DB::raw('YEAR(TanggalMulaiCuti) = "'.$tahun.'"'))
            // ->groupBy('tb_cuti.nik')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

      } elseif(($tahun == NULL or $tahun == "") and ($bulan != NULL or $bulan != "")) {

          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->whereRaw(DB::raw('MONTH(TanggalMulaiCuti) = "'.$bulan.'"'))
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

      } elseif(($tahun == NULL or $tahun == "") and ($bulan == NULL or $bulan == "")) {

          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();
      }


    $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahundropdowns = DB::select($tahundropdown);

    return view('report/resultcuti')
            ->with('Result',$Result)
            ->with('tahundropdowns',$tahundropdowns)
            ->with('tahun',$tahun)
            ->with('bulan',$bulan);

    }

    public function FUNC_GETEXPORTCUTI(Request $request) {

      $tahuninput = $request['tahuninput'];
      $bulaninput = $request['bulaninput'];

      // dd($bulaninput);

      if (($tahuninput != NULL or $tahuninput != "") and ($bulaninput != NULL or $bulaninput != "")) {
          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->whereRaw(DB::raw('YEAR(TanggalMulaiCuti) = "'.$tahuninput.'" AND MONTH(TanggalMulaiCuti) = "'.$bulaninput.'"'))
            // ->groupBy('tb_cuti.nik')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

      } elseif(($tahuninput != NULL or $tahuninput != "") and ($bulaninput == NULL or $bulaninput == "")) {

          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->whereRaw(DB::raw('YEAR(TanggalMulaiCuti) = "'.$tahuninput.'"'))
            // ->groupBy('tb_cuti.nik')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

      } elseif(($tahuninput == NULL or $tahuninput == "") and ($bulaninput != NULL or $bulaninput != "")) {

          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->whereRaw(DB::raw('MONTH(TanggalMulaiCuti) = "'.$bulaninput.'"'))
            // ->groupBy('tb_cuti.nik')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

      } elseif(($tahuninput == NULL or $tahuninput == "") and ($bulaninput == NULL or $bulaninput == "")) {

          $Result = CutiModel::select('tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            // ->groupBy('tb_cuti.nik')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();
      }



      // dd($Result);
    $tahun = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahuns = DB::select($tahun);

      Excel::create('Export Data Cuti', function($excel) use($Result, $tahuns, $tahuninput, $bulaninput) {
        $excel->sheet('Sheet 1', function($sheet) use($Result, $tahuns, $tahuninput, $bulaninput) {
          $sheet->loadView('report/resultcutiexcel')
            ->with("Result",$Result)
            ->with("tahuns",$tahuns)
            ->with("tahuninput",$tahuninput)
            ->with("bulaninput",$bulaninput);
        });
      })->export('xls');

    // }
    // return view('report/resultcutiexcel')
    // ->with("Result",$Result)
    // ->with("tahuns",$tahuns)
    // ->with("tahuninput",$tahuninput)
    // ->with("bulaninput",$bulaninput);

  }

      // TRAINING

    public function FUNC_REPORTTRAINING() {

      $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
      $tahundropdowns = DB::select($tahundropdown);

      return view('report/reporttraining')->with('tahundropdowns',$tahundropdowns);

    }

    public function FUNC_FILTERTRAINING(Request $request) {

      $tahun = $request['tahun'];
      $bulan = $request['bulan'];

      if (($tahun != NULL or $tahun != "") and ($bulan != NULL or $bulan != "")) {
          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
             ->whereRaw(DB::raw('YEAR(tgl_mulai) = "'.$tahun.'" AND MONTH(tgl_mulai) = "'.$bulan.'"'))
            ->orderby('tb_training.ID','DESC')
            ->get();

      } elseif(($tahun != NULL or $tahun != "") and ($bulan == NULL or $bulan == "")) {

          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
             ->whereRaw(DB::raw('YEAR(tgl_mulai) = "'.$tahun.'"'))
            ->orderby('tb_training.ID','DESC')
            ->get();

      } elseif(($tahun == NULL or $tahun == "") and ($bulan != NULL or $bulan != "")) {

          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
             ->whereRaw(DB::raw('MONTH(tgl_mulai) = "'.$bulan.'"'))
            ->orderby('tb_training.ID','DESC')
            ->get();

      } elseif(($tahun == NULL or $tahun == "") and ($bulan == NULL or $bulan == "")) {

          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
            ->orderby('tb_training.ID','DESC')
            ->get();
      }




    $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahundropdowns = DB::select($tahundropdown);

    return view('report/resulttraining')
            ->with('Result',$Result)
            ->with('tahundropdowns',$tahundropdowns)
            ->with('tahun',$tahun)
            ->with('bulan',$bulan);

    }

    public function FUNC_GETEXPORTTRAINING(Request $request) {

      $tahuninput = $request['tahuninput'];
      $bulaninput = $request['bulaninput'];

      // dd($bulaninput);

        if (($tahuninput != NULL or $tahuninput != "") and ($bulaninput != NULL or $bulaninput != "")) {
          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
             ->whereRaw(DB::raw('YEAR(tgl_mulai) = "'.$tahuninput.'" AND MONTH(tgl_mulai) = "'.$bulaninput.'"'))
            ->orderby('tb_training.ID','DESC')
            ->get();

      } elseif(($tahuninput != NULL or $tahuninput != "") and ($bulaninput == NULL or $bulaninput == "")) {

          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
             ->whereRaw(DB::raw('YEAR(tgl_mulai) = "'.$tahuninput.'"'))
            ->orderby('tb_training.ID','DESC')
            ->get();

      } elseif(($tahuninput == NULL or $tahuninput == "") and ($bulaninput != NULL or $bulaninput != "")) {

          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
             ->whereRaw(DB::raw('MONTH(tgl_mulai) = "'.$bulaninput.'"'))
            ->orderby('tb_training.ID','DESC')
            ->get();

      } elseif(($tahuninput == NULL or $tahuninput == "") and ($bulaninput == NULL or $bulaninput == "")) {

          $Result = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan')
            ->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
            ->orderby('tb_training.ID','DESC')
            ->get();
      }

      // dd($Result);


    $tahun = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahuns = DB::select($tahun);

      Excel::create('Export Data Cuti', function($excel) use($Result, $tahuns, $tahuninput, $bulaninput) {
        $excel->sheet('Sheet 1', function($sheet) use($Result, $tahuns, $tahuninput, $bulaninput) {
          $sheet->loadView('report/resulttrainingexcel')
            ->with("Result",$Result)
            ->with("tahuns",$tahuns)
            ->with("tahuninput",$tahuninput)
            ->with("bulaninput",$bulaninput);
        });
      })->export('xls');

    // }
    // return view('report/resultcutiexcel')
    // ->with("Result",$Result)
    // ->with("tahuns",$tahuns)
    // ->with("tahuninput",$tahuninput)
    // ->with("bulaninput",$bulaninput);

  }

      // KESEHATAN

    public function FUNC_REPORTKESEHATAN() {

      $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
      $tahundropdowns = DB::select($tahundropdown);

      return view('report/reportkesehatan')->with('tahundropdowns',$tahundropdowns);

    }

    public function FUNC_FILTERKESEHATAN(Request $request) {

      $tahun = $request['tahun'];
      $bulan = $request['bulan'];



      if (($tahun != NULL or $tahun != "") and ($bulan != NULL or $bulan != "")) {
          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              ->whereRaw(DB::raw('YEAR(tglklaim) = "'.$tahun.'" AND MONTH(tglklaim) = "'.$bulan.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();

      } elseif(($tahun != NULL or $tahun != "") and ($bulan == NULL or $bulan == "")) {

          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              ->whereRaw(DB::raw('YEAR(tglklaim) = "'.$tahun.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();

      } elseif(($tahun == NULL or $tahun == "") and ($bulan != NULL or $bulan != "")) {

          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              ->whereRaw(DB::raw('MONTH(tglklaim) = "'.$bulan.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();

      } elseif(($tahun == NULL or $tahun == "") and ($bulan == NULL or $bulan == "")) {

          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              // ->whereRaw(DB::raw('YEAR(tglklaim) = "'.$tahun.'" AND MONTH(tglklaim) = "'.$bulan.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();
      }




    $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahundropdowns = DB::select($tahundropdown);

    return view('report/resultkesehatan')
            ->with('Result',$Result)
            ->with('tahundropdowns',$tahundropdowns)
            ->with('tahun',$tahun)
            ->with('bulan',$bulan);

    }

    public function FUNC_GETEXPORTKESEHATAN(Request $request) {

      $tahuninput = $request['tahuninput'];
      $bulaninput = $request['bulaninput'];

      // dd($bulaninput);

        if (($tahuninput != NULL or $tahuninput != "") and ($bulaninput != NULL or $bulaninput != "")) {
          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              ->whereRaw(DB::raw('YEAR(tglklaim) = "'.$tahuninput.'" AND MONTH(tglklaim) = "'.$bulaninput.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();

      } elseif(($tahuninput != NULL or $tahuninput != "") and ($bulaninput == NULL or $bulaninput == "")) {

          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              ->whereRaw(DB::raw('YEAR(tglklaim) = "'.$tahuninput.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();

      } elseif(($tahuninput == NULL or $tahuninput == "") and ($bulaninput != NULL or $bulaninput != "")) {

          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              ->whereRaw(DB::raw('MONTH(tglklaim) = "'.$bulaninput.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();

      } elseif(($tahuninput == NULL or $tahuninput == "") and ($bulaninput == NULL or $bulaninput == "")) {

          $Result = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv',
              DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK)
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE NIK = tb_kesehatan.NIK and AnakKe = 3)
                END as nama')
              )
              ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
              ->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
              // ->whereRaw(DB::raw('YEAR(tglklaim) = "'.$tahun.'" AND MONTH(tglklaim) = "'.$bulan.'"'))
              ->orderBy('tglklaim','DESC')
              ->get();
      }

      // dd($Result);


    $tahun = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahuns = DB::select($tahun);

      Excel::create('Export Data Kesehatan', function($excel) use($Result, $tahuns, $tahuninput, $bulaninput) {
        $excel->sheet('Sheet 1', function($sheet) use($Result, $tahuns, $tahuninput, $bulaninput) {
          $sheet->loadView('report/resultkesehatanexcel')
            ->with("Result",$Result)
            ->with("tahuns",$tahuns)
            ->with("tahuninput",$tahuninput)
            ->with("bulaninput",$bulaninput);
        });
      })->export('xls');

    // }
    // return view('report/resultcutiexcel')
    // ->with("Result",$Result)
    // ->with("tahuns",$tahuns)
    // ->with("tahuninput",$tahuninput)
    // ->with("bulaninput",$bulaninput);

  }

  public function FUNC_GETEXPORTLEMBUR(Request $request)
  {
        $tahun = $request['tahun'];
        $bulan = $request['bulan'];
        if (($tahun != NULL or $tahun != "") and ($bulan != NULL or $bulan != ""))
        {
            if(!Session::get('login') or Session::get('login')==false)
              {
                return redirect('login');
              }

            if (Session::get('admin') == 1) {
              $data = LemburModel::select('tb_lembur.*','tb_datapribadi.Nama','tb_jabatan.jabatan')
                                  ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                                  ->leftjoin('tb_jabatan','tb_jabatan.id','=','tb_datapribadi.idjabatan')
                                  ->whereRaw(DB::raw('YEAR(TanggalMulaiLembur) = "'.$tahun.'" AND MONTH(TanggalMulaiLembur) = "'.$bulan.'"'))
                                  ->where('tb_lembur.status','S')
                                  ->get();
            }else{
              $data = LemburModel::select('tb_lembur.*','tb_datapribadi.Nama','tb_jabatan.jabatan')
                                  ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                                  ->leftjoin('tb_jabatan','tb_jabatan.id','=','tb_datapribadi.idjabatan')
                                  ->whereRaw(DB::raw('YEAR(TanggalMulaiLembur) = "'.$tahun.'" AND MONTH(TanggalMulaiLembur) = "'.$bulan.'"'))
                                  ->where('tb_lembur.status','S')
                                  ->whereRaw('tb_datapribadi.atasan1 = "'.Session::get('nik').'" or tb_datapribadi.atasan2 = "'.Session::get('nik').'"')
                                  ->get();
            }

             Excel::create('Export Data Lembur', function($excel) use($data, $tahun, $bulan) {
              $excel->sheet('Sheet 1', function($sheet) use($data, $tahun, $bulan) {
                $sheet->loadView('report/resultlemburexcel')
                  ->with("data",$data)
                  ->with("tahun",$tahun)
                  ->with("bulan",$bulan);
              });
            })->export('xls');

        } else {
            return redirect('reportlembur')->with('error','anda belum memilih tahun atau bulan');
        }
  }

  public function FUNC_REPORTLEMBUR()
  {
        $nik = Session::get('nik');
        $tahun = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
        $tahuns = DB::select($tahun);

        return view('report.reportlembur')->with('tahun',$tahuns);
  }

  public function surveyReport($id='')
  {
    $data = $id;

    Excel::create('Survey_Report', function($excel) use($data) {
      $excel->sheet('Survey Report', function($sheet) use($data) {
        $sheet->mergeCells('A1:O1');
        $sheet->row(1, array('Survey Report_'.date("Ymd_his")));
        $sheet->row(1, function($row) {
          $row->setFontWeight('bold');
          $row->setFontColor('#000000');
        });

        // HEADER
        $sheet->cells('A2:AU2', function($cells) {
          $cells->setBackground('#3498DB');
          $cells->setFontWeight('bold');
          $cells->setFontColor('#000000');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->row(2,array('No','Position','Division','Sex','Education','Years of Work','Q1','Q2','Q3','Q4','Q5','Q6','Q7','Q8','Q9','Q10','Q11','Q12','Q13','Q14','Q15','Q16','Q17','Q18','Q19','Q20','Q21','Q22','Q23','Q24','Q25','Q26','Q27','Q28','Q29','Q30','Q31','Q32','Q33','Q34','Q35','Q36','Q37','Q38','Q39','Q40','QO1','QO2'));
        // HEADER

        // DATA
        $no = 1;
        $rowX = 3;
        foreach (DB::table('t_survei_employee')
                ->leftjoin('tb_datapribadi', 't_survei_employee.email', '=', 'tb_datapribadi.email')
                ->leftjoin('tbldivmaster','tb_datapribadi.divisi','=','tbldivmaster.id')
                ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
                ->select('t_survei_employee.email','tbldivmaster.nama_div_ext','tb_datapribadi.jk','tb_jabatan.jabatan','tb_pangkat.pangkat',
                  DB::raw('CASE WHEN (SELECT ab.jenjang
                       FROM tb_pendidikan aa
                       LEFT JOIN tb_jenjang ab ON aa.jenjang = ab.id_j
                       WHERE aa.NIK = tb_datapribadi.nik
                       ORDER BY aa.jenjang DESC LIMIT 1) IS NOT NULL THEN
                       (SELECT ab.jenjang
                       FROM tb_pendidikan aa
                       LEFT JOIN tb_jenjang ab ON aa.jenjang = ab.id_j
                       WHERE aa.NIK = tb_datapribadi.nik
                       ORDER BY aa.jenjang DESC LIMIT 1)
                       ELSE
                       "S1" END as edu'),
                  DB::raw('CONCAT(FLOOR(DATEDIFF(CURDATE(),TglKontrak)/365), " Tahun "
                          , FLOOR((DATEDIFF(CURDATE(),TglKontrak)/365 - FLOOR(DATEDIFF(CURDATE(),TglKontrak)/365))* 12) , " Bulan "
                          , CEILING((((DATEDIFF(CURDATE(),TglKontrak)/365 - FLOOR(DATEDIFF(CURDATE(),TglKontrak)/365))* 12)
                          - FLOOR((DATEDIFF(CURDATE(),TglKontrak)/365 - FLOOR(DATEDIFF(CURDATE(),TglKontrak)/365))* 12))* 30) , " Hari") masa_kerja'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 1) AS q1'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 2) AS q2'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 3) AS q3'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 4) AS q4'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 5) AS q5'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 6) AS q6'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 7) AS q7'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 8) AS q8'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 9) AS q9'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 10) AS q10'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 11) AS q11'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 12) AS q12'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 13) AS q13'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 14) AS q14'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 15) AS q15'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 16) AS q16'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 17) AS q17'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 18) AS q18'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 19) AS q19'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 20) AS q20'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 21) AS q21'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 22) AS q22'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 23) AS q23'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 24) AS q24'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 25) AS q25'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 26) AS q26'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 27) AS q27'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 28) AS q28'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 29) AS q29'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 30) AS q30'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 31) AS q31'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 32) AS q32'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 33) AS q33'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 34) AS q34'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 35) AS q35'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 36) AS q36'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 37) AS q37'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 38) AS q38'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 39) AS q39'),
                  DB::raw('(SELECT ad.point FROM t_survei_employee_result ac LEFT JOIN m_survei_value ad ON ac.survei_value_id = ad.id WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 40) AS q40'),
                  DB::raw('(SELECT ac.survei_open_value FROM t_survei_employee_result ac WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 41) qo1'),
                  DB::raw('(SELECT ac.survei_open_value FROM t_survei_employee_result ac WHERE ac.survei_employee_id = t_survei_employee.id AND ac.survei_question_id = 42) qo2')
                  )
                ->where('t_survei_employee.survei_join', 2)
                ->orderby('t_survei_employee.updated_at')
                ->get()
         as $dataRow):
          $sheet->getStyle('D'.$rowX.':O'.$rowX)->getAlignment()->setWrapText(true);
          $sheet->row($rowX, array(
            $no, $dataRow->pangkat, $dataRow->nama_div_ext, $dataRow->jk, $dataRow->edu, $dataRow->masa_kerja,
            $dataRow->q1, $dataRow->q2, $dataRow->q3,$dataRow->q4, $dataRow->q5, $dataRow->q6, $dataRow->q7, $dataRow->q8,$dataRow->q9, $dataRow->q10,
            $dataRow->q11, $dataRow->q12, $dataRow->q13,$dataRow->q14, $dataRow->q15, $dataRow->q16, $dataRow->q17, $dataRow->q18,$dataRow->q19, $dataRow->q20,
            $dataRow->q21, $dataRow->q22, $dataRow->q23,$dataRow->q24, $dataRow->q25, $dataRow->q26, $dataRow->q27, $dataRow->q28,$dataRow->q29, $dataRow->q30,
            $dataRow->q31, $dataRow->q32, $dataRow->q33,$dataRow->q34, $dataRow->q35, $dataRow->q36, $dataRow->q37, $dataRow->q38,$dataRow->q39, $dataRow->q40,
            Crypt::decrypt($dataRow->qo1), Crypt::decrypt($dataRow->qo2)
          ));

          $no++;
          $rowX++;
        endforeach;

        // $sheet->cells('F2:F'.($rowX-1), function($cells) {
        //  $cells->setBackground('#FF0000');
        //  $cells->setFontWeight('bold');
        // });
        // DATA
      });

      $excel->setTitle('HC SURVEY - EMPLOYEE ENGAGEMENT SURVEY 2016');

      $excel->setCreator('Ibnu Ridho Rifai')
            ->setCompany('RnD - EDI Indonesia');

      $excel->setDescription('');

    })->store('xlsx', storage_path('excel/survey'));

    // $mailAttachment = storage_path().'\excel\expiredUserReport\engagement_survey_'.date("MY").".xlsx";

    // Mail::send('hello', array('type'=>'activation'),
    // function($message) use ($mailAttachment){
    //     $message->to("ibnu.ridho@edi-indonesia.co.id", 'UserPortalCommunity')
    //            ->subject('EDI Portal Community - Report Expired Customer')
    //            ->attach($mailAttachment);
    // });
    }

}