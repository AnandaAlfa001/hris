<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\EmployeeModel;
use App\Models\AbsenIjinModel;
use App\Models\CutiModel;
use App\Models\LogPerubahanModel;
use App\Models\AgamaModel;
use Mail;

class EmailController extends Controller
{
    public function TEST_SEND_EMAIL()
    {
        $data = [];
        $sendemail = Mail::send('email.test_email', $data, function ($mail)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            // $mail->to($email_atasan);
                            $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Test Email');
                        });
    }

    public function FUNC_EMAIL_IZIN_ATASAN()
    {
        $data = AbsenIjinModel::select('absen_izin.*',DB::raw('DATE_FORMAT(absen_izin.tanggal,"%d %M %Y") as tanggal_izin'),'absen_izin.id as idbro')
                            ->where('status_email','0')
                            ->where('statusApp','0')
                            ->get();

        foreach ($data as $datas) {
            $atasan = EmployeeModel::select('atasan1',DB::raw('(SELECT email from tb_datapribadi a where a.NIK = tb_datapribadi.atasan1) as email_atasan'),
                                        DB::raw('(SELECT Nama from tb_datapribadi b where b.NIK = tb_datapribadi.atasan1) as nama_atasan'),
                                        DB::raw('(SELECT jk from tb_datapribadi c where c.NIK = tb_datapribadi.atasan1) as jk_atasan'))
                                        ->where('NIK',$datas->nik)
                                        ->orWhere('old_nik',$datas->nik)
                                        ->first();


            $nikatasan = $atasan->atasan1;
            $email_atasan = $atasan->email_atasan;
            $nama_atasan = $atasan->nama_atasan;
            $nama_karyawan = $datas->nik."-".$datas->nama;
            $status = $datas->stat;
            $keterangan = $datas->ket;
            $tanggal = $datas->tanggal_izin;
            $jam_mulai = $datas->jam_mulai;
            $jam_selesai = $datas->jam_selesai;
            $id_izin = $datas->idbro;
            $jk = $datas->jk_atasan;

            if ($jk == 'L') {
                $panggilan = "Bpk.";
            }elseif ($jk == 'P') {
                $panggilan = "Ibu.";
            }else{
                $panggilan = "";
            }


            $dataw = [
                    'nikatasan' => $nikatasan,
                    'nama_atasan' => $nama_atasan,
                    'nama_karyawan' => $nama_karyawan,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'id_izin' => $id_izin,
                    'panggilan' => $panggilan
                    ];



            $sendemail = Mail::send('email.email_izin_atasan', $dataw, function ($mail) use ($email_atasan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_atasan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Izin - Approval');
                        });

            $update = AbsenIjinModel::where('id',$id_izin)->first();
            $update->status_email = '1';
            $update->update();

        }
    }

    public function FUNC_EMAIL_IZIN_KAR_APPROVE()
    {
        $data = AbsenIjinModel::select('absen_izin.*',DB::raw('DATE_FORMAT(absen_izin.tanggal,"%d %M %Y") as tanggal_izin'),'absen_izin.id as idbro')
                            ->where('status_email','1')
                            ->where('statusApp','1')
                            ->get();

        foreach ($data as $datas) {

            $atasan = EmployeeModel::select('atasan1', 'email as email_karyawan', DB::raw('(SELECT Nama from tb_datapribadi b where b.NIK = tb_datapribadi.atasan1) as nama_atasan'))
                                        ->where('NIK',$datas->nik)
                                        ->orWhere('old_nik',$datas->nik)
                                        ->first();

            $approvedby = EmployeeModel::select('NIK','Nama')->where('NIK',$datas->act_by)->first();

            $nikatasan = $atasan->atasan1;
            $email_karyawan = $atasan->email_karyawan;
            $nama_atasan = $atasan->nama_atasan;
            $nama_karyawan = $datas->nik."-".$datas->nama;
            $nama_ori = $datas->nama;
            $status = $datas->stat;
            $keterangan = $datas->ket;
            $tanggal = $datas->tanggal_izin;
            $jam_mulai = $datas->jam_mulai;
            $jam_selesai = $datas->jam_selesai;
            $id_izin = $datas->idbro;
            $approvedbys = $approvedby->NIK." - ".$approvedby->Nama;


            $dataw = [
                    'nikatasan' => $nikatasan,
                    'nama_atasan' => $nama_atasan,
                    'nama_karyawan' => $nama_karyawan,
                    'nama_ori' => $nama_ori,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'id_izin' => $id_izin,
                    'approvedbys' => $approvedbys
                    ];



            $sendemail = Mail::send('email.email_izin_kar_approve', $dataw, function ($mail) use ($email_karyawan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_karyawan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Izin - Approve');
                        });

            $update = AbsenIjinModel::where('id',$id_izin)->first();
            $update->status_email = '2';
            $update->update();

        }
    }

    public function FUNC_EMAIL_IZIN_KAR_REJECT()
    {
        $data = AbsenIjinModel::select('absen_izin.*',DB::raw('DATE_FORMAT(absen_izin.tanggal,"%d %M %Y") as tanggal_izin'),'absen_izin.id as idbro')
                            ->where('status_email','1')
                            ->where('statusApp','2')
                            ->get();



        foreach ($data as $datas) {
            $atasan = EmployeeModel::select('atasan1', 'email as email_karyawan', DB::raw('(SELECT Nama from tb_datapribadi b where b.NIK = tb_datapribadi.atasan1) as nama_atasan'))
                                        ->where('NIK',$datas->nik)
                                        ->orWhere('old_nik',$datas->nik)
                                        ->first();

            $rejectedby = EmployeeModel::select('NIK','Nama')->where('NIK',$datas->act_by)->first();

            $nikatasan = $atasan->atasan1;
            $email_karyawan = $atasan->email_karyawan;
            $nama_atasan = $atasan->nama_atasan;
            $nama_karyawan = $datas->nik."-".$datas->nama;
            $nama_ori = $datas->nama;
            $status = $datas->stat;
            $keterangan = $datas->ket;
            $tanggal = $datas->tanggal_izin;
            $jam_mulai = $datas->jam_mulai;
            $jam_selesai = $datas->jam_selesai;
            $id_izin = $datas->idbro;
            $alasan_reject = $datas->alasan_reject;
            $rejectedbys = $rejectedby->NIK." - ".$rejectedby->Nama;

            $dataw = [
                    'nikatasan' => $nikatasan,
                    'nama_atasan' => $nama_atasan,
                    'nama_karyawan' => $nama_karyawan,
                    'nama_ori' => $nama_ori,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'id_izin' => $id_izin,
                    'rejectedbys' => $rejectedbys,
                    'alasan_reject' => $alasan_reject
                    ];



            $sendemail = Mail::send('email.email_izin_kar_reject', $dataw, function ($mail) use ($email_karyawan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_karyawan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Izin - REJECT');
                        });

            $update = AbsenIjinModel::where('id',$id_izin)->first();
            $update->status_email = '2';
            $update->update();

        }
    }

    //status email 0 = kirim email approval ke atasan1
    //status email 1 = kirim email approval ke atasan2
    //status email 2 = kirim email ke pegawai terkait
    //status email 3 = done


    public function FUNC_EMAIL_CUTI_ATASAN1()
    {
        $data = CutiModel::select('tb_cuti.*','tb_cuti.ID as id_cuti')
                            ->where('status','0')
                            ->where('status_email',0)
                            ->where('approve_1','N')
                            ->get();

        foreach ($data as $datas) {

            $atasan = EmployeeModel::select('NIK as nik_karyawan','Nama as nama_karyawan','atasan1',DB::raw('(SELECT email from tb_datapribadi a where a.NIK = tb_datapribadi.atasan1) as email_atasan'),
                                        DB::raw('(SELECT Nama from tb_datapribadi b where b.NIK = tb_datapribadi.atasan1) as nama_atasan'),
                                        DB::raw('(SELECT jk from tb_datapribadi c where c.NIK = tb_datapribadi.atasan1) as jk_atasan'))
                                        ->where('NIK',$datas->NIK)
                                        ->orWhere('old_nik',$datas->NIK)
                                        ->first();

            $nikatasan = $atasan->atasan1;
            $email_atasan = $atasan->email_atasan;
            $nama_atasan = $atasan->nama_atasan;
            $nik_karyawan = $atasan->nik_karyawan;
            $nama_karyawan = $atasan->nama_karyawan;
            $tanggal_awal = $datas->TanggalMulaiCuti;
            $tanggal_selesai = $datas->TanggalSelesaiCuti;
            $alamat_cuti = $datas->AlamatSelamaCuti;
            $keterangan = $datas->Keterangan;
            $id_cuti = $datas->id_cuti;
            $jk = $datas->jk_atasan;

            if ($jk == 'L') {
                $panggilan = "Bpk.";
            }elseif ($jk == 'P') {
                $panggilan = "Ibu.";
            }else{
                $panggilan = "";
            }

            $dataw = [
                    'nikatasan' => $nikatasan,
                    'email_atasan' => $email_atasan,
                    'nama_atasan' => $nama_atasan,
                    'nik_karyawan' => $nik_karyawan,
                    'nama_karyawan' => $nama_karyawan,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_selesai' => $tanggal_selesai,
                    'alamat_cuti' => $alamat_cuti,
                    'keterangan' => $keterangan,
                    'id_cuti' => $id_cuti,
                    'panggilan' => $panggilan
                    ];

            $sendemail = Mail::send('email.email_cuti_atasan', $dataw, function ($mail) use ($email_atasan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_atasan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Cuti - Approval');
                        });

            $update = CutiModel::where('ID',$id_cuti)->first();
            $update->status_email = '1';
            $update->update();

        }


    }

    public function FUNC_EMAIL_CUTI_ATASAN2()
    {
        $data = CutiModel::select('tb_cuti.*','tb_cuti.ID as id_cuti')
                            ->where('status','0')
                            ->where('status_email',1)
                            ->where('approve_1','Y')
                            ->where('approve_2','N')
                            ->get();

        foreach ($data as $datas) {

            $atasan = EmployeeModel::select('NIK as nik_karyawan','Nama as nama_karyawan','atasan2',DB::raw('(SELECT email from tb_datapribadi a where a.NIK = tb_datapribadi.atasan2) as email_atasan'),
                                        DB::raw('(SELECT Nama from tb_datapribadi b where b.NIK = tb_datapribadi.atasan2) as nama_atasan'),
                                        DB::raw('(SELECT jk from tb_datapribadi c where c.NIK = tb_datapribadi.atasan2) as jk_atasan'))
                                        ->where('NIK',$datas->NIK)
                                        ->orWhere('old_nik',$datas->NIK)
                                        ->first();

            $nikatasan = $atasan->atasan2;
            $email_atasan = $atasan->email_atasan;
            $nama_atasan = $atasan->nama_atasan;
            $nik_karyawan = $atasan->nik_karyawan;
            $nama_karyawan = $atasan->nama_karyawan;
            $tanggal_awal = $datas->TanggalMulaiCuti;
            $tanggal_selesai = $datas->TanggalSelesaiCuti;
            $alamat_cuti = $datas->AlamatSelamaCuti;
            $keterangan = $datas->Keterangan;
            $id_cuti = $datas->id_cuti;
            $jk = $datas->jk_atasan;

            if ($jk == 'L') {
                $panggilan = "Bpk.";
            }elseif ($jk == 'P') {
                $panggilan = "Ibu.";
            }else{
                $panggilan = "";
            }

            $dataw = [
                    'nikatasan' => $nikatasan,
                    'email_atasan' => $email_atasan,
                    'nama_atasan' => $nama_atasan,
                    'nik_karyawan' => $nik_karyawan,
                    'nama_karyawan' => $nama_karyawan,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_selesai' => $tanggal_selesai,
                    'alamat_cuti' => $alamat_cuti,
                    'keterangan' => $keterangan,
                    'id_cuti' => $id_cuti,
                    'panggilan' => $panggilan
                    ];

            $sendemail = Mail::send('email.email_cuti_atasan', $dataw, function ($mail) use ($email_atasan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_atasan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Cuti - Approval');
                        });

            $update = CutiModel::where('ID',$id_cuti)->first();
            $update->status_email = '2';
            $update->update();

        }


    }

    public function FUNC_EMAIL_CUTI_KAR_APPROVE()
    {
        $data = CutiModel::select('tb_cuti.*','tb_cuti.ID as id_cuti')
                            ->where('status','1')
                            ->where('status_email',2)
                            ->whereRaw('(approve_1 = "Y" or approve_2 = "Y" )')
                            ->get();

        foreach ($data as $datas) {

            $atasan = EmployeeModel::select('NIK as nik_karyawan','Nama as nama_karyawan','email as email_karyawan')
                                        ->where('NIK',$datas->NIK)
                                        ->orWhere('old_nik',$datas->NIK)
                                        ->first();

            $nik_karyawan = $atasan->nik_karyawan;
            $nama_karyawan = $atasan->nama_karyawan;
            $email_karyawan = $atasan->email_karyawan;
            $tanggal_awal = $datas->TanggalMulaiCuti;
            $tanggal_selesai = $datas->TanggalSelesaiCuti;
            $alamat_cuti = $datas->AlamatSelamaCuti;
            $keterangan = $datas->Keterangan;
            $id_cuti = $datas->id_cuti;
            $catatan_approve_1 = $datas->catatan_approve_1;
            $catatan_approve_2 = $datas->catatan_approve_2;

            $dataw = [
                    'nik_karyawan' => $nik_karyawan,
                    'nama_karyawan' => $nama_karyawan,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_selesai' => $tanggal_selesai,
                    'alamat_cuti' => $alamat_cuti,
                    'keterangan' => $keterangan,
                    'catatan_approve_1' => $catatan_approve_1,
                    'catatan_approve_2' => $catatan_approve_2,
                    'id_cuti' => $id_cuti
                    ];

            $sendemail = Mail::send('email.email_cuti_kar_approve', $dataw, function ($mail) use ($email_karyawan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_karyawan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Cuti - Approve');
                        });

            $update = CutiModel::where('ID',$id_cuti)->first();
            $update->status_email = '3';
            $update->update();
        }
    }

    public function FUNC_EMAIL_CUTI_KAR_REJECT()
    {
        $data = CutiModel::select('tb_cuti.*','tb_cuti.ID as id_cuti')
                            ->where('status','<>','1')
                            ->where('status_email',2)
                            ->whereRaw('(approve_1 = "R" or approve_2 = "R" )')
                            ->get();

        foreach ($data as $datas) {

            $atasan = EmployeeModel::select('NIK as nik_karyawan','Nama as nama_karyawan','email as email_karyawan')
                                        ->where('NIK',$datas->NIK)
                                        ->orWhere('old_nik',$datas->NIK)
                                        ->first();

            $nik_karyawan = $atasan->nik_karyawan;
            $nama_karyawan = $atasan->nama_karyawan;
            $email_karyawan = $atasan->email_karyawan;
            $tanggal_awal = $datas->TanggalMulaiCuti;
            $tanggal_selesai = $datas->TanggalSelesaiCuti;
            $alamat_cuti = $datas->AlamatSelamaCuti;
            $keterangan = $datas->Keterangan;
            $id_cuti = $datas->id_cuti;
            $alasan_reject = $datas->alasan_reject;

            $dataw = [
                    'nik_karyawan' => $nik_karyawan,
                    'nama_karyawan' => $nama_karyawan,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_selesai' => $tanggal_selesai,
                    'alamat_cuti' => $alamat_cuti,
                    'keterangan' => $keterangan,
                    'id_cuti' => $id_cuti,
                    'alasan_reject' => $alasan_reject
                    ];

            $sendemail = Mail::send('email.email_cuti_kar_reject', $dataw, function ($mail) use ($email_karyawan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($email_karyawan);
                            // $mail->to('dandygantengkok@gmail.com');
                            $mail->subject('Request Cuti - Reject');
                        });

            $update = CutiModel::where('ID',$id_cuti)->first();
            $update->status_email = '3';
            $update->update();
        }
    }

    public function FUNC_EMAIL_CUTI_KAR_DAFTAR()
    {
        $atasan = EmployeeModel::select('*')
                                ->whereRaw('idpangkat IN (1,2,3,4,5,6,7)')
                                ->where('resign','N')
                                ->where('Nama','idris kusuma bhakti')
                                ->get();
        $email_karyawan = "muhammad.ibrahim@edi-indonesia.co.id";

        //dd($atasan);
        foreach ($atasan as $atasankaryawan) {
            //dd($atasankaryawan->Nama);
            $datarr = CutiModel::select('tb_cuti.NIK as nik_karyawan',
                                      'tb_datapribadi.nama as nama_karyawan',
                                      'tb_cuti.TanggalMulaiCuti as tgl_mulai',
                                      'tb_cuti.TanggalSelesaiCuti as tgl_selesai',
                                      'tb_cuti.AlamatSelamaCuti as alamat',
                                      'tb_cuti.keterangan as tujuan',
                                      'tb_cuti.tgl_approved_1 as tgl_approved_1',
                                      'tb_cuti.tgl_approved_2 as tgl_approved_2')
                            ->leftjoin('tb_datapribadi','tb_cuti.NIK','=','tb_datapribadi.NIK')
                            ->whereRaw('(CURDATE() BETWEEN tb_cuti.TanggalMulaiCuti AND tb_cuti.TanggalSelesaiCuti)')
                            //->where('tb_cuti.TanggalMulaiCuti','2015-07-22')
                            ->where('tb_datapribadi.atasan1',$atasankaryawan->NIK)
                            ->orwhere('tb_datapribadi.atasan2',$atasankaryawan->NIK)
                            ->limit(1)
                            ->get();
                             // dd($datarr);

            $data = ['array' => $datarr];
            //return view('email.email_cuti_daftar', $data);

            //dd($data);
            if (count($datarr)>0) {
                $sendemail = Mail::send('email.email_cuti_daftar', $data, function ($mail) use ($email_karyawan)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            // $mail->to($email_karyawan);
                            $mail->to('muhammad.ibrahim@edi-indonesia.co.id');
                            $mail->subject('Daftar Karyawan Cuti');
                        });
            }


        }
    }

    public function insertlogperubahan($data, $flag='')
    {
        $insert = new LogPerubahanModel();
        $insert->nik = $data['nik_yang_diganti'];
        unset($data['nik_yang_diganti']);
        $string_send = '';
        $hitung_index = 0;
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $hitung_index++;

                if ($key == 'Agama') {
                    $value = AgamaModel::where('idagama', $value)->value('nama_agama');
                }

                if (count($data) == $hitung_index) {
                    $separator = '';
                }else{
                    $separator = '<li>';
                }

                if ($flag) {
                    $string_send .= $value.$separator;
                }else{
                    $string_send .= $key.' = '.$value.$separator;
                }

            }
        }

        $insert->datachange = $string_send;
        $insert->status = 0;
        $insert->change_by = Session::get('nik');
        $insert->save();

        return true;

    }

    public function sendemailperubahan()
    {

        $data = LogPerubahanModel::where('status',0)->get();
        foreach ($data as $datas) {
            $update_status = LogPerubahanModel::where('id',$datas->id)
                                                ->update([
                                                    'status' => '2',
                                                  ]);
        }

        $emails = ['dandy.firmansyah@edi-indonesia.co.id', 'articha.damayanti@edi-indonesia.co.id', 'ade.rachmi@edi-indonesia.co.id', 'noviyanti@edi-indonesia.co.id'];
        // send email
        foreach ($data as $datas) {
            $nama_data_dirubah = EmployeeModel::where('NIK', $datas->nik)->value('Nama');
            $nama_change_by = EmployeeModel::where('NIK', $datas->change_by)->value('Nama');

            $dataw = [
                        'nik_data_dirubah' => $datas->nik,
                        'nama_data_dirubah' => $nama_data_dirubah,
                        'datachange' => $datas->datachange,
                        'change_by' => $datas->change_by,
                        'nama_change_by' => $nama_change_by,
                        'tanggal_update' => $datas->created_at
                    ];

            $sendemail = Mail::send('email.log_perubahan_data', $dataw, function ($mail) use ($emails)
                        {
                            $mail->from('noreply@hris.edii.local', 'HRIS 2.0');
                            $mail->to($emails);
                            // $mail->to('dandy.firmansyah@edi-indonesia.co.id');
                            $mail->subject('Log Perubahan Data Karyawan');
                        });

            if ($sendemail) {
                $update_status_again = LogPerubahanModel::where('id',$datas->id)
                                                ->update([
                                                    'status' => '1',
                                                  ]);
            }
        }
    }
}
