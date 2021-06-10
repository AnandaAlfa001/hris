<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use App\Models\KesehatanModel;
use App\Models\HakKesehatanModel;
use App\Models\HealthModel;
use Mail;

class HealthController extends Controller
{
	// public function FUNC_SENDMAIL() {
	// 	$stat = KesehatanModel::where('stat',0)->get();
	// 	$status = CutiModel::where('status',0)->get();
	// 	if($stat) {
	// 		foreach ($stat as $key => $stats) {
	// 			$link = url('daftarreq');
	// 			$data = array(
	// 			'nama' => $stats->status,
	// 	        'nik' => $stats->NIK,
	// 	        'link' => $link,
	// 		    );
	// 		    Mail::queue('health.mail.Mail', $data, function ($message) {
	// 	    	$file = asset('image/notfound.png');
	// 	    	 $message->attach($file);
	// 	        $message->from('dandyfirmansyah@edi-indonesia.co.id', 'riyan');

	// 	        $message->to('dwi.riyantono@edi-indonesia.co.id')->subject('HRIS EDII Kesehatan');

	// 	    	});
	// 	    	$stats->stat = 1;
	// 	    	$stats->update();
	// 		}
	// 	}

	// 	elseif($status) {
	// 		foreach ($status as $key => $statuss) {
	// 			$link = url('approvecuti');
	// 			$data = array(
	// 			'nama' => $statuss->status,
	// 	        'nik' => $statuss->NIK,
	// 	        'link' => $link,
	// 		    );
	// 		    Mail::queue('cuti.emailcuti', $data, function ($message) {

	// 	        $message->from('dandyfirmansyah@edi-indonesia.co.id', 'riyan');

	// 	        $message->to('dwi.riyantono@edi-indonesia.co.id')->subject('HRIS EDII Kesehatan');

	// 	    	});
	// 	    	$statuss->status = 1;
	// 	    	$statuss->update();
	// 		}

	// 	}

	// }

	public function FUNC_SENDMAILAPPRV() {
		$data = array(
		    );
		Mail::send('health.mail.Approve', $data,function ($message) {

	        $message->from('dwiriyan855@gmail.com', 'riyan');

	        $message->to('dwi.riyan855@gmail.com')->subject('HRIS EDII Kesehatan');

	    });
	}

	public function FUNC_HAKKES() {
		$hak = HakKesehatanModel::leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_hakkesehatan.NIK')
								  ->where('tb_datapribadi.resign','N')
								  ->orderBy('tb_hakkesehatan.NIK','ASC')->get();
		return view('health/HakKes')->with('hak',$hak);
	}

