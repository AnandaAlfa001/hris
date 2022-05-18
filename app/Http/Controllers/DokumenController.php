<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use App\Models\DokumenModel;
use App\Models\JenisDokumenModel;

class DokumenController extends Controller
{
	public function FUNC_TAMBAHDOK()
	{
		// $data = JenisDokumenModel::All();
		$data = JenisDokumenModel::whereIn('id',[6,7,8])->get();
		return view('dokumen.dokumen')->with('data',$data);
	}

	public function FUNC_SAVEDOK(Request $request)
	{
       $tes = DokumenModel::orderBy('id','DESC')->first();

        $id = (int) $tes->id;
        $id +=1;
       	$nik = Session::get('nik');
        if($request->file('gambar') == '') {
           return redirect('listdok')->with('error','Data Gagal Ditambahkan.');
        } else {
        	$tambah = new DokumenModel();
        	$file = $request->file('gambar');
	        	if($file != null) {
	        		$fileName = $file->getClientOriginalExtension();
	            	$fileName = $id.'.'.$fileName;
	            	$file->move("image/Dokumen",$fileName);
	        		}

	        		$tambah->File=$fileName;

	            $tambah->NIK = $request['nik'];
	            $tambah->Jenis = $request['jenis'];
	            $tambah->save();
        }

        return redirect('listdok')->with('success','Dokumen Berhasil Ditambah');
	}

	public function FUNC_SAVEDOK_API(Request $request)
	{
       $tes = DokumenModel::orderBy('id','DESC')->first();

        $id = (int) $tes->id;
        $id +=1;
       	$nik = Session::get('nik');
        if($request->file('gambar') == '') {
           return response(["status" => 'Gagal'], 200);;
        } else {
        	$tambah = new DokumenModel();
        	$file = $request->file('gambar');
	        	if($file != null) {
	        		$fileName = $file->getClientOriginalExtension();
	            	$fileName = $id.'.'.$fileName;
	            	$file->move("image/Dokumen",$fileName);
	        		}

	        		$tambah->File=$fileName;

	            $tambah->NIK = $request['nik'];
	            $tambah->Jenis = $request['jenis'];
	            $tambah->save();
        }

        return response(["status" => 'Success'], 200);
	}

	public function FUNC_LISTDOK()
	{
		$nik = Session::get('nik');
		$data = DokumenModel::select('tb_dokumen.*','tb_datapribadi.Nama','tb_jenis_dokumen.dokumen as dok')
								->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_dokumen.NIK')
								->leftjoin('tb_jenis_dokumen','tb_jenis_dokumen.id','=','tb_dokumen.Jenis')
								->where('tb_dokumen.NIK',$nik)
								->get();
		return view('dokumen.listdokumen')->with('data',$data);
	}

	public function FUNC_DOWNDOK(Request $request, $id)
	{
		$dok = DokumenModel::where('id',$id)->first();
		$a= public_path();
        $pdf = $dok->File;
        $dat = substr($pdf,-3);
        if($dat=="pdf") {
          $type = "pdf";
        } else {
          $type = "img";
        }
        if($type=="pdf")
        {
        	$file = $a.'/image/Dokumen/'.$dok->File;
        	return response()->download($file);

        } elseif ($type=="img") {
	    		$file = $a.'/image/Dokumen/'.$dok->File;
	    		return response()->download($file);
	    }
    }

	public function FUNC_PREVIEWPDF(Request $request,$id) {
		$a= public_path();
		$dok = DokumenModel::where('id',$id)->first();
		$file = $a.'/image/Dokumen/'.$dok->File;
		return Response::make(file_get_contents($file), 200, [
		    'Content-Type' => 'application/pdf'
		]);
	}

	public function FUNC_EDITDOK(Request $request,$id)
	{
		$data = DokumenModel::where('id',$id)->first();
		// $jenis = JenisDokumenModel::All();
		$jenis = JenisDokumenModel::whereIn('id',[6,7,8])->get();
		return view('dokumen.editdokumen')->with('data',$data)->with('jenis',$jenis);
	}

	public function FUNC_UPDATEDOK(Request $request,$id)
	{
		$update = DokumenModel::where('id',$id)->first();
		$update->Jenis = $request['jenis'];
		if($request->file('gambar') == '') {
            $update->File = $update->File;
        } else {
            $file = $request->file('gambar');
            $fileName = $file->getClientOriginalExtension();
            $fileName = $id.'.'.$fileName;
            $request->file('gambar')->move("image/Dokumen",$fileName);
            $update->File = $fileName;
        }
        $update->update();
        return redirect('listdok')->with('success','Dokumen Berhasil Diedit');
	}

	public function FUNC_PREVIEWIMG(Request $request) {
    	$data = $request['data'];
    	$cek = DokumenModel::where('id',$data)->first();

    	$bukti = $cek->File;
    	$count = explode("|", $bukti);
    	$out = count($count);

    	return response()->json(['out' => $count],200);
    }

    public function FUNC_DELETEDOK(Request $request,$id)
	{
		$delete = DokumenModel::find($id);
        $delete->delete();

        return redirect('listdok')->with('success','Dokumen Berhasil Didelete');
	}
}
