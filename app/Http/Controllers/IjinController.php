<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\AbsenIjinModel;
use App\Models\AbsenRekapModel;
use App\Models\EmployeeModel;
use App\Models\CutiModel;
use App\Models\HakCutiModel;

class IjinController extends Controller
{

        public function FUNC_CEKREQUESTIJIN(Request $request) {

            $coba = $request['nik'];

            $querydivjab = EmployeeModel::select('tb_datapribadi.Nama as nama_karyawan','tb_datapribadi.TglTetap as mulai_bekerja','tbldivmaster.nama_div_ext as namadivisi','tb_jabatan.jabatan as namajabatan')
                ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                ->where('tb_datapribadi.NIK',$coba)
                ->first();

            $mulai_bekerja = $querydivjab->mulai_bekerja;
            $divisi = $querydivjab->namadivisi;
            $jabatan = $querydivjab->namajabatan;


            return response()->json(['tes' => $coba,
                                     'nama' => $querydivjab->nama_karyawan,
                                     'mulai_bekerja' => $mulai_bekerja,
                                     'divisi' => $divisi,
                                     'jabatan' => $jabatan
                                     ],200);

        }


        public function FUNC_REQUESTIJIN() {
            $nik = Session::get('nik');
            if (Session::get('admin') == 1) {
                $query = EmployeeModel::select('NIK','Nama',DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('resign','N')->get();
            }else{
                $query = EmployeeModel::select('NIK','Nama',DB::raw('CONCAT(NIK," - ",Nama) as tampil_drop'))->where('NIK',$nik)->get();
            }

            $hisizin =  AbsenIjinModel::select('absen_izin.nama','absen_izin.nik','absen_izin.tanggal','absen_izin.stat','absen_izin.ket','absen_izin.alasan_reject','absen_izin.statusApp','tb_datapribadi.Nama','tb_datapribadi.NIK','absen_izin.act_by',
                 DB::raw('CASE
                    WHEN `statusApp` = "1" and act_by IS NOT NULL THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = absen_izin.act_by))
                    WHEN `statusApp` = "2" and act_by IS NOT NULL THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = absen_izin.act_by))
                    WHEN `statusApp` = "0" THEN "Dalam Proses Persetujuan"
                    WHEN `statusApp` = "1" and act_by IS NULL THEN "Approved"
                    WHEN `statusApp` = "2" AND act_by IS NULL THEN "Rejected"
                    ELSE ""
                END as statusizin'))
                ->leftjoin('tb_datapribadi','absen_izin.nik','=','tb_datapribadi.NIK')
                ->orderby('absen_izin.tgl_input','DESC')
                ->where('absen_izin.nik',$nik)
                ->get();

            // dd($hisizin);

