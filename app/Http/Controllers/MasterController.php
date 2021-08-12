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

    public function FUNC_MASTERJABATAN()
    {

        $jabatanlist = JabatanModel::select('id', 'jabatan', 'disabled')
            ->where('type', null)
            ->orderBy('id', 'ASC')
            ->get();

        return view('master/jabatan/jabatanlist')->with('jabatanlist', $jabatanlist);
    }

    public function FUNC_ADDJABATAN()
    {

        return view('master/jabatan/addjabatan');
    }

    public function FUNC_SAVEJABATAN(Request $request)
    {

        $this->validate($request, [
            'jabatan' => 'required',
            'disabled' => 'required'

        ]);

        $tambah = new JabatanModel();
        $tambah->jabatan = $request['jabatan'];
        $tambah->disabled = $request['disabled'];

        $tambah->save();

        return redirect('jabatanlist')->with('success', 'Data Berhasil Disimpan');
    }

    public function FUNC_EDITJABATAN($id)
    {
        $tampiledit = JabatanModel::where('id', $id)->first();
        return view('master/jabatan/editjabatan')->with('tampiledit', $tampiledit);
    }

    public function FUNC_UPDATEJABATAN(Request $request, $id)
    {

        $update = JabatanModel::where('id', $id)->first();
        $update->jabatan = $request['jabatan'];
        $update->disabled = $request['disabled'];

        $update->update();

        return redirect('jabatanlist')->with('success', 'Data Berhasil Diupdate');
    }

    public function FUNC_DELETEJABATAN(Request $request, $id)
    {
        $hapus = JabatanModel::find($id);
        $hapus->delete();

        return redirect('jabatanlist')->with('success', 'Data Berhasil Dihapus');
    }


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

    public function newGrade()
    {
        return view('master/grade/new');
    }

    public function FUNC_SAVEPANGKAT(Request $request)
    {

        $this->validate($request, [
            'pangkat' => 'required',
            'disabled' => 'required'

        ]);

        $tambah = new PangkatModel();
        $tambah->pangkat = $request['pangkat'];
        $tambah->disabled = $request['disabled'];

        $tambah->save();

        return redirect('pangkatlist')->with('success', 'Data Berhasil Disimpan');
    }

    public function FUNC_EDITPANGKAT($id)
    {
        $tampiledit = PangkatModel::where('id', $id)->first();
        return view('master/pangkat/editpangkat')->with('tampiledit', $tampiledit);
    }

    public function FUNC_UPDATEPANGKAT(Request $request, $id)
    {

        $update = PangkatModel::where('id', $id)->first();
        $update->pangkat = $request['pangkat'];
        $update->disabled = $request['disabled'];

        $update->update();

        return redirect('pangkatlist')->with('success', 'Data Berhasil Diupdate');
    }

    public function FUNC_DELETEPANGKAT(Request $request, $id)
    {
        $hapus = PangkatModel::find($id);
        $hapus->delete();

        return redirect('pangkatlist')->with('success', 'Data Berhasil Dihapus');
    }

    // ---------- End Pangkat ----------


    // MASTER DIVISI //

    public function FUNC_MASTERDIVISI()
    {

        $divisilist = DivisiModel::select('id', 'nama_div_ext', 'disabled')
            ->where('type', null)
            ->orderBy('id', 'ASC')
            ->get();

        return view('master/divisi/divisilist')->with('divisilist', $divisilist);
    }

    public function FUNC_ADDDIVISI()
    {

        return view('master/divisi/adddivisi');
    }

    public function FUNC_SAVEDIVISI(Request $request)
    {

        $this->validate($request, [
            'nama_div_ext' => 'required',
            'disabled' => 'required'

        ]);

        $tambah = new DivisiModel();
        $tambah->nama_div_ext = $request['nama_div_ext'];
        $tambah->disabled = $request['disabled'];

        $tambah->save();

        return redirect('divisilist')->with('success', 'Data Berhasil Disimpan');
    }

    public function FUNC_EDITDIVISI($id)
    {
        $tampiledit = DivisiModel::where('id', $id)->first();
        return view('master/divisi/editdivisi')->with('tampiledit', $tampiledit);
    }

    public function FUNC_UPDATEDIVISI(Request $request, $id)
    {

        $update = DivisiModel::where('id', $id)->first();
        $update->nama_div_ext = $request['nama_div_ext'];
        $update->disabled = $request['disabled'];

        $update->update();

        return redirect('divisilist')->with('success', 'Data Berhasil Diupdate');
    }

    public function FUNC_DELETEDIVISI(Request $request, $id)
    {
        $hapus = DivisiModel::find($id);
        $hapus->delete();

        return redirect('divisilist')->with('success', 'Data Berhasil Dihapus');
    }

    // MASTER SUBDIVISI //

    public function FUNC_MASTERSUBDIVISI()
    {

        $subdivisilist = SubDivisiModel::select(
            'tb_subdivisi.id',
            'tb_subdivisi.subdivisi',
            'tbldivmaster.nama_div_ext as nama_divisi',
            'tb_subdivisi.disabled'
        )
            ->leftjoin('tbldivmaster', 'tb_subdivisi.iddivisi', '=', 'tbldivmaster.id')
            ->where('tb_subdivisi.type', null)
            ->orderBy('id', 'ASC')
            ->get();

        // $subdivisilist = DB::table('tb_subdivisi')
        //                 ->join('tbldivmaster', 'tb_subdivisi.iddivisi','=','tbldivmaster.id')
        //                 ->select('tb_subdivisi.id','tb_subdivisi.subdivisi','tb_subdivisi.type','tbldivmaster.nama_div_ext as nama_divisi')
        //                 ->get();

        return view('master/subdivisi/subdivisilist')->with('subdivisilist', $subdivisilist);
    }

    public function FUNC_ADDSUBDIVISI()
    {

        $divisi = DivisiModel::where('type', null)->orderBy('id', 'ASC')->paginate(100000000000);

        return view('master/subdivisi/addsubdivisi')->with('divisi', $divisi);
    }

    public function FUNC_SAVESUBDIVISI(Request $request)
    {

        $this->validate($request, [
            'subdivisi' => 'required',
            'disabled' => 'required'

        ]);

        $tambah = new SubDivisiModel();
        $tambah->subdivisi = $request['subdivisi'];
        $tambah->disabled = $request['disabled'];
        $tambah->iddivisi = $request['iddivisi'];

        $tambah->save();

        return redirect('subdivisilist')->with('success', 'Data Berhasil Disimpan');
    }

    public function FUNC_EDITSUBDIVISI($id)
    {
        // $tampiledit = SubDivisiModel::where('id',$id)->first();

        $tampiledit = SubDivisiModel::select(
            'tb_subdivisi.id',
            'tb_subdivisi.subdivisi',
            'tb_subdivisi.type',
            'tb_subdivisi.disabled',
            'tbldivmaster.nama_div_ext as nama_divisi',
            'tbldivmaster.id as divisiid'
        )
            ->orderBy('id', 'ASC')
            ->leftjoin('tbldivmaster', 'tb_subdivisi.iddivisi', '=', 'tbldivmaster.id')
            ->where('tb_subdivisi.id', $id)
            ->first();

        $divisi = DivisiModel::All();
        return view('master/subdivisi/editsubdivisi')->with('tampiledit', $tampiledit)->with('divisi', $divisi);
    }

    public function FUNC_UPDATESUBDIVISI(Request $request, $id)
    {

        $update = SubDivisiModel::where('id', $id)->first();
        $update->subdivisi = $request['subdivisi'];
        $update->disabled = $request['disabled'];
        $update->iddivisi = $request['iddivisi'];

        $update->update();

        return redirect('subdivisilist')->with('success', 'Data Berhasil Diupdate');
    }

    public function FUNC_DELETESUBDIVISI(Request $request, $id)
    {
        $hapus = SubDivisiModel::find($id);
        $hapus->delete();

        return redirect('subdivisilist')->with('success', 'Data Berhasil Dihapus');
    }

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
