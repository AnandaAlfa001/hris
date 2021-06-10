<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\EmployeeModel;
use App\Models\AbsenLogModel;
use App\Models\AbsenMobileLogModel;
use App\Models\AbsenRekapModel;
use GuzzleHttp\Client;


class AbsenController extends Controller
{
    public function FUNC_DAFTARABSEN()
    {
    	$nik = Session::get('nik');
    	$tahun = date('Y');
    	$bulan = date('m');
    	$dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
    								  ->where('nik',$nik)
    								  ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."'")
    								  ->get();
    	return view('absen/daftarabsen')->with('data',$dataabsen);

    }

    public function FUNC_VIEWABSENPEG($id="",$bulan="",$tahun="")
    {
        if ($id=='0') {
            $id=Session::get('nik');
            $tahun = date('Y');
            $bulan = date('m');
        }
        $datapegawai = EmployeeModel::select('*')
                            ->where('nik',$id)
                            ->first();
                            //dd($datapegawai);

        $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
                                      ->where('nik',$id)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."'")
                                      ->get();
                                      //dd($dataabsen);
        return view('absen/daftarabsenpeg')->with('data',$dataabsen)->with('datapegawai',$datapegawai);

    }

    public function FUNC_DAFTARPEG()
    {
        $nik = Session::get('nik');
        $idpangkat = Session::get('idpangkat');

        if($idpangkat == 2 && $idpangkat == 3){
            $datapegawai = EmployeeModel::select('*')->get();
        }else if ($idpangkat == 5) {
            $datapegawai = EmployeeModel::select('*')
                            ->where('atasan1',$nik)
                            ->where('atasan2',$nik)
                            ->get();
        }else{
             $datapegawai = EmployeeModel::select('*')
                            ->where('atasan1',$nik)
                            ->get();
        }

        return view('absen/daftarpeg')->with('data',$datapegawai);

    }

    public function FUNC_SEARCHABSEN($bulan,$tahun)
    {
        $nik = Session::get('nik');
        $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
                                      ->where('nik',$nik)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."'")
                                      ->get();
        return view('absen/daftarabsen')->with('data',$dataabsen);

    }

    public function FUNC_SEARCHABSENPEG($bulan,$tahun)
    {
        $nik = Session::get('nik');
        $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
                                      ->where('nik',$nik)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."'")
                                      ->get();
        return view('absen/daftarabsenpeg')->with('data',$dataabsen);

    }

    public function FUNC_SAVEABSEN(Request $request)
    {
        $id=$request['id'];
        $keterangan=$request['keterangan'];
        $keterangan2=$request['keterangan2'];

        print_r($id);

        $count=count($id);
        for ($i=0; $i < $count; $i++) {
            if ($keterangan['0']!='') {
                DB::table('absen_rekap')
                    ->where('id', $id[$i])
                    ->update(
                      ['approved1' => 'M','ket_tamp'=>$keterangan[$i]]
                    );
            }else{
                DB::table('absen_rekap')
                    ->where('id', $id[$i])
                    ->update(
                      ['approved1' => 'M','ket_tamp'=>$keterangan2[$i]]
                    );
            }
        }
        return back();
    }

    public function FUNC_APPROVEABSEN($id,$bulan,$tahun)
    {
         $datapegawai = EmployeeModel::select('*')
                            ->where('nik',$id)
                            ->first();

        $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
                                      ->where('nik',$id)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."'")
                                      ->get();
        return view('absen/approveabsen')->with('data',$dataabsen)->with('bulan',$bulan)->with('tahun',$tahun)->with('nik',$id)->with('datapegawai',$datapegawai);

    }

    public function FUNC_APPABSEN(Request $request,$nik,$bulan,$tahun)
    {
        $idpangkat = EmployeeModel::select('idpangkat')->where('NIK',$nik)->first();
        $pangkat = $idpangkat->idpangkat;

        $id_absen=$request['id'];
        $app1=$request['app1'];
        $app2=$request['app2'];
        $ket=$request['ket'];
       /* $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
                                      ->where('nik',$nik)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."'")
                                      ->first();*/

        if ($pangkat=='7' or $pangkat=='8' or $pangkat=='9' or $pangkat=='10') {

            if ($app1[0]=='M' and $app2[0]==null) {

                $count=count($id_absen);
                for ($i=0; $i < $count; $i++) {
                        DB::table('absen_rekap')
                            ->where('id', $id_absen[$i])
                            ->update(
                              ['approved1' => 'Y','approved2'=>'M']
                            );
                }

            } elseif ($app1[0]=='Y' and $app2[0]=='M') {

                $count=count($id_absen);
                for ($i=0; $i < $count; $i++) {
                        DB::table('absen_rekap')
                            ->where('id', $id_absen[$i])
                            ->update(
                              ['approved2'=>'Y','ket'=>$ket[$i]]
                            );
                }

            }
            return redirect('/');

        } elseif ($pangkat=='2' or $pangkat=='3' or $pangkat=='4' or $pangkat=='5' or $pangkat=='6') {

            $count=count($id_absen);
            for ($i=0; $i < $count; $i++) {
                    DB::table('absen_rekap')
                        ->where('id', $id_absen[$i])
                        ->update(
                          ['approved1'=>'Y','ket'=>$ket[$i]]
                        );
            }
            return redirect('/');
        }

        //print_r($id_absen);
    }

    public function FUNC_APPRABSEN($nik,$bulan,$tahun)
    {
        $idpangkat = EmployeeModel::select('idpangkat')->where('NIK',$nik)->first();
        $pangkat = $idpangkat->idpangkat;

        $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as absen_rekap_id')
                                      ->where('nik',$nik)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."' AND selisih>0")
                                      ->get();

        foreach($dataabsen as $request) {
            $id_absen=$request->absen_rekap_id;
            $app1=$request->approved1;
            $app2=$request->approved2;
            $ket=$request->ket_tamp;
        }

        dd($dataabsen);