	public function FUNC_HISTORYKES() {
		$nik = Session::get('nik');
		if(Session::get('admin')==1) {
			$kes = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv','approve',
							DB::raw('CASE
			                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = tb_kesehatan.NIK))
			                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = tb_kesehatan.NIK OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = tb_kesehatan.NIK))
			                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = tb_kesehatan.NIK OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = tb_kesehatan.NIK)) and AnakKe = 1)
			                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = tb_kesehatan.NIK OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = tb_kesehatan.NIK)) and AnakKe = 2)
			                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = tb_kesehatan.NIK OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = tb_kesehatan.NIK)) and AnakKe = 3)
			                END as nama')
							)
							->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
							->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
							->orderBy('tglklaim','DESC')
							->get();
		} else {
			$kes = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','total_apprv','approve',
							DB::raw('CASE
			                WHEN tb_kesehatan.status = "P" THEN (SELECT Nama FROM tb_datapribadi WHERE NIK = "'.$nik.'" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "'.$nik.'"))
			                WHEN tb_kesehatan.status = "I" THEN (SELECT NamaPasangan FROM tb_datapribadi WHERE NIK = "'.$nik.'" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "'.$nik.'"))
			                WHEN tb_kesehatan.status = "A1" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "'.$nik.'" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "'.$nik.'")) and AnakKe = 1)
			                WHEN tb_kesehatan.status = "A2" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "'.$nik.'" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "'.$nik.'")) and AnakKe = 2)
			                WHEN tb_kesehatan.status = "A3" THEN (SELECT NamaAnak FROM tb_anak WHERE (NIK = "'.$nik.'" OR NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "'.$nik.'")) and AnakKe = 3)
			                END as nama'))
							->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_kesehatan.NIK')
							->leftjoin('tb_remb','tb_remb.id','=','tb_kesehatan.jn_remb')
							// ->where('tb_kesehatan.NIK',Session::get('nik'))
							->whereRaw('(tb_kesehatan.NIK = "'.$nik.'" OR tb_kesehatan.NIK = (SELECT old_nik FROM tb_datapribadi WHERE tb_datapribadi.NIK = "'.$nik.'"))')
							->orderBy('tglklaim','DESC')
							->get();
		}

		// dd($kes);

		return view('health/HistoryKes')->with('kes',$kes);

	}

	public function FUNC_ADDKES() {
        $data = HakKesehatanModel::select('tb_datapribadi.NIK as NIK','nama_pas','tb_hakkesehatan.status as status',
                                    DB::raw('CONCAT(tb_datapribadi.NIK , " - [" ,tb_hakkesehatan.status, "] ", nama_pas) as pasien')
                                    )
        							->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_hakkesehatan.NIK')
                                    ->where('tb_datapribadi.resign','N')
                                    ->where('tb_datapribadi.statuskar','1')
                                    ->orwhere('tb_datapribadi.statuskar','2')
                                    ->orwhere('tb_datapribadi.statuskar','3')
                                    ->orwhere('tb_datapribadi.statuskar','4')
                                    ->get();

        return view('health/AddKes')->with('data',$data);
    }

    public function FUNC_REQKES() {
    	$nik = Session::get('nik');
    	$data = HakKesehatanModel::select('tb_datapribadi.NIK as NIK','nama_pas','tb_hakkesehatan.status as status22',
                                    DB::raw('CONCAT(tb_datapribadi.NIK , " - [" ,tb_hakkesehatan.status, "] ", nama_pas) as pasien')
                                    )
        							->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_hakkesehatan.NIK')
        							->where('tb_hakkesehatan.NIK',$nik)->get();

    	$kelrj=0;$kelkm=0;$kelrg=0;$kelmh=0;
    	if($data) {
    		$allrj = HakKesehatanModel::where('NIK',$nik)->get();
    		foreach ($allrj as $allrjs) {
    			$kelrj += $allrjs->sisa_rawat_jalan;
    			$kelkm += $allrjs->sisa_kacamata;
    			$kelrg += $allrjs->sisa_gigi;
    			$kelmh += $allrjs->sisa_melahirkan;
    		}

    		return view('health/ReqKes')->with('data',$data)
    									->with('kelrj',$kelrj)
    									->with('kelkm',$kelkm)
    									->with('kelrg',$kelrg)
    									->with('kelmh',$kelmh);
    	} else {
    		return view('health/CantReq');
    	}
    }

    public function FUNC_LISTREQ() {
    	$data = KesehatanModel::select('tb_kesehatan.ID as idkes','tglklaim','remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','approve',
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
							->where('approve','N')
							->orderBy('tglklaim','DESC')
							->get();

    	return view('health/DaftarRequest')->with('data',$data);
    }

    public function FUNC_DETAILREQ(Request $request,$id)
    {
    	$data = KesehatanModel::select('tb_kesehatan.ID as idkes','tb_kesehatan.NIK','tglklaim','remb','jn_remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','approve','tb_kesehatan.status as status2',
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
							->where('tb_kesehatan.ID',$id)
							->first();

    	// $NIK = KesehatanModel::where('ID',$id)->first();

    	$data2 = HakKesehatanModel::where('NIK',$data->NIK)->where('status',$data->status2)->first();
    	$kelrj=0;$kelkm=0;$kelrg=0;$kelmh=0;
		$allrj = HakKesehatanModel::where('NIK',$data->NIK)->get();

		foreach ($allrj as $allrjs) {
			$kelrj += $allrjs->sisa_rawat_jalan;
			$kelkm += $allrjs->sisa_kacamata;
			$kelrg += $allrjs->sisa_gigi;
			$kelmh += $allrjs->sisa_melahirkan;
		}

		if ($data->jn_remb==1) {
			$tot = $kelrj;
			$juben = $data2->rawat_jalan;
			$siben = $data2->sisa_rawat_jalan;
		} elseif ($data->jn_remb==2) {
			$tot = $kelrg;
			$juben = $data2->gigi;
			$siben = $data2->sisa_gigi;
		} elseif ($data->jn_remb==3) {
			$tot = $kelkm;
			$juben = $data2->kacamata;
			$siben = $data2->sisa_kacamata;
		} else {
			$tot = $kelmh;
			$juben = $data2->melahirkan;
			$siben = $data2->sisa_melahirkan;
		}

    	return view('health/DetailReq')->with('data',$data)
    								   ->with('data2',$data2)
    								   ->with('juben',$juben)
    								   ->with('siben',$siben)
    									->with('tot',$tot);
    }

    public function FUNC_SAVEREQRAWATJALAN(Request $request) {

    	$this->validate($request, [
            'nik' => 'required',
            'nama' => 'required',
            'diagnosa' => 'required',
            'obat' => 'required',
            'klaim' => 'required',
            'jklaim' => 'required',
        	]);

    		$nik = $request['nik'];
	        $status = $request['status'];

    		$tambah = new KesehatanModel();
	        $tambah->NIK = $request['nik'];
	        $tambah->status = $request['status'];
	        $tambah->jn_remb = 1;
	        $tambah->tglklaim = $request['klaim'];
	        $tambah->nama_apotek = $request['nama'];
	        $tambah->tglberobat = $request['obat'];
	        $tambah->diagnosa = $request['diagnosa'];
	        $tambah->total_klaim = $request['jklaim'];
	        $tambah->approve = 'N';
	        $tambah->approve_vp = 'N';
	        $tambah->approve_svp = 'N';

	       $tes = KesehatanModel::orderBy('ID','DESC')->first();
	        $id =(int) $tes->ID;
	        $id +=1;
			$N=null;
	        $file = $request->file('gambar');
	        foreach ($file as $key => $files) {
	        	if($files != null) {
	        		$fileName = $files->getClientOriginalExtension();
	            	$fileName = $id.'_'.$key.'.'.$fileName;
	            	$N[$key]=$fileName;
	            	$files->move("image/Kesehatan",$fileName);
	        		}
	        	}
	        	if($N) {
	        		$nm = implode("|", $N);
            		$tambah->kwitansi = $nm;
	        	}

	        $tambah->save();

	        $new = KesehatanModel::select('tb_kesehatan.ID as idkes','tb_kesehatan.NIK','tglklaim','remb','jn_remb','kwitansi','nama_apotek','tglberobat','diagnosa','total_klaim','approve','tb_kesehatan.status as status2',
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
							->where('tb_kesehatan.ID',$id)
							->first();

	        	return redirect()->back()->with('success','Request Berhasil Diajukan');

    }

    public function FUNC_SAVEREQRAWATKM(Request $request) {
    	$this->validate($request, [
            'nik' => 'required',
            'nama' => 'required',
            'diagnosa' => 'required',
            'obat' => 'required',
            'klaim' => 'required',
            'jklaim' => 'required',
        	]);

    		$nik = $request['nik'];
	        $status = $request['status'];

    		$tambah = new KesehatanModel();
	        $tambah->NIK = $request['nik'];
	        $tambah->status = $request['status'];
	        $tambah->jn_remb = 3;
	        $tambah->tglklaim = $request['klaim'];
	        $tambah->nama_apotek = $request['nama'];
	        $tambah->tglberobat = $request['obat'];
	        $tambah->diagnosa = $request['diagnosa'];
	        $tambah->total_klaim = $request['jklaim'];
	        $tambah->approve = 'N';
	        $tambah->approve_vp = 'N';
	        $tambah->approve_svp = 'N';

	       $tes = KesehatanModel::orderBy('ID','DESC')->first();
	        $id =(int) $tes->ID;
	        $id +=1;
			$N=null;
	        $file = $request->file('gambar');
	        foreach ($file as $key => $files) {
	        	if($files != null) {
	        		$fileName = $files->getClientOriginalExtension();
	            	$fileName = $id.'_'.$key.'.'.$fileName;
	            	$N[$key]=$fileName;
	            	$files->move("image/Kesehatan",$fileName);
	        		}
	        	}
	        	if($N) {
	        		$nm = implode("|", $N);
            		$tambah->kwitansi = $nm;
	        	}

	        $tambah->save();

	        	return redirect()->back()->with('success','Request Berhasil Diajukan');

    }

    public function FUNC_SAVEREQRAWATGIGI(Request $request) {
    	$this->validate($request, [
            'nik' => 'required',
            'nama' => 'required',
            'diagnosa' => 'required',
            'obat' => 'required',
            'klaim' => 'required',
            'jklaim' => 'required',
        	]);

    		$nik = $request['nik'];
	        $status = $request['status'];

    		$tambah = new KesehatanModel();
	        $tambah->NIK = $request['nik'];
	        $tambah->status = $request['status'];
	        $tambah->jn_remb = 2;
	        $tambah->tglklaim = $request['klaim'];
	        $tambah->nama_apotek = $request['nama'];
	        $tambah->tglberobat = $request['obat'];
	        $tambah->diagnosa = $request['diagnosa'];
	        $tambah->total_klaim = $request['jklaim'];
	        $tambah->approve = 'N';
	        $tambah->approve_vp = 'N';
	        $tambah->approve_svp = 'N';

	       $tes = KesehatanModel::orderBy('ID','DESC')->first();
	        $id =(int) $tes->ID;
	        $id +=1;
			$N=null;
	        $file = $request->file('gambar');
	        foreach ($file as $key => $files) {
	        	if($files != null) {
	        		$fileName = $files->getClientOriginalExtension();
	            	$fileName = $id.'_'.$key.'.'.$fileName;
	            	$N[$key]=$fileName;
	            	$files->move("image/Kesehatan",$fileName);
	        		}
	        	}
	        	if($N) {
	        		$nm = implode("|", $N);
            		$tambah->kwitansi = $nm;
	        	}

	        $tambah->save();

	        	return redirect()->back()->with('success','Request Berhasil Diajukan');

    }

    public function FUNC_SAVEREQRAWATLAHIR(Request $request) {
    	$this->validate($request, [
            'nik' => 'required',
            'nama' => 'required',
            'diagnosa' => 'required',
            'obat' => 'required',
            'klaim' => 'required',
            'jklaim' => 'required',
        	]);

    		$nik = $request['nik'];
	        $status = $request['status'];

    		$tambah = new KesehatanModel();
	        $tambah->NIK = $request['nik'];
	        $tambah->status = $request['status'];
	        $tambah->jn_remb = 4;
	        $tambah->tglklaim = $request['klaim'];
	        $tambah->nama_apotek = $request['nama'];
	        $tambah->tglberobat = $request['obat'];
	        $tambah->diagnosa = $request['diagnosa'];
	        $tambah->total_klaim = $request['jklaim'];
	        $tambah->approve = 'N';
	        $tambah->approve_vp = 'N';
	        $tambah->approve_svp = 'N';

	       $tes = KesehatanModel::orderBy('ID','DESC')->first();
	        $id =(int) $tes->ID;
	        $id +=1;
			$N=null;
	        $file = $request->file('gambar');
	        foreach ($file as $key => $files) {
	        	if($files != null) {
	        		$fileName = $files->getClientOriginalExtension();
	            	$fileName = $id.'_'.$key.'.'.$fileName;
	            	$N[$key]=$fileName;
	            	$files->move("image/Kesehatan",$fileName);
	        		}
	        	}
	        	if($N) {
	        		$nm = implode("|", $N);
            		$tambah->kwitansi = $nm;
	        	}

	        $tambah->save();

	        	return redirect()->back()->with('success','Request Berhasil Diajukan');

    }

    public function FUNC_CEKRAWATJALAN(Request $request) {
    	$pasien = $request['pasien'];
    	$pasien = explode("|", $pasien);
		$nik = $pasien[0];
		$status= $pasien[1];
    	$data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
                                    ->first();
        $cari = HakKesehatanModel::where('NIK',$nik)
                                    ->get();
        $total = 0;
        foreach($cari as $caris) {
        	$total += $caris->sisa_rawat_jalan;
        }

        $benefit = $data->rawat_jalan;
        $sisa = $data->sisa_rawat_jalan;
        $sisak = $total;
        return response()->json(['nik' => $nik,
        						 'benefit' => $benefit,
        						 'sisa' => $sisa,
        						 'sisak' => $sisak,
        						 'status' => $status ],200);
    }

    public function FUNC_BUKTIKW(Request $request) {
    	$data = $request['data'];
    	$cek = KesehatanModel::where('ID',$data)->first();

    	$bukti = $cek->kwitansi;
    	$count = explode("|", $bukti);
    	$out = count($count);

    	return response()->json(['out' => $count],200);

    }

    public function FUNC_SAVERAWATJALAN(Request $request) {


    	if($request['sisak']>=$request['jsetuju'])
    	{
    		$nik = $request['nik'];
	        $status = $request['status'];
	        $setuju = $request['jsetuju'];

    		if($request['id'])
    		{
    			if($request['rejet']=='hm')
    			{
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'alasan' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);

    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->approve = 'R';
	    			$up->approve_vp = 'R';
	    			$up->approve_svp = 'R';
	    			$up->alasan_reject = $request['alasan'];
	    			$up->update();
	    			$msg='Klaim Berhasil Direject.';
    			} else {
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'jsetuju' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);
    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->total_apprv = $request['jsetuju'];
	    			$up->approve = 'Y';
	    			$up->approve_vp = 'Y';
	    			$up->approve_svp = 'Y';
	    			$up->update();
	    			$msg='Klaim Berhasil Diapprove.';

    			}

    		} else
    		{
    			$tambah = new KesehatanModel();
		        $tambah->NIK = $request['nik'];
		        $tambah->status = $request['status'];
		        $tambah->jn_remb = 1;
		        $tambah->tglklaim = $request['klaim'];
		        $tambah->nama_apotek = $request['nama'];
		        $tambah->tglberobat = $request['obat'];
		        $tambah->diagnosa = $request['diagnosa'];
		        $tambah->total_klaim = $request['jklaim'];
		        $tambah->total_apprv = $request['jsetuju'];
		        $tambah->approve = 'Y';
		        $tambah->approve_vp = 'Y';
		        $tambah->approve_svp = 'Y';

		       $tes = KesehatanModel::orderBy('ID','DESC')->first();
		        $id =(int) $tes->ID;
		        $id +=1;
				$N=null;
		        $file = $request->file('gambar');
		        foreach ($file as $key => $files) {
		        	if($files != null) {
		        		$fileName = $files->getClientOriginalExtension();
		            	$fileName = $id.'_'.$key.'.'.$fileName;
		            	$N[$key]=$fileName;
		            	$files->move("image/Kesehatan",$fileName);
		        		}
		        	}
		        	if($N) {
		        		$nm = implode("|", $N);
	            		$tambah->kwitansi = $nm;
		        	}

		        $tambah->save();
		        $msg='Data Berhasil Ditambah.';
    		}

	        $data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
	                                    ->first();
	        $sisa = $data->sisa_rawat_jalan;
	        $hasil = $sisa - $request['jsetuju'];
	        $akhir=0;
	        $stat = array('P','I','A1','A2','A3','P','I','A1','A2','A3');
	        if($hasil<0) {
	        	for ($i=0; $i < 5; $i++) {
	        		$a=$i+1; $b=$i+2; $c=$i+3;
	        		$data->sisa_rawat_jalan = 0;
		        	$akhir = $setuju - $sisa;

		        	if($status==$stat[$i])
		        	{
		        		$data1 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+1])
		                                    ->first();
		                $sisa1 = $data1->sisa_rawat_jalan;
		                if($sisa1<$akhir)
		               	{
		               		$data1->sisa_rawat_jalan = 0;
		               		$data1->update();
		               		$akhir1 = $akhir - $sisa1;

		               		$data2 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+2])
		                                    ->first();
			                $sisa2 = $data2->sisa_rawat_jalan;
			                if($sisa2<$akhir1)
			               	{
			               		$data2->sisa_rawat_jalan = 0;
			               		$data2->update();
			               		$akhir2 = $akhir1 - $sisa2;

			               		$data3 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+3])
			                                    ->first();
				                $sisa3 = $data3->sisa_rawat_jalan;
				                if($sisa3<$akhir2)
				               	{
				               		$data3->sisa_rawat_jalan = 0;
				               		$data3->update();
				               		$akhir3 = $akhir2 - $sisa3;

				               		$data4 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+4])
			                                    ->first();
					                $sisa4 = $data4->sisa_rawat_jalan;
					                if($sisa4<$akhir3)
					               	{
					               		$data4->sisa_rawat_jalan = 0;
					               		$data4->update();
					               		$akhir4 = $akhir3 - $sisa4;



					               	} else
					               	{
					               		$data4->sisa_rawat_jalan = $sisa4 - $akhir3;
					               		$data4->update();
					               	}

				               	} else
				               	{
				               		$data3->sisa_rawat_jalan = $sisa3 - $akhir2;
				               		$data3->update();
				               	}

			               	} else
			               	{
			               		$data2->sisa_rawat_jalan = $sisa2 - $akhir1;
			               		$data2->update();
			               	}

		               	}
		               	else
		               	{
		               		$data1->sisa_rawat_jalan = $sisa1 - $akhir;
		               		$data1->update();
		               	}

		        	}
		        	else {
		        		//return redirect()->back()->with('error','Error');
		        		continue;
		        	}
	        	}

	        } else {
	        	$data->sisa_rawat_jalan = $hasil;
	        }
	        $data->update();

	        return redirect('historykesehatan')->with('success',$msg);
    	} else
    	{
    		return redirect()->back()->with('error','Sisa Benefit/Benefit Keluarga Tidak Mencukupi');
    	}
    }

    public function FUNC_CEKRAWATGIGI(Request $request) {
    	$pasien = $request['pasien'];
    	$pasien = explode("|", $pasien);
		$nik = $pasien[0];
		$status= $pasien[1];
    	$data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
                                    ->first();
        $cari = HakKesehatanModel::where('NIK',$nik)
                                    ->get();
        $total = 0;
        foreach($cari as $caris) {
        	$total += $caris->sisa_gigi;
        }

        $benefit = $data->gigi;
        $sisa = $data->sisa_gigi;
        $sisak = $total;
        return response()->json(['nik' => $nik,
        						 'benefit' => $benefit,
        						 'sisa' => $sisa,
        						 'sisak' => $sisak,
        						 'status' => $status ],200);
    }

    public function FUNC_BUKTIKW2(Request $request) {
    	$data = $request['data'];
    	$cek = KesehatanModel::where('ID',$data)->first();

    	$bukti = $cek->kwitansi;
    	$count = explode("|", $bukti);
    	$out = count($count);

    	return response()->json(['out' => $count],200);

    }

    public function FUNC_SAVERAWATGIGI(Request $request) {
    	if($request['sisak']>=$request['jsetuju'])
    	{
    		$nik = $request['nik'];
	        $status = $request['status'];
	        $setuju = $request['jsetuju'];

    		if($request['id'])
    		{
    			if($request['rejet']=='hm')
    			{
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'alasan' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);

    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->approve = 'R';
	    			$up->approve_vp = 'R';
	    			$up->approve_svp = 'R';
	    			$up->alasan_reject = $request['alasan'];
	    			$up->update();
	    			$msg='Klaim Berhasil Direject.';
    			} else {
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'jsetuju' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);
    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->total_apprv = $request['jsetuju'];
	    			$up->approve = 'Y';
	    			$up->approve_vp = 'Y';
	    			$up->approve_svp = 'Y';
	    			$up->update();
	    			$msg='Klaim Berhasil Diapprove.';
    			}

    		} else
    		{
    			$tambah = new KesehatanModel();
		        $tambah->NIK = $request['nik'];
		        $tambah->status = $request['status'];
		        $tambah->jn_remb = 2;
		        $tambah->tglklaim = $request['klaim'];
		        $tambah->nama_apotek = $request['nama'];
		        $tambah->tglberobat = $request['obat'];
		        $tambah->diagnosa = $request['diagnosa'];
		        $tambah->total_klaim = $request['jklaim'];
		        $tambah->total_apprv = $request['jsetuju'];
		        $tambah->approve = 'Y';
		        $tambah->approve_vp = 'Y';
		        $tambah->approve_svp = 'Y';

		       $tes = KesehatanModel::orderBy('ID','DESC')->first();
		        $id =(int) $tes->ID;
		        $id +=1;
				$N=null;
		        $file = $request->file('gambar');
		        foreach ($file as $key => $files) {
		        	if($files != null) {
		        		$fileName = $files->getClientOriginalExtension();
		            	$fileName = $id.'_'.$key.'.'.$fileName;
		            	$N[$key]=$fileName;
		            	$files->move("image/Kesehatan",$fileName);
		        		}
		        	}
		        	if($N) {
		        		$nm = implode("|", $N);
	            		$tambah->kwitansi = $nm;
		        	}

		        $tambah->save();
		        $msg='Data Berhasil Ditambah.';
    		}

	        $data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
	                                    ->first();
	        $sisa = $data->sisa_gigi;
	        $hasil = $sisa - $request['jsetuju'];
	        $akhir=0;
	        $stat = array('P','I','A1','A2','A3','P','I','A1','A2','A3');
	        if($hasil<0) {
	        	for ($i=0; $i < 5; $i++) {
	        		$a=$i+1; $b=$i+2; $c=$i+3;
	        		$data->sisa_gigi = 0;
		        	$akhir = $setuju - $sisa;

		        	if($status==$stat[$i])
		        	{
		        		$data1 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+1])
		                                    ->first();
		                $sisa1 = $data1->sisa_gigi;
		                if($sisa1<$akhir)
		               	{
		               		$data1->sisa_gigi = 0;
		               		$data1->update();
		               		$akhir1 = $akhir - $sisa1;

		               		$data2 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+2])
		                                    ->first();
			                $sisa2 = $data2->sisa_gigi;
			                if($sisa2<$akhir1)
			               	{
			               		$data2->sisa_gigi = 0;
			               		$data2->update();
			               		$akhir2 = $akhir1 - $sisa2;

			               		$data3 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+3])
			                                    ->first();
				                $sisa3 = $data3->sisa_gigi;
				                if($sisa3<$akhir2)
				               	{
				               		$data3->sisa_gigi = 0;
				               		$data3->update();
				               		$akhir3 = $akhir2 - $sisa3;

				               		$data4 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+4])
			                                    ->first();
					                $sisa4 = $data4->sisa_gigi;
					                if($sisa4<$akhir3)
					               	{
					               		$data4->sisa_gigi = 0;
					               		$data4->update();
					               		$akhir4 = $akhir3 - $sisa4;



					               	} else
					               	{
					               		$data4->sisa_gigi = $sisa4 - $akhir3;
					               		$data4->update();
					               	}

				               	} else
				               	{
				               		$data3->sisa_gigi = $sisa3 - $akhir2;
				               		$data3->update();
				               	}

			               	} else
			               	{
			               		$data2->sisa_gigi = $sisa2 - $akhir1;
			               		$data2->update();
			               	}

		               	}
		               	else
		               	{
		               		$data1->sisa_gigi = $sisa1 - $akhir;
		               		$data1->update();
		               	}

		        	}
		        	else {
		        		//return redirect()->back()->with('error','Error');
		        		continue;
		        	}
	        	}

	        } else {
	        	$data->sisa_gigi = $hasil;
	        }
	        $data->update();

	        return redirect('historykesehatan')->with('success','Data Berhasil Ditambah.');
    	} else
    	{
    		return redirect()->back()->with('error','Sisa Benefit/Benefit Keluarga Tidak Mencukupi');
    	}
    }

    public function FUNC_CEKRAWATKM(Request $request) {
    	$pasien = $request['pasien'];
    	$pasien = explode("|", $pasien);
		$nik = $pasien[0];
		$status= $pasien[1];
    	$data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
                                    ->first();
        $cari = HakKesehatanModel::where('NIK',$nik)
                                    ->get();
        $total = 0;
        foreach($cari as $caris) {
        	$total += $caris->sisa_kacamata;
        }

        $benefit = $data->kacamata;
        $sisa = $data->sisa_kacamata;
        $sisak = $total;
        return response()->json(['nik' => $nik,
        						 'benefit' => $benefit,
        						 'sisa' => $sisa,
        						 'sisak' => $sisak,
        						 'status' => $status ],200);
    }

    public function FUNC_BUKTIKW3(Request $request) {
    	$data = $request['data'];
    	$cek = KesehatanModel::where('ID',$data)->first();

    	$bukti = $cek->kwitansi;
    	$count = explode("|", $bukti);
    	$out = count($count);

    	return response()->json(['out' => $count],200);

    }

    public function FUNC_SAVERAWATKM(Request $request) {

    	if($request['sisak']>=$request['jsetuju'])
    	{
    		$nik = $request['nik'];
	        $status = $request['status'];
	        $setuju = $request['jsetuju'];

    		if($request['id'])
    		{
    			if($request['rejet']=='hm')
    			{
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'alasan' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);

    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->approve = 'R';
	    			$up->approve_vp = 'R';
	    			$up->approve_svp = 'R';
	    			$up->alasan_reject = $request['alasan'];
	    			$up->update();
	    			$msg='Klaim Berhasil Direject.';
    			} else {
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'jsetuju' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);
    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->total_apprv = $request['jsetuju'];
	    			$up->approve = 'Y';
	    			$up->approve_vp = 'Y';
	    			$up->approve_svp = 'Y';
	    			$up->update();
	    			$msg='Klaim Berhasil Diapprove.';
    			}

    		} else
    		{
    			$tambah = new KesehatanModel();
		        $tambah->NIK = $request['nik'];
		        $tambah->status = $request['status'];
		        $tambah->jn_remb = 3;
		        $tambah->tglklaim = $request['klaim'];
		        $tambah->nama_apotek = $request['nama'];
		        $tambah->tglberobat = $request['obat'];
		        $tambah->diagnosa = $request['diagnosa'];
		        $tambah->total_klaim = $request['jklaim'];
		        $tambah->total_apprv = $request['jsetuju'];
		        $tambah->approve = 'Y';
		        $tambah->approve_vp = 'Y';
		        $tambah->approve_svp = 'Y';

		       $tes = KesehatanModel::orderBy('ID','DESC')->first();
		        $id =(int) $tes->ID;
		        $id +=1;
				$N=null;
		        $file = $request->file('gambar');
		        foreach ($file as $key => $files) {
		        	if($files != null) {
		        		$fileName = $files->getClientOriginalExtension();
		            	$fileName = $id.'_'.$key.'.'.$fileName;
		            	$N[$key]=$fileName;
		            	$files->move("image/Kesehatan",$fileName);
		        		}
		        	}
		        	if($N) {
		        		$nm = implode("|", $N);
	            		$tambah->kwitansi = $nm;
		        	}

		        $tambah->save();
		        $msg='Data Berhasil Ditambah.';
    		}

	        $data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
	                                    ->first();
	        $sisa = $data->sisa_kacamata;
	        $hasil = $sisa - $request['jsetuju'];
	        $akhir=0;
	        $stat = array('P','I','A1','A2','A3','P','I','A1','A2','A3');
	        if($hasil<0) {
	        	for ($i=0; $i < 5; $i++) {
	        		$a=$i+1; $b=$i+2; $c=$i+3;
	        		$data->sisa_kacamata = 0;
		        	$akhir = $setuju - $sisa;

		        	if($status==$stat[$i])
		        	{
		        		$data1 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+1])
		                                    ->first();
		                $sisa1 = $data1->sisa_kacamata;
		                if($sisa1<$akhir)
		               	{
		               		$data1->sisa_kacamata = 0;
		               		$data1->update();
		               		$akhir1 = $akhir - $sisa1;

		               		$data2 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+2])
		                                    ->first();
			                $sisa2 = $data2->sisa_kacamata;
			                if($sisa2<$akhir1)
			               	{
			               		$data2->sisa_kacamata = 0;
			               		$data2->update();
			               		$akhir2 = $akhir1 - $sisa2;

			               		$data3 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+3])
			                                    ->first();
				                $sisa3 = $data3->sisa_kacamata;
				                if($sisa3<$akhir2)
				               	{
				               		$data3->sisa_kacamata = 0;
				               		$data3->update();
				               		$akhir3 = $akhir2 - $sisa3;

				               		$data4 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+4])
			                                    ->first();
					                $sisa4 = $data4->sisa_kacamata;
					                if($sisa4<$akhir3)
					               	{
					               		$data4->sisa_kacamata = 0;
					               		$data4->update();
					               		$akhir4 = $akhir3 - $sisa4;



					               	} else
					               	{
					               		$data4->sisa_kacamata = $sisa4 - $akhir3;
					               		$data4->update();
					               	}

				               	} else
				               	{
				               		$data3->sisa_kacamata = $sisa3 - $akhir2;
				               		$data3->update();
				               	}

			               	} else
			               	{
			               		$data2->sisa_kacamata = $sisa2 - $akhir1;
			               		$data2->update();
			               	}

		               	}
		               	else
		               	{
		               		$data1->sisa_kacamata = $sisa1 - $akhir;
		               		$data1->update();
		               	}

		        	}
		        	else {
		        		//return redirect()->back()->with('error','Error');
		        		continue;
		        	}
	        	}

	        } else {
	        	$data->sisa_kacamata = $hasil;
	        }
	        $data->update();

	        return redirect('historykesehatan')->with('success','Data Berhasil Ditambah.');
    	} else
    	{
    		return redirect()->back()->with('error','Sisa Benefit/Benefit Keluarga Tidak Mencukupi');
    	}
    }

    public function FUNC_CEKRAWATLAHIR(Request $request) {
    	$pasien = $request['pasien'];
    	$pasien = explode("|", $pasien);
		$nik = $pasien[0];
		$status= $pasien[1];
    	$data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
                                    ->first();
        $cari = HakKesehatanModel::where('NIK',$nik)
                                    ->get();
        $total = 0;
        foreach($cari as $caris) {
        	$total += $caris->sisa_melahirkan;
        }

        $benefit = $data->melahirkan;
        $sisa = $data->sisa_melahirkan;
        $sisak = $total;
        return response()->json(['nik' => $nik,
        						 'benefit' => $benefit,
        						 'sisa' => $sisa,
        						 'sisak' => $sisak,
        						 'status' => $status ],200);
    }

    public function FUNC_BUKTIKW4(Request $request) {
    	$data = $request['data'];
    	$cek = KesehatanModel::where('ID',$data)->first();

    	$bukti = $cek->kwitansi;
    	$count = explode("|", $bukti);
    	$out = count($count);

    	return response()->json(['out' => $count],200);

    }

    public function FUNC_SAVERAWATLAHIR(Request $request) {

    	if($request['sisak']>=$request['jsetuju'])
    	{
    		$nik = $request['nik'];
	        $status = $request['status'];
	        $setuju = $request['jsetuju'];

    		if($request['id'])
    		{
    			if($request['rejet']=='hm')
    			{
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'alasan' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);

    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->approve = 'R';
	    			$up->approve_vp = 'R';
	    			$up->approve_svp = 'R';
	    			$up->alasan_reject = $request['alasan'];
	    			$up->update();
	    			$msg='Klaim Berhasil Direject.';
    			} else {
    				$this->validate($request, [
			            'nik' => 'required',
			            'status' => 'required',
			            'jsetuju' => 'required',
			            'nama' => 'required',
			            'diagnosa' => 'required',
			            'obat' => 'required',
			            'klaim' => 'required',
			            'jklaim' => 'required',

			        	]);
    				$up = HealthModel::where('ID',$request['id'])->first();
	    			$up->total_apprv = $request['jsetuju'];
	    			$up->approve = 'Y';
	    			$up->approve_vp = 'Y';
	    			$up->approve_svp = 'Y';
	    			$up->update();
	    			$msg='Klaim Berhasil Diapprove.';
    			}

    		} else
    		{
    			$tambah = new KesehatanModel();
		        $tambah->NIK = $request['nik'];
		        $tambah->status = $request['status'];
		        $tambah->jn_remb = 4;
		        $tambah->tglklaim = $request['klaim'];
		        $tambah->nama_apotek = $request['nama'];
		        $tambah->tglberobat = $request['obat'];
		        $tambah->diagnosa = $request['diagnosa'];
		        $tambah->total_klaim = $request['jklaim'];
		        $tambah->total_apprv = $request['jsetuju'];
		        $tambah->approve = 'Y';
		        $tambah->approve_vp = 'Y';
		        $tambah->approve_svp = 'Y';

		       $tes = KesehatanModel::orderBy('ID','DESC')->first();
		        $id =(int) $tes->ID;
		        $id +=1;
				$N=null;
		        $file = $request->file('gambar');
		        foreach ($file as $key => $files) {
		        	if($files != null) {
		        		$fileName = $files->getClientOriginalExtension();
		            	$fileName = $id.'_'.$key.'.'.$fileName;
		            	$N[$key]=$fileName;
		            	$files->move("image/Kesehatan",$fileName);
		        		}
		        	}
		        	if($N) {
		        		$nm = implode("|", $N);
	            		$tambah->kwitansi = $nm;
		        	}

		        $tambah->save();
		        $msg='Data Berhasil Ditambah.';
    		}

	        $data = HakKesehatanModel::where('NIK',$nik)->where('status',$status)
	                                    ->first();
	        $sisa = $data->sisa_melahirkan;
	        $hasil = $sisa - $request['jsetuju'];
	        $akhir=0;
	        $stat = array('P','I','A1','A2','A3','P','I','A1','A2','A3');
	        if($hasil<0) {
	        	for ($i=0; $i < 5; $i++) {
	        		$a=$i+1; $b=$i+2; $c=$i+3;
	        		$data->sisa_melahirkan = 0;
		        	$akhir = $setuju - $sisa;

		        	if($status==$stat[$i])
		        	{
		        		$data1 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+1])
		                                    ->first();
		                $sisa1 = $data1->sisa_melahirkan;
		                if($sisa1<$akhir)
		               	{
		               		$data1->sisa_melahirkan = 0;
		               		$data1->update();
		               		$akhir1 = $akhir - $sisa1;

		               		$data2 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+2])
		                                    ->first();
			                $sisa2 = $data2->sisa_melahirkan;
			                if($sisa2<$akhir1)
			               	{
			               		$data2->sisa_melahirkan = 0;
			               		$data2->update();
			               		$akhir2 = $akhir1 - $sisa2;

			               		$data3 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+3])
			                                    ->first();
				                $sisa3 = $data3->sisa_melahirkan;
				                if($sisa3<$akhir2)
				               	{
				               		$data3->sisa_melahirkan = 0;
				               		$data3->update();
				               		$akhir3 = $akhir2 - $sisa3;

				               		$data4 = HakKesehatanModel::where('NIK',$nik)->where('status',$stat[$i+4])
			                                    ->first();
					                $sisa4 = $data4->sisa_melahirkan;
					                if($sisa4<$akhir3)
					               	{
					               		$data4->sisa_melahirkan = 0;
					               		$data4->update();
					               		$akhir4 = $akhir3 - $sisa4;



					               	} else
					               	{
					               		$data4->sisa_melahirkan = $sisa4 - $akhir3;
					               		$data4->update();
					               	}

				               	} else
				               	{
				               		$data3->sisa_melahirkan = $sisa3 - $akhir2;
				               		$data3->update();
				               	}

			               	} else
			               	{
			               		$data2->sisa_melahirkan = $sisa2 - $akhir1;
			               		$data2->update();
			               	}

		               	}
		               	else
		               	{
		               		$data1->sisa_melahirkan = $sisa1 - $akhir;
		               		$data1->update();
		               	}

		        	}
		        	else {
		        		//return redirect()->back()->with('error','Error');
		        		continue;
		        	}
	        	}

	        } else {
	        	$data->sisa_melahirkan = $hasil;
	        }
	        $data->update();

	        return redirect('historykesehatan')->with('success','Data Berhasil Ditambah.');
    	} else
    	{
    		return redirect()->back()->with('error','Sisa Benefit/Benefit Keluarga Tidak Mencukupi');
    	}
    }
}
