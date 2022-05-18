<?php

namespace App\Http\Controllers;

use App\Models\KesehatanModel;
use App\Models\CutiModel;
use App\Models\AbsenIjinModel;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
	public function FUNC_SENDMAIL() {
		$stat = KesehatanModel::where('stat',0)->get();
		$status = CutiModel::where('status',0)->get();
		$ijin = AbsenIjinModel::where('statusApp',0)->get();
		if($stat) {
			foreach ($stat as $key => $stats) {
				$link = url('daftarreq');
				$data = array(
				'nama' => $stats->status,
		        'nik' => $stats->NIK,
		        'link' => $link,
			    );
			    Mail::queue('health.mail.Mail', $data, function ($message) {
		    	$file = asset('image/notfound.png');
		    	 $message->attach($file);
		        $message->from('dandy.firmansyah@edi-indonesia.co.id', 'riyan');

		        $message->to('dwi.riyantono@edi-indonesia.co.id')->subject('HRIS EDII Kesehatan');

		    	});
		    	$stats->stat = 1;
		    	$stats->update();
			}
		} elseif($status) {
			foreach ($status as $key => $statuss) {
				$link = url('approvecuti');
				$data = array(
				'nama' => $statuss->status,
		        'nik' => $statuss->NIK,
		        'link' => $link,
			    );
			    Mail::queue('cuti.emailcuti', $data, function ($message) {
		        $message->from('dandy.firmansyah@edi-indonesia.co.id', 'riyan');
		        $message->to('dwi.riyantono@edi-indonesia.co.id')->subject('HRIS EDII Cuti');

		    	});
		    	$statuss->status = 1;
		    	$statuss->update();
			}

		} elseif ($ijin) {
			foreach ($ijin as $key => $ijins) {
					$link = url('approveijin');
					$data = array(
					'nama' => $ijins->nama,
			        'nik' => $ijins->nik,
			        'link' => $link,
				    );
				    Mail::queue('ijin.emailijin', $data, function ($message) {
			        $message->from('dandy.firmansyah@edi-indonesia.co.id', 'riyan');
			        $message->to('dwi.riyantono@edi-indonesia.co.id')->subject('HRIS EDII IJIN');

			    	});
			}
		}
    }
}