/*
        if ($pangkat=='7' or $pangkat=='8' or $pangkat=='9' or $pangkat=='10') {

            if ($app1[0]=='M' and $app2[0]==null) {

                $count=count($id_absen);
                for ($i=0; $i < $count; $i++) {
                        DB::table('absen_rekap')
                            ->where('id', $id_absen[$i])
                            ->update(
                              ['approved1' => 'Y','approved2'=>'M']
                            );
                }

            } elseif ($app1[0]=='Y' and $app2[0]=='M') {

                $count=count($id_absen);
                for ($i=0; $i < $count; $i++) {
                        DB::table('absen_rekap')
                            ->where('id', $id_absen[$i])
                            ->update(
                              ['approved2'=>'Y','ket'=>$ket[$i]]
                            );
                }

            }
            return redirect('/');

        } elseif ($pangkat=='2' or $pangkat=='3' or $pangkat=='4' or $pangkat=='5' or $pangkat=='6') {

            $count=count($id_absen);
            for ($i=0; $i < $count; $i++) {
                    DB::table('absen_rekap')
                        ->where('id', $id_absen[$i])
                        ->update(
                          ['approved1'=>'Y','ket'=>$ket[$i]]
                        );
            }
            return redirect('/');
        }*/

        //print_r($id_absen);
    }

    public function FUNC_REJECTABSEN($nik,$bulan,$tahun)
    {
        $idpangkat = EmployeeModel::select('idpangkat')->where('NIK',$nik)->first();
        $pangkat = $idpangkat->idpangkat;

        $dataabsen = AbsenRekapModel::select('*','absen_rekap.id as id_absen')
                                      ->where('nik',$nik)
                                      ->whereRaw("YEAR(tgl)='".$tahun."' AND MONTH(tgl)='".$bulan."' AND selisih>0")
                                      ->get();
                                      //dd($dataabsen);
        foreach ($dataabsen as $dataabsens) {
            $id_absen[]=$dataabsens->id_absen;
            $app1[]=$dataabsens->approved1;
            $app2[]=$dataabsens->approved2;
        }

        if ($pangkat=='7' or $pangkat=='8' or $pangkat=='9' or $pangkat=='10') {

            if ($app1[0]=='M' and $app2[0]==null) {

                $count=count($id_absen);
                for ($i=0; $i < $count; $i++) {
                        DB::table('absen_rekap')
                            ->where('id', $id_absen[$i])
                            ->update(
                              ['approved1' => 'N','ket_tamp'=>NULL]
                            );
                }

            } elseif ($app1[0]=='Y' and $app2[0]=='M') {

                $count=count($id_absen);
                for ($i=0; $i < $count; $i++) {
                        DB::table('absen_rekap')
                            ->where('id', $id_absen[$i])
                            ->update(
                              ['approved2'=>'N','approved1'=>'N','ket_tamp'=>NULL]
                            );
                }

            }
            return redirect('/');

        } elseif ($pangkat=='2' or $pangkat=='3' or $pangkat=='4' or $pangkat=='5' or $pangkat=='6') {

            $count=count($id_absen);
            for ($i=0; $i < $count; $i++) {
                    DB::table('absen_rekap')
                        ->where('id', $id_absen[$i])
                        ->update(
                          ['approved1'=>'N','ket_tamp'=>NULL]
                        );
            }
            return redirect('/');
        }
    }

    public function FUNC_LISTAPPABSEN()
    {
        $nik = Session::get('nik');

         $daftarabsen = AbsenRekapModel::select('tb_datapribadi.nik','tb_datapribadi.nama','tb_datapribadi.photo',DB::raw('MONTH(absen_rekap.tgl) AS bulan'),DB::raw('YEAR(absen_rekap.tgl) AS tahun'),DB::raw('SUM(absen_rekap.selisih) AS selisih'),'absen_rekap.id as id_absen')
                                                ->leftjoin('tb_datapribadi','tb_datapribadi.nik','=','absen_rekap.nik')
                                                //->where('absen_rekap.approved1','M')
                                                ->whereRaw(
                                                  DB::raw('CASE WHEN absen_rekap.approved1 = "M" and absen_rekap.approved2 IS NULL THEN tb_datapribadi.atasan1 = "'.$nik.'"
                                    WHEN absen_rekap.approved2 = "M" and absen_rekap.approved1 = "Y" THEN tb_datapribadi.atasan2 = "'.$nik.'" END'))
                                                //->where('tb_datapribadi.atasan1',$nikatasan)
                                                //->whereRaw('tb_datapribadi.atasan1 = "'.$nik.'" OR tb_datapribadi.atasan2="'.$nik.'"')
                                                ->groupby('absen_rekap.nik')
                                                ->get();
                                                //dd($daftarabsen);

         return view('absen/daftarpeg')->with('data',$daftarabsen);

    }

    public function pullPresenceHadirkoe($startDate='',$endDate='')
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

        $response = $client->request('GET', env('APP_HADIRKOE').'/api/presence/7ed4c107115c481da59b5dae3403c24f21b69aa7', [
            'json' => [
                'presence_start_date' => $startDate,
                'presence_end_date' => $endDate
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $dataPresence = $data['data'];

        if (count($dataPresence) > 0) {
            foreach ($dataPresence as $key => $value) {
                $logUpdate = AbsenLogModel::updateOrCreate([
                    'nik'           => $value['EMPLOYEE_NIK'],
                    'tgl_absen'     => $value['ABSENSI_TGL_ABSEN'],
                    'jam_absen'     => $value['ABSENSI_JAM_ABSEN']
                ],[
                    'lat'           => $value['ABSENSI_LAT'],
                    'lng'           => $value['ABSENSI_LNG'],
                    'keterangan'    => $value['ABSENSI_KETERANGAN'],
                    'tipe'          => $value['ABSENSI_TIPE'],
                    'jns'           => $value['ABSENSI_JNS']
                ]);

                $mobilelogUpdate = AbsenMobileLogModel::updateOrCreate([
                    'nik'           => $value['EMPLOYEE_NIK'],
                    'tgl_absen'     => $value['ABSENSI_TGL_ABSEN'],
                    'jam_absen'     => $value['ABSENSI_JAM_ABSEN']
                ],[
                    'log_id'        => 'MOB' . $value['ABSENSI_ID'],
                    'lat'           => $value['ABSENSI_LAT'],
                    'lng'           => $value['ABSENSI_LNG'],
                    'keterangan'    => '[From Hadirkoe] ' . $value['ABSENSI_KETERANGAN'],
                    'tipe'          => $value['ABSENSI_TIPE'],
                    'jns'           => $value['ABSENSI_JNS']
                ]);
            }
            return 'Done';
        }else{
            return 'Data Not Exist';
        }
    }
}
