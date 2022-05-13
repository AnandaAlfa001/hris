<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Models\EmployeeModel;
use App\Models\KaryawanModel;
use App\Models\AgamaModel;
use App\Models\DivisiModel;
use App\Models\SubDivisiModel;
use App\Models\GolonganModel;
use App\Models\GolonganOutModel;
use App\Models\JabatanModel;
use App\Models\JenjangModel;
use App\Models\LokkerModel;
use App\Models\PangkatModel;
use App\Models\StatusKarModel;
use App\Models\RiwayatKerjaModel;
use App\Models\HistoryJabModel;
use App\Models\PendidikanModel;
use App\Models\PendidikanNonModel;
use App\Models\KegiatanOrganisasiModel;
use App\Models\AnakModel;
use App\Models\KontrakModel;
use App\Models\HeaderPEModel;
use App\Models\ProjectExModel;
use App\Models\CutiModel;
use App\Models\LemburModel;
use App\Models\KesehatanModel;
use App\Models\AbsenIjinModel;
use App\Models\TrainingModel;
use App\Models\OrangTerModel;
use App\Models\SPModel;
use App\Models\PerjalananDinasModel;
use App\Models\RiwayatPenyakitModel;
use App\Models\DokumenModel;
use Illuminate\Support\Facades\Crypt;
use Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use DatePeriod;
use DateTime;
use DateInterval;
use App\Models\PTHModel;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Response;

class EmployeeController extends Controller
{
  public function __construct(EmailController $_email)
  {
    $this->email = $_email;
    $this->list_pangkat_atasan = '5,7,1948,1951,1952,1954,1955,1958,1959,1961,1962,1963';
    $this->managerGradeID = "5,7,1948,1951,1952,1954,1955,1956,1957,1958,1959,1962,1963";
  }

  public function listEmployee()
  {
    $manager        = DB::table('tb_datapribadi AS A')
      ->select('A.NIK AS NIK', 'A.Nama AS NAMA', 'B.pangkat AS PANGKAT')
      ->leftJoin('tb_pangkat AS B', 'B.id', '=', 'A.idpangkat')
      ->whereRaw('A.statuskar IN (1, 2, 4)')
      ->whereRaw('A.idpangkat IN (' . $this->managerGradeID . ')')
      ->orderBy('A.Nama')
      ->get();
    $employeeStatus = DB::table('tb_statuskar')->select('id', 'status_kar')->get();
    $workLocation   = DB::table('tb_lokasikerja')->select('id', 'lokasi')->get();

    $data           = compact('manager', 'employeeStatus', 'workLocation');
    return view('employee/list', $data);
  }

  public function dataEmployee(Request $request)
  {
    $filterEmployeeStatus = $request->query('filterEmployeeStatus');
    $filterManager1       = $request->query('filterManager1');
    $filterManager2       = $request->query('filterManager2');
    $filterWorkLocation   = $request->query('filterWorkLocation');
    $filterStartContract  = $request->query('filterStartContract');
    $filterEndContract    = $request->query('filterEndContract');

    $arrCondition         = array(
      array('A.resign', '<>', 'Y')
    );

    if (!empty($filterEmployeeStatus)) {
      array_push($arrCondition, array('A.statuskar', '=', $filterEmployeeStatus));
    }
    if (!empty($filterManager1)) {
      array_push($arrCondition, array('A.atasan1', '=', $filterManager1));
    }
    if (!empty($filterManager2)) {
      array_push($arrCondition, array('A.atasan2', '=', $filterManager2));
    }
    if (!empty($filterWorkLocation)) {
      array_push($arrCondition, array('A.LokasiKer', '=', $filterWorkLocation));
    }
    if (!empty($filterStartContract)) {
      array_push($arrCondition, array('A.TglKontrak', '=', $filterStartContract));
    }
    if (!empty($filterEndContract)) {
      array_push($arrCondition, array('A.TglKontrakEnd', '=', $filterEndContract));
    }

    $employee = DB::table('tb_datapribadi AS A')
      ->select(
        'A.NIK',
        'A.sqc_nik AS SEQUENCE_NIK',
        'A.Nama as NAMA',
        'A.jk AS JENIS_KELAMIN',
        'A.Alamat AS ALAMAT',
        'A.NoHP as NOMOR_HP',
        'A.email AS EMAIL',
        'A.statuskar AS STATUS',
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
      ->where($arrCondition)
      ->get();

    return response()->json($employee);
  }

  public function HOME()
  {

    $nik = Session::get('nik');

    $jumlahkaryawan = EmployeeModel::where('resign', 'N')
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))->count();
    // $outemployee = EmployeeModel::where('resign','Y')
    //                       ->whereRaw(DB::raw('NIK NOT IN ("admin")'))->count();

    $tetap = EmployeeModel::whereRaw('(statuskar=1) and resign="N"')->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      // ->where('resign','<>','Y')
      ->count();

    $out = EmployeeModel::whereRaw('(statuskar=5 or statuskar=6) and resign="N"')->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->count();

    $kontrakemployee = EmployeeModel::whereRaw('(statuskar=2 or statuskar=3 or statuskar=4) and resign="N"')
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->count();

    $tablehistory = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tgl_sk_jab',
      'tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'TglKontrak',
      'TglKontrakEnd',
      'tb_his_jabatan.gaji',

      DB::raw('CONCAT(tb_pangkat.pangkat,"-",tb_jabatan.jabatan) as jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')

      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $nik . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $nik . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();




    $data = KaryawanModel::select('NIK', 'Nama', 'TglKontrak', 'TglKontrakEnd')
      ->where('sqc_nik', 'new')
      ->where('resign', 'N')
      ->get();

    // dd($data);
    // $historycuti = CutiModel::where('NIK',$nik)
    //                         ->where('approve_1','Y')
    //                         ->where('approve_2','Y')
    //                         ->limit(4)
    //                         ->get();
    // $historycutijum = CutiModel::where('NIK',$nik)
    //                             ->where('approve_1','Y')
    //                             ->where('approve_2','Y')
    //                             ->count();

    $historycutiat = CutiModel::where('NIK', $nik)
      ->where('approve_1', 'Y')
      ->where('approve_2', null)
      ->limit(4)
      ->get();
    $historycutiatjum = CutiModel::where('NIK', $nik)
      ->where('approve_1', 'Y')
      ->where('approve_2', null)
      ->count();

    $historylembur = LemburModel::where('NIK', $nik)
      ->where('status', 'S')
      ->limit(4)
      ->get();

    $jumlahjamlembur = LemburModel::select(DB::raw('SUM(TotalSelisihJamLembur) as jumlahjamlembur'))
      ->whereRaw('(NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE NIK = "' . $nik . '")) AND STATUS = "S"')
      ->first();

    $jumlahlembur = $jumlahjamlembur->jumlahjamlembur;
    if ($jumlahlembur == null or $jumlahlembur == 0) {
      $historylemburjum = 0;
    } else {
      $historylemburjum = $jumlahlembur;
    }

