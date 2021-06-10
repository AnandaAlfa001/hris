<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Storage;
use Session;
use App\Models\TrainingModel;
use App\Models\KaryawanModel;
use App\Models\EmployeeModel;
use Response;

class TrainingController extends Controller
{
	public function FUNC_HISTORYTRAIN()
	{
		$nik = Session::get('nik');
		if(Session::get('admin')==1) {
			$his = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan','tb_training.photo as phototrain','jenis_penyedia')
						->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
						->orderby('tb_training.ID','DESC')
						->get();
		} else {
			$his = TrainingModel::select('tb_training.ID','tb_datapribadi.Nama','tb_training.NIK as NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan','tb_training.photo as phototrain','jenis_penyedia')
						->join('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
						->where('tb_training.NIK','=',$nik)
						->orderby('tb_training.ID','DESC')
						->get();
		}

		return view('training.HistoryTrain')->with('his',$his);
	}

	public function FUNC_EDITTRAIN($ID)
	{
		$his = TrainingModel::select('tb_training.ID','tb_training.jenis_penyedia','tb_datapribadi.TglTetap','tb_jabatan.jabatan','tbldivmaster.nama_div_ext as divisi','tb_datapribadi.Nama','tb_training.NIK','tgl_mulai','tgl_akhir','penyedia','Nama_Pelatihan','tb_training.photo as phototrain')
						->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_training.NIK')
						->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
						->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
						->where('tb_training.ID',$ID)
						->first();

		return view('training.EditTrain')->with('his',$his);
	}

	public function FUNC_UPDATETRAIN(Request $request, $ID)
	{
		$this->validate($request, [
            'tgl_mulai' => 'required',
            'tgl_akhir' => 'required',
            'jenis_penyedia' => 'required',
            'penyedia' => 'required',
            'Nama_Pelatihan' => 'required',
        	]);
		$update = TrainingModel::where('ID',$ID)->first();
		$update->tgl_mulai = $request['tgl_mulai'];
		$update->tgl_akhir = $request['tgl_akhir'];
		$update->jenis_penyedia = $request['jenis_penyedia'];
		$update->penyedia = $request['penyedia'];
		$update->Nama_Pelatihan = $request['Nama_Pelatihan'];
		if($request->file('gambar') == '') {
            $update->photo = $update->photo;
        } else {

        	Storage::delete("image/Sertifikat",$update->photo);
            $file = $request->file('gambar');
            $fileName = $file->getClientOriginalExtension();
            $fileName = $update->ID.$update->NIK.'.'.$fileName;
            $request->file('gambar')->move("image/Sertifikat",$fileName);
            $update->photo = $fileName;
        }
		$update->update();

		return redirect(url('edittrain',[$ID]))->with('success','Data Berhasil Diedit.');
	}

	public function FUNC_ADDTRAIN()
	{
		 $data = KaryawanModel::select('NIK as nik','Nama',
                                    DB::raw('CONCAT(NIK , "-" , Nama) as nama')
                                    )
                                    ->where('resign','N')
                                    ->get();

		return view('training.AddTrain')->with('data',$data);
	}

	public function FUNC_SAVETRAIN(Request $request)
	{
		$this->validate($request, [
            'tgl_mulai' => 'required',
            'tgl_akhir' => 'required',
            'jenis_penyedia' => 'required',
            'penyedia' => 'required',
            'Nama_Pelatihan' => 'required',
        	]);

		$tes = TrainingModel::orderBy('ID','DESC')->first();

		$his = new TrainingModel();

        $id = (int) $tes->ID;
        $id +=1;

        if($request->file('gambar') == '') {
           // return redirect('listdok')->with('error','Data Gagal Ditambahkan.');
        	$his->photo='';
        } else {
        	$file = $request->file('gambar');
        	if($file != null) {
        		$fileName = $file->getClientOriginalExtension();
            	$fileName = $id.$request['NIK'].'.'.$fileName;
            	$file->move("image/Sertifikat",$fileName);
        	}

	        $his->photo=$fileName;
        }


		$his->NIK = $request['NIK'];
		$his->tgl_mulai = $request['tgl_mulai'];
		$his->tgl_akhir = $request['tgl_akhir'];
		$his->jenis_penyedia = $request['jenis_penyedia'];
		$his->penyedia = $request['penyedia'];
		$his->Nama_Pelatihan = $request['Nama_Pelatihan'];

		$his->save();

		return redirect('historytrain')->with('success','Data Berhasil Ditambah.');
	}



	public function FUNC_DELETETRAIN($ID)
	{
		$delete = TrainingModel::find($ID);
        $delete->delete();

        return redirect()->back()->with('success','Data Berhasil Didelete.');
	}

	public function FUNC_CEKADDTRAIN(Request $request)
	{
		$nik = $request['nik'];
		$data = EmployeeModel::select('tb_datapribadi.TglTetap','tb_jabatan.jabatan','tbldivmaster.nama_div_ext as divisi','tb_datapribadi.Nama')
						->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
						->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
						->where('tb_datapribadi.NIK',$nik)
						->first();

		$nama = $data->Nama;
		$mulai = $data->TglTetap;
		$jabatan = $data->jabatan;
		$divisi = $data->divisi;

		return response()->json(['nama' => $nama,
        						 'mulai' => $mulai,
        						 'jabatan' => $jabatan,
        						 'divisi' => $divisi ],200);
	}

	public function FUNC_DOWNDOK(Request $request, $id)
	{
		$dok = TrainingModel::where('ID',$id)->first();

		if ($dok->photo != NULL or $dok->photo != '') {
			$a= public_path();
	        $pdf = $dok->photo;
	        $dat = substr($pdf,-3);
	        if($dat=="pdf") {
	          $type = "pdf";
	        } else {
	          $type = "img";
	        }
	        if($type=="pdf")
	        {
	        	$file = $a.'/image/Sertifikat/'.$dok->photo;
	        	return response()->download($file);

	        } elseif ($type=="img") {
		    		$file = $a.'/image/Sertifikat/'.$dok->photo;
		    		return response()->download($file);
		    }
		} else {
			return redirect('historytrain')->with('error','Data Tidak Tersedia.');;
		}
    }

	public function FUNC_PREVIEWPDF(Request $request,$id) {
		$a= public_path();
		$dok = TrainingModel::where('ID',$id)->first();
		$file = $a.'/image/Sertifikat/'.$dok->photo;
		return Response::make(file_get_contents($file), 200, [
		    'Content-Type' => 'application/pdf'
		]);
	}

	public function FUNC_PREVIEWIMG(Request $request) {
    	$data = $request['data'];
    	$cek = TrainingModel::where('ID',$data)->first();

    	$bukti = $cek->photo;
    	$count = explode("|", $bukti);
    	$out = count($count);

    	return response()->json(['out' => $count],200);
    }

}
