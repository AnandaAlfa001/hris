<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\EmployeeModel;
use App\Models\CutiModel;
use App\Models\CalendarModel;
use App\Models\HakCutiModel;
use App\Models\AbsenIjinModel;
use Mail;
use App\Models\PTHModel;
use GuzzleHttp\Client;

class CutiController extends Controller
{
    public function FUNC_SENDEMAIL($data) {

        Mail::send('cuti.emailcuti', $data, function ($message) {

            $message->from('dandygantengkok@gmail.com', 'riyan');
            $message->to('dandygantengkok@gmail.com')->subject('HRIS EDII Kesehatan');

        });
    }

    public function FUNC_ADDCUTI() {

        $tampilnama = EmployeeModel::select('NIK as nik','Nama',
                                    DB::raw('CONCAT(NIK , "-" , Nama) as uhuy')
                                    )
                                    ->where('resign','N')
                                    ->orwhere('statuskar','1')
                                    ->orwhere('statuskar','2')
                                    ->orwhere('statuskar','3')
                                    ->orwhere('statuskar','4')
                                    ->get();

        $listpth = EmployeeModel::select('Nama','NIK')
                                ->where('resign','N')
                                ->whereRaw('idpangkat IN (4,5,6,7)')
                                ->get();

        return view('cuti/addcuti')->with('tampilnama',$tampilnama)->with('listpth',$listpth);
    }



