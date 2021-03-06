<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JabatanModel;
use App\Models\PangkatModel;
use App\Models\DivisiModel;
use App\Models\SubDivisiModel;
use App\Models\GolonganModel;
use App\Models\GolonganOutModel;
use Illuminate\Support\Facades\DB;


class MasterController extends Controller
{

    // ---------- Start Pangkat ----------

    public function listGrade()
    {
        return view('master/grade/list');
    }

    public function dataGrade()
    {
        $data = DB::table('tb_pangkat AS A')
            ->select('A.id', 'A.pangkat')
            ->where('type', null)
            ->orderBy('pangkat', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function formGrade($id = NULL)
    {
        if ($id == NULL) {
            $data['formAction'] = 'master/grade';
            $data['grade']      = NULL;
        } else {
            $data['formAction'] = "master/grade/$id/update";
            $data['grade']      = PangkatModel::where('id', $id)->first();
        }

        return view('master/grade/form', $data);
    }

    public function createGrade(Request $request)
    {
        $this->validate($request, [
            'pangkat'   => 'required',
            'disabled'  => 'required'
        ]);

        $grade             = new PangkatModel();
        $grade->pangkat    = $request->pangkat;
        $grade->disabled   = $request->disabled;
        $grade->save();

        return redirect('master/grade');
    }

    public function updateGrade(Request $request, $id)
    {
        $grade              = PangkatModel::where('id', $id)->first();
        $grade->pangkat     = $request->pangkat;
        $grade->disabled    = $request->disabled;
        $grade->update();

        return redirect('master/grade');
    }

    public function deleteGrade($id)
    {
        $grade = PangkatModel::find($id);
        $grade->delete();

        return redirect('master/grade');
    }

    // ---------- End Pangkat ----------


    // ---------- Start Function ----------

    public function listFunction()
    {
        return view('master/function/list');
    }

    public function dataFunction()
    {
        $data = DB::table('tb_jabatan AS A')
            ->select('A.id', 'A.jabatan')
            ->where('type', null)
            ->orderBy('jabatan', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function formFunction($id = NULL)
    {
        if ($id == NULL) {
            $data['formAction'] = 'master/function';
            $data['function']   = NULL;
        } else {
            $data['formAction'] = "master/function/$id/update";
            $data['function']   = JabatanModel::where('id', $id)->first();
        }

        return view('master/function/form', $data);
    }

    public function createFunction(Request $request)
    {
        $this->validate($request, [
            'jabatan'   => 'required',
            'disabled'  => 'required'
        ]);

        $function            = new JabatanModel();
        $function->jabatan   = $request->jabatan;
        $function->disabled  = $request->disabled;
        $function->save();

        return redirect('master/function');
    }

    public function updateFunction(Request $request, $id)
    {
        $function            = JabatanModel::where('id', $id)->first();
        $function->jabatan   = $request->jabatan;
        $function->disabled  = $request->disabled;
        $function->update();

        return redirect('master/function');
    }

    public function deleteFunction($id)
    {
        $function = JabatanModel::find($id);
        $function->delete();

        return redirect('master/function');
    }

    // ---------- End Function ----------


    // ---------- Start Division ----------

    public function listDivision()
    {
        return view('master/division/list');
    }

    public function dataDivision()
    {
        $data = DB::table('tbldivmaster AS A')
            ->select('A.id', 'A.nama_div_ext AS divisi')
            ->where('type', null)
            ->orderBy('divisi', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function formDivision($id = NULL)
    {
        if ($id == NULL) {
            $data['formAction'] = 'master/division';
            $data['division']   = NULL;
        } else {
            $data['formAction'] = "master/division/$id/update";
            $data['division']   = DivisiModel::where('id', $id)->first();
        }

        return view('master/division/form', $data);
    }

    public function createDivision(Request $request)
    {
        $this->validate($request, [
            'divisi'    => 'required',
            'disabled'  => 'required'
        ]);

        $division               = new DivisiModel();
        $division->nama_div_ext = $request->divisi;
        $division->disabled     = $request->disabled;
        $division->save();

        return redirect('master/division');
    }

    public function updateDivision(Request $request, $id)
    {
        $division               = DivisiModel::where('id', $id)->first();
        $division->nama_div_ext = $request->divisi;
        $division->disabled     = $request->disabled;
        $division->update();

        return redirect('master/division');
    }

    public function deleteDivision($id)
    {
        $division = DivisiModel::find($id);
        $division->delete();

        return redirect('master/division');
    }

    // ---------- End Division ----------


    // ---------- Start Subdivision ----------

    public function listSubdivision()
    {
        return view('master/subdivision/list');
    }

    public function dataSubdivision()
    {
        $data = DB::table('tb_subdivisi AS A')
            ->select('A.id', 'A.subdivisi', 'B.nama_div_ext AS divisi')
            ->leftJoin('tbldivmaster AS B', 'B.id', '=', 'A.iddivisi')
            ->where('A.type', null)
            ->orderBy('subdivisi', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function formSubdivision($id = NULL)
    {
        if ($id == NULL) {
            $data['formAction']     = 'master/subdivision';
            $data['subdivision']    = NULL;
            $data['division']       = DivisiModel::All();
        } else {
            $data['formAction']     = "master/subdivision/$id/update";
            $data['subdivision']    = DB::table('tb_subdivisi AS A')
                ->select('A.id', 'A.subdivisi', 'A.type', 'A.disabled', 'B.nama_div_ext AS divisi', 'B.id AS divisiID')
                ->leftJoin('tbldivmaster AS B', 'B.id', '=', 'A.iddivisi')
                ->where('A.id', $id)
                ->orderBy('subdivisi', 'ASC')
                ->first();
            $data['division']       = DivisiModel::All();
        }

        return view('master/subdivision/form', $data);
    }

    public function createSubdivision(Request $request)
    {
        $this->validate($request, [
            'subdivisi' => 'required',
            'disabled'  => 'required'
        ]);

        $subdivision            = new SubDivisiModel();
        $subdivision->subdivisi = $request->subdivisi;
        $subdivision->disabled  = $request->disabled;
        $subdivision->iddivisi  = $request->iddivisi;
        $subdivision->save();

        return redirect('master/subdivision');
    }

    public function updateSubdivision(Request $request, $id)
    {
        $subdivision            = SubDivisiModel::where('id', $id)->first();
        $subdivision->subdivisi = $request->subdivisi;
        $subdivision->disabled  = $request->disabled;
        $subdivision->iddivisi  = $request->iddivisi;
        $subdivision->save();

        return redirect('master/subdivision');
    }

    public function deleteSubdivision($id)
    {
        $subdivision = SubDivisiModel::find($id);
        $subdivision->delete();

        return redirect('master/subdivision');
    }

    // ---------- End Division ----------

    
    // MASTER GOLONGAN //

    public function FUNC_MASTERGOLONGAN()
    {

        $golonganlist = GolonganModel::select('id', 'gol', 'disabled')
            ->where('type', null)
            ->orderBy('id', 'ASC')
            ->get();

        return view('master/golongan/golonganlist')->with('golonganlist', $golonganlist);
    }

    public function FUNC_ADDGOLONGAN()
    {

        return view('master/golongan/addgolongan');
    }

    public function FUNC_SAVEGOLONGAN(Request $request)
    {

        $this->validate($request, [
            'gol' => 'required',
            'disabled' => 'required'

        ]);

        $tambah = new GolonganModel();
        $tambah->gol = $request['gol'];
        $tambah->disabled = $request['disabled'];

        $tambah->save();

        return redirect('golonganlist')->with('success', 'Data Berhasil Disimpan');
    }

    public function FUNC_EDITGOLONGAN($id)
    {
        $tampiledit = GolonganModel::where('id', $id)->first();
        return view('master/golongan/editgolongan')->with('tampiledit', $tampiledit);
    }

    public function FUNC_UPDATEGOLONGAN(Request $request, $id)
    {

        $update = GolonganModel::where('id', $id)->first();
        $update->gol = $request['gol'];
        $update->disabled = $request['disabled'];

        $update->update();

        return redirect('golonganlist')->with('success', 'Data Berhasil Diupdate');
    }

    public function FUNC_DELETEGOLONGAN(Request $request, $id)
    {
        $hapus = GolonganModel::find($id);
        $hapus->delete();

        return redirect('golonganlist')->with('success', 'Data Berhasil Dihapus');
    }

    // MASTER GOLONGAN OUT //

    public function FUNC_MASTERGOLONGANOUT()
    {

        $golonganoutlist = GolonganOutModel::select('id', 'gol', 'disabled')
            ->where('type', null)
            ->orderBy('id', 'ASC')
            ->get();

        return view('master/golonganout/golonganoutlist')->with('golonganoutlist', $golonganoutlist);
    }

    public function FUNC_ADDGOLONGANOUT()
    {

        return view('master/golonganout/addgolonganout');
    }

    public function FUNC_SAVEGOLONGANOUT(Request $request)
    {

        $this->validate($request, [
            'gol' => 'required',
            'disabled' => 'required'

        ]);

        $tambah = new GolonganOutModel();
        $tambah->gol = $request['gol'];
        $tambah->disabled = $request['disabled'];

        $tambah->save();

        return redirect('golonganoutlist')->with('success', 'Data Berhasil Disimpan');
    }

    public function FUNC_EDITGOLONGANOUT($id)
    {
        $tampiledit = GolonganOutModel::where('id', $id)->first();
        return view('master/golonganout/editgolonganout')->with('tampiledit', $tampiledit);
    }

    public function FUNC_UPDATEGOLONGANOUT(Request $request, $id)
    {

        $update = GolonganOutModel::where('id', $id)->first();
        $update->gol = $request['gol'];
        $update->disabled = $request['disabled'];

        $update->update();

        return redirect('golonganoutlist')->with('success', 'Data Berhasil Diupdate');
    }

    public function FUNC_DELETEGOLONGANOUT(Request $request, $id)
    {
        $hapus = GolonganOutModel::find($id);
        $hapus->delete();

        return redirect('golonganoutlist')->with('success', 'Data Berhasil Dihapus');
    }
}