    $historykes = KesehatanModel::select(
      'tb_kesehatan.ID as idkes',
      'tglklaim',
      'remb',
      'kwitansi',
      'nama_apotek',
      'tglberobat',
      'diagnosa',
      'total_klaim',
      'approve',
      DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = "' . $nik . '")
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = "' . $nik . '")
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '")) and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '")) and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '")) and AnakKe = 3)
                END as nama')
    )
      ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_kesehatan.NIK')
      ->leftjoin('tb_remb', 'tb_remb.id', '=', 'tb_kesehatan.jn_remb')
      ->whereRaw('(tb_kesehatan.NIK = "' . $nik . '" OR tb_kesehatan.NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->where('approve', 'Y')
      ->where('approve_vp', 'Y')
      ->where('approve_svp', 'Y')
      ->orderBy('tglklaim', 'DESC')
      ->limit(4)
      ->get();

    $historykesjum = KesehatanModel::whereRaw('(tb_kesehatan.NIK = "' . $nik . '" OR tb_kesehatan.NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->where('approve', 'Y')
      ->where('approve_vp', 'Y')
      ->where('approve_svp', 'Y')
      ->orderBy('tglklaim', 'DESC')
      ->count();

    $historyizin = AbsenIjinModel::whereRaw('(absen_izin.nik = "' . $nik . '" OR absen_izin.nik = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->where('statusApp', 1)
      ->orderBy('tanggal', 'DESC')
      ->limit(4)
      ->get();
    $historyizinjum = AbsenIjinModel::whereRaw('(absen_izin.nik = "' . $nik . '" OR absen_izin.nik = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->where('statusApp', 1)
      ->count();

    $historytraining = TrainingModel::whereRaw('(tb_training.NIK = "' . $nik . '" OR tb_training.NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->limit(4)
      ->get();

    $historytrainingjum = TrainingModel::whereRaw('(tb_training.NIK = "' . $nik . '" OR tb_training.NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->count();

    return view('main')->with('jumlahkaryawan', $jumlahkaryawan)
      // ->with('outemployee',$outemployee)
      ->with('tetap', $tetap)
      ->with('out', $out)
      ->with('tablehistory', $tablehistory)
      ->with('kontrakemployee', $kontrakemployee)
      ->with('data', $data)
      // ->with('historycuti',$historycuti)
      // ->with('historycutijum',$historycutijum)
      ->with('historycutiat', $historycutiat)
      ->with('historycutiatjum', $historycutiatjum)
      ->with('historylembur', $historylembur)
      ->with('historylemburjum', $historylemburjum)
      ->with('historykes', $historykes)
      ->with('historykesjum', $historykesjum)
      ->with('historyizin', $historyizin)
      ->with('historyizinjum', $historyizinjum)
      ->with('historytrainingjum', $historytrainingjum)
      ->with('historytraining', $historytraining);
  }


  public function INDEX()
  {

    $CallEmp = KaryawanModel::select(
      'sqc_nik',
      'NIK',
      'Nama',
      'Alamat',
      'NoHp',
      'email',
      'statuskar',
      DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
      DB::raw('CASE
                                    			WHEN tb_datapribadi.idpangkat IS NULL or tb_datapribadi.idpangkat = 0 or tb_datapribadi.idpangkat = ""
                                    				THEN CONCAT(" - ", " - " , (SELECT jabatan FROM tb_jabatan WHERE tb_datapribadi.idjabatan = tb_jabatan.id))
                                    			WHEN tb_datapribadi.idjabatan IS NULL or tb_datapribadi.idjabatan = 0 or tb_datapribadi.idjabatan = 75 or tb_datapribadi.idjabatan = ""
                                    				THEN CONCAT((SELECT pangkat FROM tb_pangkat WHERE tb_datapribadi.idpangkat = tb_pangkat.id), " - " , " - ")
                                    			WHEN (tb_datapribadi.idpangkat IS NULL or tb_datapribadi.idpangkat = 0 or tb_datapribadi.idpangkat = "") and (tb_datapribadi.idjabatan IS NULL or tb_datapribadi.idjabatan = 0 or tb_datapribadi.idjabatan = 75 or tb_datapribadi.idjabatan = "")
                                    				THEN CONCAT("-"," - ","-")
                                    			ELSE
                                    				CONCAT((SELECT pangkat FROM tb_pangkat WHERE tb_datapribadi.idpangkat = tb_pangkat.id), " - " , (SELECT jabatan FROM tb_jabatan WHERE tb_datapribadi.idjabatan = tb_jabatan.id))
                                    		END as Jabatan'),
      DB::raw('CASE
                                    			WHEN tb_datapribadi.Divisi IS NULL or tb_datapribadi.Divisi = 0 or tb_datapribadi.Divisi = 1 or tb_datapribadi.Divisi = ""
                                    				THEN CONCAT("-"," - ",(SELECT subdivisi FROM tb_subdivisi WHERE tb_datapribadi.SubDivisi = tb_subdivisi.id))
                                    			WHEN tb_datapribadi.SubDivisi IS NULL or tb_datapribadi.SubDivisi = 0 or tb_datapribadi.SubDivisi = ""
                                    				THEN CONCAT((SELECT nama_div_ext FROM tbldivmaster WHERE tbldivmaster.id = tb_datapribadi.Divisi)," - ","-")
                                    			WHEN (tb_datapribadi.Divisi IS NULL or tb_datapribadi.Divisi = 0 or tb_datapribadi.Divisi = 1 or tb_datapribadi.Divisi = "") and (tb_datapribadi.SubDivisi IS NULL or tb_datapribadi.SubDivisi = 0 or tb_datapribadi.SubDivisi = "")
                                    				THEN CONCAT("-"," - ","-")
                                    			ELSE
                                    				CONCAT((SELECT nama_div_ext FROM tbldivmaster WHERE tbldivmaster.id = tb_datapribadi.Divisi)," - ",(SELECT subdivisi FROM tb_subdivisi WHERE tb_datapribadi.SubDivisi = tb_subdivisi.id))
                                    		END as Divisi'),
      // DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
      // DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN statuskar = "5" or statuskar = "6"
                                    				THEN
                                    					CASE WHEN tb_datapribadi.Golongan_out IS NULL OR tb_datapribadi.Golongan_out = 0 OR tb_datapribadi.Golongan_out = "" THEN "-"
                                    						 ELSE (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.Golongan_out)
                                    					END
                                    			  ELSE
                                    			  	CASE WHEN tb_datapribadi.Golongan IS NULL or tb_datapribadi.Golongan = 0 or tb_datapribadi.Golongan = ""
                                    			  		THEN "="
                                    			  		ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan)
                                    			  	END
                                    			  END as Gol')
    )
      // ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
      // ->leftjoin('tb_subdivisi','tb_datapribadi.SubDivisi','=','tb_subdivisi.id')
      // ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
      // ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
      ->where('resign', '=', "N")
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->orderby('statuskar')
      ->orderby('sqc_nik', 'ASC')
      ->get();

    // $atasan1 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    // $atasan2 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948,1951,1952,1954)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948,1951)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $statuskar = StatusKarModel::all();
    $lokasikerja = LokkerModel::all();


    return view('employee/employeelist')->with('CallEmp', $CallEmp)->with('atasan1', $atasan1)->with('atasan2', $atasan2)->with('statuskar', $statuskar)->with('lokasikerja', $lokasikerja);
  }

  public function KARTTPLIST()
  {
    $CallEmp = KaryawanModel::select(
      'sqc_nik',
      'NIK',
      'Nama',
      'Alamat',
      'NoHp',
      'email',
      DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
      DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      // ->where('resign','<>',"Y")
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->whereRaw('(statuskar=1) and resign="N"')
      ->orderby('statuskar')
      ->orderby('sqc_nik', 'ASC')
      ->get();

    return view('employee/karttplist')->with('CallEmp', $CallEmp);
  }

  public function KARKONLIST()
  {
    $CallEmp = KaryawanModel::select(
      'sqc_nik',
      'NIK',
      'Nama',
      'Alamat',
      'NoHp',
      'email',
      DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
      DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      // ->where('resign','<>',"Y")
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->whereRaw('(statuskar=2 or statuskar=3 or statuskar=4) and resign="N"')
      ->orderby('statuskar')
      ->orderby('sqc_nik', 'ASC')
      ->get();

    return view('employee/karkonlist')->with('CallEmp', $CallEmp);
  }

  public function KAROUTLIST()
  {

    $CallEmp = KaryawanModel::select(
      'sqc_nik',
      'NIK',
      'Nama',
      'Alamat',
      'NoHp',
      'email',
      DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
      DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      // ->where('resign','<>',"Y")
      ->whereRaw('(statuskar=5 or statuskar=6) and resign="N"')
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->orderby('statuskar')
      ->orderby('sqc_nik', 'ASC')
      ->get();

    return view('employee/karoutlist')->with('CallEmp', $CallEmp);
  }



  public function FUNC_SEARCHEMPLOYEE(Request $request)
  {

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
    $atasan1 = $request['atasan1'];
    $atasan2 = $request['atasan2'];

    $snull = "";
    $lnull = "";
    $tanull = "";
    $tknull = "";
    $a1null = "";
    $a2null = "";
    if ($status == null or $status == "") {
      $status = "";
      $snull = "or statuskar IS NULL";
      // dd($snull);
    }
    if ($lokasi == NULL or $lokasi == "") {
      $lokasi = "";
      $lnull = "or LokasiKer IS NULL";
    }
    if ($tgl_kontrak == NULL or $tgl_kontrak == "") {
      $tgl_kontrak = "";
      $tknull = "or TglKontrak IS NULL";
    }
    if ($tgl_akhir == NULL or $tgl_akhir == "") {
      $tgl_akhir = "";
      $tanull = "or TglKontrakEnd IS NULL";
    }
    if ($atasan2 == NULL or $atasan2 == "") {
      $atasan2 = "";
      $a2null = "or atasan2 IS NULL";
    }
    if ($atasan1 == NULL or $atasan1 == "") {
      $atasan1 = "";
      $a1null = "or atasan1 IS NULL";
    }
    // DB::enableQueryLog();
    $Result = KaryawanModel::select(
      'sqc_nik',
      'NIK',
      'Nama',
      'Alamat',
      'NoHp',
      'email',
      DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
      DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan_out) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('(statuskar LIKE "%' . $status . '%" ' . $snull . ') and (LokasiKer LIKE "%' . $lokasi . '%" ' . $lnull . ') and (atasan1 LIKE "%' . $atasan1 . '%" ' . $a1null . ') and (atasan2 LIKE "%' . $atasan2 . '%" ' . $a2null . ') and (TglKontrak LIKE "%' . $tgl_kontrak . '%" ' . $tknull . ') and (TglKontrakEnd LIKE "%' . $tgl_akhir . '%" ' . $tanull . ')'))
      // ->where('statuskar','LIKE','%' . $status . '%')
      // ->where('LokasiKer','LIKE','%' . $lokasi  . '%')
      // ->where('atasan1','LIKE','%' . $atasan1 . '%')
      // ->where('atasan2','LIKE','%' . $atasan2 . '%')
      // ->where('TglKontrak','LIKE','%' . $tgl_kontrak . '%')
      // ->where('TglKontrakEnd','LIKE','%' . $tgl_akhir . '%')
      ->where('resign', 'N')
      ->orderby('statuskar')
      ->orderby('Nama', 'ASC')
      // ->toSql();
      ->get();
    // dd(DB::getQueryLog());
    //dd($Result);

    // $atasan1 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    // $atasan2 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948,1951,1952,1954)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948,1951)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $statuskar = StatusKarModel::all();
    $lokasikerja = LokkerModel::all();

    return view('employee/resultsearch', compact('Result', 'atasan1', 'atasan2', 'statuskar', 'lokasikerja'));
    // ->with('CallEmp',$CallEmp)->with('atasan1',$atasan1)->with('atasan2',$atasan2)->with('statuskar',$statuskar)->with('lokasikerja',$lokasikerja);

  }

  public function OUTEMPLOYEE()
  {
    $CallEmp = KaryawanModel::select(
      'sqc_nik',
      'NIK',
      'Nama',
      'Alamat',
      'NoHp',
      'email',
      'alasan_out',
      'tgl_out',
      DB::raw('CONCAT(SUBSTR(NIK,1,LENGTH(NIK) - 3),"-", RIGHT(NIK,3)) as nikFormat'),
      DB::raw('CONCAT(tbldivmaster.nama_div_ext, IF(ISNULL(tb_subdivisi.subdivisi),"",CONCAT(" - ",tb_subdivisi.subdivisi))) as Divisi'),
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_datapribadi.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_datapribadi.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->where('resign', '=', "Y")
      ->whereRaw(DB::raw('NIK NOT IN ("admin")'))
      ->orderby('statuskar')
      ->orderby('Nama', 'ASC')
      ->get();


    return view('employee/outemployeelist')->with('CallEmp', $CallEmp);
  }


  public function FUNC_ADDEMPLOYEE()
  {
    if (Session::get('admin') != 1) {
      return redirect('/');
    }
    $agama = AgamaModel::orderBy('idagama', 'ASC')->get();
    $divisi = DivisiModel::orderBy('nama_div_ext', 'ASC')->where('TYPE', null)->where('disabled', 1)->get();
    $subdivisi = SubDivisiModel::orderBy('subdivisi', 'ASC')->where('TYPE', null)->where('disabled', 1)->get();
    $golongan = GolonganModel::orderBy('id', 'ASC')->where('TYPE', null)->where('disabled', 1)->get();
    $golonganout = GolonganOutModel::orderBy('id', 'ASC')->where('TYPE', null)->where('disabled', 1)->get();
    $jabatan = JabatanModel::orderBy('jabatan', 'ASC')->where('TYPE', null)->where('disabled', 1)->get();
    $jenjang = JenjangModel::orderBy('id_j', 'ASC')->get();
    $lokker = LokkerModel::orderBy('id', 'ASC')->get();
    // $pangkat = PangkatModel::orderBy('id','ASC')->where('TYPE',null)->where('disabled',1)->get();
    $pangkat = PangkatModel::orderBy('urutan', 'ASC')->where('TYPE', null)->where('disabled', 1)->get();
    $statuskar = StatusKarModel::orderBy('id', 'ASC')->get();

    // $sql = "SELECT nik, CONCAT(a.nik,' - ', a.nama, ' (', b.pangkat, ')') atasan
    //         FROM tb_datapribadi a LEFT JOIN tb_pangkat b ON a.idpangkat = b.id
    //         WHERE a.statuskar IN (1,2,4,3) AND a.resign = 'N' AND a.idpangkat IN (2,3,4,5,6,7,1948)
    //         ORDER BY a.idpangkat";
    // $atasan1 = DB::select($sql);
    // $sql2 = "SELECT nik, CONCAT(a.nik,' - ', a.nama, ' (', b.pangkat, ')') atasan
    //         FROM tb_datapribadi a LEFT JOIN tb_pangkat b ON a.idpangkat = b.id
    //         WHERE a.statuskar IN (1,2,4,3) AND a.resign = 'N' AND a.idpangkat IN (2,3,4,5,1948)
    //         ORDER BY a.idpangkat";
    // $atasan2 = DB::select($sql2);

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948,1951,1952,1954)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948,1951)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    return view('employee/addemployee')->with('agama', $agama)
      ->with('divisi', $divisi)
      ->with('subdivisi', $subdivisi)
      ->with('golongan', $golongan)
      ->with('golonganout', $golonganout)
      ->with('jabatan', $jabatan)
      ->with('jenjang', $jenjang)
      ->with('pangkat', $pangkat)
      ->with('lokker', $lokker)
      ->with('statuskar', $statuskar)
      ->with('atasan1', $atasan1)
      ->with('atasan2', $atasan2);
  }

  public function FUNC_SAVEDATAPRIBADI(Request $request)
  {
    $tambah = new KaryawanModel();
    $tambah->NIK = $request['nik'];
    $tambah->Nama = $request['nama'];
    $tambah->TempatLahir = $request['TempatLahir'];
    $tambah->TanggalLahir = $request['tglLahir'];
    $tambah->Agama = $request['agama'];
    $tambah->Alamat = $request['alamat'];
    $tambah->Alamat_KTP = $request['alamat_ktp'];
    $tambah->jk = $request['jk'];
    $tambah->npwp = $request['npwp'];
    $tambah->nama_npwp = $request['nama_npwp'];
    $tambah->alamat_npwp = $request['alamat_npwp'];
    $tambah->ktp_sim = $request['ktp'];
    $tambah->gol_darah = $request['GolonganDarah'];
    $tambah->status_pernikahan = $request['status_pernikahan'];
    $tambah->norek = $request['norek'];
    $tambah->jamsostek = $request['jamsostek'];
    $tambah->bpjs = $request['bpjs'];
    $tambah->NoTelepon = $request['notelepon'];
    $tambah->NoHp = $request['NoHp'];
    $tambah->emailreg = $request['emailreg'];
    $tambah->email = $request['email'] . "@edi-indonesia.co.id";
    $tambah->statuskar = $request['status'];
    $tambah->TglKontrak = $request['tglKontrak'];
    $tambah->tglKontrakEnd = $request['tglKontrakEnd'];
    $tambah->idpangkat = $request['pangkat'];
    $tambah->idjabatan = $request['jabatan'];
    $tambah->Divisi = $request['divisi'];
    $tambah->SubDivisi = $request['subdivisi'];

    if ($request['gaji'] == null or $request['gaji'] == 0) {
      $gaji = null;
    } else {
      $gaji = Crypt::encrypt($request['gaji']);
    }

    if ($request['tunj_tmr'] == null or $request['tunj_tmr'] == 0) {
      $tunj_tmr = null;
    } else {
      $tunj_tmr = Crypt::encrypt($request['tunj_tmr']);
    }

    if ($request['tunj_jab'] == null or $request['tunj_jab'] == 0) {
      $tunj_jab = null;
    } else {
      $tunj_jab = Crypt::encrypt($request['tunj_jab']);
    }

    $tambah->gaji = $gaji;
    $tambah->tunj_tmr = $tunj_tmr;
    $tambah->tunj_jab = $tunj_jab;

    $byproyek = NULL;
    $proyek = NULL;

    if ($request['Vendor'] == '') {
      $tambah->vendor = null;
    } else {
      $tambah->vendor = $request['Vendor'];
    }
    if ($request['Golongan'] == '') {
      $tambah->Golongan = null;
    } else {
      $tambah->Golongan = $request['Golongan'];
    }
    if ($request['Golongan_out'] == '') {
      $tambah->Golongan_out = null;
    } else {
      $tambah->Golongan_out = $request['Golongan_out'];
      $byproyek = $request['byproyek'];
      if ($byproyek == 1) {
        $proyek = $request['proyek'];
      }
    }

    $tambah->byproyek = $byproyek;
    $tambah->proyek = $proyek;

    $subnik = substr($request['nik'], -3);
    $sqc_nik = $request['status'] . $subnik;
    $tambah->sqc_nik = $sqc_nik;
    $tambah->userpriv = 0000;
    $tambah->tgl_sk_gol = $request['TglGol'];
    $tambah->tgl_sk_jab = $request['TglGol'];
    $tambah->atasan1 = $request['atasan1'];
    $tambah->atasan2 = $request['atasan2'];
    $tambah->LokasiKer = $request['LokasiKer'];

    /** Add By Dandy Firmansyah 31 Maret 2019 **/

    // dokumen KTP
    if ($request->file('file_ktp') <> '') {
      $file = $request->file('file_ktp');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'KTP_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_ktp')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_ktp = new DokumenModel;
      $tambah_ktp->NIK = $request['nik'];
      $tambah_ktp->Jenis = '8';
      $tambah_ktp->File = $fileName;
      $tambah_ktp->save();
    }

    if ($request->file('file_npwp') <> '') {
      $file = $request->file('file_npwp');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'NPWP_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_npwp')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_npwp = new DokumenModel;
      $tambah_npwp->NIK = $request['nik'];
      $tambah_npwp->Jenis = '7';
      $tambah_npwp->File = $fileName;
      $tambah_npwp->save();
    }

    if ($request->file('file_no_rek') <> '') {
      $file = $request->file('file_no_rek');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'NoRek_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_no_rek')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_no_rek = new DokumenModel;
      $tambah_no_rek->NIK = $request['nik'];
      $tambah_no_rek->Jenis = '11';
      $tambah_no_rek->File = $fileName;
      $tambah_no_rek->save();
    }

    if ($request->file('file_jamsostek') <> '') {
      $file = $request->file('file_jamsostek');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'Jamsostek_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_jamsostek')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_jamsostek = new DokumenModel;
      $tambah_jamsostek->NIK = $request['nik'];
      $tambah_jamsostek->Jenis = '10';
      $tambah_jamsostek->File = $fileName;
      $tambah_jamsostek->save();
    }

    if ($request->file('file_bpjs') <> '') {
      $file = $request->file('file_bpjs');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'BPJS_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_bpjs')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_bpjs = new DokumenModel;
      $tambah_bpjs->NIK = $request['nik'];
      $tambah_bpjs->Jenis = '9';
      $tambah_bpjs->File = $fileName;
      $tambah_bpjs->save();
    }

    /** End Add By Dandy Firmansyah 31 Maret 2019 **/

    $tambah->save();

    $add = new HistoryJabModel();
    $add->nik = $request['nik'];
    $add->TglKontrak = $request['tglKontrak'];
    $add->tglKontrakEnd = $request['tglKontrakEnd'];
    if ($request['Golongan'] == '') {
      $add->Golongan = null;
    } else {
      $add->Golongan = $request['Golongan'];
    }
    if ($request['Golongan_out'] == '') {
      $add->Golongan_out = null;
    } else {
      $add->Golongan_out = $request['Golongan_out'];
    }
    $add->tgl_sk_gol = $request['TglGol'];
    $add->tgl_sk_jab = $request['TglGol'];
    $add->idpangkat = $request['pangkat'];
    $add->idjabatan = $request['jabatan'];
    $add->Divisi = $request['divisi'];
    $add->SubDivisi = $request['subdivisi'];
    $add->atasan1 = $request['atasan1'];
    $add->atasan2 = $request['atasan2'];
    $add->statuskar = $request['status'];
    $add->LokasiKer = $request['LokasiKer'];
    $add->gaji = $gaji;
    $add->tunj_tmr = $tunj_tmr;
    $add->tunj_jab = $tunj_jab;
    $add->byproyek = $byproyek;
    $add->proyek = $proyek;
    $add->save();

    //untuk if user role

    if ($request['pangkat'] == '2' || $request['pangkat'] == '3' || $request['pangkat'] == '4' || $request['pangkat'] == '5') {
      $user_role = '2';
    } elseif ($request['pangkat'] == '6' || $request['pangkat'] == '7') {
      $user_role = '3';
    } else {
      if ($request['divisi'] == '1937') {
        $user_role = '5';
      } else {
        $user_role = '4';
      }
    }

    $insertProdev = DB::connection('prodev')->table('user_app')->insert([
      [
        'nik' => $request['nik'],
        'nama' => $request['nama'],
        'id_divisi' => NULL,
        'status' => 1,
        'user_role' => $user_role,
        'id_pangkat' => $request['jabatan'],
        'status_kar' => $request['status']
      ]
    ]);

    $divisinama = DivisiModel::select('nama_div_ext')->where('id', $request['divisi'])->first();
    $namadivisi = $divisinama->nama_div_ext;

    $insertTUSER = DB::connection('mysql')->table('tr_user')->insert([
      [
        'NIK' => $request['nik'],
        'NAMA' => $request['nama'],
        'ADMIN' => '2',
        'DIVISI' => $namadivisi,
        'EMAIL' => $request['email'] . "@edi-indonesia.co.id",
        'TELP' => $request['NoHp']
      ]
    ]);

    //Session::put('nik',$request['nik']);
    $nik = $request['nik'];
    return redirect(url('editemployee', [$nik . '-employeelist']))->with('success', 'Data Berhasil Ditambahkan.')->with('b', true);
  }

  public function FUNC_DETAILEMPLOYEE($nik)
  {
    if (Session::get('admin') != 1) {
      if (Session::get('nik') != $nik) {
        return redirect('/');
      }
    }

    // $data = KaryawanModel::select('*','tb_agama.nama_agama as namaagamakar')
    //         ->leftjoin('tb_agama','tb_datapribadi.Agama','=','tb_agama.idagama')
    //         ->where('NIK', $nik)
    //         ->first();

    $data = KaryawanModel::select(
      'tb_datapribadi.*',
      'tb_agama.nama_agama as namaagamakar',
      'dokumen_ktp.File as file_ktp',
      'dokumen_npwp.File as file_npwp',
      'dokumen_no_rek.File as file_no_rek',
      'dokumen_jamsostek.File as file_jamsostek',
      'dokumen_bpjs.File as file_bpjs'
    )
      ->leftjoin('tb_agama', 'tb_datapribadi.Agama', '=', 'tb_agama.idagama')
      ->leftjoin('tb_dokumen AS dokumen_ktp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_ktp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_ktp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_ktp.Jenis', '=', '8');
      })
      ->leftjoin('tb_dokumen AS dokumen_npwp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_npwp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_npwp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_npwp.Jenis', '=', '7');
      })
      ->leftjoin('tb_dokumen AS dokumen_no_rek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_no_rek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_no_rek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_no_rek.Jenis', '=', '11');
      })
      ->leftjoin('tb_dokumen AS dokumen_jamsostek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_jamsostek.Jenis', '=', '10');
      })
      ->leftjoin('tb_dokumen AS dokumen_bpjs', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_bpjs.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_bpjs.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_bpjs.Jenis', '=', '9');
      })
      ->where('tb_datapribadi.NIK', $nik)
      ->first();
    $agama = AgamaModel::All();
    $anak = AnakModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->orderby('AnakKe', 'ASC')
      ->get();
    $showripek = RiwayatKerjaModel::where('nik', $nik)->orWhere('NIK', $data->old_nik)->orderBy('ID_rk', 'ASC')->get();
    // $showripen = PendidikanModel::where('NIK', $nik)->orWhere('NIK',$data->old_nik)
    //                                                 ->leftjoin('tb_jenjang','id_j','=','tb_pendidikan.Jenjang')
    //                                                 ->orderBy('tb_pendidikan.Jenjang','ASC')
    //                                                 ->get();
    $showripen = PendidikanModel::select('tb_pendidikan.*', 'tb_dokumen.File as FileDokumen', 'tb_jenjang.jenjang')
      ->leftjoin('tb_dokumen', 'tb_dokumen.id', '=', 'tb_pendidikan.id_dokumen')
      ->leftjoin('tb_jenjang', 'id_j', '=', 'tb_pendidikan.Jenjang')
      ->where('tb_pendidikan.NIK', $nik)->orWhere('tb_pendidikan.NIK', $data->old_nik)->orderBy('id', 'ASC')->get();
    $showripennon = PendidikanNonModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->orderBy('id_pnf', 'ASC')
      ->get();
    $showkegiatan = KegiatanOrganisasiModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id_org', 'ASC')->get();

    $showorangter = OrangTerModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id', 'ASC')->get();
    $showriwayatpenyakit = RiwayatPenyakitModel::where('nik', $nik)->orWhere('nik', $data->old_nik)->orderBy('id', 'ASC')->get();

    $historyjab = HistoryJabModel::select(
      'tb_his_jabatan.tgl_sk_jab',
      'tb_his_jabatan.tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'tb_statuskar.status_kar as statuskar',
      'tb_his_jabatan.no_sk as no_sk',
      'tb_his_jabatan.gaji',
      'tb_his_jabatan.tunj_jab',
      'tb_his_jabatan.tunj_tmr',
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN tb_his_jabatan.statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan_out) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.nik')
      ->leftjoin('tb_statuskar', 'tb_his_jabatan.statuskar', '=', 'tb_statuskar.id')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $nik . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $nik . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();

    $trainingdet = TrainingModel::where('tb_training.NIK', $nik)->orWhere('tb_training.NIK', $data->old_nik)->get();
    $spdet = SPModel::select(
      'tb_sp.id',
      'tb_sp.tgl_sk',
      'tb_sp.type_sp',
      'tb_sp.keterangan',
      'tb_sp.photo',
      DB::raw('(Select tb_datapribadi.Nama from tb_datapribadi where tb_datapribadi.NIK = tb_sp.nik_pemberi_sp OR tb_datapribadi.old_nik = tb_sp.nik_pemberi_sp) as nama_pemberisp'),
      DB::raw('(select jenis_sp from tb_mst_sp where tb_mst_sp.id = tb_sp.jenis_sp) as jenisSp')
    )
      ->where('tb_sp.nik', $nik)->orWhere('tb_sp.nik', $data->old_nik)->get();

    $cutidet = CutiModel::where('NIK', $nik)
      ->where('approve_1', 'Y')
      ->where('approve_2', null)
      ->get();

    $lemburdet = LemburModel::select(
      'ID',
      'TanggalMulaiLembur',
      'TanggalSelesaiLembur',
      'JamMulai',
      'PerkiraanJamSelesai',
      'Kegiatan',
      'JamSelesaiAktual',
      DB::raw('(select Nama from tb_datapribadi where NIK = tb_lembur.NIKPemberiLembur) as NamaPemberiLembur')
    )
      ->where('NIK', $nik)
      ->where('status', 'S')
      ->get();

    $kesehatandet = KesehatanModel::select(
      'tb_kesehatan.ID as idkes',
      'tglklaim',
      'remb',
      'kwitansi',
      'nama_apotek',
      'tglberobat',
      'diagnosa',
      'total_klaim',
      'total_apprv',
      'approve',
      DB::raw('CASE
                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))
                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))
                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '")) and AnakKe = 1)
                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '")) and AnakKe = 2)
                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "' . $nik . '" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '")) and AnakKe = 3)
                END as nama')
    )
      ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_kesehatan.NIK')
      ->leftjoin('tb_remb', 'tb_remb.id', '=', 'tb_kesehatan.jn_remb')
      ->whereRaw('(tb_kesehatan.NIK = "' . $nik . '" OR tb_kesehatan.NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      // ->where('approve','Y')
      // ->where('approve_vp','Y')
      // ->where('approve_svp','Y')
      ->orderBy('tglklaim', 'DESC')
      ->get();

    $izindet = AbsenIjinModel::select(
      'absen_izin.nama',
      'absen_izin.nik',
      'absen_izin.tanggal',
      'absen_izin.stat',
      'absen_izin.ket',
      'absen_izin.alasan_reject',
      'absen_izin.statusApp',
      'absen_izin.jam_mulai',
      'absen_izin.jam_selesai',
      DB::raw('CASE
                    WHEN `statusApp` = "1" and act_by IS NOT NULL THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = absen_izin.act_by))
                    WHEN statusApp = "1" and act_by IS NULL THEN "Approved"
                    WHEN `statusApp` = "2" and act_by IS NOT NULL THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = absen_izin.act_by))
                    WHEN statusApp = "2" and act_by IS NULL THEN "Rejected"
                    WHEN `statusApp` = "0" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statusizin')
    )
      ->whereRaw('(absen_izin.nik = "' . $nik . '" OR absen_izin.nik = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "' . $nik . '"))')
      ->get();

    $pddet = PerjalananDinasModel::select(
      'tb_perjalanandinas.id',
      'tb_perjalanandinas.nik',
      'tb_perjalanandinas.jam_awal',
      'tb_perjalanandinas.jam_akhir',
      DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_awal,"%d %M %Y") as tgl_awal'),
      DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_akhir,"%d %M %Y") as tgl_akhir'),
      DB::raw('(Select Nama From tb_datapribadi where tb_datapribadi.NIK = tb_perjalanandinas.nik OR tb_datapribadi.old_nik = tb_perjalanandinas.nik) as nama_kar'),
      'tb_perjalanandinas.keterangan'
    )
      ->whereRaw('tb_perjalanandinas.nik = "' . $nik . '" OR tb_perjalanandinas.nik = (Select old_nik from tb_datapribadi where tb_datapribadi.NIK = "' . $nik . '")')
      ->orderby('tb_perjalanandinas.id', 'DESC')
      ->get();

    $coba = array();
    foreach ($historyjab as $historyjabs) {
      if ($historyjabs->gaji == null) {
        $gajimen = 'null';
      } else {
        $gaji = Crypt::decrypt($historyjabs->gaji);
        $gajis = floatval($gaji);
        $gajimen = number_format($gajis, 0, ",", ".");
      }

      if ($historyjabs->tunj_tmr == null) {
        $tunj_tmrmen = 'null';
      } else {
        $tunj_tmr = Crypt::decrypt($historyjabs->tunj_tmr);
        $tunj_tmrs = floatval($tunj_tmr);
        $tunj_tmrmen = number_format($tunj_tmrs, 0, ",", ".");
      }

      if ($historyjabs->tunj_jab == null) {
        $tunj_jabmen = 'null';
      } else {
        $tunj_jab = Crypt::decrypt($historyjabs->tunj_jab);
        $tunj_jabs = floatval($tunj_jab);
        $tunj_jabmen = number_format($tunj_jabs, 0, ",", ".");
      }

      $coba[] = array('gaji' => $gajimen, 'tunj_tmr' => $tunj_tmrmen, 'tunj_jab' => $tunj_jabmen);
    }


    return view('employee/showdetail')->with('data', $data)
      ->with('agama', $agama)
      ->with('anak', $anak)
      ->with('ripek', $showripek)
      ->with('ripen', $showripen)
      ->with('non', $showripennon)
      ->with('kegiatan', $showkegiatan)
      ->with('historyjab', $historyjab)
      ->with('arraygaji', $coba)
      ->with('trainingdet', $trainingdet)
      ->with('spdet', $spdet)
      ->with('lemburdet', $lemburdet)
      ->with('cutidet', $cutidet)
      ->with('kesehatandet', $kesehatandet)
      ->with('izindet', $izindet)
      ->with('pddet', $pddet)
      ->with('orangter', $showorangter)
      ->with('riwayatpenyakit', $showriwayatpenyakit);
  }

  public function FUNC_PROFILEMPLOYEE($nik)
  {
    if (Session::get('admin') != 1) {
      if (Session::get('nik') != $nik) {
        return redirect('/');
      }
    }
    $data = KaryawanModel::select(
      'tb_datapribadi.*',
      'tb_agama.nama_agama as namaagamakar',
      'dokumen_ktp.File as file_ktp',
      'dokumen_npwp.File as file_npwp',
      'dokumen_no_rek.File as file_no_rek',
      'dokumen_jamsostek.File as file_jamsostek',
      'dokumen_bpjs.File as file_bpjs'
    )
      ->leftjoin('tb_agama', 'tb_datapribadi.Agama', '=', 'tb_agama.idagama')
      ->leftjoin('tb_dokumen AS dokumen_ktp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_ktp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_ktp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_ktp.Jenis', '=', '8');
      })
      ->leftjoin('tb_dokumen AS dokumen_npwp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_npwp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_npwp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_npwp.Jenis', '=', '7');
      })
      ->leftjoin('tb_dokumen AS dokumen_no_rek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_no_rek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_no_rek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_no_rek.Jenis', '=', '11');
      })
      ->leftjoin('tb_dokumen AS dokumen_jamsostek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_jamsostek.Jenis', '=', '10');
      })
      ->leftjoin('tb_dokumen AS dokumen_bpjs', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_bpjs.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_bpjs.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_bpjs.Jenis', '=', '9');
      })
      ->where('tb_datapribadi.NIK', $nik)
      ->first();

    $agama = AgamaModel::All();
    $anak = AnakModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->orderby('AnakKe', 'ASC')
      ->get();
    $showripek = RiwayatKerjaModel::where('nik', $nik)->orWhere('NIK', $data->old_nik)->orderBy('ID_rk', 'ASC')->get();
    // $showripen = PendidikanModel::where('NIK', $nik)->orWhere('NIK',$data->old_nik)
    //                                                 ->leftjoin('tb_jenjang','id_j','=','tb_pendidikan.Jenjang')
    //                                                 ->orderBy('tb_pendidikan.Jenjang','ASC')
    //                                                 ->get();
    $showripen = PendidikanModel::select('tb_pendidikan.*', 'tb_dokumen.File as FileDokumen', 'tb_jenjang.jenjang')
      ->leftjoin('tb_dokumen', 'tb_dokumen.id', '=', 'tb_pendidikan.id_dokumen')
      ->leftjoin('tb_jenjang', 'id_j', '=', 'tb_pendidikan.Jenjang')
      ->where('tb_pendidikan.NIK', $nik)->orWhere('tb_pendidikan.NIK', $data->old_nik)->orderBy('id', 'ASC')->get();
    $showripennon = PendidikanNonModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->orderBy('id_pnf', 'ASC')
      ->get();
    $showkegiatan = KegiatanOrganisasiModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id_org', 'ASC')->get();

    $showorangter = OrangTerModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id', 'ASC')->get();
    $showriwayatpenyakit = RiwayatPenyakitModel::where('nik', $nik)->orWhere('nik', $data->old_nik)->orderBy('id', 'ASC')->get();

    $historyjab = HistoryJabModel::select(
      'tb_his_jabatan.tgl_sk_jab',
      'tb_his_jabatan.tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'tb_statuskar.status_kar as statuskar',
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN tb_his_jabatan.statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.nik')
      ->leftjoin('tb_statuskar', 'tb_his_jabatan.statuskar', '=', 'tb_statuskar.id')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $nik . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $nik . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();

    return view('employee/showprofil')->with('data', $data)
      ->with('agama', $agama)
      ->with('anak', $anak)
      ->with('ripek', $showripek)
      ->with('ripen', $showripen)
      ->with('non', $showripennon)
      ->with('kegiatan', $showkegiatan)
      ->with('orangter', $showorangter)
      ->with('riwayatpenyakit', $showriwayatpenyakit)
      ->with('historyjab', $historyjab);
  }

  public function FUNC_DETAILEMPLOYEEOUT($nik)
  {
    if (Session::get('admin') != 1) {
      return redirect('/');
    }
    // $data = KaryawanModel::select('*','tb_agama.nama_agama as namaagamakar')
    //         ->leftjoin('tb_agama','tb_datapribadi.Agama','=','tb_agama.idagama')
    //         ->where('NIK', $nik)
    //         ->first();

    $data = KaryawanModel::select(
      'tb_datapribadi.*',
      'tb_agama.nama_agama as namaagamakar',
      'dokumen_ktp.File as file_ktp',
      'dokumen_npwp.File as file_npwp',
      'dokumen_no_rek.File as file_no_rek',
      'dokumen_jamsostek.File as file_jamsostek',
      'dokumen_bpjs.File as file_bpjs'
    )
      ->leftjoin('tb_agama', 'tb_datapribadi.Agama', '=', 'tb_agama.idagama')
      ->leftjoin('tb_dokumen AS dokumen_ktp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_ktp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_ktp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_ktp.Jenis', '=', '8');
      })
      ->leftjoin('tb_dokumen AS dokumen_npwp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_npwp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_npwp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_npwp.Jenis', '=', '7');
      })
      ->leftjoin('tb_dokumen AS dokumen_no_rek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_no_rek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_no_rek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_no_rek.Jenis', '=', '11');
      })
      ->leftjoin('tb_dokumen AS dokumen_jamsostek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_jamsostek.Jenis', '=', '10');
      })
      ->leftjoin('tb_dokumen AS dokumen_bpjs', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_bpjs.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_bpjs.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_bpjs.Jenis', '=', '9');
      })
      ->where('tb_datapribadi.NIK', $nik)
      ->first();

    $agama = AgamaModel::All();
    $anak = AnakModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->orderby('AnakKe', 'ASC')
      ->get();
    $showripek = RiwayatKerjaModel::where('nik', $nik)->orWhere('NIK', $data->old_nik)->orderBy('ID_rk', 'ASC')->get();
    $showripen = PendidikanModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->leftjoin('tb_jenjang', 'id_j', '=', 'tb_pendidikan.Jenjang')
      ->orderBy('tb_pendidikan.Jenjang', 'ASC')
      ->get();
    $showripennon = PendidikanNonModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
      ->orderBy('id_pnf', 'ASC')
      ->get();
    $showkegiatan = KegiatanOrganisasiModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id_org', 'ASC')->get();

    $historyjab = HistoryJabModel::select(
      'tb_his_jabatan.tgl_sk_jab',
      'tb_his_jabatan.tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'tb_statuskar.status_kar as statuskar',
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN tb_his_jabatan.statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.nik')
      ->leftjoin('tb_statuskar', 'tb_his_jabatan.statuskar', '=', 'tb_statuskar.id')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $nik . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $nik . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();

    return view('employee/showdetailout')->with('data', $data)
      ->with('agama', $agama)
      ->with('anak', $anak)
      ->with('ripek', $showripek)
      ->with('ripen', $showripen)
      ->with('non', $showripennon)
      ->with('kegiatan', $showkegiatan)
      ->with('historyjab', $historyjab);
  }

  public function FUNC_CETAKSK_PHK($nik)
  {

    $data = EmployeeModel::select(
      'tb_datapribadi.NIK as nik',
      'tb_datapribadi.Nama',
      'tb_datapribadi.dir_1_resign as nik_dir_utama',
      'tb_datapribadi.dir_1_resign as nik_dir_keuangan',
      'tb_datapribadi.alasan_out',
      'tb_datapribadi.surat_pemberhentian as no_sk',
      DB::raw('DATE_FORMAT(tb_datapribadi.tgl_out,"%d %M %Y") as tgl_resign'),
      DB::raw('(select Nama from tb_datapribadi a where a.NIK = tb_datapribadi.dir_1_resign OR old_nik = tb_datapribadi.dir_1_resign) as direktur_utama'),
      DB::raw('(select Nama from tb_datapribadi b where b.NIK = tb_datapribadi.dir_2_resign OR old_nik = tb_datapribadi.dir_2_resign) as direktur_keuangan')
    )
      ->where('tb_datapribadi.NIK', $nik)
      ->first();

    $pdf = PDF::loadView('employee.generatePdfPHK', compact('data'))
      ->setPaper('a4')->setOrientation('potrait');

    return $pdf->stream();
  }

  public function FUNC_EDITEMPLOYEE($id)
  {

    //$url_prev = explode('/',$_SERVER['HTTP_REFERER']);
    //$urlmen = end($url_prev);

    $explode = explode('-', $id);
    $nik = $explode[0];
    $url = $explode[1];

    if (Session::get('admin') != 1) {
      if (Session::get('nik') != $nik) {
        return redirect('/');
      }
    }
    $historyjab = HistoryJabModel::select(
      'tb_his_jabatan.tgl_sk_jab',
      'tb_his_jabatan.tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'tb_his_jabatan.gaji as gaji',
      'tb_his_jabatan.no_sk as no_sk',
      DB::raw('CONCAT(tb_pangkat.pangkat, " - " , tb_jabatan.jabatan) as Jabatan'),
      DB::raw('CASE WHEN tb_his_jabatan.statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.nik')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $nik . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $nik . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();

    // $data = KaryawanModel::where('nik', $nik)->first();
    $data = KaryawanModel::select(
      'tb_datapribadi.*',
      'tb_agama.nama_agama as namaagamakar',
      'dokumen_ktp.File as file_ktp',
      'dokumen_npwp.File as file_npwp',
      'dokumen_no_rek.File as file_no_rek',
      'dokumen_jamsostek.File as file_jamsostek',
      'dokumen_bpjs.File as file_bpjs'
    )
      ->leftjoin('tb_agama', 'tb_datapribadi.Agama', '=', 'tb_agama.idagama')
      ->leftjoin('tb_dokumen AS dokumen_ktp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_ktp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_ktp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_ktp.Jenis', '=', '8');
      })
      ->leftjoin('tb_dokumen AS dokumen_npwp', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_npwp.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_npwp.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_npwp.Jenis', '=', '7');
      })
      ->leftjoin('tb_dokumen AS dokumen_no_rek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_no_rek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_no_rek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_no_rek.Jenis', '=', '11');
      })
      ->leftjoin('tb_dokumen AS dokumen_jamsostek', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_jamsostek.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_jamsostek.Jenis', '=', '10');
      })
      ->leftjoin('tb_dokumen AS dokumen_bpjs', function ($join) use ($nik) {
        $join->on(function ($query) {
          $query->on('dokumen_bpjs.NIK', '=', 'tb_datapribadi.NIK');
          $query->orWhere('dokumen_bpjs.NIK', '=', 'tb_datapribadi.old_nik');
        });
        $join->where('dokumen_bpjs.Jenis', '=', '9');
      })
      ->where('tb_datapribadi.NIK', $nik)
      ->first();
    $emailedi = str_replace("@edi-indonesia.co.id", "", $data->email);
    $agama = AgamaModel::orderBy('idagama', 'ASC')->get();
    $divisi = DivisiModel::orderBy('id', 'ASC')->where('TYPE', null)->get();
    $subdivisi = SubDivisiModel::orderBy('id', 'ASC')->where('TYPE', null)->get();
    $golongan = GolonganModel::orderBy('id', 'ASC')->where('TYPE', null)->get();
    $golonganout = GolonganOutModel::orderBy('id', 'ASC')->where('TYPE', null)->get();
    $jabatan = JabatanModel::orderBy('jabatan', 'ASC')->where('TYPE', null)->get();
    $jenjang = JenjangModel::orderBy('id_j', 'ASC')->get();
    $lokker = LokkerModel::orderBy('id', 'ASC')->get();
    // $pangkat = PangkatModel::orderBy('id','ASC')->where('TYPE',null)->get();
    $pangkat = PangkatModel::orderBy('urutan', 'ASC')->where('TYPE', null)->get();
    $statuskar = StatusKarModel::orderBy('id', 'ASC')->get();

    // dd($data->gaji);
    $gaji = $data->gaji;
    if ($gaji == null) {
      $gajifix = null;
    } else {
      $gajifix = Crypt::decrypt($gaji);
      $gajifix = "Rp. " . number_format($gajifix, 0, ',', '.');
    }

    $tunj_tmr = $data->tunj_tmr;
    if ($tunj_tmr == null) {
      $tunj_tmrfix = null;
    } else {
      $tunj_tmrfix = Crypt::decrypt($tunj_tmr);
      $tunj_tmrfix = "Rp. " . number_format($tunj_tmrfix, 0, ',', '.');
    }

    $tunj_jab = $data->tunj_jab;
    if ($tunj_jab == null) {
      $tunj_jabfix = null;
    } else {
      $tunj_jabfix = Crypt::decrypt($tunj_jab);
      $tunj_jabfix = "Rp. " . number_format($tunj_jabfix, 0, ',', '.');
    }

    // dd($gajifix);

    $ripek = RiwayatKerjaModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->count();
    if ($ripek > 0) {
      $showripek = RiwayatKerjaModel::where('nik', $nik)->orWhere('NIK', $data->old_nik)->orderBy('ID_rk', 'ASC')->get();
    } else {
      $showripek = null;
    }

    $riwayatpenyakit = RiwayatPenyakitModel::where('nik', $nik)->orWhere('nik', $data->old_nik)->count();
    if ($riwayatpenyakit > 0) {
      $showriwayatpenyakit = RiwayatPenyakitModel::where('nik', $nik)->orWhere('nik', $data->old_nik)->orderBy('id', 'ASC')->get();
    } else {
      $showriwayatpenyakit = null;
    }

    $ripen = PendidikanModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->count();
    if ($ripen > 0) {
      $showripen = PendidikanModel::select('tb_pendidikan.*', 'tb_dokumen.File as FileDokumen')
        ->leftjoin('tb_dokumen', 'tb_dokumen.id', '=', 'tb_pendidikan.id_dokumen')
        ->where('tb_pendidikan.NIK', $nik)->orWhere('tb_pendidikan.NIK', $data->old_nik)->orderBy('id', 'ASC')->get();
    } else {
      $showripen = null;
    }

    $ripennon = PendidikanNonModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->count();
    if ($ripennon > 0) {
      $showripennon = PendidikanNonModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id_pnf', 'ASC')->get();
    } else {
      $showripennon = null;
    }

    $orngtrdkt = OrangTerModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->count();
    if ($orngtrdkt > 0) {
      $showorangter = OrangTerModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id', 'ASC')->get();
    } else {
      $showorangter = null;
    }

    $kegiatan = KegiatanOrganisasiModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->count();
    if ($kegiatan > 0) {
      $showkegiatan = KegiatanOrganisasiModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->orderBy('id_org', 'ASC')->get();
    } else {
      $showkegiatan = null;
    }

    $anak = AnakModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)->count();
    if ($anak > 0) {
      $showanak = AnakModel::where('NIK', $nik)->orWhere('NIK', $data->old_nik)
        ->orderby('AnakKe', 'ASC')
        ->get();
    } else {
      $showanak = null;
    }

    // $sql = "SELECT nik, CONCAT(a.nik,' - ', a.nama, ' (', b.pangkat, ')') atasan
    //         FROM tb_datapribadi a LEFT JOIN tb_pangkat b ON a.idpangkat = b.id
    //         WHERE a.statuskar IN (1,2,3,4) AND a.resign = 'N' AND a.idpangkat IN (2,3,4,5,6,7,1948)
    //         ORDER BY a.idpangkat";
    // $atasan1 = DB::select($sql);
    // $sql2 = "SELECT nik, CONCAT(a.nik,' - ', a.nama, ' (', b.pangkat, ')') atasan
    //         FROM tb_datapribadi a LEFT JOIN tb_pangkat b ON a.idpangkat = b.id
    //         WHERE a.statuskar IN (1,2,4,3) AND a.resign = 'N' AND a.idpangkat IN (2,3,4,5,1948)
    //         ORDER BY a.idpangkat";
    // $atasan2 = DB::select($sql2);

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948,1951,1952,1954)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948,1951)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    return view('employee/editemployee')->with('agama', $agama)
      ->with('divisi', $divisi)
      ->with('subdivisi', $subdivisi)
      ->with('golongan', $golongan)
      ->with('golonganout', $golonganout)
      ->with('jabatan', $jabatan)
      ->with('jenjang', $jenjang)
      ->with('pangkat', $pangkat)
      ->with('lokker', $lokker)
      ->with('statuskar', $statuskar)
      ->with('atasan1', $atasan1)
      ->with('atasan2', $atasan2)
      ->with('ripek', $showripek)
      ->with('cripek', $ripek)
      ->with('data', $data)
      ->with('emailedi', $emailedi)
      ->with('riwayatpenyakit', $showriwayatpenyakit)
      ->with('ripen', $showripen)
      ->with('ripennon', $showripennon)
      ->with('kegiatan', $showkegiatan)
      ->with('anak', $showanak)
      ->with('orangterdekat', $showorangter)
      ->with('historyjab', $historyjab)
      ->with('gajifix', $gajifix)
      ->with('tunj_tmrfix', $tunj_tmrfix)
      ->with('tunj_jabfix', $tunj_jabfix)
      ->with('z', true);
  }
  public function FUNC_UPDATEEMPLOYEE(Request $request, $id)
  {

    $explode = explode('-', $id);
    $nik = $explode[0];
    $url = $explode[1];

    $symbol = array(".", "-", "_");
    $npwp = str_replace($symbol, "", $request['npwp']);

    $update = EmployeeModel::where('NIK', $nik)->first();
    $update->NIK = $request['nik'];
    $update->Nama = $request['nama'];
    $update->TempatLahir = $request['TempatLahir'];
    $update->TanggalLahir = $request['tglLahir'];
    $update->Agama = $request['agama'];
    $update->Alamat = $request['alamat'];
    $update->Alamat_KTP = $request['alamat_ktp'];
    $update->gol_darah = $request['GolonganDarah'];
    $update->status_pernikahan = $request['status_pernikahan'];
    $update->jk = $request['jk'];
    $update->npwp = $npwp;
    $update->nama_npwp = $request['nama_npwp'];
    $update->alamat_npwp = $request['alamat_npwp'];
    $update->ktp_sim = $request['ktp'];
    $update->norek = $request['norek'];
    $update->jamsostek = $request['jamsostek'];
    $update->bpjs = $request['bpjs'];
    $update->NoTelepon = $request['notelepon'];
    $update->NoHp = $request['NoHp'];
    $update->emailreg = $request['emailreg'];
    $update->email = $request['email'] . "@edi-indonesia.co.id";
    $update->noext = $request['noext'];

    // $update->statuskar = $request['status'];
    // $update->TglKontrak = $request['tglKontrak'];
    // $update->tglKontrakEnd = $request['tglKontrakEnd'];
    // $update->idpangkat = $request['pangkat'];
    // $update->idjabatan = $request['jabatan'];
    // $update->Divisi = $request['divisi'];
    // $update->SubDivisi = $request['subdivisi'];
    // if($request['Vendor'] == '') {
    //     $update->vendor = null;
    // } else {
    //     $update->vendor = $request['Vendor'];
    // }
    // if($request['Golongan'] == '') {
    //     $update->Golongan = null;
    // } else {
    //     $update->Golongan = $request['Golongan'];
    // }
    // if($request['Golongan_out'] == '') {
    //     $update->Golongan_out = null;
    // } else {
    //     $update->Golongan_out = $request['Golongan_out'];
    // }
    // $update->tgl_sk_gol = $request['TglGol'];
    // $update->tgl_sk_jab = $request['TglGol'];
    // $update->atasan1 = $request['atasan1'];
    // $update->atasan2 = $request['atasan2'];
    // $update->LokasiKer = $request['LokasiKer'];
    $arrayChange = $update->getDirty();
    $update->update();

    /** Add By Dandy Firmansyah 31 Maret 2019 **/

    // dokumen KTP
    $nik_send =  $request['nik'];
    $old_nik_send = KaryawanModel::where('NIK', $nik_send)->value('old_nik');
    if ($request->file('file_ktp') <> '') {
      // delete dulu
      $delete_ktp_lama = DokumenModel::where(function ($query) use ($nik_send, $old_nik_send) {
        $query->where('NIK', $nik_send);
        $query->orWhere('NIK', $old_nik_send);
      })
        ->where('Jenis', '8')
        ->delete();

      $file = $request->file('file_ktp');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'KTP_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_ktp')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_ktp = new DokumenModel;
      $tambah_ktp->NIK = $request['nik'];
      $tambah_ktp->Jenis = '8';
      $tambah_ktp->File = $fileName;
      $tambah_ktp->save();
    }

    if ($request->file('file_npwp') <> '') {
      // delete npwp lama
      $delete_npwp_lama = DokumenModel::where(function ($query) use ($nik_send, $old_nik_send) {
        $query->where('NIK', $nik_send);
        $query->orWhere('NIK', $old_nik_send);
      })
        ->where('Jenis', '7')
        ->delete();

      $file = $request->file('file_npwp');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'NPWP_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_npwp')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_npwp = new DokumenModel;
      $tambah_npwp->NIK = $request['nik'];
      $tambah_npwp->Jenis = '7';
      $tambah_npwp->File = $fileName;
      $tambah_npwp->save();
    }

    if ($request->file('file_no_rek') <> '') {
      // delete no rek lama
      $delete_no_rek_lama = DokumenModel::where(function ($query) use ($nik_send, $old_nik_send) {
        $query->where('NIK', $nik_send);
        $query->orWhere('NIK', $old_nik_send);
      })
        ->where('Jenis', '11')
        ->delete();

      $file = $request->file('file_no_rek');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'NoRek_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_no_rek')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_no_rek = new DokumenModel;
      $tambah_no_rek->NIK = $request['nik'];
      $tambah_no_rek->Jenis = '11';
      $tambah_no_rek->File = $fileName;
      $tambah_no_rek->save();
    }

    if ($request->file('file_jamsostek') <> '') {

      // delete no rek lama
      $delete_jamsostek_lama = DokumenModel::where(function ($query) use ($nik_send, $old_nik_send) {
        $query->where('NIK', $nik_send);
        $query->orWhere('NIK', $old_nik_send);
      })
        ->where('Jenis', '10')
        ->delete();

      $file = $request->file('file_jamsostek');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'Jamsostek_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_jamsostek')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_jamsostek = new DokumenModel;
      $tambah_jamsostek->NIK = $request['nik'];
      $tambah_jamsostek->Jenis = '10';
      $tambah_jamsostek->File = $fileName;
      $tambah_jamsostek->save();
    }

    if ($request->file('file_bpjs') <> '') {

      // delete no rek lama
      $delete_bpjs_lama = DokumenModel::where(function ($query) use ($nik_send, $old_nik_send) {
        $query->where('NIK', $nik_send);
        $query->orWhere('NIK', $old_nik_send);
      })
        ->where('Jenis', '9')
        ->delete();

      $file = $request->file('file_bpjs');
      $fileName = $file->getClientOriginalExtension();
      $fileName = 'BPJS_' . $request['nik'] . '_' . date('YmdHis') . '.' . $fileName;
      $request->file('file_bpjs')->move("image/Dokumen", $fileName);
      // $update->photo = $fileName;
      // add to table dokumen penting
      $tambah_bpjs = new DokumenModel;
      $tambah_bpjs->NIK = $request['nik'];
      $tambah_bpjs->Jenis = '9';
      $tambah_bpjs->File = $fileName;
      $tambah_bpjs->save();
    }

    /** End Add By Dandy Firmansyah 31 Maret 2019 **/

    $divisinama = EmployeeModel::select('tb_datapribadi.Nama', 'tbldivmaster.nama_div_ext')
      ->leftjoin('tbldivmaster', 'tbldivmaster.id', '=', 'tb_datapribadi.Divisi')
      ->where('tb_datapribadi.NIK', $nik)
      ->first();

    $namadivisi = $divisinama->nama_div_ext;

    $cek_tr_user = DB::table('tr_user')->where('NIK', $nik)->count();
    if ($cek_tr_user > 0) {
      $updateTUSER = DB::connection('mysql')->table('tr_user')->where('NIK', $nik)->update([
        'NIK' => $request['nik'],
        'NAMA' => $request['nama'],
        'ADMIN' => '2',
        'DIVISI' => $namadivisi,
        'EMAIL' => $request['email'] . "@edi-indonesia.co.id",
        'TELP' => $request['NoHp']
      ]);
    } else {
      $insertTUSER = DB::connection('mysql')->table('tr_user')->insert([
        [
          'NIK' => $request['nik'],
          'NAMA' => $request['nama'],
          'ADMIN' => '2',
          'DIVISI' => $namadivisi,
          'EMAIL' => $request['email'] . "@edi-indonesia.co.id",
          'TELP' => $request['NoHp']
        ]
      ]);
    }

    $nik = $request['nik'];

    $arrayChange['nik_yang_diganti'] = $nik;
    // insert to log_perubahan
    $this->email->insertlogperubahan($arrayChange);

    return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diupdate.')->with('a', true);
  }
  public function FUNC_UPDATEPICTURE(Request $request, $id)
  {

    $explode = explode('-', $id);
    $nik = $explode[0];
    $url = $explode[1];

    $nikses = Session::get('nik');
    $update = EmployeeModel::where('NIK', $nik)->first();


    if ($request->file('gambar') == '') {
      $update->photo = $update->photo;
    } else {
      $file = $request->file('gambar');
      $fileName = $file->getClientOriginalExtension();
      $fileName = $nik . '.' . $fileName;
      $request->file('gambar')->move("image/Photo", $fileName);
      $update->photo = $fileName;
    }

    $update->update();
    if ($nikses == $nik) {
      Session::put('photo', $fileName);
    } else {
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['Photo'] = 'Perubahan Foto Karyawan';

    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diupdate.')->with('b', true);
  }
  public function FUNC_UPDATEKELUARGA(Request $request, $id)
  {

    $explode = explode('-', $id);
    $nik = $explode[0];
    $url = $explode[1];

    $update = EmployeeModel::where('NIK', $nik)->first();
    $update->nama_bapak = $request['namabp'];
    $update->kerja_bapak = $request['pekerjaanbp'];
    $update->tmplhr_bapak = $request['TempatLahirbp'];
    $update->tgllhr_bapak = $request['tglLahirbp'];
    $update->alamat_bapak = $request['alamatbp'];
    $update->pnddk_bapak = $request['pendidikanbp'];
    $update->agama_bapak = $request['agamabp'];

    $update->nama_ibu = $request['namaib'];
    $update->kerja_ibu = $request['pekerjaanib'];
    $update->tmplhr_ibu = $request['TempatLahirib'];
    $update->tgllhr_ibu = $request['tglLahirib'];
    $update->alamat_ibu = $request['alamatib'];
    $update->pnddk_ibu = $request['pendidikanib'];
    $update->agama_ibu = $request['agamaib'];

    $update->NamaPasangan = $request['namais'];
    $update->jobpasangan = $request['pekerjaanis'];
    $update->TmplLahirPasangan = $request['TempatLahiris'];
    $update->TanggalLahirPasangan = $request['tglLahiris'];
    $update->pndkpasangan = $request['pendidikanis'];
    $update->agamapasangan = $request['agamais'];
    $update->update();

    $id = $request['id'];
    $nama = $request['nama'];
    $lahir = $request['lahir'];
    $jk = $request['jk'];
    $pendidikan = $request['pendidikan'];
    $namau = $request['namau'];
    $lahiru = $request['lahiru'];
    $jku = $request['jku'];
    $pendidikanu = $request['pendidikanu'];
    $for = 0;
    $go = null;
    if ($id) {
      foreach ($namau as $k => $namau) {
        $jju[$k] = $namau;
        $for++;
      }
    }
    $z = $for - 1;
    $a = 0;
    foreach ($nama as  $namas) {
      $jj[$a] = $namas;
      $for++;
      $z++;
      $a++;
      $id[$z] = null;
    }
    $v = 0;
    for ($i = 0; $i < $for; $i++) {
      if ($id) {
        if ($id[$i]) {
          $update = AnakModel::where('id', $id[$i])->first();
          $update->NamaAnak = $jju[$i];
          $update->TanggalAnak = $lahiru[$i];
          $update->jk = $jku[$i];
          $update->didikan = $pendidikanu[$i];
          $update->update();
          $go = 'ada';
        } else {
          if ($jj[$v] != '') {
            if ($jj[$v] != null) {
              $tambah = new AnakModel();
              $tambah->nik = $nik;
              $tambah->NamaAnak = $jj[$v];
              $tambah->TanggalAnak = $lahir[$v];
              $tambah->jk = $jk[$v];
              $tambah->didikan = $pendidikan[$v];
              $tambah->AnakKe = $i + 1;
              $tambah->save();
            }
          }
          $v++;
        }
      } else {
        if ($jj[$v] != '') {
          if ($jj[$v] != null) {
            $tambah = new AnakModel();
            $tambah->nik = $nik;
            $tambah->NamaAnak = $jj[$v];
            $tambah->TanggalAnak = $lahir[$v];
            $tambah->jk = $jk[$v];
            $tambah->didikan = $pendidikan[$v];
            $tambah->AnakKe = $i + 1;
            $tambah->save();
          }
        }
        $v++;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Keluarga';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diupdate.')->with('c', true);
  }

  public function FUNC_UPDATERIPEK(Request $request, $id)
  {

    $explode = explode('-', $id);
    $nik = $explode[0];
    $url = $explode[1];

    $ripek = RiwayatKerjaModel::where('NIK', $nik)->count();
    $go = null;
    //$url = URL::route('welcome') . '#hash';
    //return Redirect::to($url); // domain.com/welcome#hash
    for ($i = 1; $i <= 3; $i++) {
      if ($request['namaperu' . $i]) {
        $tambah = new RiwayatKerjaModel();
        $tambah->NIK = $nik;
        $tambah->NamaPerusahaan = $request['namaperu' . $i];
        $tambah->Jabatansblm = $request['jabatan' . $i];
        $tambah->Divsblm = $request['bagiandiv' . $i];
        $tambah->TanggalMasuksblm = $request['mulaikerja' . $i];
        $tambah->TanggalKeluarsblm = $request['sampaikerja' . $i];
        $tambah->AlamatPerusahaan = $request['alamatperu' . $i];
        $tambah->TelpPerusahaan = $request['teleponperu' . $i];
        $tambah->FaxPerusahaan = $request['faxperu' . $i];
        $tambah->Pendapatan = $request['pendapatan' . $i];
        $tambah->Fasilitas = $request['fasilitas' . $i];
        $tambah->save();
        $go = $i;
      }
    }
    for ($i = 1; $i <= 3; $i++) {
      if ($request['namaperu' . $i . 'u']) {
        $update = RiwayatKerjaModel::where('ID_rk', $request['id' . $i])->first();
        $update->NamaPerusahaan = $request['namaperu' . $i . 'u'];
        $update->Jabatansblm = $request['jabatan' . $i . 'u'];
        $update->Divsblm = $request['bagiandiv' . $i . 'u'];
        $update->TanggalMasuksblm = $request['mulaikerja' . $i . 'u'];
        $update->TanggalKeluarsblm = $request['sampaikerja' . $i . 'u'];
        $update->AlamatPerusahaan = $request['alamatperu' . $i . 'u'];
        $update->TelpPerusahaan = $request['teleponperu' . $i . 'u'];
        $update->FaxPerusahaan = $request['faxperu' . $i . 'u'];
        $update->Pendapatan = $request['pendapatan' . $i . 'u'];
        $update->Fasilitas = $request['fasilitas' . $i . 'u'];
        $update->update();
        $go = null;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Pekerjaan';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    if ($go) {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Ditambah.')->with('d', true);
    } else {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diupdate.')->with('d', true);
    }
  }

  public function FUNC_UPDATERIPEN(Request $request, $aaa)
  {

    $explode = explode('-', $aaa);
    $nik = $explode[0];
    $url = $explode[1];

    $id = $request['id'];
    $jenjang = $request['jenjang'];
    $nama = $request['nama'];
    $jurusan = $request['jurusan'];
    $masuk = $request['masuk'];
    $lulus = $request['lulus'];
    // $tingkat = $request['tingkat'];
    $ipk = $request['ipk'];
    $fileijazah = $request->file('fileijazah');
    $jenjangu = $request['jenjangu'];
    $namau = $request['namau'];
    $jurusanu = $request['jurusanu'];
    $masuku = $request['masuku'];
    $lulusu = $request['lulusu'];
    // $tingkatu = $request['tingkatu'];
    $ipku = $request['ipku'];
    $fileijazahu = $request->file('fileijazahu');
    $for = 0;
    $go = null;
    if ($id) {
      foreach ($jenjangu as $k => $jenjangu) {
        $jju[$k] = $jenjangu;
        $for++;
      }
    }
    $z = $for - 1;
    $a = 0;
    foreach ($jenjang as  $jenjangs) {
      $jj[$a] = $jenjangs;
      $for++;
      $z++;
      $a++;
      $id[$z] = null;
    }
    $v = 0;
    for ($i = 0; $i < $for; $i++) {
      if ($id) {
        if ($id[$i]) {
          $update = PendidikanModel::where('id', $id[$i])->first();
          $update->Jenjang = $jju[$i];
          $update->PeriodeIn = $masuku[$i];
          $update->PeriodeOut = $lulusu[$i];
          $update->Sekolah_Institut = $namau[$i];
          $update->Jurusan = $jurusanu[$i];
          // $update->TkTerakhir = $tingkatu[$i];
          $update->ipk = $ipku[$i];

          if ($fileijazahu[$i] == NULL) {
            $update->id_dokumen = $update->id_dokumen;
          } else {
            // check dokumen sebelumnya
            if ($update->id_dokumen) {
              $delete = DokumenModel::where('id', '=', $update->id_dokumen)->delete();
            }

            // get keterangan jenjang
            $jenjang_data = JenjangModel::where('id_j', $jju[$i])->first();
            $keterangan_jenjang = $jenjang_data->jenjang;
            $id_dokumen_ijazah = $jenjang_data->id_dokumen_ijazah;

            $file = $fileijazahu[$i];
            $fileExt = $file->getClientOriginalExtension();
            $fileName = $keterangan_jenjang . '-' . $nik . '-' . date('YmdHis') . '.' . $fileExt;
            $fileijazahu[$i]->move("image/Dokumen", $fileName);

            // tambah dokumen
            $tambah_dokumen = new DokumenModel();
            $tambah_dokumen->NIK = $nik;
            $tambah_dokumen->Jenis = $id_dokumen_ijazah;
            $tambah_dokumen->File = $fileName;
            $tambah_dokumen->save();
            $id_dokumen = $tambah_dokumen->id;

            $update->id_dokumen = $id_dokumen;
          }

          $update->update();
          $go = 'ada';
        } else {
          if ($jj[$v] != 0) {
            if ($jj[$v] != 1) {
              $tambah = new PendidikanModel();
              $tambah->NIK = $nik;
              $tambah->Jenjang = $jj[$v];
              $tambah->PeriodeIn = $masuk[$v];
              $tambah->PeriodeOut = $lulus[$v];
              $tambah->Sekolah_Institut = $nama[$v];
              $tambah->Jurusan = $jurusan[$v];
              // $tambah->TkTerakhir = $tingkat[$v];
              $tambah->ipk = $ipk[$v];
              if ($fileijazah[$v]) {

                // get keterangan jenjang
                $jenjang_data = JenjangModel::where('id_j', $jj[$v])->first();
                $keterangan_jenjang = $jenjang_data->jenjang;
                $id_dokumen_ijazah = $jenjang_data->id_dokumen_ijazah;

                // add to table dokumen
                $file = $fileijazah[$v];
                $fileExt = $file->getClientOriginalExtension();
                $fileName = $keterangan_jenjang . '-' . $nik . '-' . date('YmdHis') . '.' . $fileExt;
                $fileijazah[$v]->move("image/Dokumen", $fileName);

                $tambah_dokumen = new DokumenModel();
                $tambah_dokumen->NIK = $nik;
                $tambah_dokumen->Jenis = $id_dokumen_ijazah;
                $tambah_dokumen->File = $fileName;
                $tambah_dokumen->save();
                $id_dokumen = $tambah_dokumen->id;

                $tambah->id_dokumen = $id_dokumen;
              }

              $tambah->save();
            }
          }
          $v++;
        }
      } else {
        if ($jj[$v] != 0) {
          if ($jj[$v] != 1) {
            $tambah = new PendidikanModel();
            $tambah->NIK = $nik;
            $tambah->Jenjang = $jj[$v];
            $tambah->PeriodeIn = $masuk[$v];
            $tambah->PeriodeOut = $lulus[$v];
            $tambah->Sekolah_Institut = $nama[$v];
            $tambah->Jurusan = $jurusan[$v];
            // $tambah->TkTerakhir = $tingkat[$v];
            $tambah->ipk = $ipk[$v];

            if ($fileijazah[$v]) {
              // get keterangan jenjang
              $jenjang_data = JenjangModel::where('id_j', $jj[$v])->first();
              $keterangan_jenjang = $jenjang_data->jenjang;
              $id_dokumen_ijazah = $jenjang_data->id_dokumen_ijazah;

              // add to table dokumen
              $file = $fileijazah[$v];
              $fileExt = $file->getClientOriginalExtension();
              $fileName = $keterangan_jenjang . '-' . $nik . '-' . date('YmdHis') . '.' . $fileExt;
              $fileijazah[$v]->move("image/Dokumen", $fileName);

              $tambah_dokumen = new DokumenModel();
              $tambah_dokumen->NIK = $nik;
              $tambah_dokumen->Jenis = $id_dokumen_ijazah;
              $tambah_dokumen->File = $fileName;
              $tambah_dokumen->save();
              $id_dokumen = $tambah_dokumen->id;

              $tambah->id_dokumen = $id_dokumen;
            }

            $tambah->save();
          }
        }
        $v++;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Pendidikan (Tambah dan Update)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    if ($go) {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diedit.')->with('e', true);
    } else {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Ditambah.')->with('e', true);
    }
  }

  public function FUNC_DELETERIPEN($id)
  {
    $nik = PendidikanModel::where('id', $id)->value('NIK');

    $delete = PendidikanModel::find($id);
    $delete->delete();

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Pendidikan (Hapus)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect()->back()->with('success', 'Data Berhasil Didelete.')->with('e', true);
  }

  public function FUNC_UPDATERIWAYATPENYAKIT(Request $request, $aaa)
  {

    $explode = explode('-', $aaa);
    $nik = $explode[0];
    $url = $explode[1];

    $id = $request['id'];
    $tahun = $request['tahun'];
    $nama_penyakit = $request['nama_penyakit'];
    $dirawat = $request['dirawat'];
    $lama_rawat = $request['lama_rawat'];
    $cacat = $request['cacat'];
    $nama_penyakitu = $request['nama_penyakitu'];
    $tahunu = $request['tahunu'];
    $dirawatu = $request['dirawatu'];
    $lama_rawatu = $request['lama_rawatu'];
    $cacatu = $request['cacatu'];
    $for = 0;
    $go = null;
    if ($id) {
      foreach ($tahunu as $k => $tahunu) {
        $jju[$k] = $tahunu;
        $for++;
      }
    }
    $z = $for - 1;
    $a = 0;
    foreach ($tahun as  $tahuns) {
      $jj[$a] = $tahuns;
      $for++;
      $z++;
      $a++;
      $id[$z] = null;
    }
    $v = 0;
    for ($i = 0; $i < $for; $i++) {
      if ($id) {
        if ($id[$i]) {
          $update = RiwayatPenyakitModel::where('id', $id[$i])->first();
          $update->tahun = $jju[$i];
          $update->nama_penyakit = $nama_penyakitu[$i];
          $update->dirawat = $dirawatu[$i];
          $update->lama_rawat = $lama_rawatu[$i];
          $update->cacat = $cacatu[$i];
          $update->update();
          $go = 'ada';
        } else {
          if ($jj[$v] != 0) {
            if ($jj[$v] != 1) {
              $tambah = new RiwayatPenyakitModel();
              $tambah->nik = $nik;
              $tambah->tahun = $jj[$v];
              $tambah->nama_penyakit = $nama_penyakit[$v];
              $tambah->dirawat = $dirawat[$v];
              $tambah->lama_rawat = $lama_rawat[$v];
              $tambah->cacat = $cacat[$v];
              $tambah->save();
            }
          }
          $v++;
        }
      } else {
        if ($jj[$v] != 0) {
          if ($jj[$v] != 1) {
            $tambah = new RiwayatPenyakitModel();
            $tambah->nik = $nik;
            $tambah->tahun = $jj[$v];
            $tambah->nama_penyakit = $nama_penyakit[$v];
            $tambah->dirawat = $dirawat[$v];
            $tambah->lama_rawat = $lama_rawat[$v];
            $tambah->cacat = $cacat[$v];
            $tambah->save();
          }
        }
        $v++;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Penyakit (Tambah dan Update)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    if ($go) {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diedit.')->with('j', true);
    } else {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Ditambah.')->with('j', true);
    }
  }

  public function FUNC_DELETERIWAYATPENYAKIT($id)
  {
    $nik = RiwayatPenyakitModel::where('id', $id)->value('NIK');

    $delete = RiwayatPenyakitModel::find($id);
    $delete->delete();

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Penyakit (Hapus)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect()->back()->with('success', 'Data Berhasil Didelete.')->with('j', true);
  }

  public function FUNC_UPDATERIPENNON(Request $request, $aaa)
  {

    $explode = explode('-', $aaa);
    $nik = $explode[0];
    $url = $explode[1];

    $id = $request['id'];
    $nama = $request['nama'];
    $bidang = $request['bidang'];
    $tahun = $request['tahun'];
    $sertifikat = $request['sertifikat'];
    $filesertifikat = $request->file('filesertifikat');
    $namau = $request['namau'];
    $bidangu = $request['bidangu'];
    $tahunu = $request['tahunu'];
    $sertifikatu = $request['sertifikatu'];
    $filesertifikatu = $request->file('filesertifikatu');

    $for = 0;
    $go = null;
    if ($id) {
      foreach ($namau as $k => $namau) {
        $jju[$k] = $namau;
        $for++;
      }
    }
    $z = $for - 1;
    $a = 0;
    foreach ($nama as  $namas) {
      $jj[$a] = $namas;
      $for++;
      $z++;
      $a++;
      $id[$z] = null;
    }
    $v = 0;
    for ($i = 0; $i < $for; $i++) {
      if ($id) {
        if ($id[$i]) {
          $update = PendidikanNonModel::where('id_pnf', $id[$i])->first();
          $update->nama_kursus = $jju[$i];
          $update->keahlian = $bidangu[$i];
          $update->thikut = $tahunu[$i];
          $update->Sertifikat = $sertifikatu[$i];

          if ($filesertifikatu[$i] == NULL) {
            $update->FileSertifikat = $update->FileSertifikat;
          } else {
            $file = $filesertifikatu[$i];
            $fileExt = $file->getClientOriginalExtension();
            $fileName = 'Sertifikat-' . $nik . '-' . date('YmdHis') . '.' . $fileExt;
            $filesertifikatu[$i]->move("image/Sertifikat", $fileName);
            $update->FileSertifikat = $fileName;
          }

          $update->update();
          $go = 'ada';
        } else {
          if ($jj[$v] != '') {
            if ($jj[$v] != null) {
              $tambah = new PendidikanNonModel();
              $tambah->nik = $nik;
              $tambah->nama_kursus = $jj[$v];
              $tambah->keahlian = $bidang[$v];
              $tambah->thikut = $tahun[$v];
              $tambah->Sertifikat = $sertifikat[$v];

              if ($filesertifikat[$v]) {
                $file = $filesertifikat[$v];
                $fileExt = $file->getClientOriginalExtension();
                $fileName = 'Sertifikat-' . $nik . '-' . date('YmdHis') . '.' . $fileExt;
                $filesertifikat[$v]->move("image/Sertifikat", $fileName);
                $tambah->FileSertifikat = $fileName;
              }

              $tambah->save();
            }
          }
          $v++;
        }
      } else {
        if ($jj[$v] != '') {
          if ($jj[$v] != null) {
            $tambah = new PendidikanNonModel();
            $tambah->nik = $nik;
            $tambah->nama_kursus = $jj[$v];
            $tambah->keahlian = $bidang[$v];
            $tambah->thikut = $tahun[$v];
            $tambah->Sertifikat = $sertifikat[$v];

            if ($filesertifikat[$v]) {
              $file = $filesertifikat[$v];
              $fileExt = $file->getClientOriginalExtension();
              $fileName = 'Sertifikat-' . $nik . '-' . date('YmdHis') . '.' . $fileExt;
              $filesertifikatu[$i]->move("image/Sertifikat", $fileName);
              $tambah->FileSertifikat = $fileName;
            }

            $tambah->save();
          }
        }
        $v++;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Pendidikan Non Firmal (Tambah dan Update)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    if ($go) {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diedit.')->with('f', true);
    } else {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Ditambah.')->with('f', true);
    }
  }

  public function FUNC_DELETERIPENNON($id)
  {
    $nik = PendidikanNonModel::where('id', $id)->value('NIK');

    $delete = PendidikanNonModel::find($id);
    $delete->delete();

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Pendidikan Non Formal (Hapus)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect()->back()->with('success', 'Data Berhasil Didelete.')->with('f', true);
  }

  public function FUNC_UPDATEORANGTERDEKAT(Request $request, $aaa)
  {

    $explode = explode('-', $aaa);
    $nik = $explode[0];
    $url = $explode[1];

    $id = $request['id'];
    $nama = $request['nama'];
    $status = $request['status'];
    $telp = $request['telp'];
    $alamat = $request['alamat'];
    $namau = $request['namau'];
    $statusu = $request['statusu'];
    $telpu = $request['telpu'];
    $alamatu = $request['alamatu'];
    $for = 0;
    $go = null;
    if ($id) {
      foreach ($namau as $k => $namau) {
        $jju[$k] = $namau;
        $for++;
      }
    }
    $z = $for - 1;
    $a = 0;
    foreach ($nama as  $namas) {
      $jj[$a] = $namas;
      $for++;
      $z++;
      $a++;
      $id[$z] = null;
    }
    $v = 0;
    for ($i = 0; $i < $for; $i++) {
      if ($id) {
        if ($id[$i]) {
          $update = OrangTerModel::where('id', $id[$i])->first();
          $update->nama = $jju[$i];
          $update->status = $statusu[$i];
          $update->no_telp = $telpu[$i];
          $update->alamat = $alamatu[$i];
          $update->update();
          $go = 'ada';
        } else {
          if ($jj[$v] != '') {
            if ($jj[$v] != null) {
              $tambah = new OrangTerModel();
              $tambah->nik = $nik;
              $tambah->nama = $jj[$v];
              $tambah->status = $status[$v];
              $tambah->no_telp = $telp[$v];
              $tambah->alamat = $alamat[$v];
              $tambah->save();
            }
          }
          $v++;
        }
      } else {
        if ($jj[$v] != '') {
          if ($jj[$v] != null) {
            $tambah = new OrangTerModel();
            $tambah->nik = $nik;
            $tambah->nama = $jj[$v];
            $tambah->status = $status[$v];
            $tambah->no_telp = $telp[$v];
            $tambah->alamat = $alamat[$v];
            $tambah->save();
          }
        }
        $v++;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Orang Terdekat (Tambah dan Update)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    if ($go) {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diedit.')->with('i', true);
    } else {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Ditambah.')->with('i', true);
    }
  }

  public function FUNC_DELETEORANGTERDEKAT($id)
  {
    $nik = OrangTerModel::where('id', $id)->value('NIK');

    $delete = OrangTerModel::find($id);
    $delete->delete();

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Orang Terdekat (Hapus)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect()->back()->with('success', 'Data Berhasil Didelete.')->with('i', true);
  }

  public function FUNC_UPDATEKEGIATAN(Request $request, $aaa)
  {

    $explode = explode('-', $aaa);
    $nik = $explode[0];
    $url = $explode[1];

    $id = $request['id'];
    $namau = $request['namau'];
    $kedudukanu = $request['kedudukanu'];
    $masuku = $request['masuku'];
    $keluaru = $request['keluaru'];
    $alasanu = $request['alasanu'];
    $nama = $request['nama'];
    $kedudukan = $request['kedudukan'];
    $masuk = $request['masuk'];
    $keluar = $request['keluar'];
    $alasan = $request['alasan'];
    $for = 0;
    $go = null;
    if ($id) {
      foreach ($namau as $k => $namau) {
        $jju[$k] = $namau;
        $for++;
      }
    }
    $z = $for - 1;
    $a = 0;
    foreach ($nama as  $namas) {
      $jj[$a] = $namas;
      $for++;
      $z++;
      $a++;
      $id[$z] = null;
    }
    $v = 0;
    for ($i = 0; $i < $for; $i++) {
      if ($id) {
        if ($id[$i]) {
          $update = KegiatanOrganisasiModel::where('id_org', $id[$i])->first();
          $update->nama_organisasi = $jju[$i];
          $update->kedudukan = $kedudukanu[$i];
          $update->th_gabung = $masuku[$i];
          $update->th_berhenti = $keluaru[$i];
          $update->alshenti = $alasanu[$i];
          $update->update();
          $go = 'ada';
        } else {
          if ($jj[$v] != '') {
            if ($jj[$v] != null) {
              $tambah = new KegiatanOrganisasiModel();
              $tambah->NIK = $nik;
              $tambah->nama_organisasi = $jj[$v];
              $tambah->kedudukan = $kedudukan[$v];
              $tambah->th_gabung = $masuk[$v];
              $tambah->th_berhenti = $keluar[$v];
              $tambah->alshenti = $alasan[$v];
              $tambah->save();
            }
          }
          $v++;
        }
      } else {
        if ($jj[$v] != '') {
          if ($jj[$v] != null) {
            $tambah = new KegiatanOrganisasiModel();
            $tambah->NIK = $nik;
            $tambah->nama_organisasi = $jj[$v];
            $tambah->kedudukan = $kedudukan[$v];
            $tambah->th_gabung = $masuk[$v];
            $tambah->th_berhenti = $keluar[$v];
            $tambah->alshenti = $alasan[$v];
            $tambah->save();
          }
        }
        $v++;
      }
    }

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Organisasi (Tambah dan Update)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    if ($go) {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Diedit.')->with('g', true);
    } else {
      return redirect(url('editemployee', [$nik . '-' . $url]))->with('success', 'Data Berhasil Ditambah.')->with('g', true);
    }
  }

  public function FUNC_DELETEKEGIATAN($id)
  {
    $nik = KegiatanOrganisasiModel::where('id', $id)->value('NIK');

    $delete = KegiatanOrganisasiModel::find($id);
    $delete->delete();

    $arrayChange['nik_yang_diganti'] = $nik;
    $arrayChange['perubahan'] = 'Perubahan Data Riwayat Organisasi (Hapus)';
    $this->email->insertlogperubahan($arrayChange, 'flag');

    return redirect()->back()->with('success', 'Data Berhasil Didelete.')->with('h', true);
  }

  public function FUNC_DELETEANAK($id)
  {
    $delete = AnakModel::find($id);
    $delete->delete();

    return redirect()->back()->with('success', 'Data Berhasil Didelete.')->with('c', true);
  }


  public function FUNC_MUTASI($NIK)
  {

    $mutasiquery = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.nik',
      'tb_datapribadi.Nama as nama',
      'tb_his_jabatan.tgl_sk_jab as tanggal1',
      'tb_his_jabatan.tgl_sk_gol as tanggal2',
      'tb_his_jabatan.subdivisi as subdivisi',
      'tb_his_jabatan.atasan1 as atasan1',
      'tb_his_jabatan.atasan2 as atasan2',
      'tb_his_jabatan.gaji as gaji',
      'tb_his_jabatan.tunj_tmr as tunj_tmr',
      'tb_his_jabatan.tunj_jab as tunj_jab',
      'tb_his_jabatan.golongan as golonganmen',
      'tb_his_jabatan.golongan_out as golonganout',
      'tb_his_jabatan.TglKontrak as tgl_kontrak',
      'tb_his_jabatan.TglKontrakEnd as tgl_akhir_kontrak',
      'tb_his_jabatan.divisi as divisi',
      'tb_his_jabatan.lokasiker as lokasi',
      'tb_his_jabatan.statuskar as statuskar',
      'tb_his_jabatan.idpangkat as pangkat',
      'tb_his_jabatan.idjabatan as jabatanid'
      // DB::raw('CONCAT(tb_pangkat.pangkat,"-",tb_jabatan.jabatan) as jabatan')
      // DB::raw('CASE WHEN (select statuskar from tb_datapribadi where NIK = "'.$NIK.'") = "5" THEN (select gol from tb_golongan_outsource where id = tb_datapribadi.Golongan_out)
      //         WHEN ((select a.old_nik from tb_his_jabatan a where a.id = tb_his_jabatan.id) IS NULL AND (select statuskar from tb_datapribadi where NIK = tb_his_jabatan.nik) = "5" )
      //         THEN (Select id from tb_golongan_outsource where id = tb_his_jabatan.golongan_out)
      //         ELSE (select id from tb_golongan where id = tb_his_jabatan.golongan) END as golongan')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.NIK')
      ->leftjoin('tb_golongan', 'tb_his_jabatan.golongan', '=', 'tb_golongan.id')
      ->leftjoin('tb_golongan_outsource', 'tb_his_jabatan.golongan_out', '=', 'tb_golongan_outsource.id')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $NIK . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $NIK . '")'))
      ->orderBy('id', 'DESC')
      ->first();

    $tablehistory = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tgl_sk_jab',
      'tb_his_jabatan.no_sk',
      'tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'TglKontrak',
      'TglKontrakEnd',
      'tb_his_jabatan.gaji',
      'tb_his_jabatan.nik as nik',
      'tb_his_jabatan.id_mutasi',
      'tb_his_jabatan.no_sk',
      DB::raw('CONCAT(tb_pangkat.pangkat,"-",tb_jabatan.jabatan) as jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')

      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $NIK . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $NIK . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();


    foreach ($tablehistory as $tablehistorys) {
      if ($tablehistorys->gaji == null) {
        $gajimen = 'null';
      } else {
        $gaji = Crypt::decrypt($tablehistorys->gaji);
        $gajis = floatval($gaji);
        $gajimen = number_format($gajis, 0, ",", ".");
      }

      $coba[] = array('gaji' => $gajimen);
    }

    // print_r($coba[0]['gaji']);
    // dd('aaaa');

    // $atasan1 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    // $atasan2 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      // ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948,1951,1952,1954)'))
      ->whereRaw(DB::raw('(tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (' . $this->list_pangkat_atasan . '))'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (' . $this->list_pangkat_atasan . ')'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    // $pangkat = PangkatModel::where('type',NULL)->get();
    $pangkat = PangkatModel::orderBy('urutan', 'ASC')->where('TYPE', null)->get();
    $jabatan = JabatanModel::where('type', NULL)->get();
    $divisi = DivisiModel::where('type', NULL)->get();
    $subdivisi = SubDivisiModel::where('type', NULL)->get();
    $lokasikerja = LokkerModel::all();
    $golongan = GolonganModel::where('type', NULL)->get();
    $golonganout = GolonganOutModel::where('type', NULL)->get();
    $statuskar = StatusKarModel::all();

    $gaji = $mutasiquery->gaji;
    if ($gaji == null) {
      $gajifix = null;
    } else {
      $gajifix = Crypt::decrypt($gaji);
    }

    $tunj_tmr = $mutasiquery->tunj_tmr;
    if ($tunj_tmr == null) {
      $tunj_tmrfix = null;
    } else {
      $tunj_tmrfix = Crypt::decrypt($tunj_tmr);
    }

    $tunj_jab = $mutasiquery->tunj_jab;
    if ($tunj_jab == null) {
      $tunj_jabfix = null;
    } else {
      $tunj_jabfix = Crypt::decrypt($tunj_jab);
    }

    return view('employee/mutasi')
      ->with('pangkat', $pangkat)
      ->with('jabatan', $jabatan)
      ->with('divisi', $divisi)
      ->with('subdivisi', $subdivisi)
      ->with('lokasikerja', $lokasikerja)
      ->with('mutasiquery', $mutasiquery) //->with('mutasiquerys',$mutasiquerys)
      ->with('tablehistory', $tablehistory)
      ->with('golongan', $golongan)
      ->with('golonganout', $golonganout)
      ->with('atasan1', $atasan1)
      ->with('atasan2', $atasan2)
      ->with('statuskar', $statuskar)
      ->with('gaji', $gajifix)
      ->with('tunj_tmr', $tunj_tmrfix)
      ->with('tunj_jab', $tunj_jabfix)
      ->with('decgaji', $coba);
  }

  public function FUNC_SAVEMUTASI(Request $request)
  {

    //dd($request->all());

    if ($request['typemutasi'] == '1' or $request['typemutasi'] == '10' or $request['typemutasi'] == '11') {
      # code...
      $this->validate($request, [
        'NIK' => 'required'
      ]);
    }

    $this->validate($request, [
      //'NIK' => 'required',
      //'TglKontrak' => 'required',
      //'TglKontrakEnd' => 'required',
      //'tgl_sk_jab' => 'required',
      //'tgl_sk_gol' => 'required',
      // 'statuskar' => 'required',
      // 'idpangkat' => 'required',
      // 'idjabatan' => 'required',
      //'Golongan' => 'required',
      //'Golongan_out' => 'required',
      //'Divisi' => 'required',
      //'SubDivisi' => 'required',
      // 'typemutasi' => 'required',
      'no_sk' => 'required',
      'atasan1' => 'required',
      'atasan2' => 'required',
      'LokasiKer' => 'required',

    ]);

    if ($request['gaji'] == null or $request['gaji'] == 0) {
      $gaji = null;
    } else {
      $gaji = Crypt::encrypt($request['gaji']);
    }

    if ($request['tunj_tmr'] == null or $request['tunj_tmr'] == 0) {
      $tunj_tmr = null;
    } else {
      $tunj_tmr = Crypt::encrypt($request['tunj_tmr']);
    }

    if ($request['tunj_jab'] == null or $request['tunj_jab'] == 0) {
      $tunj_jab = null;
    } else {
      $tunj_jab = Crypt::encrypt($request['tunj_jab']);
    }

    $tambah = new HistoryJabModel();

    if ($request['typemutasi'] == '1' or $request['typemutasi'] == '10' or $request['typemutasi'] == '11') {

      if ($request['idpangkat'] == '2' or $request['idpangkat'] == '3' or $request['idpangkat'] == '1948') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = null;
        $tambah->SubDivisi = '1';
      } elseif ($request['idpangkat'] == '6' or $request['idpangkat'] == '7' or $request['idpangkat'] == '1952' or $request['idpangkat'] == '1954') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      } elseif ($request['idpangkat'] == '5' or $request['idpangkat'] == '1951') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = '1';
      } else {

        $tambah->idjabatan = $request['idjabatan'];
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      }

      $tambah->nik = $request['NIK'];
      $tambah->TglKontrak = $request['TglKontrak'];
      $tambah->TglKontrakEnd = $request['TglKontrakEnd'];
      $tambah->old_nik = $request['old_nik'];
      $tambah->tgl_sk_jab = $request['tgl_sk_jab']; // tglsk
      $tambah->tgl_sk_gol = $request['tgl_sk_gol']; // tgltmt
      $tambah->statuskar = $request['statuskar'];
      $tambah->idpangkat = $request['idpangkat'];
      $tambah->Golongan = $request['Golongan'];
      $tambah->Golongan_out = $request['Golongan_out'];
      $tambah->atasan1 = $request['atasan1'];
      $tambah->atasan2 = $request['atasan2'];
      $tambah->LokasiKer = $request['LokasiKer'];

      /** Add By Dandy Firmansyah 09 Oktober 2018 **/

      if ($request['old_nik'] != $request['NIK']) {

        // delete dulu data absen yang sudah terlanjur pakai NIK baru
        DB::table('absen_rekap')->where('nik', '=', $request['NIK'])->delete();
        DB::table('absen_log')->where('nik', '=', $request['NIK'])->delete();

        // update absen_rekap + absen_log
        DB::table('absen_rekap')
          ->where('nik', $request['old_nik'])
          ->update([
            'id'  => DB::raw('REPLACE(id, nik, "' . $request['NIK'] . '")'),
            'nik' => $request['NIK'],
          ]);

        DB::table('absen_log')
          ->where('nik', $request['old_nik'])
          ->update(['nik' => $request['NIK']]);

        // DB::table('absen_izin')
        //   ->where('nik', $request['old_nik'])
        //   ->update([
        //       'id'  => DB::raw('REPLACE(id, nik, "'.$request['NIK'].'")'),
        //       'nik' => $request['NIK'],
        //     ]);
      }

      /** End Add By Dandy Firmansyah 09 Oktober 2018 **/
    } else if ($request['typemutasi'] == '5' or $request['typemutasi'] == '3') {

      if ($request['idpangkat'] == '2' or $request['idpangkat'] == '3' or $request['idpangkat'] == '1948') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = null;
        $tambah->SubDivisi = '1';
      } elseif ($request['idpangkat'] == '6' or $request['idpangkat'] == '7' or $request['idpangkat'] == '1952' or $request['idpangkat'] == '1954') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      } elseif ($request['idpangkat'] == '5' or $request['idpangkat'] == '1951') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = '1';
      } else {

        $tambah->idjabatan = $request['idjabatan'];
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      }

      $tambah->nik = $request['old_nik'];
      $tambah->TglKontrak = $request['TglKontrak'];
      $tambah->TglKontrakEnd = $request['TglKontrakEnd'];
      $tambah->old_nik = $request['old_nik'];
      $tambah->tgl_sk_jab = $request['tgl_sk_jab'];
      $tambah->tgl_sk_gol = $request['tgl_sk_gol'];
      $tambah->statuskar = $request['statuskar'];
      $tambah->idpangkat = $request['idpangkat'];
      $tambah->Golongan = $request['Golongan'];
      $tambah->Golongan_out = $request['Golongan_out'];
      $tambah->atasan1 = $request['atasan1'];
      $tambah->atasan2 = $request['atasan2'];
      $tambah->LokasiKer = $request['LokasiKer'];
    } else if ($request['typemutasi'] == '7' or $request['typemutasi'] == '6') {

      if ($request['pangkat7'] == '2' or $request['pangkat7'] == '3' or $request['pangkat7'] == '1948') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = null;
        $tambah->SubDivisi = '1';
      } elseif ($request['pangkat7'] == '6' or $request['pangkat7'] == '7' or $request['pangkat7'] == '1952' or $request['pangkat7'] == '1954') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      } elseif ($request['pangkat7'] == '5' or $request['pangkat7'] == '1951') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = '1';
      } else {

        $tambah->idjabatan = $request['jabatan7'];
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      }

      $tambah->nik = $request['old_nik'];
      $tambah->TglKontrak = $request['TglKontrak'];
      $tambah->TglKontrakEnd = $request['TglKontrakEnd'];
      $tambah->old_nik = $request['old_nik'];
      $tambah->tgl_sk_jab = $request['tgl_sk_jab'];
      $tambah->tgl_sk_gol = $request['tgl_sk_gol'];
      $tambah->statuskar = $request['statuskep7'];
      $tambah->idpangkat = $request['pangkat7'];
      $tambah->Golongan = $request['golongan7'];
      $tambah->Golongan_out = $request['golonganout7'];
      $tambah->atasan1 = $request['atasan1'];
      $tambah->atasan2 = $request['atasan2'];
      $tambah->LokasiKer = $request['LokasiKer'];
    } else {

      if ($request['idpangkat'] == '2' or $request['idpangkat'] == '3' or $request['idpangkat'] == '1948') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = null;
        $tambah->SubDivisi = '1';
      } elseif ($request['idpangkat'] == '6' or $request['idpangkat'] == '7' or $request['idpangkat'] == '1952' or $request['idpangkat'] == '1954') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      } elseif ($request['idpangkat'] == '5' or $request['idpangkat'] == '1951') {

        $tambah->idjabatan = '1';
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = '1';
      } else {

        $tambah->idjabatan = $request['idjabatan'];
        $tambah->Divisi = $request['Divisi'];
        $tambah->SubDivisi = $request['SubDivisi'];
      }

      $tambah->nik = $request['old_nik'];
      $tambah->TglKontrak = $request['TglKontrak'];
      $tambah->TglKontrakEnd = $request['TglKontrakEnd'];
      $tambah->old_nik = $request['old_nik'];
      $tambah->tgl_sk_jab = $request['tgl_sk_jab'];
      $tambah->tgl_sk_gol = $request['tgl_sk_gol'];
      $tambah->statuskar = $request['statuskar'];
      $tambah->idpangkat = $request['idpangkat'];
      $tambah->Golongan = $request['Golongan'];
      $tambah->Golongan_out = $request['Golongan_out'];
      $tambah->atasan1 = $request['atasan1'];
      $tambah->atasan2 = $request['atasan2'];
      $tambah->LokasiKer = $request['LokasiKer'];
    }

    //if ($request['NIK'] != NULL) {

    $nomorkaryawanbro = $request['old_nik'];

    $update = EmployeeModel::where('NIK', $nomorkaryawanbro)->first();

    // $namacoba = $update->Nama;
    //dd($namacoba);
    //$update = DB::statement('select Nama from tb_datapribadi where `NIK` = "');

    //dd($update);

    if ($request['typemutasi'] == '1' or $request['typemutasi'] == '10' or $request['typemutasi'] == '11') {
      if ($request['idpangkat'] == '2' or $request['idpangkat'] == '3' or $request['idpangkat'] == '1948') {

        $update->idjabatan = '1';
        $update->Divisi = null;
        $update->SubDivisi = '1';
      } elseif ($request['idpangkat'] == '6' or $request['idpangkat'] == '7' or $request['idpangkat'] == '1952' or $request['idpangkat'] == '1954') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      } elseif ($request['idpangkat'] == '5' or $request['idpangkat'] == '1951') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = '1';
      } else {
        $update->idjabatan = $request['idjabatan'];
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      }

      $update->NIK = $request['NIK'];
      $update->old_nik = $request['old_nik'];
      $update->TglKontrak = $request['TglKontrak'];
      $update->TglKontrakEnd = $request['TglKontrakEnd'];
      $update->tgl_sk_jab = $request['tgl_sk_jab'];
      $update->tgl_sk_gol = $request['tgl_sk_gol'];
      $update->statuskar = $request['statuskar'];
      $update->idpangkat = $request['idpangkat'];
      $update->Golongan = $request['Golongan'];
      $update->Golongan_out = $request['Golongan_out'];
      $update->atasan1 = $request['atasan1'];
      $update->atasan2 = $request['atasan2'];
      $update->LokasiKer = $request['LokasiKer'];
    } elseif ($request['typemutasi'] == '5' or $request['typemutasi'] == '3') {

      if ($request['idpangkat'] == '2' or $request['idpangkat'] == '3' or $request['idpangkat'] == '1948') {

        $update->idjabatan = '1';
        $update->Divisi = null;
        $update->SubDivisi = '1';
      } elseif ($request['idpangkat'] == '6' or $request['idpangkat'] == '7' or $request['idpangkat'] == '1952' or $request['idpangkat'] == '1954') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      } elseif ($request['idpangkat'] == '5' or $request['idpangkat'] == '1951') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = '1';
      } else {
        $update->idjabatan = $request['idjabatan'];
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      }

      $update->NIK = $request['old_nik'];
      $update->tgl_sk_jab = $request['tgl_sk_jab'];
      $update->tgl_sk_gol = $request['tgl_sk_gol'];
      $update->statuskar = $request['statuskar'];
      $update->idpangkat = $request['idpangkat'];
      $update->Golongan = $request['Golongan'];
      $update->Golongan_out = $request['Golongan_out'];
      $update->atasan1 = $request['atasan1'];
      $update->atasan2 = $request['atasan2'];
      $update->LokasiKer = $request['LokasiKer'];
    } elseif ($request['typemutasi'] == '7' or $request['typemutasi'] == '6') {

      if ($request['pangkat7'] == '2' or $request['pangkat7'] == '3' or $request['pangkat7'] == '1948') {

        $update->idjabatan = '1';
        $update->Divisi = null;
        $update->SubDivisi = '1';
      } elseif ($request['pangkat7'] == '6' or $request['pangkat7'] == '7' or $request['pangkat7'] == '1952' or $request['pangkat7'] == '1954') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      } elseif ($request['pangkat7'] == '5' or $request['pangkat7'] == '1951') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = '1';
      } else {
        $update->idjabatan = $request['jabatan7'];
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      }

      $update->NIK = $request['old_nik'];
      $update->tgl_sk_jab = $request['tgl_sk_jab'];
      $update->tgl_sk_gol = $request['tgl_sk_gol'];
      $update->statuskar = $request['statuskep7'];
      $update->idpangkat = $request['pangkat7'];
      $update->Golongan = $request['golongan7'];
      $update->Golongan_out = $request['golonganout7'];
      $update->atasan1 = $request['atasan1'];
      $update->atasan2 = $request['atasan2'];
      $update->LokasiKer = $request['LokasiKer'];
    } else {

      if ($request['idpangkat'] == '2' or $request['idpangkat'] == '3' or $request['idpangkat'] == '1948') {

        $update->idjabatan = '1';
        $update->Divisi = null;
        $update->SubDivisi = '1';
      } elseif ($request['idpangkat'] == '6' or $request['idpangkat'] == '7' or $request['idpangkat'] == '1952' or $request['idpangkat'] == '1954') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      } elseif ($request['idpangkat'] == '5' or $request['idpangkat'] == '1951') {

        $update->idjabatan = '1';
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = '1';
      } else {
        $update->idjabatan = $request['idjabatan'];
        $update->Divisi = $request['Divisi'];
        $update->SubDivisi = $request['SubDivisi'];
      }

      $update->NIK = $request['old_nik'];
      $update->TglKontrak = $request['TglKontrak'];
      $update->TglKontrakEnd = $request['TglKontrakEnd'];
      $update->tgl_sk_jab = $request['tgl_sk_jab'];
      $update->tgl_sk_gol = $request['tgl_sk_gol'];
      $update->statuskar = $request['statuskar'];
      $update->idpangkat = $request['idpangkat'];
      $update->Golongan = $request['Golongan'];
      $update->Golongan_out = $request['Golongan_out'];
      $update->atasan1 = $request['atasan1'];
      $update->atasan2 = $request['atasan2'];
      $update->LokasiKer = $request['LokasiKer'];
    }

    $tambah->gaji = $gaji;
    $update->gaji = $gaji;
    $tambah->tunj_tmr = $tunj_tmr;
    $update->tunj_tmr = $tunj_tmr;
    $tambah->tunj_jab = $tunj_jab;
    $update->tunj_jab = $tunj_jab;

    $direktur_1 = EmployeeModel::select('NIK')->where('idpangkat', '1962')->where('idjabatan', '2169')->where('resign', 'N')->first();
    $dir_1 = $direktur_1->NIK;
    $direktur_2 = EmployeeModel::select('NIK')->where('idpangkat', '1962')->where('idjabatan', '2170')->where('resign', 'N')->first();
    if (!isset($direktur_2)) {
      $dir_2 = '';
    } else {
      $dir_2 = $direktur_2->NIK;
    }

    $tambah->direktur_1 = $dir_1;
    $tambah->direktur_2 = $dir_2;

    $tambah->id_mutasi = $request['typemutasi'];
    $tambah->no_sk = $request['no_sk'];

    if ($request['typemutasi'] == '1') {
      $nikuntukbawah = $request['NIK'];
    } else {
      $nikuntukbawah = $request['old_nik'];
    }

    // $pangkat_tinggi = array(2,3,1948,4,5,1951,6,1952,7,1954);
    $pangkat_tinggi = array(2, 3, 1948, 4, 5, 1951, 6, 1952);
    if (in_array($request['idpangkat'], $pangkat_tinggi)) {
      if ($request['idpangkat'] == 5 or $request['idpangkat'] == 1951) {
        $showkarbiasa = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('Divisi', $request['Divisi'])
          ->where('resign', 'N')
          ->whereRaw('idpangkat NOT IN(2,3,1948,4,5,1951,6,1952,7,1954)')
          ->get();

        foreach ($showkarbiasa as $showkarbiasas) {
          list($namabro, $nikbro) = explode('#', $showkarbiasas->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro)->first();
          if ($hisjab) {
            $hisjab->atasan2 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan2 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan2 = $nikuntukbawah;
            $updateEmp->update();
          }
        }

        $showkaryVPAVP = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('Divisi', $request['Divisi'])
          ->where('resign', 'N')
          ->whereRaw('idpangkat IN (6,7,1952,1954)')
          ->get();

        foreach ($showkaryVPAVP as $showkaryVPAVPs) {
          list($namabro2, $nikbro2) = explode('#', $showkaryVPAVPs->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro2)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro2)->first();
          if ($hisjab) {
            $hisjab->atasan1 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          }
        }
      } elseif ($request['idpangkat'] == 3) {
        /** Edit By Dandy Firmansyah 11 Desember 2019 **/
        $showkarSVP = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('resign', 'N')
          ->whereRaw('idpangkat IN (5,1951)')
          ->whereRaw('Divisi IN (6,7)')
          ->get();
        foreach ($showkarSVP as $showkarSVPs) {
          list($namabro3, $nikbro3) = explode('#', $showkarSVPs->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro3)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro3)->first();
          if ($hisjab) {
            $hisjab->atasan1 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          }
        }
      } elseif ($request['idpangkat'] == 1948) { // dir kom
        /** Edit By Dandy Firmansyah 11 Desember 2019 **/
        $showkarSVP = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('resign', 'N')
          ->whereRaw('idpangkat IN (5,1951)')
          ->whereRaw('Divisi IN (8,1952,13,1953)')
          ->get();
        foreach ($showkarSVP as $showkarSVPs) {
          list($namabro4, $nikbro4) = explode('#', $showkarSVPs->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro4)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro4)->first();
          if ($hisjab) {
            $hisjab->atasan1 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          }
        }
      } elseif ($request['idpangkat'] == 2) {
        $showkarSVP = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('resign', 'N')
          ->whereRaw('idpangkat IN (5,1951)')
          ->whereRaw('Divisi IN (1935,3,5,10,1949,1950,1951,1956,1949,1954)')
          ->get();
        foreach ($showkarSVP as $showkarSVPs) {
          list($namabro5, $nikbro5) = explode('#', $showkarSVPs->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro5)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro5)->first();
          if ($hisjab) {
            $hisjab->atasan2 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan2 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan2 = $nikuntukbawah;
            $updateEmp->update();
          }
        }

        $showDirKeuDirKom = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('resign', 'N')
          ->whereRaw('idpangkat IN (3,1948)')
          ->get();
        foreach ($showDirKeuDirKom as $showDirKeuDirKoms) {
          list($namabro6, $nikbro6) = explode('#', $showDirKeuDirKoms->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro6)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro6)->first();
          if ($hisjab) {
            $hisjab->atasan1 = $nikuntukbawah;
            $hisjab->atasan2 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->atasan2 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->atasan2 = $nikuntukbawah;
            $updateEmp->update();
          }
        }
        // }elseif ($request['idpangkat'] == 6 or $request['idpangkat'] == 7 or $request['idpangkat'] == 1952 or $request['idpangkat'] == 1954) {
      } elseif ($request['idpangkat'] == 6 or $request['idpangkat'] == 1952) {
        $showkarbiasa = EmployeeModel::select('tb_datapribadi.*', DB::raw('CONCAT(Nama,"#",NIK) as namaformat'))
          ->where('Divisi', $request['Divisi'])
          ->where('SubDivisi', $request['SubDivisi'])
          ->where('resign', 'N')
          ->whereRaw('idpangkat NOT IN(2,3,1948,4,5,1951,6,1952)')
          ->get();

        foreach ($showkarbiasa as $showkarbiasas) {
          list($namabro7, $nikbro7) = explode('#', $showkarbiasas->namaformat);
          $hisjab = HistoryJabModel::where('nik', $nikbro7)->orderby('id', 'DESC')->first();
          $updateEmp = EmployeeModel::where('NIK', $nikbro7)->first();
          if ($hisjab) {
            $hisjab->atasan1 = $nikuntukbawah;
            $hisjab->update();

            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          } else {
            $updateEmp->atasan1 = $nikuntukbawah;
            $updateEmp->update();
          }
        }
      }
    }


    $tambah->save();
    $update->update();

    // UPDATE DATABASE PRODEV
    if ($request['typemutasi'] == 1 or $request['typemutasi'] == '10' or $request['typemutasi'] == '11') {
      $nik_prodev = $request['NIK'];
    } else {
      $nik_prodev = $request['old_nik'];
    }

    //untuk if user role

    if ($request['idpangkat'] == '2' || $request['idpangkat'] == '3' || $request['idpangkat'] == '4' || $request['idpangkat'] == '5' || $request['idpangkat'] == '1948' || $request['idpangkat'] == '1951') {
      $user_role = '2';
    } elseif ($request['idpangkat'] == '6' || $request['idpangkat'] == '7' || $request['idpangkat'] == '1952' || $request['idpangkat'] == '1954') {
      $user_role = '3';
    } else {
      if ($request['Divisi'] == '1937') {
        $user_role = '5';
      } else {
        $user_role = '4';
      }
    }

    $update_prodev = DB::connection('prodev')->table('user_app')
      ->where('nik', $request['old_nik'])
      ->update([
        'nik' => $nik_prodev,
        'id_pangkat' => $request['idjabatan'],
        'user_role' => $user_role,
        'status_kar' => $request['statuskar']
      ]);

    // END UPDATE DATABASE PRODEV

    return redirect('employeelist')->with('success', 'Data Berhasil Disimpan');
  }

  public function FUNC_HISTORYJABATAN($NIK)
  {

    $historyquery = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.nik',
      'tb_datapribadi.Nama as nama'
    )
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.NIK')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $NIK . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $NIK . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->first();

    $tablehistory = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.no_sk',
      'tgl_sk_jab',
      'tgl_sk_gol',
      'tb_lokasikerja.lokasi as lokasi',
      'tbldivmaster.nama_div_ext as divisi',
      'TglKontrak',
      'TglKontrakEnd',
      'old_nik as nik_lama',
      'tb_his_jabatan.gaji as gaji',

      DB::raw('CONCAT(tb_pangkat.pangkat,"-",tb_jabatan.jabatan) as jabatan'),
      DB::raw('CASE WHEN statuskar = "5" THEN (SELECT gol FROM tb_golongan_outsource WHERE id = tb_his_jabatan.golongan) ELSE (SELECT gol FROM tb_golongan WHERE id = tb_his_jabatan.golongan) END as Gol')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')

      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $NIK . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $NIK . '")'))
      ->orderBy('tb_his_jabatan.id', 'DESC')
      ->get();

    foreach ($tablehistory as $tablehistorys) {
      if ($tablehistorys->gaji == null) {
        $gajimen = 'null';
      } else {
        $gaji = Crypt::decrypt($tablehistorys->gaji);
        $gajis = floatval($gaji);
        $gajimen = number_format($gajis, 0, ",", ".");
      }

      $coba[] = array('gaji' => $gajimen);
    }

    // $atasan1 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    // $atasan2 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (' . $this->list_pangkat_atasan . ')'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (' . $this->list_pangkat_atasan . ')'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    // $pangkat = PangkatModel::where('type',NULL)->get();
    $pangkat = PangkatModel::orderBy('urutan', 'ASC')->where('TYPE', null)->get();

    $jabatan = JabatanModel::where('type', NULL)->get();

    $divisi = DivisiModel::where('type', NULL)->get();

    $subdivisi = SubDivisiModel::where('type', NULL)->get();

    $lokasikerja = LokkerModel::all();
    $golongan = GolonganModel::where('type', NULL)->get();

    $golonganout = GolonganOutModel::where('type', NULL)->get();

    $statuskar = StatusKarModel::all();

    return view('employee/historyjabatan')
      ->with('pangkat', $pangkat)
      ->with('jabatan', $jabatan)
      ->with('divisi', $divisi)
      ->with('subdivisi', $subdivisi)
      ->with('lokasikerja', $lokasikerja)
      ->with('historyquery', $historyquery)
      ->with('tablehistory', $tablehistory)
      ->with('golongan', $golongan)
      ->with('golonganout', $golonganout)
      ->with('atasan1', $atasan1)
      ->with('atasan2', $atasan2)
      ->with('statuskar', $statuskar)
      ->with('decgaji', $coba);
  }

  public function FUNC_SAVEHISTORY(Request $request)
  {

    //dd($request->all());
    if ($request['gaji2'] == null or $request['gaji2'] == 0) {
      $gaji = null;
    } else {
      $gaji = Crypt::encrypt($request['gaji2']);
    }

    if ($request['tunj_tmr2'] == null or $request['tunj_tmr2'] == 0) {
      $tunj_tmr = null;
    } else {
      $tunj_tmr = Crypt::encrypt($request['tunj_tmr2']);
    }

    if ($request['tunj_jab2'] == null or $request['tunj_jab2'] == 0) {
      $tunj_jab = null;
    } else {
      $tunj_jab = Crypt::encrypt($request['tunj_jab2']);
    }
    //dd($request['radiolain']);
    $nikmen = $request['nik_baru'];

    if ($request['radiolain1'] == 'lainpangkat') {
      $this->validate($request, [
        'pangkatbaru' => 'required'
      ]);
    }
    if ($request['radiolain1'] == 'lainpangkat') {
      $this->validate($request, [
        'pangkatbaru' => 'required',

      ]);
    }
    if ($request['radiolain2'] == 'lainjabatan') {
      $this->validate($request, [
        'jabatanbaru' => 'required'
      ]);
    }
    if ($request['radiolain3'] == 'laingolongan') {
      $this->validate($request, [
        'golonganbaru' => 'required'
      ]);
    }
    if ($request['radiolain4'] == 'laingolonganout') {
      $this->validate($request, [
        'golonganoutbaru' => 'required'
      ]);
    }
    if ($request['radiolain5'] == 'laindivisi') {
      $this->validate($request, [
        'divisibaru' => 'required'
      ]);
    }
    if ($request['radiolain6'] == 'lainsubdivisi') {
      $this->validate($request, [
        'subdivisibaru' => 'required'
      ]);
    }
    if ($request['radiogol'] == '1') {
      $this->validate($request, [
        'Golongan_out' => 'required'
      ]);
    }
    if ($request['radiogol'] == '2') {
      $this->validate($request, [
        'Golongan' => 'required'
      ]);
    }


    $tambahpangkat = new PangkatModel();
    $tambahjabatan = new JabatanModel();
    $tambahgolongan = new GolonganModel();
    $tambahgolonganout = new GolonganOutModel();
    $tambahdivisi = new DivisiModel();
    $tambahsubdivisi = new SubDivisiModel();
    $tambahhistory = new HistoryJabModel();


    if ($request['radiolain1'] == 'lainpangkat') {

      $pangkatinput = $request['pangkatbaru'];
      $cekpangkat = PangkatModel::where('pangkat', $pangkatinput)->count();

      if ($cekpangkat > 0) {

        $pangkatkembar = DB::table('tb_pangkat')
          ->where('pangkat', $pangkatinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $pangkatkembars = $pangkatkembar->id;
        # code...
        $tambahhistory->idpangkat = $pangkatkembars;
        $tambahhistory->idjabatan = $request['idjabatan'];
        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];
        $tambahhistory->golongan = $request['Golongan'];
        $tambahhistory->golongan_out = $request['Golongan_out'];
      } else {
        //dd('ggggggg');
        $tambahpangkat->pangkat = $request['pangkatbaru'];
        $tambahpangkat->type = 'his';
        $tambahpangkat->save();


        //$idpangkatlast = DB::table('tb_pangkat')->max('id');

        $tambahhistory->idpangkat = $tambahpangkat->id;
        $tambahhistory->idjabatan = $request['idjabatan'];
        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];
        $tambahhistory->golongan = $request['Golongan'];
        $tambahhistory->golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain2'] == 'lainjabatan') {

      $jabataninput = $request['jabatanbaru'];
      $cekjabatan = JabatanModel::where('jabatan', $jabataninput)->count();




      if ($cekjabatan > 0) {

        $jabatankembar = DB::table('tb_jabatan')
          ->where('jabatan', $jabataninput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $jabatankembars = $jabatankembar->id;

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {
          $tambahhistory->idpangkat = $request['idpangkat'];
        }

        $tambahhistory->idjabatan = $jabatankembars;
        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];
        $tambahhistory->golongan = $request['Golongan'];
        $tambahhistory->golongan_out = $request['Golongan_out'];
      } else {


        $tambahjabatan->jabatan = $request['jabatanbaru'];
        $tambahjabatan->type = 'his';
        $tambahjabatan->save();

        //$idpangkatlast = DB::table('tb_pangkat')->max('id');

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {
          $tambahhistory->idpangkat = $request['idpangkat'];
        }

        $tambahhistory->idjabatan = $tambahjabatan->id;
        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];
        $tambahhistory->golongan = $request['Golongan'];
        $tambahhistory->golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain3'] == 'laingolongan') {

      $golonganinput = $request['golonganbaru'];
      $cekgolongan = GolonganModel::where('gol', $golonganinput)->count();




      if ($cekgolongan > 0) {

        $golongankembar = DB::table('tb_golongan')
          ->where('gol', $golonganinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $golongankembars = $golongankembar->id;

        if ($tambahhistory->idpangkat) {

          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];
        $tambahhistory->golongan = $golongankembars;
        $tambahhistory->golongan_out = $request['Golongan_out'];
      } else {

        $tambahgolongan->gol = $request['golonganbaru'];
        $tambahgolongan->type = 'his';
        $tambahgolongan->save();

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];
        $tambahhistory->golongan = $tambahgolongan->id;
        $tambahhistory->golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain4'] == 'laingolonganout') {

      $golonganoutinput = $request['golonganoutbaru'];
      $cekgolonganout = GolonganOutModel::where('gol', $golonganoutinput)->count();


      if ($cekgolonganout > 0) {

        $golonganoutkembar = DB::table('tb_golongan_outsource')
          ->where('gol', $golonganoutinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $golonganoutkembars = $golonganoutkembar->id;

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        if ($tambahhistory->golongan) {
          $tambahhistory->golongan = $tambahhistory->golongan;
        } else {
          $tambahhistory->golongan = $request['Golongan'];
        }


        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];

        $tambahhistory->golongan_out = $golonganoutkembars;
      } else {

        $tambahgolonganout->gol = $request['golonganoutbaru'];
        $tambahgolonganout->type = 'his';
        $tambahgolonganout->save();


        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        if ($tambahhistory->golongan) {
          $tambahhistory->golongan = $tambahhistory->golongan;
        } else {
          $tambahhistory->golongan = $request['Golongan'];
        }


        $tambahhistory->divisi = $request['Divisi'];
        $tambahhistory->subdivisi = $request['SubDivisi'];

        $tambahhistory->golongan_out = $tambahgolonganout->id;
      }
    }

    if ($request['radiolain5'] == 'laindivisi') {

      $divisiinput = $request['divisibaru'];
      $cekdivisi = DivisiModel::where('nama_div_ext', $divisiinput)->count();

      if ($cekdivisi > 0) {

        $divisikembar = DB::table('tbldivmaster')
          ->where('nama_div_ext', $divisiinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $divisikembars = $divisikembar->id;

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        if ($tambahhistory->golongan) {
          $tambahhistory->golongan = $tambahhistory->golongan;
        } else {
          $tambahhistory->golongan = $request['Golongan'];
        }

        if ($tambahhistory->golongan_out) {
          $tambahhistory->golongan_out = $tambahhistory->golongan_out;
        } else {
          $tambahhistory->golongan_out = $request['Golongan_out'];
        }


        $tambahhistory->divisi = $divisikembars;
        $tambahhistory->subdivisi = $request['SubDivisi'];
      } else {

        $tambahdivisi->nama_div_ext = $request['divisibaru'];
        $tambahdivisi->type = 'his';
        $tambahdivisi->save();

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        if ($tambahhistory->golongan) {
          $tambahhistory->golongan = $tambahhistory->golongan;
        } else {
          $tambahhistory->golongan = $request['Golongan'];
        }

        if ($tambahhistory->golongan_out) {
          $tambahhistory->golongan_out = $tambahhistory->golongan_out;
        } else {
          $tambahhistory->golongan_out = $request['Golongan_out'];
        }


        $tambahhistory->divisi = $tambahdivisi->id;
        $tambahhistory->subdivisi = $request['SubDivisi'];
      }
    }

    if ($request['radiolain6'] == 'lainsubdivisi') {

      $subdivisiinput = $request['subdivisibaru'];
      $ceksubdivisi = SubDivisiModel::where('subdivisi', $subdivisiinput)->count();




      if ($ceksubdivisi > 0) {

        $subdivisikembar = DB::table('tb_subdivisi')
          ->where('subdivisi', $subdivisiinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $subdivisikembars = $subdivisikembar->id;

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        if ($tambahhistory->golongan) {
          $tambahhistory->golongan = $tambahhistory->golongan;
        } else {
          $tambahhistory->golongan = $request['Golongan'];
        }

        if ($tambahhistory->golongan_out) {
          $tambahhistory->golongan_out = $tambahhistory->golongan_out;
        } else {
          $tambahhistory->golongan_out = $request['Golongan_out'];
        }

        if ($tambahhistory->divisi) {
          $tambahhistory->divisi = $tambahhistory->divisi;
        } else {
          $tambahhistory->divisi = $request['Divisi'];
        }

        $tambahhistory->subdivisi = $tambahsubdivisi->id;
      } else {

        $tambahsubdivisi->subdivisi = $request['subdivisibaru'];
        $tambahsubdivisi->type = 'his';
        $tambahsubdivisi->save();

        if ($tambahhistory->idpangkat) {
          $tambahhistory->idpangkat = $tambahhistory->idpangkat;
        } else {

          $tambahhistory->idpangkat = $request['idpangkat'];
        }


        if ($tambahhistory->idjabatan) {
          $tambahhistory->idjabatan = $tambahhistory->idjabatan;
        } else {
          $tambahhistory->idjabatan = $request['idjabatan'];
        }

        if ($tambahhistory->golongan) {
          $tambahhistory->golongan = $tambahhistory->golongan;
        } else {
          $tambahhistory->golongan = $request['Golongan'];
        }

        if ($tambahhistory->golongan_out) {
          $tambahhistory->golongan_out = $tambahhistory->golongan_out;
        } else {
          $tambahhistory->golongan_out = $request['Golongan_out'];
        }

        if ($tambahhistory->divisi) {
          $tambahhistory->divisi = $tambahhistory->divisi;
        } else {
          $tambahhistory->divisi = $request['Divisi'];
        }

        $tambahhistory->subdivisi = $tambahsubdivisi->id;
      }
    }

    if (($request['radiolain1'] != 'lainpangkat') and
      ($request['radiolain2'] != 'lainjabatan') and
      ($request['radiolain3'] != 'laingolongan') and
      ($request['radiolain4'] != 'laingolonganout') and
      ($request['radiolain5'] != 'laindivisi') and
      ($request['radiolain6'] != 'lainsubdivisi')
    ) {

      $tambahhistory->idpangkat = $request['idpangkat'];
      $tambahhistory->idjabatan = $request['idjabatan'];
      $tambahhistory->divisi = $request['Divisi'];
      $tambahhistory->subdivisi = $request['SubDivisi'];
      $tambahhistory->golongan = $request['Golongan'];
      $tambahhistory->golongan_out = $request['Golongan_out'];
    }
    // else  {

    //     $tambahhistory->idpangkat = $request['idpangkat'];
    //     $tambahhistory->idjabatan = $request['idjabatan'];
    //     $tambahhistory->divisi = $request['Divisi'];
    //     $tambahhistory->subdivisi = $request['SubDivisi'];
    //     $tambahhistory->golongan = $request['Golongan'];
    //     $tambahhistory->Golongan_out = $request['Golongan_out'];
    // }


    $tambahhistory->nik = $request['nik_baru'];
    $tambahhistory->old_nik = $request['nik_lama'];
    $tambahhistory->tgl_sk_jab = $request['tgl_sk'];
    $tambahhistory->tgl_sk_gol = $request['tgl_tmt'];
    $tambahhistory->statuskar = $request['statuskar'];


    $tambahhistory->atasan1 = $request['atasan1'];
    $tambahhistory->atasan2 = $request['atasan2'];
    $tambahhistory->lokasiker = $request['LokasiKer'];
    $tambahhistory->gaji = $gaji;
    $tambahhistory->tunj_tmr = $tunj_tmr;
    $tambahhistory->tunj_jab = $tunj_jab;
    $tambahhistory->no_sk = $request['no_sk'];


    $direktur_1 = EmployeeModel::select('NIK')->where('idpangkat', '2')->where('resign', 'N')->first();
    $dir_1 = $direktur_1->NIK;
    $direktur_2 = EmployeeModel::select('NIK')->where('idpangkat', '3')->where('resign', 'N')->first();
    $dir_2 = $direktur_2->NIK;

    $tambahhistory->direktur_1 = $dir_1;
    $tambahhistory->direktur_2 = $dir_2;

    // $tambahpangkat->save();
    // $tambahjabatan->save();
    // $tambahgolonganout->save();
    // $tambahgolongan->save();
    // $tambahdivisi->save();
    // $tambahsubdivisi->save();
    $tambahhistory->save();



    // return redirect('employeelist')->with('success','Data Berhasil Disimpan');
    return redirect(url('historyjabatan', [$nikmen]))->with('success', 'Data Berhasil Disimpan.');
    // return redirect('employeelist');
  }

  public function FUNC_PROJECTEXPERIENCE($id)
  {

    // dd($id);

    $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahundropdowns = DB::select($tahundropdown);

    $statuskaryawan = StatusKarModel::select('id', 'status_kar')->get();

    $headerpe =  HeaderPEModel::select('tb_headproj.id as idhead', 'tb_headproj.nik as nikhead', 'tb_datapribadi.Nama as namahead', 'tb_datapribadi.TempatLahir', 'tb_datapribadi.TanggalLahir', 'tb_headproj.posisi as posisihead', 'tb_headproj.didikan', 'tb_headproj.didikannf', 'tb_headproj.bhs', 'tb_projectexp.id', 'tb_projectexp.id_head', 'tb_projectexp.nik as nikpe', 'tb_projectexp.nama', 'tb_projectexp.lokasi', 'tb_projectexp.pengguna', 'tb_projectexp.perusahaan', 'tb_projectexp.uraian_tugas', 'tb_projectexp.waktu_pelaksanaan', 'tb_projectexp.posisi', 'tb_projectexp.status')
      ->leftjoin('tb_projectexp', 'tb_headproj.id', '=', 'tb_projectexp.id_head')
      ->leftjoin('tb_datapribadi', 'tb_headproj.nik', '=', 'tb_datapribadi.NIK')
      ->where('tb_headproj.id', $id)
      ->first();

    $nik = $headerpe->nikhead;
    $idbro = $headerpe->idhead;

    // dd($idbro);
    $tablemen = ProjectExModel::select('tb_projectexp.*', 'tb_statuskar.status_kar as statusbro')->where('tb_projectexp.id_head', $idbro)
      ->leftjoin('tb_statuskar', 'tb_projectexp.status', '=', 'tb_statuskar.id')
      ->get();

    return view('employee/projectexperience')->with('headerpe', $headerpe)
      ->with('tahundropdowns', $tahundropdowns)
      ->with('statuskaryawan', $statuskaryawan)
      ->with('tablemen', $tablemen)
      ->with('z', true)
      ->with('id', $id);
  }

  public function FUNC_PERPANJANGKONTRAK($NIK)
  {
    $resigndata = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.nik',
      'tb_datapribadi.Nama as nama'
    )

      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.NIK')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $NIK . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $NIK . '")'))
      ->orderBy('id', 'DESC')
      ->first();
    $tablekontrak = KontrakModel::select(
      'tb_kontrak.nik',
      'tb_kontrak.id',
      'tb_kontrak.tgl_kontrak',
      'tb_kontrak.akhir_kontrak',
      'tb_kontrak.level',
      'tb_kontrak.gaji',
      'tb_golongan_outsource.gol as golongan_out'
    )
      ->leftjoin('tb_golongan_outsource', 'tb_golongan_outsource.id', '=', 'tb_kontrak.golongan_out')
      // ->leftjoin('tb_datapribadi','tb_datapribadi.nik','=','tb_kontrak.nik')

      ->whereRaw(DB::raw('tb_kontrak.nik = "' . $NIK . '" or tb_kontrak.nik = (select old_nik from tb_datapribadi where NIK = "' . $NIK . '")'))
      ->get();

    $golonganouts = GolonganOutModel::where('type', NULL)->get();

    return view('employee/perpanjangkontrak')->with('resigndata', $resigndata)->with('tablekontrak', $tablekontrak)->with('golonganouts', $golonganouts);
  }

  public function FUNC_SAVEPERPANJANGKONTRAK(Request $request)
  {

    //menggunakan update data pribadi dan data history jabatan biasa

    $this->validate($request, [

      'tgl_kontrak' => 'required',
      'akhir_kontrak' => 'required',
      'golongan_out' => 'required',
      // 'gaji1' => 'required',

    ]);

    $tambahkontrak = new KontrakModel();

    $tambahkontrak->nik = $request['NIK'];
    $tambahkontrak->tgl_kontrak = $request['tgl_kontrak'];
    $tambahkontrak->akhir_kontrak = $request['akhir_kontrak'];
    // $tambahkontrak->level = $request['level'];
    $tambahkontrak->golongan_out = $request['golongan_out'];

    if ($request['gaji2'] == null or $request['gaji2'] == 0) {
      $gaji = null;
    } else {
      $gaji = Crypt::encrypt($request['gaji2']);
    }

    $tambahkontrak->gaji = $gaji;

    $tambahkontrak->save();

    $id_kontrak = $tambahkontrak->id;
    // dd($id_kontrak);

    $nikkar = $request['NIK'];
    $updatedatakaryawan = EmployeeModel::where('NIK', $nikkar)->first();

    $updatedatakaryawan->TglKontrak = $request['tgl_kontrak'];
    $updatedatakaryawan->TglKontrakEnd = $request['akhir_kontrak'];
    $updatedatakaryawan->Golongan_out = $request['golongan_out'];
    $updatedatakaryawan->gaji = $gaji;

    $updatedatakaryawan->update();

    $tambahhistory = new HistoryJabModel();
    $tampilhistoryjab = HistoryJabModel::where('nik', $nikkar)->orderBy('id', 'DESC')->first();


    $tambahhistory->nik = $tampilhistoryjab->nik;
    $tambahhistory->tgl_sk_jab = $tampilhistoryjab->tgl_sk_jab;
    $tambahhistory->tgl_sk_gol = $tampilhistoryjab->tgl_sk_gol;
    $tambahhistory->statuskar = $tampilhistoryjab->statuskar;
    $tambahhistory->idpangkat = $tampilhistoryjab->idpangkat;
    $tambahhistory->idjabatan = $tampilhistoryjab->idjabatan;
    $tambahhistory->divisi = $tampilhistoryjab->divisi;
    $tambahhistory->subdivisi = $tampilhistoryjab->subdivisi;
    $tambahhistory->golongan = $tampilhistoryjab->golongan;
    $tambahhistory->golongan_out = $request['golongan_out'];
    $tambahhistory->atasan1 = $tampilhistoryjab->atasan1;
    $tambahhistory->atasan2 = $tampilhistoryjab->atasan2;
    $tambahhistory->lokasiker = $tampilhistoryjab->lokasiker;
    $tambahhistory->old_nik = $tampilhistoryjab->old_nik;
    $tambahhistory->TglKontrak = $request['tgl_kontrak'];
    $tambahhistory->TglKontrakEnd = $request['akhir_kontrak'];
    $tambahhistory->id_kontrak = $id_kontrak;
    $tambahhistory->gaji = $gaji;

    $tambahhistory->save();

    // return redirect('employeelist')->with('success','Data Berhasil Disimpan');
    return redirect()->back()->with('success', 'Data Berhasil Disimpan.');
  }

  public function FUNC_RESIGNEMPLOYEE($nik)
  {

    $resigndata = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.nik',
      'tb_datapribadi.Nama as nama'

    )
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.NIK')
      ->whereRaw(DB::raw('tb_his_jabatan.nik = "' . $nik . '" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "' . $nik . '")'))
      ->orderby('tb_his_jabatan.id', 'desc')
      ->first();

    return view('employee/resignemployee')->with('resigndata', $resigndata);
  }

  public function FUNC_SAVERESIGN(Request $request)
  {

    $nik = $request['NIK'];
    $direktur_1 = EmployeeModel::select('NIK')
      ->where('idpangkat', '1962')
      ->where('idjabatan', '2169')
      ->where('resign', 'N')
      ->first();

    $dir_1 = $direktur_1->NIK;
    $direktur_2 = EmployeeModel::select('NIK')
      ->where('idpangkat', '1962')
      ->where('idjabatan', '2170')
      ->where('resign', 'N')
      ->first();

    if ($direktur_2) {
      $dir_2 = $direktur_2->NIK;
    } else {
      $dir_2 = 'not exist';
    }

    $update = EmployeeModel::where('NIK', $nik)->first();

    $update->tgl_out = $request['tgl_out'];
    // $update->tgl_pengajuan_out = $request['tgl_pengajuan_out'];
    $update->tgl_pengajuan_out = $request['tgl_pengajuan_out'];
    $update->alasan_out = $request['alasan_out'];
    if ($request['detail_out'] == '' or $request['detail_out'] == NULL) {
      $update->detail_out = $request['detail_out2'];
    } else {
      $update->detail_out = $request['detail_out'];
    }
    $update->resign = "Y";
    $update->surat_pemberhentian = $request['no_sk'];

    $update->dir_1_resign = $dir_1;
    $update->dir_2_resign = $dir_2;

    $update->update();

    // $update_prodev = DB::connection('prodev')->table('user_app')
    //                     ->where('nik', $nik)
    //                     ->update([
    //                               'resign' => 'Y'
    //                             ]);

    return redirect('employeelist')->with('success', 'Data Berhasil Disimpan');
  }

  public function FUNC_EDITKONTRAK($id)
  {

    $tampilkontrak = DB::table('tb_kontrak')
      ->select('id', 'nik', 'tgl_kontrak', 'akhir_kontrak', 'level', 'gaji')
      ->where('tb_kontrak.id', $id)
      ->first();

    return view('employee/editkontrak')->with('tampilkontrak', $tampilkontrak);
  }

  public function FUNC_SAVEKONTRAK(Request $request)
  {

    $NIK = $request['nik'];

    $updatedatapribadi = EmployeeModel::where('NIK', $NIK)->first();

    $updatedatapribadi->TglKontrak = $request['tgl_kontrak'];
    $updatedatapribadi->TglKontrakEnd = $request['akhir_kontrak'];

    $updatedatapribadi->update();

    $updatehistoryjab = HistoryJabModel::where('nik', $NIK)->orderBy('id', 'DESC')->first();
    $updatehistoryjab->TglKontrak = $request['tgl_kontrak'];
    $updatehistoryjab->TglKontrakEnd = $request['akhir_kontrak'];

    $updatehistoryjab->update();

    $id = $request['id'];

    $updatekontrak = KontrakModel::where('id', $id)->first();
    $updatekontrak->tgl_kontrak = $request['tgl_kontrak'];
    $updatekontrak->akhir_kontrak = $request['akhir_kontrak'];
    $updatekontrak->gaji = $request['gaji'];
    $updatekontrak->level = $request['level'];

    $updatekontrak->update();

    // return redirect()->back();

    return redirect('employeelist')->with('success', 'Data Berhasil Disimpan');
  }

  public function FUNC_DELETEKONTRAK($id)
  {

    $niks = KontrakModel::select('nik')->where('id', $id)->first();
    $nik = $niks->nik;

    $deletekontrak = KontrakModel::find($id);
    $deletekontrak->delete();

    $deletehisjab = HistoryJabModel::where('id_kontrak', $id);
    $deletehisjab->delete();

    $cari = HistoryJabModel::select('TglKontrak', 'TglKontrakEnd', 'golongan_out')->where('nik', $nik)->orderby('id', 'desc')->first();
    $tglkontrak = $cari->TglKontrak;
    $TglKontrakEnd = $cari->TglKontrakEnd;
    $golonganout = $cari->golongan_out;

    // dd($tglkontrak);

    $updatedatakaryawan = EmployeeModel::where('NIK', $nik)->first();

    $updatedatakaryawan->TglKontrak = $tglkontrak;
    $updatedatakaryawan->TglKontrakEnd = $TglKontrakEnd;
    $updatedatakaryawan->Golongan_out = $golonganout;

    $updatedatakaryawan->update();

    return redirect()->back();
  }

  public function FUNC_EDITHISTORY($id)
  {

    $nikquery = HistoryJabModel::where('id', $id)->first();
    $NIK = $nikquery->nik;

    // dd($NIK);
    $tampilhistory = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.nik',
      'tb_datapribadi.Nama as nama',
      'tb_his_jabatan.old_nik as nik_lama',
      'tb_his_jabatan.gaji as gaji',
      'tb_his_jabatan.tunj_jab as tunj_jab',
      'tb_his_jabatan.tunj_tmr as tunj_tmr',
      'tb_his_jabatan.tgl_sk_jab as tanggal1',
      'tb_his_jabatan.tgl_sk_gol as tanggal2',
      'tb_his_jabatan.subdivisi as subdivisi',
      'tb_his_jabatan.golongan as golonganid',
      'tb_his_jabatan.golongan_out as golonganoutid',
      'tb_golongan.gol as nama_golongan',
      'tb_golongan_outsource.gol as nama_golonganout',
      'tb_pangkat.pangkat as nama_pangkat',
      'tb_jabatan.jabatan as nama_jabatan',
      'tbldivmaster.nama_div_ext as nama_divisi',
      'tb_subdivisi.subdivisi as nama_subdivisi',
      'tb_pangkat.type as typepangkat',
      'tb_jabatan.type as typejabatan',
      'tb_golongan.type as typegolongan',
      'tb_golongan_outsource.type as typegolonganout',
      'tbldivmaster.type as typedivisi',
      'tb_subdivisi.type as typesubdivisi',
      'tb_his_jabatan.atasan1 as atasan1',
      'tb_his_jabatan.atasan2 as atasan2',
      'tb_his_jabatan.no_sk',
      'tb_his_jabatan.TglKontrak as tgl_kontrak',
      'tb_his_jabatan.TglKontrakEnd as tgl_akhir_kontrak',
      'tb_his_jabatan.divisi as divisi',
      'tb_his_jabatan.lokasiker as lokasi',
      'tb_his_jabatan.statuskar as statuskar',
      'tb_his_jabatan.idpangkat as pangkat',
      'tb_his_jabatan.idjabatan as jabatanid',
      DB::raw('CONCAT(tb_pangkat.pangkat,"-",tb_jabatan.jabatan) as jabatan'),
      DB::raw('CASE WHEN (select statuskar from tb_datapribadi where NIK = "' . $NIK . '") = "5" THEN (select gol from tb_golongan_outsource where id = tb_datapribadi.Golongan_out)
                                            WHEN ((select a.old_nik from tb_his_jabatan a where a.id = tb_his_jabatan.id) IS NULL AND (select statuskar from tb_datapribadi where NIK = tb_his_jabatan.nik) = "5" )
                                            THEN (Select id from tb_golongan_outsource where id = tb_his_jabatan.golongan)
                                            ELSE (select id from tb_golongan where id = tb_his_jabatan.golongan) END as golongan'),
      DB::raw('(select Nama from tb_datapribadi where old_nik = "' . $NIK . '" or NIK = "' . $NIK . '") as nama_karyawan')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_subdivisi', 'tb_his_jabatan.subdivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.NIK')
      ->leftjoin('tb_golongan', 'tb_his_jabatan.golongan', '=', 'tb_golongan.id')
      ->leftjoin('tb_golongan_outsource', 'tb_his_jabatan.golongan_out', '=', 'tb_golongan_outsource.id')
      // ->whereRaw(DB::raw('tb_his_jabatan.nik = "'.$NIK.'" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "'.$NIK.'")'))
      ->where('tb_his_jabatan.id', $id)
      // ->whereRaw(DB::raw('tb_his_jabatan.nik = "'.$NIK.'" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "'.$NIK.'")'))
      ->orderBy('id', 'asc')
      ->first();

    // $atasan1 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    // $atasan2 = EmployeeModel::select('nik','nama',
    //                       DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    //                       )
    //                       ->leftjoin('tb_pangkat','tb_datapribadi.idpangkat','=','tb_pangkat.id')
    //                       ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948)'))
    //                       ->orderby('tb_datapribadi.idpangkat','ASC')
    //                       ->get();

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,6,7,1948,1951,1952,1954)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (2,3,4,5,1948,1951)'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();



    // $pangkat = PangkatModel::where('type',NULL)->get();
    $pangkat = PangkatModel::orderBy('urutan', 'ASC')->where('TYPE', null)->get();
    $jabatan = JabatanModel::where('type', NULL)->get();
    $divisi = DivisiModel::where('type', NULL)->get();
    $subdivisi = SubDivisiModel::where('type', NULL)->get();
    $lokasikerja = LokkerModel::all();
    $golongan = GolonganModel::where('type', NULL)->get();
    $golonganout = GolonganOutModel::where('type', NULL)->get();
    $statuskar = StatusKarModel::all();

    $gaji = $tampilhistory->gaji;
    if ($gaji == null) {
      $gajifix = null;
    } else {
      $gajifix = Crypt::decrypt($gaji);
    }

    $tunj_tmr = $tampilhistory->tunj_tmr;
    if ($tunj_tmr == null) {
      $tunj_tmrfix = null;
    } else {
      $tunj_tmrfix = Crypt::decrypt($tunj_tmr);
    }

    $tunj_jab = $tampilhistory->tunj_jab;
    if ($tunj_jab == null) {
      $tunj_jabfix = null;
    } else {
      $tunj_jabfix = Crypt::decrypt($tunj_jab);
    }

    // dd($tampilhistory);

    return view('employee/edithistory')
      ->with('tampilhistory', $tampilhistory)
      ->with('pangkat', $pangkat)
      ->with('jabatan', $jabatan)
      ->with('divisi', $divisi)
      ->with('subdivisi', $subdivisi)
      ->with('lokasikerja', $lokasikerja)
      // ->with('mutasiquery',$mutasiquery)//->with('mutasiquerys',$mutasiquerys)
      // ->with('tablehistory',$tablehistory)
      ->with('golongan', $golongan)
      ->with('golonganout', $golonganout)
      ->with('atasan1', $atasan1)
      ->with('atasan2', $atasan2)
      ->with('statuskar', $statuskar)
      ->with('gaji', $gajifix)
      ->with('tunj_tmr', $tunj_tmrfix)
      ->with('tunj_jab', $tunj_jabfix);
  }

  public function FUNC_UPDATEHISTORY(Request $request)
  {

    // dd($request->all());
    $nikmen = $request['nik_baru'];
    $id = $request['id'];

    if ($request['gaji'] == null or $request['gaji'] == 0) {
      $gaji = null;
    } else {
      $gaji = Crypt::encrypt($request['gaji']);
    }

    $updatehistory = HistoryJabModel::where('id', $id)->first();

    // if ($request['radiolain1'] == 'lainpangkat') {
    //         $this->validate($request, [
    //         'pangkatbaru' =>'required'
    //     ]);

    //     } if ($request['radiolain1'] == 'lainpangkat')  {
    //         $this->validate($request, [
    //         'pangkatbaru' => 'required',

    //     ]);
    //     } if ($request['radiolain2'] == 'lainjabatan') {
    //         $this->validate($request, [
    //         'jabatanbaru' =>'required'
    //     ]);
    //     } if ($request['radiolain3'] == 'laingolongan') {
    //         $this->validate($request, [
    //         'golonganbaru' =>'required'
    //     ]);
    //     } if ($request['radiolain4'] == 'laingolonganout') {
    //         $this->validate($request, [
    //         'golonganoutbaru' =>'required'
    //     ]);
    //     } if ($request['radiolain5'] == 'laindivisi') {
    //         $this->validate($request, [
    //         'divisibaru' =>'required'
    //     ]);
    //     } if ($request['radiolain6'] == 'lainsubdivisi') {
    //         $this->validate($request, [
    //         'subdivisibaru' =>'required'
    //     ]);
    //     } if ($request['radiogol'] == '1') {
    //         $this->validate($request, [
    //         'Golongan_out' => 'required'
    //     ]);
    //     } if ($request['radiogol'] == '2') {
    //         $this->validate($request, [
    //         'Golongan' => 'required'
    //     ]);
    //     }

    $tambahpangkat = new PangkatModel();
    $tambahjabatan = new JabatanModel();
    $tambahgolongan = new GolonganModel();
    $tambahgolonganout = new GolonganOutModel();
    $tambahdivisi = new DivisiModel();
    $tambahsubdivisi = new SubDivisiModel();



    if ($request['radiolain1'] == 'lainpangkat') {

      $pangkatinput = $request['pangkatbaru'];
      $cekpangkat = PangkatModel::where('pangkat', $pangkatinput)->count();

      if ($cekpangkat > 0) {

        $pangkatkembar = DB::table('tb_pangkat')
          ->where('pangkat', $pangkatinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $pangkatkembars = $pangkatkembar->id;
        # code...
        $updatehistory->idpangkat = $pangkatkembars;
        $updatehistory->idjabatan = $request['idjabatan'];
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];
      } else {
        //dd('ggggggg');
        $tambahpangkat->pangkat = $request['pangkatbaru'];
        $tambahpangkat->type = 'his';
        $tambahpangkat->save();


        //$idpangkatlast = DB::table('tb_pangkat')->max('id');

        $updatehistory->idpangkat = $tambahpangkat->id;
        $updatehistory->idjabatan = $request['idjabatan'];
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain2'] == 'lainjabatan') {

      $jabataninput = $request['jabatanbaru'];
      $cekjabatan = JabatanModel::where('jabatan', $jabataninput)->count();




      if ($cekjabatan > 0) {

        $jabatankembar = DB::table('tb_jabatan')
          ->where('jabatan', $jabataninput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $jabatankembars = $jabatankembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {
          $updatehistory->idpangkat = $request['idpangkat'];
        }

        $updatehistory->idjabatan = $jabatankembars;
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];
      } else {


        $tambahjabatan->jabatan = $request['jabatanbaru'];
        $tambahjabatan->type = 'his';
        $tambahjabatan->save();

        //$idpangkatlast = DB::table('tb_pangkat')->max('id');

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {
          $updatehistory->idpangkat = $request['idpangkat'];
        }

        $updatehistory->idjabatan = $tambahjabatan->id;
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain3'] == 'laingolongan') {

      $golonganinput = $request['golonganbaru'];
      $cekgolongan = GolonganModel::where('gol', $golonganinput)->count();




      if ($cekgolongan > 0) {

        $golongankembar = DB::table('tb_golongan')
          ->where('gol', $golonganinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $golongankembars = $golongankembar->id;

        if ($updatehistory->idpangkat) {

          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $golongankembars;
        $updatehistory->golongan_out = $request['Golongan_out'];
      } else {

        $tambahgolongan->gol = $request['golonganbaru'];
        $tambahgolongan->type = 'his';
        $tambahgolongan->save();

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $tambahgolongan->id;
        $updatehistory->golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain4'] == 'laingolonganout') {

      $golonganoutinput = $request['golonganoutbaru'];
      $cekgolonganout = GolonganOutModel::where('gol', $golonganoutinput)->count();


      if ($cekgolonganout > 0) {

        $golonganoutkembar = DB::table('tb_golongan_outsource')
          ->where('gol', $golonganoutinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $golonganoutkembars = $golonganoutkembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
        }


        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];

        $updatehistory->golongan_out = $golonganoutkembars;
      } else {

        $tambahgolonganout->gol = $request['golonganoutbaru'];
        $tambahgolonganout->type = 'his';
        $tambahgolonganout->save();


        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
        }


        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];

        $updatehistory->golongan_out = $tambahgolonganout->id;
      }
    }

    if ($request['radiolain5'] == 'laindivisi') {

      $divisiinput = $request['divisibaru'];
      $cekdivisi = DivisiModel::where('nama_div_ext', $divisiinput)->count();

      if ($cekdivisi > 0) {

        $divisikembar = DB::table('tbldivmaster')
          ->where('nama_div_ext', $divisiinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $divisikembars = $divisikembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
        }


        $updatehistory->divisi = $divisikembars;
        $updatehistory->subdivisi = $request['SubDivisi'];
      } else {

        $tambahdivisi->nama_div_ext = $request['divisibaru'];
        $tambahdivisi->type = 'his';
        $tambahdivisi->save();

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }

        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
        }


        $updatehistory->divisi = $tambahdivisi->id;
        $updatehistory->subdivisi = $request['SubDivisi'];
      }
    }

    if ($request['radiolain6'] == 'lainsubdivisi') {

      $subdivisiinput = $request['subdivisibaru'];
      $ceksubdivisi = SubDivisiModel::where('subdivisi', $subdivisiinput)->count();

      if ($ceksubdivisi > 0) {

        $subdivisikembar = DB::table('tb_subdivisi')
          ->where('subdivisi', $subdivisiinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $subdivisikembars = $subdivisikembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
        }

        if ($updatehistory->divisi) {
          $updatehistory->divisi = $updatehistory->divisi;
        } else {
          $updatehistory->divisi = $request['Divisi'];
        }

        $updatehistory->subdivisi = $tambahsubdivisi->id;
      } else {

        $tambahsubdivisi->subdivisi = $request['subdivisibaru'];
        $tambahsubdivisi->type = 'his';
        $tambahsubdivisi->save();

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
        }

        if ($updatehistory->divisi) {
          $updatehistory->divisi = $updatehistory->divisi;
        } else {
          $updatehistory->divisi = $request['Divisi'];
        }

        $updatehistory->subdivisi = $tambahsubdivisi->id;
      }
    }

    if (($request['radiolain1'] != 'lainpangkat') and
      ($request['radiolain2'] != 'lainjabatan') and
      ($request['radiolain3'] != 'laingolongan') and
      ($request['radiolain4'] != 'laingolonganout') and
      ($request['radiolain5'] != 'laindivisi') and
      ($request['radiolain6'] != 'lainsubdivisi')
    ) {

      $updatehistory->idpangkat = $request['idpangkat'];
      $updatehistory->idjabatan = $request['idjabatan'];
      $updatehistory->divisi = $request['Divisi'];
      $updatehistory->subdivisi = $request['SubDivisi'];
      $updatehistory->golongan = $request['Golongan'];
      $updatehistory->golongan_out = $request['Golongan_out'];
    }

    // $updatehistory->nik = $request['nik_baru'];
    $updatehistory->old_nik = $request['nik_lama'];
    $updatehistory->tgl_sk_jab = $request['tgl_sk'];
    $updatehistory->tgl_sk_gol = $request['tgl_tmt'];
    $updatehistory->TglKontrak = $request['tgl_kontrak'];
    $updatehistory->TglKontrakEnd = $request['tgl_akhir_kontrak'];
    $updatehistory->statuskar = $request['statuskar'];
    $updatehistory->gaji = $gaji;
    $updatehistory->no_sk = $request['no_sk'];



    //ruang gawe if

    $updatehistory->atasan1 = $request['atasan1'];
    $updatehistory->atasan2 = $request['atasan2'];
    $updatehistory->lokasiker = $request['LokasiKer'];

    $updatehistory->update();

    // return redirect('employeelist')->with('success','Data Berhasil Disimpan');
    return redirect(url('edithistory', [$id]))->with('success', 'Data Berhasil Disimpan.');
  }



  public function FUNC_DELETEHISTORY($id)
  {

    $deletehistory = HistoryJabModel::find($id);
    $deletehistory->delete();

    return redirect()->back();
  }

  public function FUNC_HEADERPE($nik)
  {

    $showdata = EmployeeModel::select('Nama', 'NIK', 'TempatLahir', 'TanggalLahir')->where('NIK', $nik)->first();
    $tahundropdown = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
    $tahundropdowns = DB::select($tahundropdown);

    $historyprex = HeaderPEModel::select('id as id_head', 'nik', 'posisi', 'didikan', 'didikannf', 'bhs')->where('tb_headproj.nik', $nik)
      ->get();

    return view('employee/headerpe')->with('nik', $nik)->with('showdata', $showdata)->with('historyprex', $historyprex)->with('tahundropdowns', $tahundropdowns);
  }

  public function FUNC_SAVEHEADERPE(Request $request)
  {

    $nik = $request['nik'];
    $datenow = date("YmdHis");
    // $datenow = new DateTime();
    // dd($nik.$datenow);

    $tambah = new HeaderPEModel();
    $tambah->id = $nik . $datenow;
    $tambah->nik = $nik;
    $tambah->posisi = $request['posisi'];
    $tambah->didikan = $request['pendidikan_formal'];
    $tambah->didikannf = $request['pendidikan_nonformal'];
    $tambah->bhs = $request['penguasaanbhs'];
    // $tambah->bulan_awal = $request['bulan_awal'];
    // $tambah->tahun_awal = $request['tahun_awal'];
    // $tambah->bulan_akhir = $request['bulan_akhir'];
    // $tambah->tahun_akhir = $request['tahun_akhir'];

    $tambah->save();

    // $tambahpex = new ProjectExModel();
    // $tambahpex->id_head = $nik.$datenow;
    // $tambahpex->nik = $nik;

    // $tambahpex->save();

    $id = $nik . $datenow;

    return redirect(url('projectexperience', [$id]))->with('success', 'Data Berhasil Ditambahkan.')->with('project', true)->with('id', $id);
  }

  public function FUNC_UPDATEHEADERPE($id)
  {
    $update = HeaderPEModel::where('id', $id)->first();
    return view('employee/dataheaderpe')->with('update', $update);
  }

  public function FUNC_SAVEUPDATEHEADERPE(Request $request)
  {

    // dd($request->all());
    $id = $request['idhead'];

    $update = HeaderPEModel::where('id', $id)->first();
    $update->posisi = $request['posisi'];
    $update->didikan = $request['pendidikan_formal'];
    $update->didikannf = $request['pendidikan_nonformal'];
    $update->bhs = $request['penguasaanbhs'];
    // $update->bulan_awal = $request['bulan_awal'];
    // $update->tahun_awal = $request['tahun_awal'];
    // $update->bulan_akhir = $request['bulan_akhir'];
    // $update->tahun_akhir = $request['tahun_akhir'];

    $update->update();

    return redirect(url('projectexperience', [$id]))->with('success', 'Data Berhasil DiUpdate.')->with('project', true)->with('id', $id);
  }

  public function FUNC_SAVEPROJECTEX(Request $request)
  {

    $id = $request['idprojectex'];

    $updatepx = new ProjectExModel();
    $updatepx->id_head = $request['idprojectex'];
    $updatepx->nik = $request['nikprojectex'];
    $updatepx->nama = $request['nama_project'];
    $updatepx->lokasi = $request['lokasi_project'];
    $updatepx->pengguna = $request['pengguna_jasa'];
    $updatepx->perusahaan = $request['nama_perusahaan'];
    $updatepx->uraian_tugas = $request['uraian_tugas'];
    $updatepx->waktu_pelaksanaan = $request['waktupel'];
    $updatepx->posisi = $request['posisi_pen'];
    $updatepx->status = $request['statuskar'];

    $updatepx->save();

    return redirect(url('projectexperience', [$id]))->with('success', 'Data Berhasil Ditambahkan.')->with('project', true);
  }

  public function FUNC_CEKNIK(Request $request)
  {
    $nik = $request['nik'];
    $data = EmployeeModel::where('NIK', $nik)->exists();
    return response()->json(['data' => $data], 200);
  }

  public function FUNC_CEKTGLSK(Request $request)
  {

    $tglsklama = date($request['tglsklama']);
    $tglskbaru = date($request['tglskbaru']);

    if ($tglskbaru < $tglsklama) {
      return response()->json(['success' => false], 200);
    } else {
      return response()->json(['success' => true], 200);
    }
  }

  public function FUNC_EDITHISTORYTER($id)
  {

    $nikquery = HistoryJabModel::where('id', $id)->first();
    $NIK = $nikquery->nik;

    // dd($NIK);
    $tampilhistory = HistoryJabModel::select(
      'tb_his_jabatan.id',
      'tb_his_jabatan.nik',
      'tb_datapribadi.Nama as nama',
      'tb_his_jabatan.old_nik as nik_lama',
      'tb_his_jabatan.tgl_sk_jab as tanggal1',
      'tb_his_jabatan.tgl_sk_gol as tanggal2',
      'tb_his_jabatan.subdivisi as subdivisi',
      'tb_his_jabatan.golongan as golonganid',
      'tb_his_jabatan.golongan_out as golonganoutid',
      'tb_golongan.gol as nama_golongan',
      'tb_golongan_outsource.gol as nama_golonganout',
      'tb_pangkat.pangkat as nama_pangkat',
      'tb_jabatan.jabatan as nama_jabatan',
      'tbldivmaster.nama_div_ext as nama_divisi',
      'tb_subdivisi.subdivisi as nama_subdivisi',
      'tb_pangkat.type as typepangkat',
      'tb_jabatan.type as typejabatan',
      'tb_golongan.type as typegolongan',
      'tb_golongan_outsource.type as typegolonganout',
      'tbldivmaster.type as typedivisi',
      'tb_subdivisi.type as typesubdivisi',
      'tb_his_jabatan.atasan1 as atasan1',
      'tb_his_jabatan.atasan2 as atasan2',
      'tb_his_jabatan.gaji',
      'tb_his_jabatan.tunj_jab',
      'tb_his_jabatan.tunj_tmr',
      'tb_his_jabatan.no_sk',
      'tb_his_jabatan.TglKontrak as tgl_kontrak',
      'tb_his_jabatan.TglKontrakEnd as tgl_akhir_kontrak',
      'tb_his_jabatan.divisi as divisi',
      'tb_his_jabatan.lokasiker as lokasi',
      'tb_his_jabatan.statuskar as statuskar',
      'tb_his_jabatan.idpangkat as pangkat',
      'tb_his_jabatan.idjabatan as jabatanid',
      DB::raw('CONCAT(tb_pangkat.pangkat,"-",tb_jabatan.jabatan) as jabatan'),
      DB::raw('CASE WHEN (select statuskar from tb_datapribadi where NIK = "' . $NIK . '") = "5" THEN (select gol from tb_golongan_outsource where id = tb_datapribadi.Golongan_out)
                                            WHEN ((select a.old_nik from tb_his_jabatan a where a.id = tb_his_jabatan.id) IS NULL AND (select statuskar from tb_datapribadi where NIK = tb_his_jabatan.nik) = "5" )
                                            THEN (Select id from tb_golongan_outsource where id = tb_his_jabatan.golongan)
                                            ELSE (select id from tb_golongan where id = tb_his_jabatan.golongan) END as golongan')
    )
      ->leftjoin('tbldivmaster', 'tb_his_jabatan.divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_lokasikerja', 'tb_his_jabatan.lokasiker', '=', 'tb_lokasikerja.id')
      ->leftjoin('tb_subdivisi', 'tb_his_jabatan.subdivisi', '=', 'tb_subdivisi.id')
      ->leftjoin('tb_jabatan', 'tb_his_jabatan.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tb_pangkat', 'tb_his_jabatan.idpangkat', '=', 'tb_pangkat.id')
      ->leftjoin('tb_datapribadi', 'tb_his_jabatan.nik', '=', 'tb_datapribadi.NIK')
      ->leftjoin('tb_golongan', 'tb_his_jabatan.golongan', '=', 'tb_golongan.id')
      ->leftjoin('tb_golongan_outsource', 'tb_his_jabatan.golongan_out', '=', 'tb_golongan_outsource.id')
      // ->whereRaw(DB::raw('tb_his_jabatan.nik = "'.$NIK.'" or tb_his_jabatan.nik = (select old_nik from tb_datapribadi where NIK = "'.$NIK.'")'))
      ->where('tb_his_jabatan.id', $id)

      ->orderBy('id', 'asc')
      ->first();

    $atasan1 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-", tb_datapribadi.nama ,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (' . $this->list_pangkat_atasan . ')'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();

    $atasan2 = EmployeeModel::select(
      'nik',
      'nama',
      DB::raw('CONCAT(tb_datapribadi.nik,"-",tb_datapribadi.nama,"(", tb_pangkat.pangkat, ")") as atasan')
    )
      ->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
      ->whereRaw(DB::raw('tb_datapribadi.statuskar IN (1,2,4,3) AND tb_datapribadi.resign ="N" AND tb_datapribadi.idpangkat IN (' . $this->list_pangkat_atasan . ')'))
      ->orderby('tb_datapribadi.idpangkat', 'ASC')
      ->get();



    // $pangkat = PangkatModel::where('type',NULL)->get();
    $pangkat = PangkatModel::orderBy('urutan', 'ASC')->where('TYPE', null)->get();
    // $pangkathis = PangkatModel::where('type','his')->get();
    $jabatan = JabatanModel::where('type', NULL)->get();
    // $jabatanhis = JabatanModel::where('type',NULL)->get();
    $divisi = DivisiModel::where('type', NULL)->get();
    // $divisihis = DivisiModel::where('type','his')->get();
    $subdivisi = SubDivisiModel::where('type', NULL)->get();
    // $subdivisihis = SubDivisiModel::where('type','his')->get();
    $lokasikerja = LokkerModel::all();
    $golongan = GolonganModel::where('type', NULL)->get();
    // $golonganhis = GolonganModel::where('type','his')->get();
    $golonganout = GolonganOutModel::where('type', NULL)->get();
    // $golonganouthis = GolonganOutModel::where('type','his')->get();
    $statuskar = StatusKarModel::all();

    $gaji = $tampilhistory->gaji;
    if ($gaji == null) {
      $gajifix = null;
    } else {
      $gajifix = Crypt::decrypt($gaji);
    }

    $tunj_tmr = $tampilhistory->tunj_tmr;
    if ($tunj_tmr == null) {
      $tunj_tmrfix = null;
    } else {
      $tunj_tmrfix = Crypt::decrypt($tunj_tmr);
    }

    $tunj_jab = $tampilhistory->tunj_jab;
    if ($tunj_jab == null) {
      $tunj_jabfix = null;
    } else {
      $tunj_jabfix = Crypt::decrypt($tunj_jab);
    }


    // dd($tampilhistory);

    return view('employee/edithistoryter')
      ->with('tampilhistory', $tampilhistory)
      ->with('pangkat', $pangkat)
      ->with('jabatan', $jabatan)
      ->with('divisi', $divisi)
      ->with('subdivisi', $subdivisi)
      ->with('lokasikerja', $lokasikerja)
      // ->with('mutasiquery',$mutasiquery)//->with('mutasiquerys',$mutasiquerys)
      // ->with('tablehistory',$tablehistory)
      ->with('golongan', $golongan)
      ->with('golonganout', $golonganout)
      ->with('atasan1', $atasan1)
      ->with('atasan2', $atasan2)
      ->with('statuskar', $statuskar)
      ->with('gaji', $gajifix)
      ->with('tunj_tmr', $tunj_tmrfix)
      ->with('tunj_jab', $tunj_jabfix);
  }

  public function FUNC_UPDATEHISTORYTER(Request $request)
  {

    // dd($request->all());
    $nikmen = $request['nik_baru'];
    $id = $request['id'];

    if ($request['gaji'] == null or $request['gaji'] == 0) {
      $gaji = null;
    } else {
      $gaji = Crypt::encrypt($request['gaji']);
    }

    if ($request['tunj_tmr'] == null or $request['tunj_tmr'] == 0) {
      $tunj_tmr = null;
    } else {
      $tunj_tmr = Crypt::encrypt($request['tunj_tmr']);
    }

    if ($request['tunj_jab'] == null or $request['tunj_jab'] == 0) {
      $tunj_jab = null;
    } else {
      $tunj_jab = Crypt::encrypt($request['tunj_jab']);
    }

    $updatehistory = HistoryJabModel::where('id', $id)->first();
    $update123 = EmployeeModel::where('NIK', $nikmen)->first();


    // if ($request['radiolain1'] == 'lainpangkat') {
    //     $this->validate($request, [
    //     'pangkatbaru' =>'required'
    // ]);

    // } if ($request['radiolain1'] == 'lainpangkat')  {
    //     $this->validate($request, [
    //     'pangkatbaru' => 'required',

    // ]);
    // } if ($request['radiolain2'] == 'lainjabatan') {
    //     $this->validate($request, [
    //     'jabatanbaru' =>'required'
    // ]);
    // } if ($request['radiolain3'] == 'laingolongan') {
    //     $this->validate($request, [
    //     'golonganbaru' =>'required'
    // ]);
    // } if ($request['radiolain4'] == 'laingolonganout') {
    //     $this->validate($request, [
    //     'golonganoutbaru' =>'required'
    // ]);
    // } if ($request['radiolain5'] == 'laindivisi') {
    //     $this->validate($request, [
    //     'divisibaru' =>'required'
    // ]);
    // } if ($request['radiolain6'] == 'lainsubdivisi') {
    //     $this->validate($request, [
    //     'subdivisibaru' =>'required'
    // ]);
    // } if ($request['radiogol'] == '1') {
    //     $this->validate($request, [
    //     'Golongan_out' => 'required'
    // ]);
    // } if ($request['radiogol'] == '2') {
    //     $this->validate($request, [
    //     'Golongan' => 'required'
    // ]);
    // }

    // dd('asdasadasd');
    $tambahpangkat = new PangkatModel();
    $tambahjabatan = new JabatanModel();
    $tambahgolongan = new GolonganModel();
    $tambahgolonganout = new GolonganOutModel();
    $tambahdivisi = new DivisiModel();
    $tambahsubdivisi = new SubDivisiModel();



    if ($request['radiolain1'] == 'lainpangkat') {

      $pangkatinput = $request['pangkatbaru'];
      $cekpangkat = PangkatModel::where('pangkat', $pangkatinput)->count();

      if ($cekpangkat > 0) {

        $pangkatkembar = DB::table('tb_pangkat')
          ->where('pangkat', $pangkatinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $pangkatkembars = $pangkatkembar->id;
        # code...
        $updatehistory->idpangkat = $pangkatkembars;
        $updatehistory->idjabatan = $request['idjabatan'];
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];

        $update123->idpangkat = $pangkatkembars;
        $update123->idjabatan = $request['idjabatan'];
        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];
        $update123->Golongan = $request['Golongan'];
        $update123->Golongan_out = $request['Golongan_out'];
      } else {
        //dd('ggggggg');
        $tambahpangkat->pangkat = $request['pangkatbaru'];
        $tambahpangkat->type = 'his';
        $tambahpangkat->save();


        //$idpangkatlast = DB::table('tb_pangkat')->max('id');

        $updatehistory->idpangkat = $tambahpangkat->id;
        $updatehistory->idjabatan = $request['idjabatan'];
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];

        $update123->idpangkat = $tambahpangkat->id;
        $update123->idjabatan = $request['idjabatan'];
        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];
        $update123->Golongan = $request['Golongan'];
        $update123->Golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain2'] == 'lainjabatan') {

      $jabataninput = $request['jabatanbaru'];
      $cekjabatan = JabatanModel::where('jabatan', $jabataninput)->count();

      if ($cekjabatan > 0) {

        $jabatankembar = DB::table('tb_jabatan')
          ->where('jabatan', $jabataninput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $jabatankembars = $jabatankembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {
          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }

        $updatehistory->idjabatan = $jabatankembars;
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];

        $update123->idjabatan = $jabatankembars;
        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];
        $update123->Golongan = $request['Golongan'];
        $update123->Golongan_out = $request['Golongan_out'];
      } else {


        $tambahjabatan->jabatan = $request['jabatanbaru'];
        $tambahjabatan->type = 'his';
        $tambahjabatan->save();

        //$idpangkatlast = DB::table('tb_pangkat')->max('id');

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {
          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }

        $updatehistory->idjabatan = $tambahjabatan->id;
        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $request['Golongan'];
        $updatehistory->golongan_out = $request['Golongan_out'];

        $update123->idjabatan = $tambahjabatan->id;
        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];
        $update123->Golongan = $request['Golongan'];
        $update123->Golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain3'] == 'laingolongan') {

      $golonganinput = $request['golonganbaru'];
      $cekgolongan = GolonganModel::where('gol', $golonganinput)->count();

      if ($cekgolongan > 0) {

        $golongankembar = DB::table('tb_golongan')
          ->where('gol', $golonganinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $golongankembars = $golongankembar->id;

        if ($updatehistory->idpangkat) {

          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $golongankembars;
        $updatehistory->golongan_out = $request['Golongan_out'];

        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];
        $update123->Golongan = $golongankembars;
        $update123->Golongan_out = $request['Golongan_out'];
      } else {

        $tambahgolongan->gol = $request['golonganbaru'];
        $tambahgolongan->type = 'his';
        $tambahgolongan->save();

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];
        $updatehistory->golongan = $tambahgolongan->id;
        $updatehistory->golongan_out = $request['Golongan_out'];

        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];
        $update123->Golongan = $tambahgolongan->id;
        $update123->Golongan_out = $request['Golongan_out'];
      }
    }

    if ($request['radiolain4'] == 'laingolonganout') {

      $golonganoutinput = $request['golonganoutbaru'];
      $cekgolonganout = GolonganOutModel::where('gol', $golonganoutinput)->count();


      if ($cekgolonganout > 0) {

        $golonganoutkembar = DB::table('tb_golongan_outsource')
          ->where('gol', $golonganoutinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $golonganoutkembars = $golonganoutkembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
          $update123->Golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
          $update123->Golongan = $request['Golongan'];
        }


        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];

        $updatehistory->golongan_out = $golonganoutkembars;

        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];

        $update123->Golongan_out = $golonganoutkembars;
      } else {

        $tambahgolonganout->gol = $request['golonganoutbaru'];
        $tambahgolonganout->type = 'his';
        $tambahgolonganout->save();


        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
          $update123->Golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
          $update123->Golongan = $request['Golongan'];
        }


        $updatehistory->divisi = $request['Divisi'];
        $updatehistory->subdivisi = $request['SubDivisi'];

        $updatehistory->golongan_out = $tambahgolonganout->id;

        $update123->Divisi = $request['Divisi'];
        $update123->SubDivisi = $request['SubDivisi'];

        $update123->Golongan_out = $tambahgolonganout->id;
      }
    }

    if ($request['radiolain5'] == 'laindivisi') {

      $divisiinput = $request['divisibaru'];
      $cekdivisi = DivisiModel::where('nama_div_ext', $divisiinput)->count();

      if ($cekdivisi > 0) {

        $divisikembar = DB::table('tbldivmaster')
          ->where('nama_div_ext', $divisiinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $divisikembars = $divisikembar->id;

        if ($updatehistory->idpangkat) {

          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
          $update123->Golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
          $update123->Golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
          $update123->Golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
          $update123->Golongan_out = $request['Golongan_out'];
        }


        $updatehistory->divisi = $divisikembars;
        $updatehistory->subdivisi = $request['SubDivisi'];

        $update123->Divisi = $divisikembars;
        $update123->SubDivisi = $request['SubDivisi'];
      } else {

        $tambahdivisi->nama_div_ext = $request['divisibaru'];
        $tambahdivisi->type = 'his';
        $tambahdivisi->save();

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }

        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
          $update123->Golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
          $update123->Golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
          $update123->Golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
          $update123->Golongan_out = $request['Golongan_out'];
        }


        $updatehistory->divisi = $tambahdivisi->id;
        $updatehistory->subdivisi = $request['SubDivisi'];

        $update123->Divisi = $tambahdivisi->id;
        $update123->SubDivisi = $request['SubDivisi'];
      }
    }

    if ($request['radiolain6'] == 'lainsubdivisi') {

      $subdivisiinput = $request['subdivisibaru'];
      $ceksubdivisi = SubDivisiModel::where('subdivisi', $subdivisiinput)->count();

      if ($ceksubdivisi > 0) {

        $subdivisikembar = DB::table('tb_subdivisi')
          ->where('subdivisi', $subdivisiinput)
          ->orderBy('id', 'ASC')
          ->take(1)
          ->first();
        $subdivisikembars = $subdivisikembar->id;

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
          $update123->Golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
          $update123->Golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
          $update123->Golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
          $update123->Golongan_out = $request['Golongan_out'];
        }

        if ($updatehistory->divisi) {
          $updatehistory->divisi = $updatehistory->divisi;
          $update123->Divisi = $updatehistory->divisi;
        } else {
          $updatehistory->divisi = $request['Divisi'];
          $update123->Divisi = $request['Divisi'];
        }

        $updatehistory->subdivisi = $tambahsubdivisi->id;
        $update123->SubDivisi = $tambahsubdivisi->id;
      } else {

        $tambahsubdivisi->subdivisi = $request['subdivisibaru'];
        $tambahsubdivisi->type = 'his';
        $tambahsubdivisi->save();

        if ($updatehistory->idpangkat) {
          $updatehistory->idpangkat = $updatehistory->idpangkat;
          $update123->idpangkat = $updatehistory->idpangkat;
        } else {

          $updatehistory->idpangkat = $request['idpangkat'];
          $update123->idpangkat = $request['idpangkat'];
        }


        if ($updatehistory->idjabatan) {
          $updatehistory->idjabatan = $updatehistory->idjabatan;
          $update123->idjabatan = $updatehistory->idjabatan;
        } else {
          $updatehistory->idjabatan = $request['idjabatan'];
          $update123->idjabatan = $request['idjabatan'];
        }

        if ($updatehistory->golongan) {
          $updatehistory->golongan = $updatehistory->golongan;
          $update123->Golongan = $updatehistory->golongan;
        } else {
          $updatehistory->golongan = $request['Golongan'];
          $update123->Golongan = $request['Golongan'];
        }

        if ($updatehistory->golongan_out) {
          $updatehistory->golongan_out = $updatehistory->golongan_out;
          $update123->Golongan_out = $updatehistory->golongan_out;
        } else {
          $updatehistory->golongan_out = $request['Golongan_out'];
          $update123->Golongan_out = $request['Golongan_out'];
        }

        if ($updatehistory->divisi) {
          $updatehistory->divisi = $updatehistory->divisi;
          $update123->Divisi = $updatehistory->divisi;
        } else {
          $updatehistory->divisi = $request['Divisi'];
          $update123->Divisi = $request['Divisi'];
        }

        $updatehistory->subdivisi = $tambahsubdivisi->id;
      }
    }

    if (($request['radiolain1'] != 'lainpangkat') and
      ($request['radiolain2'] != 'lainjabatan') and
      ($request['radiolain3'] != 'laingolongan') and
      ($request['radiolain4'] != 'laingolonganout') and
      ($request['radiolain5'] != 'laindivisi') and
      ($request['radiolain6'] != 'lainsubdivisi')
    ) {

      $updatehistory->idpangkat = $request['idpangkat'];
      $updatehistory->idjabatan = $request['idjabatan'];
      $updatehistory->divisi = $request['Divisi'];
      $updatehistory->subdivisi = $request['SubDivisi'];
      $updatehistory->golongan = $request['Golongan'];
      $updatehistory->golongan_out = $request['Golongan_out'];

      $update123->idpangkat = $request['idpangkat'];
      $update123->idjabatan = $request['idjabatan'];
      $update123->Divisi = $request['Divisi'];
      $update123->SubDivisi = $request['SubDivisi'];
      $update123->Golongan = $request['Golongan'];
      $update123->Golongan_out = $request['Golongan_out'];
    }

    // $updatehistory->nik = $request['nik_baru'];
    $updatehistory->old_nik = $request['nik_lama'];
    $updatehistory->tgl_sk_jab = $request['tgl_sk'];
    $updatehistory->tgl_sk_gol = $request['tgl_tmt'];
    $updatehistory->TglKontrak = $request['tgl_kontrak'];
    $updatehistory->TglKontrakEnd = $request['tgl_akhir_kontrak'];
    $updatehistory->statuskar = $request['statuskar'];

    $update123->old_nik = $request['nik_lama'];
    $update123->tgl_sk_jab = $request['tgl_sk'];
    $update123->tgl_sk_gol = $request['tgl_tmt'];
    $update123->TglKontrak = $request['tgl_kontrak'];
    $update123->TglKontrakEnd = $request['tgl_akhir_kontrak'];
    $update123->statuskar = $request['statuskar'];

    //ruang gawe if

    $updatehistory->atasan1 = $request['atasan1'];
    $updatehistory->atasan2 = $request['atasan2'];
    $updatehistory->lokasiker = $request['LokasiKer'];
    $updatehistory->gaji = $gaji;
    $updatehistory->tunj_jab = $tunj_jab;
    $updatehistory->tunj_tmr = $tunj_tmr;
    $updatehistory->no_sk = $request['no_sk'];

    $update123->atasan1 = $request['atasan1'];
    $update123->atasan2 = $request['atasan2'];
    $update123->lokasiker = $request['LokasiKer'];
    $update123->gaji = $gaji;
    $update123->tunj_tmr = $tunj_tmr;
    $update123->tunj_jab = $tunj_jab;

    $updatehistory->update();
    $update123->update();

    // $update123->old_nik = $request['nik_lama'];
    // $update123->tgl_sk_jab = $request['tgl_sk'];
    // $update123->tgl_sk_gol = $request['tgl_tmt'];
    // $update123->idpangkat = $request['idpangkat'];

    // return redirect('employeelist')->with('success','Data Berhasil Disimpan');
    return redirect(url('historyjabatan', [$nikmen]))->with('success', 'Data Berhasil Disimpan.');
  }

  public function FUNC_TAMBAHSP()
  {

    $nik = Session::get('nik');
    $admin = Session::get('admin');
    $jenisSp = DB::table('tb_mst_sp')->select('id', 'jenis_sp')->get();

    if ($admin == 1) {
      $tampilnama = EmployeeModel::select(
        'NIK as nik',
        'Nama',
        DB::raw('CONCAT(NIK , "-" , Nama) as uhuy')
      )
        ->where('resign', 'N')
        // ->whereRaw('tb_datapribadi.idpangkat NOT IN(2,3,4,5,6,7)')
        ->get();
    } else {
      $tampilnama = EmployeeModel::select(
        'NIK as nik',
        'Nama',
        DB::raw('CONCAT(NIK , "-" , Nama) as uhuy')
      )
        ->where('resign', 'N')
        ->whereRaw('atasan1 = "' . $nik . '" OR atasan2 = "' . $nik . '"')
        ->get();
    }


    return view('sp/tambahsp')->with('tampilnama', $tampilnama)->with('jenisSp', $jenisSp);
  }

  public function FUNC_CEKTAMBAHSP(Request $request)
  {

    $coba = $request['nik'];
    $atasan = Session::get('nik');

    $querydivjab = EmployeeModel::select('tb_datapribadi.TglTetap as mulai_bekerja', 'tbldivmaster.nama_div_ext as namadivisi', 'tb_jabatan.jabatan as namajabatan')
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->where('tb_datapribadi.NIK', $coba)
      ->first();

    $mulai_bekerja = $querydivjab->mulai_bekerja;
    $divisi = $querydivjab->namadivisi;
    $jabatan = $querydivjab->namajabatan;


    return response()->json([
      'tes' => $coba,
      'mulai_bekerja' => $mulai_bekerja,
      'divisi' => $divisi,
      'jabatan' => $jabatan
    ], 200);
  }

  public function FUNC_CEKLASTSP(Request $request)
  {

    $nik_kar = $request['nik'];
    $tgl_mulai_new = $request['tgl_mulai_new'];
    $tgl_selesai_new = $request['tgl_selesai_new'];
    $jenis_sp_new = $request['jenis_sp_new'];

    $ceklastsp = SPModel::select('tb_sp.tgl_mulai', 'tb_sp.tgl_selesai', 'tb_sp.jenis_sp')
      ->whereRaw('tb_sp.nik = "' . $nik_kar . '" OR tb_sp.nik = (Select old_nik from tb_datapribadi where NIK = "' . $nik_kar . '")')
      ->orderby('tb_sp.id', 'DESC')
      ->first();

    $tgl_mulai_old = $ceklastsp->tgl_mulai;
    $tgl_selesai_old = $ceklastsp->tgl_selesai;
    $jenis_sp_old = $ceklastsp->jenis_sp;

    if ($ceklastsp) {

      if ($tgl_mulai_new >= $tgl_mulai_old and $tgl_mulai_new <= $tgl_selesai_old) {
        if ($jenis_sp_old == $jenis_sp_new) {
          $boom = 'harus meningkat';
        } elseif ($jenis_sp_old != $jenis_sp_new) {
          if ($jenis_sp_new > $jenis_sp_old) {
            $boom = 'sudah benar';
          } else {
            $boom = 'harus meningkat';
          }
        }
      } elseif ($tgl_mulai_new > $tgl_mulai_old) {
        $boom = 'sudah benar';
      }
    } else {
      $boom = 'belum kena';
    }

    return response()->json([
      'nik_kar' => $nik_kar,
      'tgl_mulai_old' => $tgl_mulai_old,
      'tgl_selesai_old' => $tgl_selesai_old,
      'jenis_sp_old' => $jenis_sp_old,
      'status' => $boom
    ], 200);
  }

  public function FUNC_SAVETAMBAHSP(Request $request)
  {

    if (Session::get('admin') == 1) {
      $pemberi_sp = $request['pemberi_sp'];
    } else {
      $pemberi_sp = Session::get('nik');
    }

    $tambah = new SPModel();
    $tambah->nik = $request['nik'];
    $tambah->nik_pemberi_sp = $pemberi_sp;
    $tambah->jenis_sp = $request['jenis_sp'];
    $tambah->type_sp = $request['type_sp'];
    $tambah->tgl_sk = $request['TanggalSK'];
    $tambah->tgl_mulai = $request['TanggalMulai'];
    $tambah->tgl_selesai = $request['TanggalSelesai'];
    $tambah->keterangan = $request['AlasanSP'];

    $tes = SPModel::orderBy('id', 'DESC')->first();

    $id = (int) $tes->id;
    $id += 1;

    if ($request->file('gambar') == '') {
      // return redirect('listdok')->with('error','Data Gagal Ditambahkan.');
      $tambah->photo = '';
    } else {
      $file = $request->file('gambar');
      if ($file != null) {
        $fileName = $file->getClientOriginalExtension();
        $fileName = $id . $request['nik'] . '.' . $fileName;
        $file->move("image/SuratPeringatan", $fileName);
      }

      $tambah->photo = $fileName;
    }

    $tambah->save();

    return redirect()->back()->with('success', 'Surat Peringatan Berhasil Disimpan');
  }

  public function FUNC_DAFTARSP()
  {

    // $testprodev = DB::connection('prodev')->table('user_app')->select('*')->get();

    // dd($testprodev);

    $nik = Session::get('nik');
    if (Session::get('admin') == 1) {
      $daftarsp = SPModel::select(
        'tb_sp.id',
        'tb_sp.photo as photosp',
        'tb_sp.nik',
        'tb_sp.nik_pemberi_sp',
        'tb_mst_sp.jenis_sp',
        'tb_sp.keterangan',
        'tb_sp.tgl_sk',
        DB::raw('(SELECT Nama FROM tb_datapribadi WHERE NIK = tb_sp.nik OR old_nik = tb_sp.nik) as nama'),
        DB::raw('(CASE WHEN tb_sp.type_sp = "0" THEN "MEMO"
                                    WHEN tb_sp.type_sp = "1" THEN "TEGURAN"
                                    WHEN tb_sp.type_sp IS NULL THEN "NULL"
                               END) as type_sp'),
        DB::raw('(select Nama from tb_datapribadi where NIK = tb_sp.nik_pemberi_sp) as nama_pemberisp')
      )
        ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_sp.nik')
        ->leftjoin('tb_mst_sp', 'tb_mst_sp.id', '=', 'tb_sp.jenis_sp')
        ->orderby('tb_sp.id', 'DESC')
        ->get();
    } elseif (Session::get('admin') == 2) {
      $daftarsp = SPModel::select(
        'tb_sp.id',
        'tb_sp.photo as photosp',
        'tb_sp.nik',
        'tb_sp.nik_pemberi_sp',
        'tb_mst_sp.jenis_sp',
        'tb_sp.keterangan',
        'tb_sp.tgl_sk',
        DB::raw('(SELECT Nama FROM tb_datapribadi WHERE NIK = tb_sp.nik OR old_nik = tb_sp.nik) as nama'),
        DB::raw('(CASE WHEN tb_sp.type_sp = "0" THEN "MEMO"
                                    WHEN tb_sp.type_sp = "1" THEN "TEGURAN"
                                    WHEN tb_sp.type_sp IS NULL THEN "NULL"
                               END) as type_sp'),
        DB::raw('(select Nama from tb_datapribadi where NIK = tb_sp.nik_pemberi_sp) as nama_pemberisp')
      )
        ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_sp.nik')
        ->leftjoin('tb_mst_sp', 'tb_mst_sp.id', '=', 'tb_sp.jenis_sp')
        ->where('tb_sp.nik_pemberi_sp', $nik)
        ->orderby('tb_sp.id', 'DESC')
        ->get();
    } else {
      $daftarsp = SPModel::select(
        'tb_sp.id',
        'tb_sp.photo as photosp',
        'tb_sp.nik',
        'tb_sp.nik_pemberi_sp',
        'tb_mst_sp.jenis_sp',
        'tb_sp.keterangan',
        'tb_sp.tgl_sk',
        DB::raw('(SELECT Nama FROM tb_datapribadi WHERE NIK = tb_sp.nik OR old_nik = tb_sp.nik) as nama'),
        DB::raw('(CASE WHEN tb_sp.type_sp = "0" THEN "MEMO"
                                    WHEN tb_sp.type_sp = "1" THEN "TEGURAN"
                                    WHEN tb_sp.type_sp IS NULL THEN "NULL"
                               END) as type_sp'),
        DB::raw('(select Nama from tb_datapribadi where NIK = tb_sp.nik_pemberi_sp) as nama_pemberisp')
      )
        ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_sp.nik')
        ->leftjoin('tb_mst_sp', 'tb_mst_sp.id', '=', 'tb_sp.jenis_sp')
        ->where('tb_sp.nik', $nik)
        ->orderby('tb_sp.id', 'DESC')
        ->get();
    }

    return view('sp/daftarsp')->with('daftarsp', $daftarsp);
  }

  public function FUNC_EDITSP($id)
  {
    $jenisSp = DB::table('tb_mst_sp')->select('id', 'jenis_sp')->get();
    $editsp = SPModel::select(
      'tb_sp.id',
      'tb_sp.photo as photosp',
      'tb_sp.nik',
      'tb_datapribadi.Nama as nama',
      'tb_sp.nik_pemberi_sp',
      'tb_sp.jenis_sp',
      'tb_sp.keterangan',
      'tb_sp.tgl_sk',
      'tb_sp.type_sp',
      DB::raw('(select Nama from tb_datapribadi where NIK = tb_sp.nik_pemberi_sp) as nama_pemberisp'),
      'tb_jabatan.jabatan',
      'tbldivmaster.nama_div_ext'
    )
      ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_sp.nik')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      // ->leftjoin('tb_mst_sp','tb_mst_sp.id','=','tb_sp.jenis_sp')
      ->where('tb_sp.id', $id)
      ->first();

    return view('sp/editsp')->with('editsp', $editsp)->with('jenisSp', $jenisSp);
  }

  public function FUNC_UPDATESP(Request $request)
  {
    $id = $request['id'];
    $update = SPModel::where('id', $id)->first();
    $update->tgl_sk = $request['TanggalSK'];
    $update->type_sp = $request['type_sp'];
    $update->jenis_sp = $request['jenis_sp'];
    $update->keterangan = $request['AlasanSP'];
    if ($request->file('gambar') == '') {
      $update->photo = $update->photo;
    } else {

      Storage::delete("image/SuratPeringatan", $update->photo);
      $file = $request->file('gambar');
      $fileName = $file->getClientOriginalExtension();
      $fileName = $id . $update->nik . '.' . $fileName;
      $request->file('gambar')->move("image/SuratPeringatan", $fileName);
      $update->photo = $fileName;
    }
    $update->update();

    return redirect(url('editsp', [$id]))->with('success', 'Data Berhasil Disimpan.');
  }

  public function FUNC_DELETESP($id)
  {
    $hapus = SPModel::find($id);
    Storage::delete("image/SuratPeringatan", $hapus->photo);
    $hapus->delete();

    return redirect('daftarsp')->with('success', 'Data Berhasil Dihapus');
  }

  public function FUNC_GENERATESKEXCEL($id)
  {

    $explode = explode('-', $id);
    $idnow = $explode[0];
    $nik = $explode[1];

    // $no_sk = $request['No_SK'];
    // $tgl_sk = date_format(date_create($request['tanggalSK']),"d F Y");

    $idsebelum = HistoryJabModel::select(DB::raw('MAX(id) as max_id'))
      ->whereRaw('(tb_his_jabatan.nik = "' . $nik . '"
                                              OR tb_his_jabatan.nik = (SELECT old_nik FROM tb_datapribadi WHERE NIK = "' . $nik . '" ))
                                              AND id < ' . $idnow . '')
      ->first();

    $idprev = $idsebelum->max_id;

    $datanow = HistoryJabModel::select(
      'tb_golongan.gol as nama_golongan',
      'tb_jabatan.jabatan as nama_jabatan',
      'tb_subdivisi.subdivisi as nama_subdivisi',
      'tb_his_jabatan.nik',
      'tb_his_jabatan.gaji',
      'tb_his_jabatan.tunj_jab',
      'tb_his_jabatan.tunj_tmr',
      'tb_lokasikerja.lokasi',
      'tbldivmaster.nama_div_ext as nama_divisi',
      'tb_his_jabatan.no_sk',
      DB::raw('DATE_FORMAT((SUBDATE(`tb_his_jabatan`.`tgl_sk_gol`, 1)),"%d %M %Y") as tgl_yes_tmt'),
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_jab,"%d %M %Y") as tgl_now_sk'),
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_gol,"%d %M %Y") as tgl_now_tmt'),
      DB::raw('(select Nama from tb_datapribadi where NIK = "' . $nik . '" OR old_nik = "' . $nik . '") as nama_karyawan')
    )
      ->leftjoin('tb_golongan', 'tb_golongan.id', '=', 'tb_his_jabatan.golongan')
      ->leftjoin('tb_jabatan', 'tb_jabatan.id', '=', 'tb_his_jabatan.idjabatan')
      ->leftjoin('tb_subdivisi', 'tb_subdivisi.id', '=', 'tb_his_jabatan.subdivisi')
      ->leftjoin('tb_lokasikerja', 'tb_lokasikerja.id', '=', 'tb_his_jabatan.lokasiker')
      ->leftjoin('tbldivmaster', 'tbldivmaster.id', '=', 'tb_his_jabatan.divisi')
      ->whereRaw('(tb_his_jabatan.nik = "' . $nik . '"
                                              OR tb_his_jabatan.nik = (SELECT old_nik FROM tb_datapribadi WHERE NIK = "' . $nik . '" ))
                                            AND tb_his_jabatan.id = ' . $idnow . '')
      ->first();

    $gajinow = $datanow->gaji;
    if ($gajinow == null) {
      $gajifixnow = null;
    } else {
      $gajifixnow = Crypt::decrypt($gajinow);
    }

    $tunj_tmrnow = $datanow->tunj_tmr;
    if ($tunj_tmrnow == null) {
      $tunj_tmrfixnow = null;
    } else {
      $tunj_tmrfixnow = Crypt::decrypt($tunj_tmrnow);
    }

    $tunj_jabnow = $datanow->tunj_jab;
    if ($tunj_jabnow == null) {
      $tunj_jabfixnow = null;
    } else {
      $tunj_jabfixnow = Crypt::decrypt($tunj_jabnow);
    }

    $dataprev = HistoryJabModel::select(
      'tb_golongan.gol as nama_golongan',
      'tb_jabatan.jabatan as nama_jabatan',
      'tb_subdivisi.subdivisi as nama_subdivisi',
      'tb_his_jabatan.nik',
      'tb_his_jabatan.gaji',
      'tb_his_jabatan.tunj_jab',
      'tb_his_jabatan.tunj_tmr',
      'tb_lokasikerja.lokasi',
      'tbldivmaster.nama_div_ext as nama_divisi',
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_jab,"%d %M %Y") as tgl_prev_sk'),
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_gol,"%d %M %Y") as tgl_prev_tmt'),
      // 'tb_his_jabatan.tgl_sk_jab as tgl_mulai_sk','tb_his_jabatan.tgl_sk_gol as tgl_akhir_sk',
      DB::raw('(select Nama from tb_datapribadi where NIK = "' . $nik . '" OR old_nik = "' . $nik . '") as nama_karyawan')
    )
      ->leftjoin('tb_golongan', 'tb_golongan.id', '=', 'tb_his_jabatan.golongan')
      ->leftjoin('tb_jabatan', 'tb_jabatan.id', '=', 'tb_his_jabatan.idjabatan')
      ->leftjoin('tb_subdivisi', 'tb_subdivisi.id', '=', 'tb_his_jabatan.subdivisi')
      ->leftjoin('tb_lokasikerja', 'tb_lokasikerja.id', '=', 'tb_his_jabatan.lokasiker')
      ->leftjoin('tbldivmaster', 'tbldivmaster.id', '=', 'tb_his_jabatan.divisi')
      ->whereRaw('(tb_his_jabatan.nik = "' . $nik . '"
                                              OR tb_his_jabatan.nik = (SELECT old_nik FROM tb_datapribadi WHERE NIK = "' . $nik . '" ))
                                            AND tb_his_jabatan.id = ' . $idprev . '')
      ->first();

    $gajiprev = $dataprev->gaji;
    if ($gajiprev == null) {
      $gajifixprev = null;
    } else {
      $gajifixprev = Crypt::decrypt($gajiprev);
    }

    $tunj_tmrprev = $dataprev->tunj_tmr;
    if ($tunj_tmrprev == null) {
      $tunj_tmrfixprev = null;
    } else {
      $tunj_tmrfixprev = Crypt::decrypt($tunj_tmrprev);
    }

    $tunj_jabprev = $dataprev->tunj_jab;
    if ($tunj_jabprev == null) {
      $tunj_jabfixprev = null;
    } else {
      $tunj_jabfixprev = Crypt::decrypt($tunj_jabprev);
    }

    // Excel::create('SK Promosi - "' . $nik . '"', function ($excel) use ($datanow, $gajifixnow, $tunj_tmrfixnow, $tunj_jabfixnow, $dataprev, $gajifixprev, $tunj_tmrfixprev, $tunj_jabfixprev) {
    //   $excel->sheet('Sheet 1', function ($sheet) use ($datanow, $gajifixnow, $tunj_tmrfixnow, $tunj_jabfixnow, $dataprev, $gajifixprev, $tunj_tmrfixprev, $tunj_jabfixprev) {
    //     $sheet->loadView('employee/generateskexcel')
    //       ->with("datanow", $datanow)
    //       ->with("gajifixnow", $gajifixnow)
    //       ->with("tunj_tmrfixnow", $tunj_tmrfixnow)
    //       ->with("tunj_jabfixnow", $tunj_jabfixnow)
    //       ->with("dataprev", $dataprev)
    //       ->with("gajifixprev", $gajifixprev)
    //       ->with("tunj_tmrfixprev", $tunj_tmrfixprev)
    //       ->with("tunj_jabfixprev", $tunj_jabfixprev)
    //       // ->with("no_sk",$no_sk)
    //       // ->with("tgl_sk",$tgl_sk)
    //     ;
    //   });
    // })->export('xls');
  }

  // $tambah->tgl_sk_jab = $request['tgl_sk_jab']; // tglsk
  // $tambah->tgl_sk_gol = $request['tgl_sk_gol']; // tgltmt

  // public function FUNC_GENERATESKPDF_NUM(Request $request){
  public function FUNC_GENERATESKPDF_NUM($id)
  {

    $explode = explode('-', $id);
    $idnow = $explode[0];
    $nik = $explode[1];

    // $no_sk = $request['No_SK_num'];

    $datanow = HistoryJabModel::select(
      'tb_his_jabatan.nik as nik',
      'tb_his_jabatan.no_sk',
      'tb_his_jabatan.direktur_1 as nik_dir_utama',
      'tb_his_jabatan.direktur_2 as nik_dir_keuangan',
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_jab,"%d %M %Y") as tgl_sk'),
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_gol,"%d %M %Y") as tgl_tmt'),
      DB::raw('(select Nama from tb_datapribadi where NIK = "' . $nik . '" OR old_nik = "' . $nik . '") as nama_karyawan'),
      DB::raw('(select Nama from tb_datapribadi where NIK = tb_his_jabatan.direktur_1 OR old_nik = tb_his_jabatan.direktur_1) as direktur_utama'),
      DB::raw('(select Nama from tb_datapribadi where NIK = tb_his_jabatan.direktur_2 OR old_nik = tb_his_jabatan.direktur_2) as direktur_keuangan')
    )
      ->whereRaw('(tb_his_jabatan.nik = "' . $nik . '"
                                              OR tb_his_jabatan.nik = (SELECT old_nik FROM tb_datapribadi WHERE NIK = "' . $nik . '" ))
                                            AND tb_his_jabatan.id = ' . $idnow . '')
      ->first();

    $pdf = PDF::loadView('employee.generatePdfNum', compact('datanow'))
      ->setPaper('a4')->setOrientation('potrait');

    return $pdf->stream();
  }

  // public function FUNC_GENERATESKPDF_ALIH(Request $request){
  public function FUNC_GENERATESKPDF_ALIH($id)
  {
    $explode = explode('-', $id);
    $idnow = $explode[0];
    $nik = $explode[1];

    // $no_sk = $request['No_SK_alih'];

    $datanow = HistoryJabModel::select(
      'tb_his_jabatan.nik as nik',
      'tb_his_jabatan.no_sk',
      'tb_his_jabatan.direktur_1 as nik_dir_utama',
      'tb_his_jabatan.direktur_2 as nik_dir_keuangan',
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_jab,"%d %M %Y") as tgl_sk'),
      DB::raw('DATE_FORMAT(tb_his_jabatan.tgl_sk_gol,"%d %M %Y") as tgl_tmt'),
      DB::raw('(select Nama from tb_datapribadi where NIK = "' . $nik . '" OR old_nik = "' . $nik . '") as nama_karyawan'),
      DB::raw('(select Nama from tb_datapribadi where NIK = tb_his_jabatan.direktur_1 OR old_nik = tb_his_jabatan.direktur_1) as direktur_utama'),
      DB::raw('(select Nama from tb_datapribadi where NIK = tb_his_jabatan.direktur_2 OR old_nik = tb_his_jabatan.direktur_2) as direktur_keuangan')
    )
      ->whereRaw('(tb_his_jabatan.nik = "' . $nik . '"
                                              OR tb_his_jabatan.nik = (SELECT old_nik FROM tb_datapribadi WHERE NIK = "' . $nik . '" ))
                                            AND tb_his_jabatan.id = ' . $idnow . '')
      ->first();

    $pdf = PDF::loadView('employee.generatePdfAlih', compact('datanow'))
      ->setPaper('a4')->setOrientation('potrait');

    return $pdf->stream();
  }

  public function FUNC_DOWNDOK(Request $request, $id)
  {
    $dok = SPModel::where('id', $id)->first();
    $a = public_path();
    $pdf = $dok->photo;
    $dat = substr($pdf, -3);
    if ($dat == "pdf") {
      $type = "pdf";
    } else {
      $type = "img";
    }
    if ($type == "pdf") {
      $file = $a . '/image/SuratPeringatan/' . $dok->photo;
      return response()->download($file);
    } elseif ($type == "img") {
      $file = $a . '/image/SuratPeringatan/' . $dok->photo;
      return response()->download($file);
    }
  }

  public function FUNC_PREVIEWPDF(Request $request, $id)
  {
    $a = public_path();
    $dok = SPModel::where('id', $id)->first();
    $file = $a . '/image/SuratPeringatan/' . $dok->photo;
    return Response::make(file_get_contents($file), 200, [
      'Content-Type' => 'application/pdf'
    ]);
  }

  public function FUNC_PREVIEWIMG(Request $request)
  {
    $data = $request['data'];
    $cek = SPModel::where('id', $data)->first();

    $bukti = $cek->photo;
    $count = explode("|", $bukti);
    $out = count($count);

    return response()->json(['out' => $count], 200);
  }

  public function FUNC_TAMBAHPD()
  {
    $nik = Session::get('nik');
    if (Session::get('admin') == 1) {
      $query = EmployeeModel::select('NIK', 'Nama', DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('resign', 'N')->get();
    } else {
      $query = EmployeeModel::select('NIK', 'Nama', DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('NIK', $nik)->get();
    }

    return view('pd/tambahpd')->with('nik', $nik)->with('query', $query);
  }

  public function FUNC_CEKTAMBAHPD(Request $request)
  {

    $coba = $request['nik'];

    $querydivjab = EmployeeModel::select('tb_datapribadi.Nama as nama_karyawan', 'tb_datapribadi.TglTetap as mulai_bekerja', 'tbldivmaster.nama_div_ext as namadivisi', 'tb_jabatan.jabatan as namajabatan')
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->where('tb_datapribadi.NIK', $coba)
      ->first();

    $mulai_bekerja = $querydivjab->mulai_bekerja;
    $divisi = $querydivjab->namadivisi;
    $jabatan = $querydivjab->namajabatan;


    return response()->json([
      'tes' => $coba,
      'nama' => $querydivjab->nama_karyawan,
      'mulai_bekerja' => $mulai_bekerja,
      'divisi' => $divisi,
      'jabatan' => $jabatan
    ], 200);
  }

  // public function FUNC_SAVETAMBAHPD(Request $request) {

  //     $idjalan = PerjalananDinasModel::count();
  //     if ($idjalan == 0) {
  //       $idperjalanan = 1;
  //     } else {
  //       $querybro = PerjalananDinasModel::select('id_edit')->orderby('id','DESC')->first();
  //       $idperjalanan = ($querybro->id) + 1;
  //     }



  //     $coba = substr($request['JamMulai'],6,2);
  //     $coba2 = substr($request['JamSelesai'],6,2);

  //     $jam = substr($request['JamMulai'],0,2);
  //     $jam2 = substr($request['JamSelesai'],0,2);

  //     $menit = substr($request['JamMulai'],3,2);
  //     $menit2 = substr($request['JamSelesai'],3,2);

  //     if ($coba == 'PM') {
  //         $jamfix = $jam + 12;
  //     } else {
  //         $jamfix = $jam;
  //     }


  //     if ($coba2 == 'PM') {
  //         $jamfix2 = $jam2 + 12;
  //     } else {
  //         $jamfix2 = $jam2;
  //     }

  //     $jamfixx1 = $jamfix.':'.$menit;
  //     $jamfixx2 = $jamfix2.':'.$menit2;

  //     $stop_date = $request['tgl_akhir'];
  //     $stop_date = date('Y-m-d H:i:s', strtotime($stop_date . ' +1 day'));

  //     $period = new DatePeriod(
  //          new DateTime($request['tgl_awal']),
  //          new DateInterval('P1D'),
  //          new DateTime($stop_date)
  //     );

  //     $karyawan = $request['karyawan'];
  //     foreach ($period as $dt) {
  //       foreach ($karyawan as $kars) {
  //         $tanggalbro = $dt->format("Ymd");
  //         $format_id = $tanggalbro.$kars;

  //         echo $format_id.'<br>';

  //         if (AbsenRekapModel::where('id',$format_id)->exists()) {

  //           $approve2 = AbsenRekapModel::where('id',$format_id)->first();

  //           $selisih_bu = $approve2->selisih;
  //           $status_bu = $approve2->stat;
  //           $ket_bu = $approve2->ket;

  //           $tambah_bu = new BackUpPerjalananModel();
  //           $tambah_bu->id = $format_id;
  //           $tambah_bu->selisih = $selisih_bu;
  //           $tambah_bu->stat = $status_bu;
  //           $tambah_bu->ket = $ket_bu;

  //           $tambah_bu->save();

  //           $approve2->selisih = '0';
  //           $approve2->stat = 'Perjalanan Dinas';
  //           $approve2->ket = $request['keterangan'].' - '.$request['no_surat'];
  //           $approve2->update();

  //         } else {

  //         }
  //       }
  //     }

  //     foreach ($karyawan as $nik) {
  //       $tambah = new PerjalananDinasModel();
  //       $tambah->nik = $nik;
  //       $tambah->tgl_awal = $request['tgl_awal'];
  //       $tambah->tgl_akhir = $request['tgl_akhir'];
  //       $tambah->no_surat = $request['no_surat'];
  //       $tambah->jam_awal = $jamfixx1;
  //       $tambah->jam_akhir = $jamfixx2;
  //       $tambah->keterangan = $request['keterangan'];
  //       $tambah->id_edit = $idperjalanan;

  //       $tambah->save();

  //     }

  //     return redirect()->back()->with('success','Perjalanan Dinas Berhasil Disimpan');
  // }

  public function FUNC_SAVETAMBAHPD(Request $request)
  {

    $idjalan = PerjalananDinasModel::count();
    if ($idjalan == 0) {
      $idperjalanan = 1;
    } else {
      $querybro = PerjalananDinasModel::select('id_edit')->orderby('id', 'DESC')->first();
      $idperjalanan = ($querybro->id_edit) + 1;
    }

    // dd($idperjalanan);

    $coba = substr($request['JamMulai'], 6, 2);
    $coba2 = substr($request['JamSelesai'], 6, 2);

    $jam = substr($request['JamMulai'], 0, 2);
    $jam2 = substr($request['JamSelesai'], 0, 2);

    $menit = substr($request['JamMulai'], 3, 2);
    $menit2 = substr($request['JamSelesai'], 3, 2);

    if ($coba == 'PM') {
      $jamfix = $jam + 12;
    } else {
      $jamfix = $jam;
    }


    if ($coba2 == 'PM') {
      $jamfix2 = $jam2 + 12;
    } else {
      $jamfix2 = $jam2;
    }

    $jamfixx1 = $jamfix . ':' . $menit;
    $jamfixx2 = $jamfix2 . ':' . $menit2;

    $stop_date = $request['tgl_akhir'];
    $stop_date = date('Y-m-d H:i:s', strtotime($stop_date . ' +1 day'));

    $period = new DatePeriod(
      new DateTime($request['tgl_awal']),
      new DateInterval('P1D'),
      new DateTime($stop_date)
    );

    $karyawan = $request['karyawan'];
    foreach ($period as $dt) {
      foreach ($karyawan as $kars) {
        $tanggalbro = $dt->format("Ymd");
        $format_id = $tanggalbro . $kars;

        $nama_kar = EmployeeModel::select('Nama')->where('NIK', $kars)->first();

        $tambahizin = new AbsenIjinModel();
        $tambahizin->id = $format_id;
        $tambahizin->nama = $nama_kar->Nama;
        $tambahizin->nik = $kars;
        $tambahizin->tanggal = $dt;
        $tambahizin->stat = 'Dinas Luar';
        $tambahizin->ket = $request['keterangan'];
        $tambahizin->statusApp = '1';

        $tambahizin->save();
      }
    }

    foreach ($karyawan as $nik) {
      $tambah = new PerjalananDinasModel();
      $tambah->nik = $nik;
      $tambah->tgl_awal = $request['tgl_awal'];
      $tambah->tgl_akhir = $request['tgl_akhir'];
      $tambah->no_surat = $request['no_surat'];
      $tambah->jam_awal = $jamfixx1;
      $tambah->jam_akhir = $jamfixx2;
      $tambah->keterangan = $request['keterangan'];
      $tambah->id_edit = $idperjalanan;

      $tambah->save();
    }

    return redirect()->back()->with('success', 'Perjalanan Dinas Berhasil Disimpan');
  }

  public function FUNC_DAFTARPTH()
  {
    $data = PTHModel::select(
      'tb_pth.*',
      DB::raw('(SELECT Nama From tb_datapribadi WHERE NIK = tb_pth.nik OR old_nik = tb_pth.nik) as nama_pegawai'),
      DB::raw('(SELECT Nama From tb_datapribadi WHERE NIK = tb_pth.nik_pengganti OR old_nik = tb_pth.nik_pengganti) as nama_pengganti'),
      DB::raw('(CASE
                            WHEN tb_pth.id_cuti IS NOT NULL THEN "Cuti"
                            WHEN tb_pth.id_pd IS NOT NULL THEN "Perjalanan Dinas"
                            WHEN tb_pth.id_cuti IS NULL AND tb_pth.id_pd IS NULL THEN "Belum Ada PTH"
                            END) as status
                          ')
    )
      ->get();

    $listpth = EmployeeModel::select('Nama', 'NIK')
      ->where('resign', 'N')
      ->whereRaw('idpangkat IN (4,5,6,7)')
      ->get();

    return view('pth/daftarpth')->with('data', $data)->with('listpth', $listpth);
  }

  public function FUNC_TAMBAHPTH()
  {
    $listpd = PerjalananDinasModel::select(
      'tb_perjalanandinas.id as id',
      DB::raw('CONCAT("(",tb_perjalanandinas.nik,")"," ",tb_datapribadi.Nama," - ",tb_perjalanandinas.keterangan) as tampil')
    )
      ->leftjoin('tb_datapribadi', 'tb_perjalanandinas.nik', '=', 'tb_datapribadi.NIK')
      // ->whereRaw('tb_datapribadi.resign = "N" AND (tb_datapribadi.statuskar = 1 OR tb_datapribadi.statuskar = 2 OR tb_datapribadi.statuskar = 3 OR tb_datapribadi.statuskar = 4)')
      ->where('pth', 0)
      ->where('resign', 'N')
      ->whereRaw('idpangkat IN (4,5,6,7)')
      ->get();

    $listpth = EmployeeModel::select('Nama', 'NIK')
      ->where('resign', 'N')
      ->whereRaw('idpangkat IN (4,5,6,7)')
      ->get();

    return view('pth/tambahpth')->with('listpth', $listpth)->with('listpd', $listpd);
  }


  public function FUNC_CEKADDPTH(Request $request)
  {

    $id = $request['id'];
    $data = PerjalananDinasModel::select(
      'tbldivmaster.nama_div_ext as namadivisi',
      'tb_jabatan.jabatan as namajabatan',
      'tb_perjalanandinas.nik',
      DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_awal,"%d %M %Y") as tgl_awal'),
      DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_akhir,"%d %M %Y") as tgl_akhir'),
      'tb_perjalanandinas.jam_awal',
      'tb_perjalanandinas.jam_akhir',
      'tb_perjalanandinas.keterangan',
      'tb_perjalanandinas.no_surat',
      'tb_perjalanandinas.tgl_awal as date1',
      'tb_perjalanandinas.tgl_akhir as date2'
    )
      ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_perjalanandinas.nik')
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->where('tb_perjalanandinas.id', $id)
      ->first();

    $divisi = $data->namadivisi;
    $jabatan = $data->namajabatan;
    $nik = $data->nik;
    $jam_awal = $data->jam_awal;
    $jam_akhir = $data->jam_akhir;
    $no_surat = $data->no_surat;
    $tgl_awal = $data->tgl_awal;
    $tgl_akhir = $data->tgl_akhir;
    $date1 = $data->date1;
    $date2 = $data->date2;
    $keterangan = $data->keterangan;


    return response()->json([
      'nik' => $nik,
      'divisi' => $divisi,
      'jabatan' => $jabatan,
      'jam_awal' => $jam_awal,
      'jam_akhir' => $jam_akhir,
      'no_surat' => $no_surat,
      'tgl_awal' => $tgl_awal,
      'tgl_akhir' => $tgl_akhir,
      'date1' => $date1,
      'date2' => $date2,
      'keterangan' => $keterangan,
    ], 200);
  }

  public function FUNC_SAVEADDPTH(Request $request)
  {
    $tambahpth = new PTHModel();
    $tambahpth->nik = $request['nik'];
    $tambahpth->nik_pengganti = $request['nik_pth'];
    $tambahpth->tgl_mulai = $request['pth_awal'];
    $tambahpth->tgl_selesai = $request['pth_akhir'];
    $tambahpth->keterangan = $request['keterangan'];
    $tambahpth->id_pd = $request['id'];

    $tambahpth->save();

    $updatepd = PerjalananDinasModel::where('id', $request['id'])->first();
    $updatepd->pth = 1;

    $updatepd->update();

    return redirect('daftarpth')->with('success', 'PTH Berhasil Disimpan');
  }

  public function FUNC_EDITPTH(Request $request, $id)
  {
    $query = PTHModel::select(
      'tb_pth.*',
      DB::raw('(SELECT Nama From tb_datapribadi WHERE NIK = tb_pth.nik OR old_nik = tb_pth.nik) as nama_karyawan'),
      DB::raw('(SELECT Nama From tb_datapribadi WHERE NIK = tb_pth.nik_pengganti OR old_nik = tb_pth.nik_pengganti) as nama_pengganti'),
      DB::raw('(CASE
                          WHEN tb_pth.id_cuti IS NOT NULL THEN "Cuti"
                          WHEN tb_pth.id_pd IS NOT NULL THEN "Perjalanan Dinas"
                          WHEN tb_pth.id_cuti IS NULL AND tb_pth.id_pd IS NULL THEN "Belum Ada PTH"
                          END) as status
                        ')
    )
      ->where('id', $id)
      ->first();

    $listpth = EmployeeModel::select('Nama', 'NIK')
      ->where('resign', 'N')
      ->whereRaw('idpangkat IN (4,5,6,7)')
      ->get();

    return view('pth/editpth')->with('query', $query)->with('listpth', $listpth);
  }

  public function FUNC_UPDATEPTH(Request $request)
  {
    $update = PTHModel::where('id', $request['id'])->first();
    $update->nik_pengganti = $request['nik_pth'];
    $update->tgl_mulai = $request['pth_awal'];
    $update->tgl_selesai = $request['pth_akhir'];

    $update->update();

    return redirect(url('editpth', $request['id']))->with('success', 'Data Berhasil DiUpdate.');
  }
  public function FUNC_DELETEPTH($id)
  {

    $data = PTHModel::select('id_cuti', 'id_pd')->where('id', $id)->first();

    if ($data->id_cuti != null) {
      $updatecuti = CutiModel::where('id', $data->id_cuti)->first();
      $updatecuti->pth = 0;
      $updatecuti->update();
    } elseif ($data->id_pd != null) {
      $updatepd = PerjalananDinasModel::where('id', $data->id_pd)->first();
      $updatepd->pth = 0;
      $updatepd->update();
    }

    $hapus = PTHModel::find($id);
    $hapus->delete();

    return redirect('daftarpth')->with('success', 'Data Berhasil Dihapus');
  }


  public function FUNC_DAFTARPD()
  {

    $nik = Session::get('nik');
    if (Session::get('admin') == 1) {
      $daftarpd = PerjalananDinasModel::select(
        'tb_perjalanandinas.id',
        'tb_perjalanandinas.nik',
        'tb_perjalanandinas.jam_awal',
        'tb_perjalanandinas.jam_akhir',
        DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_awal,"%d %M %Y") as tgl_awal'),
        DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_akhir,"%d %M %Y") as tgl_akhir'),
        DB::raw('(Select Nama From tb_datapribadi where tb_datapribadi.NIK = tb_perjalanandinas.nik OR tb_datapribadi.old_nik = tb_perjalanandinas.nik) as nama_kar'),
        'tb_perjalanandinas.keterangan',
        'tb_perjalanandinas.no_surat',
        'tb_perjalanandinas.id_edit'
      )
        ->orderby('tb_perjalanandinas.id', 'DESC')
        ->groupby('tb_perjalanandinas.id_edit')
        ->get();
    } else {
      $daftarpd = PerjalananDinasModel::select(
        'tb_perjalanandinas.id',
        'tb_perjalanandinas.nik',
        'tb_perjalanandinas.jam_awal',
        'tb_perjalanandinas.jam_akhir',
        DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_awal,"%d %M %Y") as tgl_awal'),
        DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_akhir,"%d %M %Y") as tgl_akhir'),
        DB::raw('(Select Nama From tb_datapribadi where tb_datapribadi.NIK = tb_perjalanandinas.nik OR tb_datapribadi.old_nik = tb_perjalanandinas.nik) as nama_kar'),
        'tb_perjalanandinas.keterangan',
        'tb_perjalanandinas.no_surat',
        'tb_perjalanandinas.id_edit'
      )
        ->whereRaw('tb_perjalanandinas.nik = "' . $nik . '" OR tb_perjalanandinas.nik = (Select old_nik from tb_datapribadi where tb_datapribadi.NIK = "' . $nik . '")')
        ->orderby('tb_perjalanandinas.id', 'DESC')
        ->groupby('tb_perjalanandinas.id_edit')
        ->get();
    }

    return view('pd/daftarpd')->with('daftarpd', $daftarpd);
  }

  public function FUNC_EDITPD($id)
  {
    $query = EmployeeModel::select('NIK', 'Nama', DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('resign', 'N')->get();

    $editpd = PerjalananDinasModel::select(
      'tb_perjalanandinas.id',
      'tb_perjalanandinas.nik',
      'tb_datapribadi.Nama as nama',
      'tb_perjalanandinas.tgl_awal',
      'tb_perjalanandinas.tgl_akhir',
      'tb_perjalanandinas.jam_awal',
      'tb_perjalanandinas.jam_akhir',
      'tb_perjalanandinas.keterangan',
      'tb_jabatan.jabatan',
      'tbldivmaster.nama_div_ext',
      'tb_perjalanandinas.id_edit',
      'tb_perjalanandinas.no_surat'
    )
      ->leftjoin('tb_datapribadi', 'tb_datapribadi.NIK', '=', 'tb_perjalanandinas.nik')
      ->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
      ->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
      ->where('tb_perjalanandinas.id_edit', $id)
      ->get();

    // dd($editpd[0]);
    // dd($editpd[0]->jam_awal);

    $jam_awal = $editpd[0]->jam_awal;
    $jam_awal1 = explode(":", $jam_awal);
    if ($jam_awal1[0] > 12) {
      $jam = $jam_awal1[0] - 12;
      $jam_fix = $jam . ':' . $jam_awal1[1] . ' PM';
    } else {
      $jam = $jam_awal1[0];
      $jam_fix = $jam . ':' . $jam_awal1[1] . ' AM';
    }

    $jam_akhir = $editpd[0]->jam_akhir;
    $jam_akhir1 = explode(":", $jam_akhir);
    if ($jam_akhir1[0] > 12) {
      $jam2 = $jam_akhir1[0] - 12;
      $jam2_fix = $jam2 . ':' . $jam_akhir1[1] . ' PM';
    } else {
      $jam2 = $jam_akhir1[0];
      $jam2_fix = $jam2 . ':' . $jam_akhir1[1] . ' AM';
    }

    return view('pd/editpd')->with('editpd', $editpd)->with('jam_awal', $jam_fix)->with('jam_akhir', $jam2_fix)->with('query', $query);
  }

  public function FUNC_UPDATEPD(Request $request, $id)
  {

    $coba = substr($request['jam_awal'], 6, 2);
    $coba2 = substr($request['jam_akhir'], 6, 2);

    $jam = substr($request['jam_awal'], 0, 2);
    $jam2 = substr($request['jam_akhir'], 0, 2);

    $menit = substr($request['jam_awal'], 3, 2);
    $menit2 = substr($request['jam_akhir'], 3, 2);

    if ($coba == 'PM') {
      $jamfix = $jam + 12;
    } else {
      $jamfix = $jam;
    }


    if ($coba2 == 'PM') {
      $jamfix2 = $jam2 + 12;
    } else {
      $jamfix2 = $jam2;
    }

    $jamfixx1 = $jamfix . ':' . $menit;
    $jamfixx2 = $jamfix2 . ':' . $menit2;

    // jika ada perubahan tanggal yang lama akan di hapus di table absen izin

    if (($request['bu_tgl_awal'] != $request['tgl_awal']) or ($request['bu_tgl_akhir'] != $request['tgl_akhir'])) {

      $loop_idedit = PerjalananDinasModel::select('id', 'id_edit')->where('id_edit', $id)->get();

      foreach ($loop_idedit as $loops) {

        $seacrhtgl = PerjalananDinasModel::select('nik', 'tgl_awal', 'tgl_akhir')->where('id', $loops->id)->first();
        $tgl_awal = $seacrhtgl->tgl_awal;
        $tgl_akhir = $seacrhtgl->tgl_akhir;
        $nik = $seacrhtgl->nik;

        $stop_date = $tgl_akhir;
        $stop_date = date('Y-m-d H:i:s', strtotime($stop_date . ' +1 day'));

        $period = new DatePeriod(
          new DateTime($tgl_awal),
          new DateInterval('P1D'),
          new DateTime($stop_date)
        );

        foreach ($period as $datas) {
          $tanggal_format = $datas->format("Ymd");
          $format_id = $tanggal_format . $nik;

          if (AbsenIjinModel::where('id', $format_id)->exists()) {

            $hapus_bu = AbsenIjinModel::find($format_id);
            $hapus_bu->delete();
          } else {
          }
        }

        // Add Baru Lagi

        $stop_date2 = $request['tgl_akhir'];
        $stop_date2 = date('Y-m-d H:i:s', strtotime($stop_date2 . ' +1 day'));

        $period2 = new DatePeriod(
          new DateTime($request['tgl_awal']),
          new DateInterval('P1D'),
          new DateTime($stop_date2)
        );

        foreach ($period2 as $dt2) {
          $tanggalbro2 = $dt2->format("Ymd");
          $format_id2 = $tanggalbro2 . $nik;

          $nama_kar = EmployeeModel::select('Nama')->where('NIK', $nik)->first();

          $tambahizin = new AbsenIjinModel();
          $tambahizin->id = $format_id2;
          $tambahizin->nama = $nama_kar->Nama;
          $tambahizin->nik = $nik;
          $tambahizin->tanggal = $dt2;
          $tambahizin->stat = 'Dinas Luar';
          $tambahizin->ket = $request['keterangan'];
          $tambahizin->statusApp = '1';

          $tambahizin->save();
        }
      }
    }

    if ($request['checkboxBaru'] == "on" or $request['checkboxBaru'] == "ON") {

      $stop_date3 = $request['tgl_akhir'];
      $stop_date3 = date('Y-m-d H:i:s', strtotime($stop_date3 . ' +1 day'));

      $period3 = new DatePeriod(
        new DateTime($request['tgl_awal']),
        new DateInterval('P1D'),
        new DateTime($stop_date3)
      );

      $karyawan3 = $request['karyawan_new'];
      foreach ($period3 as $dt3) {
        foreach ($karyawan3 as $kars3) {
          $tanggalbro3 = $dt3->format("Ymd");
          $format_id3 = $tanggalbro3 . $kars3;

          $nama_kar = EmployeeModel::select('Nama')->where('NIK', $kars3)->first();

          $tambahizin = new AbsenIjinModel();
          $tambahizin->id = $format_id3;
          $tambahizin->nama = $nama_kar->Nama;
          $tambahizin->nik = $kars3;
          $tambahizin->tanggal = $dt3;
          $tambahizin->stat = 'Dinas Luar';
          $tambahizin->ket = $request['keterangan'];
          $tambahizin->statusApp = '1';

          $tambahizin->save();
        }
      }

      foreach ($karyawan3 as $nik) {
        $tambah = new PerjalananDinasModel();
        $tambah->nik = $nik;
        $tambah->tgl_awal = $request['tgl_awal'];
        $tambah->tgl_akhir = $request['tgl_akhir'];
        $tambah->no_surat = $request['no_surat'];
        $tambah->jam_awal = $jamfixx1;
        $tambah->jam_akhir = $jamfixx2;
        $tambah->keterangan = $request['keterangan'];
        $tambah->id_edit = $id;

        $tambah->save();
      }
    } else {
    }

    $update = PerjalananDinasModel::where('id_edit', '=', $id)
      ->update([
        'tgl_awal' => $request['tgl_awal'],
        'tgl_akhir' => $request['tgl_akhir'],
        'jam_awal' => $jamfixx1,
        'jam_akhir' => $jamfixx2,
        'no_surat' => $request['no_surat'],
        'keterangan' => $request['keterangan']
      ]);

    return redirect(url('editpd', [$id]))->with('success', 'Data Berhasil Disimpan.');
  }

  public function FUNC_DELETEPD($id)
  {
    $hapus = SPModel::find($id);
    $hapus->delete();

    return redirect('daftarsp')->with('success', 'Data Berhasil Dihapus');
  }

  public function FUNC_DETAILPD(Request $request)
  {

    $id_edit = $request['id_edit'];

    $query = PerjalananDinasModel::select(
      'tb_perjalanandinas.nik',
      'tb_perjalanandinas.jam_awal',
      'tb_perjalanandinas.jam_akhir',
      'tb_perjalanandinas.keterangan',
      'tb_perjalanandinas.no_surat',
      'tb_perjalanandinas.status',
      DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_awal,"%d %M %Y") as tgl_mulai'),
      DB::raw('DATE_FORMAT(tb_perjalanandinas.tgl_akhir,"%d %M %Y") as tgl_selesai'),
      DB::raw('(select Nama from tb_datapribadi where tb_datapribadi.nik = tb_perjalanandinas.nik OR tb_datapribadi.old_nik = tb_perjalanandinas.nik) as nama_karyawan')
    )
      ->where('tb_perjalanandinas.id_edit', $id_edit)
      ->get();

    return response()->json([
      'query' => $query
    ], 200);
  }

  // public function FUNC_DELETESPDKAR($id){

  //   $pisah = explode("-", $id);
  //   $id_jalan = $pisah[0];
  //   $id_edit = $pisah[1];

  //   $seacrhtgl = PerjalananDinasModel::select('nik','tgl_awal','tgl_akhir')->where('id',$id_jalan)->first();
  //   $tgl_awal = $seacrhtgl->tgl_awal;
  //   $tgl_akhir = $seacrhtgl->tgl_akhir;
  //   $nik = $seacrhtgl->nik;

  //   $stop_date = $tgl_akhir;
  //   $stop_date = date('Y-m-d H:i:s', strtotime($stop_date . ' +1 day'));

  //   $period = new DatePeriod(
  //        new DateTime($tgl_awal),
  //        new DateInterval('P1D'),
  //        new DateTime($stop_date)
  //   );

  //   foreach ($period as $datas) {
  //     $tanggal_format = $datas->format("Ymd");
  //     $format_id = $tanggal_format.$nik;

  //     $show_awal = BackUpPerjalananModel::where('id',$format_id)->first();
  //     $selisih_awal = $show_awal->selisih;
  //     $stat_awal = $show_awal->stat;
  //     $ket_awal = $show_awal->ket;

  //     if (AbsenRekapModel::where('id',$format_id)->exists()) {

  //       $update_to_awal = AbsenRekapModel::where('id',$format_id)->first();
  //       $update_to_awal->selisih = $selisih_awal;
  //       $update_to_awal->stat = $stat_awal;
  //       $update_to_awal->ket = $ket_awal;

  //       $update_to_awal->update();

  //       $hapus_bu = BackUpPerjalananModel::find($format_id);
  //       $hapus_bu->delete();

  //     } else {

  //     }
  //   }

  //   $hapus_jalandinas = PerjalananDinasModel::where('id',$id_jalan)->where('id_edit',$id_edit)->delete();
  //   $query = EmployeeModel::select('NIK','Nama',DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('resign','N')->get();

  //   return redirect(url('editpd',[$id_edit]))->with('success','Data Berhasil Dihapus.')->with('query',$query);

  // }

  public function FUNC_DELETESPDKAR($id)
  {

    $pisah = explode("-", $id);
    $id_jalan = $pisah[0];
    $id_edit = $pisah[1];

    $seacrhtgl = PerjalananDinasModel::select('nik', 'tgl_awal', 'tgl_akhir')->where('id', $id_jalan)->first();
    $tgl_awal = $seacrhtgl->tgl_awal;
    $tgl_akhir = $seacrhtgl->tgl_akhir;
    $nik = $seacrhtgl->nik;

    $stop_date = $tgl_akhir;
    $stop_date = date('Y-m-d H:i:s', strtotime($stop_date . ' +1 day'));

    $period = new DatePeriod(
      new DateTime($tgl_awal),
      new DateInterval('P1D'),
      new DateTime($stop_date)
    );

    foreach ($period as $datas) {
      $tanggal_format = $datas->format("Ymd");
      $format_id = $tanggal_format . $nik;

      if (AbsenIjinModel::where('id', $format_id)->exists()) {

        $hapus_bu = AbsenIjinModel::find($format_id);
        $hapus_bu->delete();
      } else {
      }
    }

    $hapus_jalandinas = PerjalananDinasModel::where('id', $id_jalan)->where('id_edit', $id_edit)->delete();
    $query = EmployeeModel::select('NIK', 'Nama', DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('resign', 'N')->get();

    return redirect(url('editpd', [$id_edit]))->with('success', 'Data Berhasil Dihapus.')->with('query', $query);
  }

  public function employeedata()
  {
    $data = EmployeeModel::select('Nama', 'email', 'TanggalLahir', 'jk as gender')
      ->where('resign', 'N')
      ->whereNotIn('email', ['budi@edi-indonesia.co.id', 'masagung@edi-indonesia.co.id', 'syafrizal@edi-indonesia.co.id', 'tiara.pitaloka@edi-indonesia.co.id'])
      ->get();
    return $data;
  }

  /** Add By Dandy Firmansyah 5 Oktober 2018 **/

  public function insertToHRIS()
  {
    $getData = DB::table('temp_karyawan_30Sept')
      ->select('*')
      ->where('toHris', 0)
      ->where(function ($query) {
        $query->where('atasan1', '<>', NULL)
          ->orWhere('atasan1', '<>', '');
      })
      ->where(function ($query) {
        $query->where('divisi', '<>', NULL)
          ->orWhere('divisi', '<>', '');
      })
      ->get();

    foreach ($getData as $datas) {
      // get id divisi
      $id_pangkat   = DB::table('tb_pangkat')->where('type', NULL)->where('pangkat', $datas->pangkat)->value('id');

      $pangkat_tinggi = array(5, 6, 7, 1954);
      if (in_array($id_pangkat, $pangkat_tinggi)) {
        $id_jabatan = '1';
      } else {
        $id_jabatan   = DB::table('tb_jabatan')->where('type', NULL)->where('jabatan', $datas->jabatan)->value('id');
      }

      $id_divisi    = DB::table('tbldivmaster')->where('type', NULL)->where('nama_div_ext', $datas->divisi)->value('id');

      if ($id_pangkat == '5') {
        $id_subdivisi = '1';
      } else {
        $id_subdivisi = DB::table('tb_subdivisi')->where('type', NULL)->where('subdivisi', $datas->subdivisi)->value('id');
      }


      DB::table('temp_karyawan_30Sept')
        ->where('no', $datas->no)
        ->update([
          'idpangkat' => $id_pangkat,
          'idjabatan' => $id_jabatan,
          'iddivisi' => $id_divisi,
          'idsubdivisi' => $id_subdivisi,
        ]);
    }
  }

  public function executionToTable()
  {
    $getData = DB::table('temp_karyawan_30Sept')
      ->select('*')
      ->where('toHris', 0)
      ->where(function ($query) {
        $query->where('atasan1', '<>', NULL)
          ->orWhere('atasan1', '<>', '');
      })
      ->where(function ($query) {
        $query->where('divisi', '<>', NULL)
          ->orWhere('divisi', '<>', '');
      })
      ->whereNotIn('nik', ['6695009', '6895021', '6896023', '7397035', '7797038', '7797039', '7797052', '7697054', '7898066', '7198068', '7095015', '182687', '182688', '182689', '182690', '182693', '182694', '182695', '182691', '182696', '182697'])
      ->limit(1)
      ->get();

    // dd($getData);

    foreach ($getData as $datas) {
      //get last data history jabatan
      $data_lasthis = DB::table('tb_his_jabatan')->where('nik', $datas->nik)->orderby('id', 'DESC')->first();

      if ($datas->status == 'Direksi') {
        $statuskar = '1';
      } else {
        $statuskar   = DB::table('tb_statuskar')->where('status_kar', $datas->status)->value('id');
      }

      // get lokasi kerja
      $lokasikerja = DB::table('tb_lokasikerja')->where('lokasi', $datas->lokasiker)->value('id');

      if ($data_lasthis) {
        // add to history_jabatan
        $insert_his = DB::table('tb_his_jabatan')->insert(
          [
            'nik'             => $datas->nik,
            'TglKontrak'      => NULL,
            'TglKontrakEnd'   => NULL,
            'golongan'        => $data_lasthis->golongan,
            'golongan_out'    => $data_lasthis->golongan_out,
            'tgl_sk_gol'      => NULL,
            'idpangkat'       => $datas->idpangkat,
            'idjabatan'       => $datas->idjabatan,
            'tgl_sk_jab'      => NULL,
            'divisi'          => $datas->iddivisi,
            'subdivisi'       => $datas->idsubdivisi,
            'atasan1'         => $datas->atasan1,
            'atasan2'         => $datas->atasan2,
            'statuskar'       => $statuskar,
            'lokasiker'       => $lokasikerja,
          ]
        );

        // update tb_datapribadi
        $update_datapribadi = DB::table('tb_datapribadi')
          ->where('NIK', $datas->nik)
          ->update([
            'atasan1'         => $datas->atasan1,
            'atasan2'         => $datas->atasan2,
            'TglKontrak'      => NULL,
            'TglKontrakEnd'   => NULL,
            'idpangkat'       => $datas->idpangkat,
            'idjabatan'       => $datas->idjabatan,
            'Divisi'          => $datas->iddivisi,
            'SubDivisi'       => $datas->idsubdivisi,
            'LokasiKer'       => $lokasikerja,
            'statuskar'       => $statuskar,
            'resign'          => 'N',
          ]);

        // update toHris menjadi 1
        if ($insert_his) {
          DB::table('temp_karyawan_30Sept')
            ->where('no', $datas->no)
            ->update([
              'toHris' => '1',
            ]);
          $pesan = '<p style="color:green;">' . $datas->nik . ' - ' . $datas->nama . ' Sukses Updated.</p>';
        } else {
          DB::table('temp_karyawan_30Sept')
            ->where('no', $datas->no)
            ->update([
              'toHris' => '2',
            ]);
          $pesan = '<p style="color:red;">' . $datas->nik . ' - ' . $datas->nama . ' Gagal Updated.</p>';
        }
      } else {

        // mungkin sudah menjadi pegawai EDII
        $old_nik = DB::table('tb_datapribadi')->where('Nama', $datas->nama)->value('NIK'); // old nik
        // dd($old_nik);
        $check = DB::table('tb_his_jabatan')->where('nik', $old_nik)->orderBy('id', 'DESC')->first();

        if ($check) {

          $insert_his_new = DB::table('tb_his_jabatan')->insert(
            [
              'nik'             => $datas->nik,
              'TglKontrak'      => NULL,
              'TglKontrakEnd'   => NULL,
              'golongan'        => NULL,
              'golongan_out'    => NULL,
              'tgl_sk_gol'      => NULL,
              'idpangkat'       => $datas->idpangkat,
              'idjabatan'       => $datas->idjabatan,
              'tgl_sk_jab'      => NULL,
              'divisi'          => $datas->iddivisi,
              'subdivisi'       => $datas->idsubdivisi,
              'atasan1'         => $datas->atasan1,
              'atasan2'         => $datas->atasan2,
              'statuskar'       => $statuskar,
              'lokasiker'       => $lokasikerja,
              'old_nik'         => $old_nik,
            ]
          );

          // update tb_datapribadi
          $update_datapribadi_new = DB::table('tb_datapribadi')
            ->where('NIK', $old_nik)
            ->update([
              'NIK'             => $datas->nik,
              'atasan1'         => $datas->atasan1,
              'atasan2'         => $datas->atasan2,
              'TglKontrak'      => NULL,
              'TglKontrakEnd'   => NULL,
              'idpangkat'       => $datas->idpangkat,
              'idjabatan'       => $datas->idjabatan,
              'Divisi'          => $datas->iddivisi,
              'SubDivisi'       => $datas->idsubdivisi,
              'LokasiKer'       => $lokasikerja,
              'statuskar'       => $statuskar,
              'resign'          => 'N',
            ]);

          if ($insert_his_new) {
            DB::table('temp_karyawan_30Sept')
              ->where('no', $datas->no)
              ->update([
                'toHris' => '1',
              ]);
            $pesan = '<p style="color:green;">' . $datas->nik . ' - ' . $datas->nama . ' Sukses Updated.</p>';
          } else {
            DB::table('temp_karyawan_30Sept')
              ->where('no', $datas->no)
              ->update([
                'toHris' => '2',
              ]);
            $pesan = '<p style="color:red;">' . $datas->nik . ' - ' . $datas->nama . ' Gagal Updated.</p>';
          }
        } else {
          DB::table('temp_karyawan_30Sept')
            ->where('no', $datas->no)
            ->update([
              'toHris' => '3',
            ]);
          $pesan = '<p style="color:red;">' . $datas->nik . ' - ' . $datas->nama . ' Belum terdaftar.</p>';
        }
      }

      echo $pesan;
    }
  }

  public function checkResignOrNot()
  {
    $employeeData = DB::table('tb_datapribadi')
      ->where('resign', 'N')
      ->whereNotIn('NIK', ['2018003', '172601', '172556', '162524', '162468', '152409', '142349', '142340', '142378'])
      ->get();

    $pesan = '';
    foreach ($employeeData as $datas) {
      $checkTemp = DB::table('temp_karyawan_30Sept')->where('nik', $datas->NIK)->count();
      if ($checkTemp == 0) {
        DB::table('tb_datapribadi')
          ->where('NIK', $datas->NIK)
          ->update([
            'resign' => 'Y',
          ]);
        $pesan .= $datas->NIK . ' ' . $datas->Nama . ' Resign. <br>';
      }
    }

    echo $pesan;
    die();
  }

  /** End Add By Dandy Firmansyah 5 Oktober 2018 **/

  /** Add By Dandy Firmansyah 06 Februari 2019 **/
  public function update_idmaster()
  {
    $getData = DB::table('temp_karyawan_mutasi_Februari2019')
      ->select('*')
      ->where('update_master', 0)
      ->get();

    foreach ($getData as $datas) {
      // get id divisi
      if ($datas->Pangkat != "" || $datas->Pangkat != NULL) {
        $id_pangkat   = DB::table('tb_pangkat')->where('type', NULL)->where('pangkat', $datas->Pangkat)->value('id');
        $nik_last = $datas->NIK;
      } else {
        // get pangkat terakhir karyawan
        $getPangkatTerakhir = DB::table('tb_his_jabatan')->select('*')
          ->where('nik', $datas->NIK)
          ->orWhere('old_nik', $datas->NIK)
          ->orderBy('id', 'DESC')
          ->first();
        if ($getPangkatTerakhir) {
          $nik_last = $getPangkatTerakhir->nik;
          $id_pangkat =  $getPangkatTerakhir->idpangkat;
        } else {
          $id_pangkat = DB::table('tb_datapribadi')->where('NIK', $datas->NIK)->value('idpangkat');
          $nik_last = $datas->NIK;
        }
      }

      $pangkat_tinggi = array(5, 6, 7, 1954, 1951, 1952);
      if (in_array($id_pangkat, $pangkat_tinggi)) {
        $id_jabatan = '1';
      } else {
        $id_jabatan   = DB::table('tb_jabatan')->where('type', NULL)->where('jabatan', $datas->Jabatan)->value('id');
      }

      $id_divisi    = DB::table('tbldivmaster')->where('type', NULL)->where('nama_div_ext', $datas->Divisi)->value('id');

      if ($id_pangkat == '5') {
        $id_subdivisi = '1';
      } else {
        $id_subdivisi = DB::table('tb_subdivisi')->where('type', NULL)->where('subdivisi', $datas->Departemen)->value('id');
      }

      DB::table('temp_karyawan_mutasi_Februari2019')
        ->where('id', $datas->id)
        ->update([
          'NIK' => $nik_last,
          'id_pangkat' => $id_pangkat,
          'id_jabatan' => $id_jabatan,
          'id_divisi' => $id_divisi,
          'id_department' => $id_subdivisi,
          'update_master' => 1
        ]);
    }
  }

  public function executionToHRIS()
  {
    $getData = DB::table('temp_karyawan_mutasi_Februari2019')
      ->select('*')
      ->where('insert_to_hris', NULL)
      // ->limit(1)
      ->get();

    // dd($getData);
    foreach ($getData as $datas) {
      //get last data history jabatan
      $data_lasthis = DB::table('tb_his_jabatan')->where('nik', $datas->NIK)->orderby('id', 'DESC')->first();
      if ($data_lasthis) {
        // add to history_jabatan
        $insert_his = DB::table('tb_his_jabatan')->insert(
          [
            'nik'             => $datas->NIK,
            'TglKontrak'      => $data_lasthis->TglKontrak,
            'TglKontrakEnd'   => $data_lasthis->TglKontrakEnd,
            'golongan'        => $data_lasthis->golongan,
            'golongan_out'    => $data_lasthis->golongan_out,
            'tgl_sk_gol'      => '2019-01-01',
            'idpangkat'       => $datas->id_pangkat,
            'idjabatan'       => $datas->id_jabatan,
            'tgl_sk_jab'      => '2019-01-01',
            'divisi'          => $datas->id_divisi,
            'subdivisi'       => $datas->id_department,
            'atasan1'         => $datas->atasan1,
            'atasan2'         => $datas->atasan2,
            'statuskar'       => $data_lasthis->statuskar,
            'lokasiker'       => $data_lasthis->lokasiker,
          ]
        );

        // update tb_datapribadi
        $update_datapribadi = DB::table('tb_datapribadi')
          ->where('NIK', $datas->NIK)
          ->update([
            'atasan1'         => $datas->atasan1,
            'atasan2'         => $datas->atasan2,
            'idpangkat'       => $datas->id_pangkat,
            'idjabatan'       => $datas->id_jabatan,
            'Divisi'          => $datas->id_divisi,
            'SubDivisi'       => $datas->id_department,
            'resign'          => 'N',
          ]);

        // update toHris menjadi 1
        if ($insert_his) {
          DB::table('temp_karyawan_mutasi_Februari2019')
            ->where('id', $datas->id)
            ->update([
              'insert_to_hris' => '1',
            ]);
          $pesan = '<p style="color:green;">' . $datas->NIK . ' - ' . $datas->Nama . ' Sukses Updated.</p>';
        } else {
          DB::table('temp_karyawan_mutasi_Februari2019')
            ->where('id', $datas->id)
            ->update([
              'insert_to_hris' => '2',
            ]);
          $pesan = '<p style="color:red;">' . $datas->NIK . ' - ' . $datas->Nama . ' Gagal Updated.</p>';
        }
      } else {

        // // mungkin sudah menjadi pegawai EDII
        // $old_nik = DB::table('tb_datapribadi')->where('Nama', $datas->Nama)->value('NIK'); // old nik
        // // dd($old_nik);
        // $check = DB::table('tb_his_jabatan')->where('nik', $old_nik)->orderBy('id','DESC')->first();

        // if ($check) {

        //   $insert_his_new = DB::table('tb_his_jabatan')->insert(
        //                   [
        //                     'nik'             => $datas->NIK,
        //                     'TglKontrak'      => NULL,
        //                     'TglKontrakEnd'   => NULL,
        //                     'golongan'        => NULL,
        //                     'golongan_out'    => NULL,
        //                     'tgl_sk_gol'      => '2019-01-01',
        //                     'idpangkat'       => $datas->id_pangkat,
        //                     'idjabatan'       => $datas->id_jabatan,
        //                     'tgl_sk_jab'      => '2019-01-01',
        //                     'divisi'          => $datas->id_divisi,
        //                     'subdivisi'       => $datas->id_subdivisi,
        //                     'atasan1'         => $datas->atasan1,
        //                     'atasan2'         => $datas->atasan2,
        //                     'statuskar'       => $statuskar,
        //                     'lokasiker'       => $lokasikerja,
        //                     'old_nik'         => $old_nik,
        //                   ]
        //               );

        //   // update tb_datapribadi
        //   $update_datapribadi_new = DB::table('tb_datapribadi')
        //                         ->where('NIK', $old_nik)
        //                         ->update([
        //                             'NIK'             => $datas->nik,
        //                             'atasan1'         => $datas->atasan1,
        //                             'atasan2'         => $datas->atasan2,
        //                             'TglKontrak'      => NULL,
        //                             'TglKontrakEnd'   => NULL,
        //                             'idpangkat'       => $datas->idpangkat,
        //                             'idjabatan'       => $datas->idjabatan,
        //                             'Divisi'          => $datas->iddivisi,
        //                             'SubDivisi'       => $datas->idsubdivisi,
        //                             'LokasiKer'       => $lokasikerja,
        //                             'statuskar'       => $statuskar,
        //                             'resign'          => 'N',
        //                           ]);

        //   if ($insert_his_new) {
        //     DB::table('temp_karyawan_30Sept')
        //       ->where('no', $datas->no)
        //       ->update([
        //           'toHris' => '1',
        //         ]);
        //     $pesan = '<p style="color:green;">'.$datas->nik.' - '.$datas->nama.' Sukses Updated.</p>';
        //   }else{
        //     DB::table('temp_karyawan_30Sept')
        //       ->where('no', $datas->no)
        //       ->update([
        //           'toHris' => '2',
        //         ]);
        //     $pesan = '<p style="color:red;">'.$datas->nik.' - '.$datas->nama.' Gagal Updated.</p>';
        //   }
        // }else{
        //   DB::table('temp_karyawan_30Sept')
        //         ->where('no', $datas->no)
        //         ->update([
        //             'toHris' => '3',
        //           ]);
        //   $pesan = '<p style="color:red;">'.$datas->nik.' - '.$datas->nama.' Belum terdaftar.</p>';
        // }

        DB::table('temp_karyawan_mutasi_Februari2019')
          ->where('id', $datas->id)
          ->update([
            'insert_to_hris' => '3',
          ]);
        $pesan = '<p style="color:red;">' . $datas->NIK . ' - ' . $datas->Nama . ' Data Belum ada History.</p>';
      }

      echo $pesan;
    }
  }

  /** End Add By Dandy Firmansyah 06 Februari 2019 **/
}