     public function FUNC_HISTORYCUTI() {

        $nik = Session::get('nik');

        if (Session::get('admin') == 1) {
            $historycutis = CutiModel::select('tb_cuti.ID as id','tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti','tb_cuti.approve_1','tb_cuti.approve_2','tb_cuti.Keterangan as keterangan',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti'),
            DB::raw('CASE
                    WHEN `approve_2` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti2222')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.NIK','=','tb_datapribadi.NIK')
            ->groupBy('tb_cuti.NIK')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();
        }
        elseif (Session::get('admin') == 2 or Session::get('admin') == 3) {
            $historycutis = CutiModel::select('tb_cuti.ID as id','tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti','tb_cuti.approve_1','tb_cuti.approve_2','tb_cuti.Keterangan as keterangan',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti'),
            DB::raw('CASE
                    WHEN `approve_2` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti2222')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->where('tb_cuti.NIK',$nik)
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();
        }

        // dd($historycutis);
        $pangkat = EmployeeModel::select('idpangkat')->where('NIK',$nik)->first();
        $pangkats = $pangkat->idpangkat;

        return view('cuti/historycuti')->with('historycutis',$historycutis)->with('pangkats',$pangkats);
    }

     public function FUNC_LISTREQUESTCUTI() {

        $cutirequest = CutiModel::select('tb_cuti.ID as id','tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti','tb_cuti.approve_1','tb_cuti.approve_2','tb_cuti.Keterangan as keterangan','tb_datapribadi.idpangkat',
            DB::raw('(SELECT Nama from tb_datapribadi where NIK = tb_pth.nik_pengganti) as nama_pengganti'),
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti'),
            DB::raw('CASE
                    WHEN `approve_2` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti2222')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.NIK','=','tb_datapribadi.NIK')
            ->leftjoin('tb_pth','tb_pth.id_cuti','=','tb_cuti.ID')
            // ->groupBy('tb_cuti.NIK')
            ->where('tb_cuti.approve_1','N')
            ->orWhere('tb_cuti.approve_2','N')
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

        $listpth = EmployeeModel::select('Nama','NIK')
                        ->where('resign','N')
                        ->whereRaw('idpangkat IN (4,5,6,7)')
                        ->get();

        return view('cuti/listrequestcuti')->with('cutirequest',$cutirequest)->with('listpth',$listpth);
    }

    public function FUNC_DETAILHISTORYCUTI($nik) {

        $buatnama = EmployeeModel::select('Nama as nama','NIK','idpangkat',
            DB::raw('CONCAT(NIK, " - ", Nama) as formatnama')
            )
            ->where('tb_datapribadi.NIK',$nik)
            ->first();

        $historycutis = CutiModel::select('tb_cuti.ID as id','tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti','tb_cuti.approve_1','tb_cuti.approve_2','tb_cuti.Keterangan as keterangan',
            DB::raw('(SELECT Nama from tb_datapribadi where NIK = tb_pth.nik_pengganti) as nama_pengganti'),
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status'),
            DB::raw('CASE
                    WHEN `approve_2` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as status2')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->leftjoin('tb_pth','tb_pth.id_cuti','=','tb_cuti.ID')
            ->where('tb_cuti.nik',$nik)
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

        // dd($historycutis);

        return view('cuti/detailhistorycuti')->with('buatnama',$buatnama)->with('historycutis',$historycutis)->with('idpangkat',$buatnama->idpangkat);

    }



    public function FUNC_EDITCUTI($id) {
        $tampiledit = CutiModel::select('tb_cuti.ID as id','tb_cuti.nik as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.rencanacuti as hari','tb_cuti.Keterangan as keterangan')
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->where('tb_cuti.ID',$id)
            ->first();

        return view('cuti/editcuti')->with('tampiledit',$tampiledit);
    }

    public function FUNC_UPDATECUTI(Request $request, $id) {

        $nik = $request['nik'];

        $tgl_mulai = $request['tgl_mulai'];
        $tgl_selesai = $request['tgl_selesai'];
        $hari = $request['hari'];
        $keterangan = $request['keterangan'];

        $update = CutiModel::where('ID',$id)->first();
        $update->TanggalSelesaiCuti = $request['tgl_selesai'];
        $update->TanggalMulaiCuti = $request['tgl_mulai'];
        $update->RencanaCuti = $request['hari'];
        $update->Keterangan = $request['keterangan'];
        // $update->type = $request['type'];

        $update->update();

        return redirect(url('historycuti'))->with('success','Data Berhasil DiUpdate.');

    }

    public function FUNC_DELETECUTI(Request $request, $id) {

        $nik = $request['nik'];

        $hapus = CutiModel::find($id);
        $hapus->delete();

       return redirect(url('historycuti'))->with('success','Data Berhasil Dihapus.');
    }

    public function FUNC_INPUTPTH(Request $request)
    {
        $id = $request['id'];
        $tampiledit = CutiModel::select('tb_cuti.ID as id','tb_cuti.nik as nik','tb_datapribadi.Nama as nama','tb_cuti.rencanacuti as hari','tb_cuti.Keterangan as keterangan',
            DB::raw('DATE_FORMAT(tb_cuti.TanggalMulaiCuti,"%d %M %Y") as tgl_mulai'), DB::raw('DATE_FORMAT(tb_cuti.TanggalSelesaiCuti,"%d %M %Y") as tgl_selesai'),'tb_cuti.TanggalMulaiCuti','tb_cuti.TanggalSelesaiCuti'
        )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->where('tb_cuti.ID',$id)
            ->first();

        return response()->json(['data' => $tampiledit
                                 ],200);
    }

    public function FUNC_INPUTPTHSAVE(Request $request)
    {

        $id = $request['id'];
        $updatecuti = CutiModel::where('id',$id)->first();
        $updatecuti->pth = 1;
        $updatecuti->update();

        $tambahpth = new PTHModel();
        $tambahpth->nik = $request['nik'];
        $tambahpth->nik_pengganti = $request['nik_pth'];
        $tambahpth->tgl_mulai = $request['date1'];
        $tambahpth->tgl_selesai = $request['date2'];
        $tambahpth->keterangan = $request['keterangan'];
        $tambahpth->id_cuti = $request['id'];
        $tambahpth->save();

        return redirect('listrequestcuti')->with('success','Data Berhasil Disimpan.');

    }

    public function FUNC_CEKADDCUTI(Request $request) {

        $coba = $request['nik'];


        $querydivjab = EmployeeModel::select('tb_datapribadi.TglTetap as mulai_bekerja','tbldivmaster.nama_div_ext as namadivisi','tb_jabatan.jabatan as namajabatan','tb_hakcuti.sisa_cuti as sisa_cuti','tb_hakcuti.hak_cuti as hak_cuti','tb_hakcuti.cuti_ambil as cuti_ambil','tb_datapribadi.idpangkat')
            ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
            ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
            ->leftjoin('tb_hakcuti','tb_datapribadi.NIK','=','tb_hakcuti.NIK')
            ->where('tb_datapribadi.NIK',$coba)
            ->first();

        // $queryhakcuti = HakCutiModel::select('sisa_cuti','hak_cuti','cuti_ambil')
        //     ->where('NIK',$coba)
        //     ->first();
        $idpangkat = array(2,3,4,5,6,7);
        if(in_array($querydivjab->idpangkat,$idpangkat))
        {
            $status = "Y";
        }else{
            $status = "N";
        }

        $mulai_bekerja = $querydivjab->mulai_bekerja;
        $divisi = $querydivjab->namadivisi;
        $jabatan = $querydivjab->namajabatan;
        $sisa_cuti_seb = $querydivjab->sisa_cuti;
        $hak_cuti = $querydivjab->hak_cuti;
        $cuti_ambil = $querydivjab->cuti_ambil;
        $sisa_cuti = ($sisa_cuti_seb + $hak_cuti) - $cuti_ambil;


        return response()->json(['tes' => $coba,
                                 'mulai_bekerja' => $mulai_bekerja,
                                 'divisi' => $divisi,
                                 'jabatan' => $jabatan,
                                 'sisa_cuti_seb' => $sisa_cuti_seb,
                                 'hak_cuti' => $hak_cuti,
                                 'cuti_ambil' => $cuti_ambil,
                                 'sisa_cuti' => $sisa_cuti,
                                 'status' => $status
                                 ],200);

    }

    public function FUNC_SELISIHTANGGAL(Request $request) {

        $date1 = $request['date1'];
        $date2 = $request['date2'];

        $selisih = ((abs(strtotime ($date1) - strtotime ($date2)))/(60*60*24)+1);

        // $jumlahliburs = "SELECT COUNT(*) FROM calendar_table WHERE (dt BETWEEN '".$date1."' AND '".$date2."')
        //         AND ((isHoliday = 1 and isWeekday = 1) OR (isHoliday = 0 AND isWeekday = 0) OR (isHoliday = 1 and isWeekday = 0))";
        // $jumlahlibur = DB::select($jumlahliburs);


        $jumlahlibur = CalendarModel::whereRaw(DB::raw('(dt BETWEEN "'.$date1.'" AND "'.$date2.'") and ((isHoliday = 1 and isWeekday = 1) or (isHoliday = 0 and isWeekday = 0) or (isHoliday = 1 and isWeekday = 0))'))->count();

         $hasilcuti = $selisih - $jumlahlibur;

        return response()->json(['hasilcuti' => $hasilcuti],200);
    }

    public function FUNC_SAVEADDCUTI(Request $request) {

        // dd($request->all());

        $nik = $request['nik'];

        $tambahcuti = new CutiModel();
        $tambahcuti->NIK = $request['nik'];
        $tambahcuti->RencanaCuti = $request['rencanacuti'];
        $tambahcuti->TanggalMulaiCuti = $request['tanggal_awal'];
        $tambahcuti->TanggalSelesaiCuti = $request['tanggal_akhir'];
        $tambahcuti->AlamatSelamaCuti = $request['alamat_cuti'];
        $tambahcuti->Keterangan = $request['alasan_cuti'];
        $tambahcuti->SisaCutiTahunSebelumnya = $request['sisa_cuti_seb'];
        $tambahcuti->HakCutiTahun = $request['hak_cuti'];
        $tambahcuti->CutiSudahDiambil = $request['cuti_ambil'];
        $tambahcuti->SisaCuti = $request['sisa_cuti'];
        $tambahcuti->SisaCutiTahun = $request['sisacuti16'];
        $tambahcuti->approve_1 = "Y";

        $tambahcuti->save();

        if ($request['status'] == 'Y') {

            $tambahpth = new PTHModel();
            $tambahpth->nik = $request['nik'];
            $tambahpth->nik_pengganti = $request['nik_pth'];
            $tambahpth->tgl_mulai = $request['pth_awal'];
            $tambahpth->tgl_selesai = $request['pth_akhir'];
            $tambahpth->id_cuti = $tambahcuti->ID;
            $tambahpth->keterangan = $request['alasan_cuti'];

            $tambahpth->save();
        }

        $updatehakcuti = HakCutiModel::where('NIK',$nik)->first();
        $updatehakcuti->cuti_ambil = ($request['cuti_ambil'] + $request['rencanacuti']);

        $updatehakcuti->update();

        return redirect('historycuti')->with('success','Cuti Berhasil Disimpan');

    }



    public function FUNC_HAKCUTI() {

        $hakcutiquery = HakCutiModel::select('tb_hakcuti.id as id','tb_hakcuti.NIK as nik','tb_hakcuti.sisa_cuti','tb_hakcuti.hak_cuti','tb_hakcuti.cuti_ambil','tb_datapribadi.Nama as nama','tb_datapribadi.TglKontrak as tgl_kontrak')
                ->leftjoin('tb_datapribadi','tb_hakcuti.NIK','=','tb_datapribadi.NIK')
                ->get();

        return view('cuti/hakcuti')->with('hakcutiquery',$hakcutiquery);
    }

    public function FUNC_EDITHAKCUTI($id) {

        $tampiledit = HakCutiModel::select('tb_hakcuti.id as id','tb_hakcuti.NIK as nik','tb_hakcuti.sisa_cuti as sisa_cuti_seb','tb_hakcuti.hak_cuti','tb_hakcuti.cuti_ambil','tb_datapribadi.Nama as nama','tb_datapribadi.TglKontrak as tgl_kontrak')
                ->leftjoin('tb_datapribadi','tb_hakcuti.NIK','=','tb_datapribadi.NIK')
                ->where('id',$id)
                ->first();

        return view('cuti/edithakcuti')->with('tampiledit',$tampiledit);
    }

    public function FUNC_UPDATEHAKCUTI(Request $request,$id) {

        $update = HakCutiModel::where('id',$id)->first();
        $update->sisa_cuti = $request['sisa_cuti_seb'];
        $update->hak_cuti = $request['hak_cuti'];
        $update->cuti_ambil = $request['cuti_ambil'];

        $update->update();

        return redirect('hakcuti')->with('success','Data Berhasil DiUpdate.');

    }

    public function FUNC_REQUESTCUTI(Request $request) {

        $nik = Session::get('nik');

        $data = EmployeeModel::select('tb_datapribadi.TglTetap as mulai_bekerja','tb_datapribadi.idpangkat','tbldivmaster.nama_div_ext as namadivisi','tb_jabatan.jabatan as namajabatan','tb_hakcuti.sisa_cuti as sisa_cuti','tb_hakcuti.hak_cuti as hak_cuti','tb_hakcuti.cuti_ambil as cuti_ambil')
            ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
            ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
            ->leftjoin('tb_hakcuti','tb_datapribadi.NIK','=','tb_hakcuti.NIK')
            ->where('tb_datapribadi.NIK',$nik)
            ->first();

        $listpth = EmployeeModel::select('Nama','NIK')
                                ->where('resign','N')
                                ->whereRaw('idpangkat IN (4,5,6,7)')
                                ->get();
        $idpangkat = array(2,3,4,5,6,7);
        if(in_array($data->idpangkat,$idpangkat))
        {
            $status = "Y";
        }else{
            $status = "N";
        }

        $sisa_cuti_seb = $data->sisa_cuti;
        $hak_cuti = $data->hak_cuti;
        $cuti_ambil = $data->cuti_ambil;
        $sisa_cuti = ($sisa_cuti_seb + $hak_cuti) - $cuti_ambil;

        return view('cuti/requestcuti')->with('data',$data)->with('sisa_cuti',$sisa_cuti)->with('listpth',$listpth)->with('status',$status);
    }

    public function FUNC_CEKTGLCUTI(Request $request)
    {

        $now = date("Y-m-d");
        $tglcuti = $request['tglcuti'];
        //liburnya ga dihitung
        // $selisih = ((floor(strtotime ($tglcuti) - strtotime ($now)))/(60*60*24));
        // $jumlahlibur = CalendarModel::whereRaw(DB::raw('(dt BETWEEN "'.$now.'" AND "'.$tglcuti.'") and ((isHoliday = 1 and isWeekday = 1) or (isHoliday = 0 and isWeekday = 0) or (isHoliday = 1 and isWeekday = 0))'))->count();
        // $selisih = $selisih - $jumlahlibur;

        //liburnya dihitung
        $selisih = ((floor(strtotime ($tglcuti) - strtotime ($now)))/(60*60*24));
        if ($selisih<5) {
            return response()->json(['success' => false],200);
        }else{
            return response()->json(['success' => true],200);
        }
    }

    public function FUNC_SAVEREQUESTCUTI(Request $request) {

        $nik = $request['nik'];

        $cutisebambil = HakCutiModel::where('NIK',$nik)->first();
        $cutisebambils = $cutisebambil->cuti_ambil;

        $tambahcuti = new CutiModel();
        $tambahcuti->NIK = $request['nik'];
        $tambahcuti->RencanaCuti = $request['rencanacuti'];
        $tambahcuti->TanggalMulaiCuti = $request['tanggal_awal'];
        $tambahcuti->TanggalSelesaiCuti = $request['tanggal_akhir'];
        $tambahcuti->AlamatSelamaCuti = $request['alamat_cuti'];
        $tambahcuti->Keterangan = $request['alasan_cuti'];
        $tambahcuti->SisaCutiTahunSebelumnya = $request['sisa_cuti_seb'];
        $tambahcuti->HakCutiTahun = $request['hak_cuti'];
        $tambahcuti->CutiSudahDiambil = ($cutisebambils + $request['sisa_cuti'] - $request['sisacuti16']);
        $tambahcuti->SisaCuti = $request['sisa_cuti'];
        $tambahcuti->SisaCutiTahun = $request['sisacuti16'];
        $tambahcuti->approve_1 = "N";

        $tambahcuti->save();

        if ($request['status'] == 'Y') {

            $tambahpth = new PTHModel();
            $tambahpth->nik = $request['nik'];
            $tambahpth->nik_pengganti = $request['nik_pth'];
            $tambahpth->tgl_mulai = $request['pth_awal'];
            $tambahpth->tgl_selesai = $request['pth_akhir'];
            $tambahpth->id_cuti = $tambahcuti->ID;
            $tambahpth->keterangan = $request['alasan_cuti'];

            $tambahpth->save();
        }


        $updatehakcuti = HakCutiModel::where('NIK',$nik)->first();
        $updatehakcuti->cuti_ambil = ($request['cuti_ambil'] + $request['rencanacuti']);

        $updatehakcuti->update();
        // $nikatasan = EmployeeModel::select('atasan1','atasan2')->where('NIK',$nik)->first();
        // $atasan1 = $nikatasan->atasan1;
        // $atasan2 = $nikatasan->atasan2;

        // $showemail1 = EmployeeModel::select('email')->where('NIK',$atasan1)->first();
        // $email1 = $showemail1->email;

        // $showemail2 = EmployeeModel::select('email')->where('NIK',$atasan2)->first();
        // $email2 = $showemail2->email;

        // $nama = $request['nama'];
        // // $showemail =
        // $link = url('approvecuti');
        // $data = array(
        //     'nama' => $nama,
        //     'link' => $link,
        //     );

        // if($this->FUNC_SENDEMAIL($data)) {
        //     return redirect()->back()->with('success','Cuti Berhasil Disimpan');
        // } else {
        //     return redirect()->back()->with('success','Cuti Berhasil Disimpan');
        // }

        return redirect()->back()->with('success','Cuti Berhasil Disimpan');
    }

    public function FUNC_APPROVECUTI(Request $request) {

        $nikatasan = Session::get('nik');

        $approve = "select tb_cuti.NIK as nik, tb_datapribadi.Nama as nama, tb_cuti.TanggalMulaiCuti as tgl_mulai, tb_cuti.rencanacuti as hari,
            tb_cuti.TanggalSelesaiCuti as tgl_selesai, tb_cuti.AlamatSelamaCuti as alamatcuti, tb_cuti.id, CASE
            WHEN approve_1 = 'Y' THEN CONCAT('Approved by ', (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
            WHEN approve_1 = 'R' THEN CONCAT('Rejected by ', (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by))
            WHEN approve_1 = 'N' THEN 'Dalam Proses Persetujuan atasan langsung'
            ELSE ''
            END as status,
            CASE
            WHEN approve_2 = 'Y' THEN CONCAT('Approved by ', (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by_2))
            WHEN approve_2 = 'R' THEN CONCAT('Rejected by ', (SELECT nama FROM tb_datapribadi WHERE nik = tb_cuti.act_by_2))
            WHEN approve_2 = 'N' THEN 'Dalam Proses Persetujuan atasan 2'
            ELSE ''
            END as status2
            from tb_datapribadi left join tb_cuti on tb_datapribadi.NIK = tb_cuti.nik
            where
            CASE WHEN tb_cuti.approve_1 = 'N' and tb_cuti.approve_2 IS NULL THEN tb_datapribadi.atasan1 = '".$nikatasan."'
            WHEN tb_cuti.approve_2 = 'N' and tb_cuti.approve_1 = 'Y' THEN tb_datapribadi.atasan2 = '".$nikatasan."' END order by tb_cuti.TanggalMulaiCuti desc";
        $approves = DB::select($approve);

        return view('cuti/approvecuti')->with('approves',$approves);
    }

    public function FUNC_ACTIONAPPROVE($id) {

        $showdetail = CutiModel::select('tb_cuti.ID as id','tb_cuti.NIK as nik','TanggalMulaiCuti as tgl_mulai','TanggalSelesaiCuti as tgl_selesai','AlamatSelamaCuti as alamat_cuti','Keterangan as keterangan','tb_datapribadi.Nama as nama','RencanaCuti as hari','tb_hakcuti.sisa_cuti as sisa_cuti','tb_hakcuti.hak_cuti as hak_cuti','tb_hakcuti.cuti_ambil as cuti_ambil')
                        ->leftjoin('tb_datapribadi','tb_cuti.NIK','=','tb_datapribadi.NIK')
                        ->leftjoin('tb_hakcuti','tb_hakcuti.NIK','=','tb_cuti.NIK')
                        ->where('tb_cuti.id',$id)
                        ->first();

        $nik = $showdetail->nik;

        $historycuti = CutiModel::select('tb_cuti.ID as id','tb_cuti.NIK as nik','tb_datapribadi.Nama as nama','tb_cuti.TanggalMulaiCuti as tgl_mulai','tb_cuti.rencanacuti as hari','tb_cuti.TanggalSelesaiCuti as tgl_selesai','tb_cuti.AlamatSelamaCuti as alamatcuti','tb_cuti.approve_1','tb_cuti.approve_2','tb_cuti.Keterangan as keterangan',
            DB::raw('CASE
                    WHEN `approve_1` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by))
                    WHEN `approve_1` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti'),
            DB::raw('CASE
                    WHEN `approve_2` = "Y" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "R" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_cuti.act_by_2))
                    WHEN `approve_2` = "N" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statuscuti2222')
            )
            ->leftjoin('tb_datapribadi','tb_cuti.nik','=','tb_datapribadi.NIK')
            ->where('tb_cuti.NIK',$nik)
            ->orderBy('tb_cuti.TanggalMulaiCuti','DESC')
            ->get();

        $pangkat = EmployeeModel::select('idpangkat')->where('NIK',$nik)->first();
        $pangkats = $pangkat->idpangkat;

        $sisa_cuti_seb = $showdetail->sisa_cuti;
        $hak_cuti = $showdetail->hak_cuti;
        $cuti_ambil = $showdetail->cuti_ambil;
        $sisa_cuti = ($sisa_cuti_seb + $hak_cuti) - $cuti_ambil;

        return view('cuti/showapprove')->with('showdetail',$showdetail)->with('sisa_cuti',$sisa_cuti)->with('historycuti',$historycuti)->with('pangkats',$pangkats);

    }

    public function FUNC_REJECTCUTI($id) {
        $id_cuti = $id;
        return view('cuti/rejectcuti')->with('id_cuti',$id_cuti);
    }

    public function FUNC_CATATANCUTI($id) {
        $id_cuti = $id;
        return view('cuti/catatancuti')->with('id_cuti',$id_cuti);
    }

    public function FUNC_SAVEAPPROVECUTI(Request $request,$id) {

        $bro = CutiModel::where('ID',$id)->first();
        $app1 = $bro->approve_1;
        $app2 = $bro->approve_2;
        $nik = $bro->NIK;

        $idpangkat = EmployeeModel::select('idpangkat')->where('NIK',$nik)->first();
        $pangkat = $idpangkat->idpangkat;

        // dd($pangkat);
        // dd($app2);

        if ($pangkat=='7' or $pangkat=='8' or $pangkat=='9' or $pangkat=='10') {

            if ($app1=='N' and $app2==null) {

            $approve = CutiModel::find($id);
            $approve->approve_1 = "Y";
            $approve->approve_2 = "N";
            $approve->act_by = Session::get('nik');
            $approve->catatan_approve_1 = $request['catatan_approve'];

            $approve->update();

            } elseif ($app1=='Y' and $app2=='N') {

                $approve2 = CutiModel::find($id);
                // $approve2->approve_1 = "Y";
                $approve2->approve_2 = "Y";
                $approve2->act_by_2 = Session::get('nik');
                $approve2->status = "1";
                $approve2->status_email = "2";
                $approve2->catatan_approve_2 = $request['catatan_approve'];

                $approve2->update();

                $tanggal1 = $approve2->TanggalMulaiCuti;
                $tanggal2 = $approve2->TanggalSelesaiCuti;
                $nik = $approve2->NIK;
                $alasanbro = $approve2->Keterangan;

                $namakar = EmployeeModel::where('NIK',$approve2->NIK)->value('Nama');

                $loop = CalendarModel::whereRaw(DB::raw('(dt BETWEEN "'.$tanggal1.'" AND "'.$tanggal2.'") and (isHoliday = 0 and isWeekday = 1)'))->get();
                foreach ($loop as $loops) {
                    $id = explode("-",$loops->dt);
                    $format_id = $id[0].$id[1].$id[2].$nik;

                    $tambahijin = new AbsenIjinModel();
                    $tambahijin->id = $format_id;
                    $tambahijin->nama = $namakar;
                    $tambahijin->nik = $nik;
                    $tambahijin->tanggal = $loops->dt;
                    $tambahijin->stat = 'Cuti';
                    $tambahijin->ket = $alasanbro;
                    $tambahijin->statusApp = '1';
                    $tambahijin->status_email = '2';
                    $tambahijin->catatan_approve = $request['catatan_approve'];

                    $tambahijin->save();
                }
            }

        } elseif ($pangkat=='2' or $pangkat=='3' or $pangkat=='4' or $pangkat=='5' or $pangkat=='6') {

            $approve = CutiModel::find($id);
            $approve->approve_1 = "Y";
            $approve->act_by = Session::get('nik');
            $approve->status= "1";
            $approve->status_email = "2";
            $approve->catatan_approve_1 = $request['catatan_approve'];

            $approve->update();

            $tanggal1 = $approve->TanggalMulaiCuti;
            $tanggal2 = $approve->TanggalSelesaiCuti;
            $nik = $approve->NIK;
            $alasanbro = $approve->Keterangan;

            $namakar = EmployeeModel::where('NIK',$approve->NIK)->value('Nama');

            $loop = CalendarModel::whereRaw(DB::raw('(dt BETWEEN "'.$tanggal1.'" AND "'.$tanggal2.'") and (isHoliday = 0 and isWeekday = 1)'))->get();
            foreach ($loop as $loops) {
                $id = explode("-",$loops->dt);
                $format_id = $id[0].$id[1].$id[2].$nik;

                $tambahijin = new AbsenIjinModel();
                $tambahijin->id = $format_id;
                $tambahijin->nama = $namakar;
                $tambahijin->nik = $nik;
                $tambahijin->tanggal = $loops->dt;
                $tambahijin->stat = 'Cuti';
                $tambahijin->ket = $alasanbro;
                $tambahijin->statusApp = '1';
                $tambahijin->status_email = '2';
                $tambahijin->catatan_approve = $request['catatan_approve'];

                $tambahijin->save();
            }
        }



        return redirect('approvecuti')->with('success','Cuti Berhasil Sudah DiApprove.');

    }

    public function FUNC_SAVEREJECTCUTI(Request $request) {
        $id = $request['id'];
        $nik = CutiModel::select('NIK','RencanaCuti','approve_1','approve_2')->where('ID',$id)->first();
        $niks = $nik->NIK;
        $hari = $nik->RencanaCuti;
        $app1 = $nik->approve_1;
        $app2 = $nik->approve_2;

        // dd($app1);
        if ($app1=='N' and $app2==null) {

            $reject = CutiModel::where('ID',$id)->first();

            $reject->approve_1 = "R";
            $reject->act_by = Session::get('nik');
            $reject->alasan_reject = $request['alasan_reject'];
            $reject->status_email = "2";

            $reject->update();

            $rubahhakcuti = HakCutiModel::where('NIK',$niks)->first();
            $diambil = $rubahhakcuti->cuti_ambil;

            // dd($diambil);
            $rubahhakcuti->cuti_ambil = $diambil - $hari;

            $rubahhakcuti->update();

        } elseif ($app1=='Y' and $app2=='N') {

            $reject = CutiModel::where('ID',$id)->first();

            $reject->approve_2 = "R";
            $reject->act_by_2 = Session::get('nik');
            $reject->alasan_reject = $request['alasan_reject'];
            $reject->status_email = "2";

            $reject->update();

            $rubahhakcuti = HakCutiModel::where('NIK',$niks)->first();
            $diambil = $rubahhakcuti->cuti_ambil;

            // dd($diambil);
            $rubahhakcuti->cuti_ambil = $diambil - $hari;

            $rubahhakcuti->update();
        }

        return redirect('approvecuti')->with('success','Cuti Berhasil Sudah Di Reject.');
    }

    // absence_type = 0:all 1:Izin 2:Sakit 3:Dinas Luar 4:Cuti
    // data_type = 0:all, 1:sync-all, 2:sync-approved, 3:sync-rejected, 4:not-sync-all, 5:not-sync-approved, 6:not-sync-rejected
    public function pullAbsenceHadirkoe($startDate='',$endDate='', $absence_type='0', $data_type='5')
    {
        $startDate = ($startDate) ? $startDate : date('Y-m-d');
        $endDate = ($endDate) ? $endDate : date('Y-m-d');

        $client = new Client([
                    'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                ]);

        $response = $client->request('POST', env('APP_HADIRKOE').'/api/login', [
            'json' => [
                'email' => 'human.capital@edi-indonesia.co.id',
                'password' => 'P@ssw0rd',
                'secret_id' => '7ed4c107115c481da59b5dae3403c24f21b69aa7',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $accessToken = $data['message']['access_token'];

        // get data absensi
        $client = new Client([
                'headers' => [
                        'content-type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $accessToken
                    ],
                ]);

        $response = $client->request('GET', env('APP_HADIRKOE').'/api/absence/7ed4c107115c481da59b5dae3403c24f21b69aa7', [
            'json' => [
                'absence_start_date' => $startDate,
                'absence_end_date' => $endDate,
                'data_type' => $data_type,
                'absence_type' => $absence_type,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $dataAbsence = $data['data'];
        $return = [];

        if (count($dataAbsence) > 0) {
            foreach ($dataAbsence as $value) {
                // return $dataAbsence;
                $ABSENCE_START_DATE = $value['ABSENCE_START_DATE'];
                $ABSENCE_END_DATE = $value['ABSENCE_END_DATE'];
                // $datediff = $ABSENCE_END_DATE - $ABSENCE_START_DATE;
                // return round($datediff / (60 * 60 * 24)) . " <=> " . $value['ABSENCE_START_DATE'] . " - " . $value['ABSENCE_END_DATE'];

                $loop = CalendarModel::whereRaw(DB::raw('(dt BETWEEN "'.$ABSENCE_START_DATE.'" AND "'.$ABSENCE_END_DATE.'") and (isHoliday = 0 and isWeekday = 1)'))->get();
                // return $loop;
                foreach ($loop as $loops) {
                    $id = explode("-",$loops->dt);
                    $format_id = $id[0] . $id[1] . $id[2] . $value['EMPLOYEE_NIK'];

                    $tambahijin = AbsenIjinModel::updateOrCreate([
                        'id' => $format_id
                    ],[
                        'nama' => $value['EMPLOYEE_NAME'],
                        'nik' => $value['EMPLOYEE_NIK'],
                        'tanggal' => $loops->dt,
                        'stat' => $value['ABSENCE_TYPE_DESC'],
                        'ket' => $value['ABSENCE_TYPE_DESC'] . ' (entry from Hadirkoe)',
                        'statusApp' => '1',
                        'status_email' => '2',
                        'catatan_approve' => '-',
                    ]);

                    $return[$value['EMPLOYEE_NIK']] = [
                        'Name'=> $value['EMPLOYEE_NAME'],
                        'Date' => $loops->dt,
                        'Status' => 'Done',
                    ];
                }

                if ($value['ABSENCE_TYPE_DESC'] == 'Cuti') {
                    $tambahcuti = new CutiModel();
                    $tambahcuti->NIK = $value['EMPLOYEE_NIK'];
                    $tambahcuti->TanggalMulaiCuti = $value['ABSENCE_START_DATE'];
                    $tambahcuti->TanggalSelesaiCuti = $value['ABSENCE_END_DATE'];
                    $tambahcuti->AlamatSelamaCuti = $value['ABSENCE_DESCRIPTION'];
                    $tambahcuti->Keterangan = $value['ABSENCE_TYPE_DESC'] . ' (entry from Hadirkoe)';
                    $tambahcuti->approve_1 = "Y";

                    $tambahcuti->save();
                }

            }
            return $return;
        }else{
            return 'Data Not Exist';
        }
    }
}