            return view('ijin/requestijin')->with('nik',$nik)->with('query',$query)->with('hisizin',$hisizin);
        }

        public function FUNC_SAVEREQUESTIJIN(Request $request) {

            $coba = substr($request['JamMulaiIzin'],6,2);
            $coba2 = substr($request['JamSelesaiIzin'],6,2);

            $jam = substr($request['JamMulaiIzin'],0,2);
            $jam2 = substr($request['JamSelesaiIzin'],0,2);

            $menit = substr($request['JamMulaiIzin'],3,2);
            $menit2 = substr($request['JamSelesaiIzin'],3,2);

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

            $jamfixx1 = $jamfix.':'.$menit;
            $jamfixx2 = $jamfix2.':'.$menit2;


            $id = explode("-",$request['tanggal']);
            $nik = $request['nikfix'];
            $format_id = $id[0].$id[1].$id[2].$nik;

            // dd($format_id);

            //status 0 = kirim ke atasan
            //status 1 = kirim ke pegawai terkait
            //status 2 = done
            $tambah = new AbsenIjinModel();
            if (Session::get('admin') == 1) {
                if (Session::get('nik') == $nik) {
                    $tambah->statusApp = '0';
                    $tambah->status_email = '2';
                }else{
                    $tambah->statusApp = '1';
                    $tambah->act_by = Session::get('nik');
                    $tambah->status_email = '0';

                    if (AbsenRekapModel::where('id',$format_id)->exists()) {
                       // user found
                        $approve2 = AbsenRekapModel::where('id',$format_id)->first();
                        $approve2->selisih = '0';
                        $approve2->stat = $request['status'];
                        $approve2->ket = $request['keterangan'];
                        $approve2->update();

                    } else {

                    }
                }
            }else{

                $tambah->statusApp = '0';
            }

            $tambah->id = $format_id;
            $tambah->nik = $nik;
            $tambah->nama = $request['nama'];
            $tambah->tanggal = $request['tanggal'];
            $tambah->jam_mulai = $jamfixx1;
            $tambah->jam_selesai = $jamfixx2;
            $tambah->stat = $request['status'];
            $tambah->ket = $request['keterangan'];
            // $tambah->statusApp = '0';

            $tambah->save();

            return redirect()->back()->with('success','Ijin Berhasil Disimpan');
        }

        public function FUNC_APPROVEIJIN() {

            $nikatasan = Session::get('nik');

            $query = EmployeeModel::select('absen_izin.*')
                                    ->leftjoin('absen_izin','tb_datapribadi.NIK','=','absen_izin.nik')
                                    ->where('tb_datapribadi.atasan1',$nikatasan)
                                    ->where('absen_izin.statusApp','0')
                                    ->get();

            return view('ijin/approveijin')->with('query',$query);

        }

        public function FUNC_PROSESIJIN($id) {

            $query = AbsenIjinModel::select('absen_izin.id as idijin','absen_izin.nik','absen_izin.nama','absen_izin.stat','absen_izin.ket','absen_izin.tanggal','absen_izin.jamlembur','absen_izin.jamselesailembur','absen_izin.jam_mulai','absen_izin.jam_selesai')
                                    ->where('absen_izin.id',$id)
                                    ->first();

            return view('ijin/showproses')->with('query',$query);
        }

        public function FUNC_SAVEAPPROVEIJIN(Request $request) {

            $id = $request['id_ijin'];

            $dataijin = AbsenIjinModel::where('id',$id)->first();
            $nik = $dataijin->nik;
            $stat = $dataijin->stat;
            $ket = $dataijin->ket;

            if ($request['potong_cuti']) {

                $data = EmployeeModel::select('tb_datapribadi.TglTetap as mulai_bekerja','tb_datapribadi.idpangkat','tbldivmaster.nama_div_ext as namadivisi','tb_jabatan.jabatan as namajabatan','tb_hakcuti.sisa_cuti as sisa_cuti','tb_hakcuti.hak_cuti as hak_cuti','tb_hakcuti.cuti_ambil as cuti_ambil')
                            ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                            ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                            ->leftjoin('tb_hakcuti','tb_datapribadi.NIK','=','tb_hakcuti.NIK')
                            ->where('tb_datapribadi.NIK',$nik)
                            ->first();

                $sisa_cuti_seb = $data->sisa_cuti;
                $hak_cuti = $data->hak_cuti;
                $cuti_ambil = $data->cuti_ambil;
                $sisa_cuti = ($sisa_cuti_seb + $hak_cuti) - $cuti_ambil;

                $cutisebambil = HakCutiModel::where('NIK',$nik)->first();
                $cutisebambils = $cutisebambil->cuti_ambil;

                $tambahcuti = new CutiModel();
                $tambahcuti->NIK = $nik;
                $tambahcuti->RencanaCuti = 1;
                $tambahcuti->TanggalMulaiCuti = $dataijin->tanggal;
                $tambahcuti->TanggalSelesaiCuti = $dataijin->tanggal;
                $tambahcuti->AlamatSelamaCuti = $dataijin->ket;
                $tambahcuti->Keterangan = $dataijin->ket;
                $tambahcuti->SisaCutiTahunSebelumnya = $sisa_cuti_seb;
                $tambahcuti->HakCutiTahun = $hak_cuti;
                $tambahcuti->CutiSudahDiambil = ($cutisebambils + 1);
                $tambahcuti->SisaCuti = $sisa_cuti;
                $tambahcuti->SisaCutiTahun = $sisa_cuti-1;
                $tambahcuti->approve_1 = "Y";
                $tambahcuti->approve_2 = "Y";
                $tambahcuti->status_email = 2;
                $tambahcuti->catatan_approve_1 = $request['catatan_approve'];

                $tambahcuti->save();

                $updatehakcuti = HakCutiModel::where('NIK',$nik)->first();
                $updatehakcuti->cuti_ambil = ($cuti_ambil + 1);
                $updatehakcuti->update();

                $potong_cuti = 'y';

            }else{
                $potong_cuti = 'n';
            }

            $approve = AbsenIjinModel::find($id);
            $approve->statusApp = "1";
            $approve->act_by = Session::get('nik');
            $approve->catatan_approve = $request['catatan_approve'];
            $approve->potong_cuti = $potong_cuti;
            $approve->update();

            if (AbsenRekapModel::where('id',$id)->exists()) {
               // user found
                $approve2 = AbsenRekapModel::where('id',$id)->first();
                // $approve2->selisih = '0';
                $approve2->stat = $stat;
                $approve2->ket = $ket;

                $approve2->update();
            } else {

            }

            return redirect('approveijin')->with('success','Ijin Berhasil Sudah DiApprove.');
        }

        public function FUNC_REJECTIJIN($id) {
            $id_ijin = $id;
            return view('ijin/rejectijin')->with('id_ijin',$id_ijin);
        }

        public function FUNC_SAVEREJECTIJIN(Request $request) {
            $id = $request['id'];

            $edit = AbsenIjinModel::where('id',$id)->first();
            $edit->alasan_reject = $request['alasan_reject'];
            $edit->statusApp = '2';
            $edit->act_by = Session::get('nik');

            $edit->update();

            return redirect('approveijin')->with('success','Ijin Berhasil Sudah DiREJECT.');
        }

        public function FUNC_LISTREQUESTIZIN() {

            $query =  AbsenIjinModel::select('absen_izin.nama','absen_izin.nik','absen_izin.tanggal','absen_izin.stat','absen_izin.ket','absen_izin.alasan_reject','absen_izin.statusApp','tb_datapribadi.Nama','absen_izin.jam_mulai','absen_izin.jam_selesai',
                 DB::raw('CASE
                    WHEN `statusApp` = "1" THEN CONCAT("Approved by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = absen_izin.act_by))
                    WHEN `statusApp` = "2" THEN CONCAT("Rejected by ", (SELECT Nama FROM tb_datapribadi WHERE NIK = absen_izin.act_by))
                    WHEN `statusApp` = "0" THEN "Dalam Proses Persetujuan"
                    ELSE ""
                END as statusizin'))
                ->leftjoin('tb_datapribadi','absen_izin.nik','=','tb_datapribadi.NIK')
                ->where('absen_izin.statusApp','0')
                ->get();

            return view('ijin/listrequestijin')->with('query',$query);
        }

}
