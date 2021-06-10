<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\EmployeeModel;
use App\Models\LemburModel;
use App\Models\LembursModel;

class LemburController extends Controller
{
    public function FUNC_TAMBAHSPL() {

        $atasan = Session::get('nik');
        $admin = Session::get('admin');

        if ($admin == 1) {
            $tampilnama = EmployeeModel::select('NIK as nik','Nama',
                                        DB::raw('CONCAT(NIK , "-" , Nama) as uhuy')
                                        )
                                        ->where('resign','N')
                                        ->whereRaw('tb_datapribadi.idpangkat NOT IN(2,3,4,5,6,7)')
                                        ->get();
        } else {
            $tampilnama = EmployeeModel::select('NIK as nik','Nama',
                                        DB::raw('CONCAT(NIK , "-" , Nama) as uhuy')
                                        )
                                        ->where('resign','N')
                                        // ->where('idjabatan','<>','29')
                                        ->whereRaw('tb_datapribadi.idpangkat NOT IN(2,3,4,5,6,7)')
                                        ->where('tb_datapribadi.atasan1',$atasan)
                                        ->get();
        }

        return view('lembur/tambahspl')->with('tampilnama',$tampilnama);
    }

    public function FUNC_CEKTAMBAHSPL(Request $request) {

        $coba = $request['nik'];
        $atasan = Session::get('nik');

        $querydivjab = EmployeeModel::select('tb_datapribadi.TglTetap as mulai_bekerja','tbldivmaster.nama_div_ext as namadivisi','tb_jabatan.jabatan as namajabatan')
            ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
            ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
            ->where('tb_datapribadi.NIK',$coba)
            ->first();

        $mulai_bekerja = $querydivjab->mulai_bekerja;
        $divisi = $querydivjab->namadivisi;
        $jabatan = $querydivjab->namajabatan;


        return response()->json(['tes' => $coba,
                                 'mulai_bekerja' => $mulai_bekerja,
                                 'divisi' => $divisi,
                                 'jabatan' => $jabatan
                                 ],200);

    }

    public function FUNC_SAVETAMBAHSPL(Request $request) {

        // dd($request->all());

        $coba = substr($request['JamMulai'],6,2);
        $coba2 = substr($request['PerkiraanJamSelesai'],6,2);

        $jam = substr($request['JamMulai'],0,2);
        $jam2 = substr($request['PerkiraanJamSelesai'],0,2);

        $menit = substr($request['JamMulai'],3,2);
        $menit2 = substr($request['PerkiraanJamSelesai'],3,2);

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

        //BUAT CARI SELISIH JADI JAM//

        // list($h,$m) = explode(":", $jamfixx1);
        // $dtAwal = mktime($h,$m,"1","1");
        // list($h,$m) = explode(":", $jamfixx2);
        // $dtAkhir = mktime($h,$m,"1","1");
        // $dtSelisih = $dtAkhir-$dtAwal;

        // $hasiljam = $dtSelisih/3600;

        // dd($hasiljam);

        $tambah = new LemburModel();
        $tambah->NIK = $request['nik'];
        $tambah->NIKPemberiLembur = Session::get('nik');
        $tambah->TanggalMulaiLembur = $request['TanggalMulaiLembur'];
        $tambah->TanggalSelesaiLembur = $request['TanggalSelesaiLembur'];
        $tambah->JamMulai = $jamfixx1;
        $tambah->PerkiraanJamSelesai = $jamfixx2;
        $tambah->Kegiatan = $request['Kegiatan'];
        // $tambah->
        $tambah->status = 'M';
        $tambah->stat = '0';

        $tambah->save();

        return redirect()->back()->with('success','Perintah Lembur Berhasil Disimpan');
    }

    public function FUNC_HISTORYLEMBUR()
    {
        $nik = Session::get('nik');
        $tahun = "select DISTINCT year(TglKontrak) as tahun from tb_datapribadi where TglKontrak <> NULL or TglKontrak <> 0 order by TglKontrak";
        $tahuns = DB::select($tahun);
        $data = LemburModel::select('tb_lembur.ID','tb_lembur.NIK','tb_lembur.NIKPemberiLembur','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.JamMulai','tb_lembur.PerkiraanJamSelesai','tb_lembur.JamSelesaiAktual','tb_lembur.Kegiatan','tb_lembur.status','tb_datapribadi.Nama',
             DB::raw('CASE
                    WHEN tb_lembur.status = "M" THEN "Menunggu Persetujuan"
                    WHEN tb_lembur.status = "P" THEN "Proses Pengerjaan"
                    WHEN tb_lembur.status = "S" THEN "Selesai"
                    WHEN tb_lembur.status = "AU" THEN "Ajukan Ubah"
                    ELSE ""
                END as statuslembur'))
                    ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                    ->where('tb_lembur.NIK',$nik)
                    ->where('tb_lembur.status','!=',null)
                    ->get();

        return view('lembur.historylembur')->with('data',$data)
                                           ->with('tahun',$tahuns);
    }

    public function FUNC_DETAILLEMBUR(Request $request,$id)
    {
        $data = LemburModel::select('*','tb_datapribadi.Nama as nama','tb_jabatan.jabatan','tbldivmaster.nama_div_ext as divisi')
                    ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                    ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                    ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                    ->where('tb_lembur.ID',$id)->first();
                    // dd($data);
        // $pem = EmployeeModel::select('tb_datapribadi.atasan1')
        //                      ->leftjoin('tb_lembur','tb_lembur.NIK','=','tb_datapribadi.NIK')
        //                      ->where('tb_lembur.ID',$id)
        //                      ->first();
        $pemberi = EmployeeModel::where('NIK',$data->NIKPemberiLembur)->first();

        return view('lembur.detaillembur')->with('data',$data)
                                          ->with('pem',$pemberi);
    }

    //STAT LEMBUR DI DATABASE//

    // 0  = saat pertama lembur masuk dan email belum dikirim
    // 1  = saat pertama lembur masuk dan email sudah masuk ke karyawan

    // 2  = saat lemburan di approve karyawan dan email belum dikirim ke atasan
    // 3  = saat lemburan di approve karyawan dan email sudah dikirim ke atasan

    // 4  = saat lemburan diubah oleh karyawan dan email belum dikirim ke atasan
    // 5  = saat lemburan diubah oleh karyawan dan email sudah masuk ke atasan

    // 6  = saat pengajuan ubah karyawan di approve atasan belum dikirim email
    // 7  = saat pengajuan ubah karyawan di approve atasan dan sudah kirim email

    // 8  = saat pengajuan ubah karyawan di reject atasan belum dikirim email
    // 9  = saat pengajuan ubah karyawan di reject atasan sudah dikirim email

    // 10 = saat lemburan selesai dan belum kirim email ke atasan
    // 11 = saat lemburan selesai dan sudah kirim email ke atasan

    public function FUNC_SAVEAPPROVELEMBUR(Request $request,$id) {
        $approve = LembursModel::where('ID',$id)->first();
        $approve->status = 'P';
        $approve->stat = '2';

        $approve->update();

        return redirect('historylembur')->with('success','Lemburan Berhasil DiApprove');
    }

    public function FUNC_EDITLEMBUR(Request $request,$id) {

        $data = LemburModel::select('*','tb_datapribadi.Nama as nama','tb_jabatan.jabatan','tbldivmaster.nama_div_ext as divisi')
                    ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                    ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                    ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                    ->where('tb_lembur.ID',$id)->first();
        $pemberi = $pemberi = EmployeeModel::where('NIK',$data->NIKPemberiLembur)->first();

        return view('lembur/editlembur')->with('data',$data)->with('pemberi',$pemberi);

    }

    public function FUNC_SAVEEDITLEMBUR(Request $request,$id) {

        $coba = substr($request['JamMulaiBaru'],6,2);
        $coba2 = substr($request['PerkiraanJamSelesaiBaru'],6,2);

        $jam = substr($request['JamMulaiBaru'],0,2);
        $jam2 = substr($request['PerkiraanJamSelesaiBaru'],0,2);

        $menit = substr($request['JamMulaiBaru'],3,2);
        $menit2 = substr($request['PerkiraanJamSelesaiBaru'],3,2);

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

        $tambah = new LemburModel();

        $tambah->NIK = $request['nik'];
        $tambah->NIKPemberiLembur = $request['PemberiLembur'];
        $tambah->TanggalMulaiLembur = $request['tglmulai'];
        $tambah->TanggalSelesaiLembur = $request['tglselesai'];
        $tambah->JamMulai = $jamfixx1;
        $tambah->PerkiraanJamSelesai = $jamfixx2;
        $tambah->Kegiatan = $request['Kegiatan'];
        // $tambah->status = '';
        $tambah->stat = '4';
        $tambah->save();

       $awaw = LemburModel::orderBy('ID','DESC')->first();
       $idbro = (int)$awaw->ID;

        $ubah = LembursModel::where('ID',$id)->first();
        $ubah->id_ubah = $idbro;
        $ubah->status = 'AU';
        $ubah->update();


        return redirect('historylembur')->with('success','Lemburan Berhasil DiUbah Menunggu Persetujuan Atasan');

    }

    public function FUNC_SELESAILEMBUR(Request $request,$id) {

        $data = LemburModel::select('*','tb_datapribadi.Nama','tb_jabatan.jabatan','tbldivmaster.nama_div_ext as divisi')
                    ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                    ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                    ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                    ->where('tb_lembur.ID',$id)->first();
        $pemberi = $pemberi = EmployeeModel::where('NIK',$data->NIKPemberiLembur)->first();

        return view('lembur/selesailembur')->with('data',$data)->with('pemberi',$pemberi);
    }


    public function FUNC_SAVESELESAILEMBUR(Request $request,$id) {

        $coba = substr($request['JamAktualSelesai'],6,2);
        $jam = substr($request['JamAktualSelesai'],0,2);
        $menit = substr($request['JamAktualSelesai'],3,2);



        if ($coba == 'PM') {
            $jamfix = $jam + 12;
        } else {
            $jamfix = $jam;
        }

        $jamfixx2 = $jamfix.':'.$menit;
        $jamfixx1 = $request['jammulai'];

        // dd($jamfixx1);

        //BUAT CARI SELISIH JADI JAM//

        list($h,$m) = explode(":", $jamfixx1);
        $dtAwal = mktime($h,$m,"1","1");
        list($h,$m) = explode(":", $jamfixx2);
        $dtAkhir = mktime($h,$m,"1","1");
        $dtSelisih = $dtAkhir-$dtAwal;

        $hasiljam = $dtSelisih/3600;

        // dd($hasiljam);

        // dd($jamfixx1);

        $selesai = LembursModel::where('ID',$id)->first();
        $selesai->JamSelesaiAktual = $jamfixx2;
        $selesai->status = 'S';
        $selesai->stat = '10';
        $selesai->SelisihJamLembur = $hasiljam;
        $selesai->JumlahJamLembur = $hasiljam;
        $selesai->TotalSelisihJamLembur = $hasiljam;
        $selesai->Kegiatan = $request['Kegiatan'];

        $selesai->update();

        return redirect('historylembur')->with('success','Lemburan telah DiSelesaikan');

    }

    public function FUNC_LISTEDITLEMBUR() {

        $nik = Session::get('nik');
        $admin = Session::get('admin');
        if ($admin == 1) {
            $data = LemburModel::select('tb_lembur.ID','tb_lembur.NIK','tb_lembur.NIKPemberiLembur','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.JamMulai','tb_lembur.PerkiraanJamSelesai','tb_lembur.JamSelesaiAktual','tb_lembur.Kegiatan','tb_lembur.status','tb_datapribadi.Nama',
                 DB::raw('CASE
                        WHEN tb_lembur.status = "M" THEN "Menunggu Persetujuan"
                        WHEN tb_lembur.status = "P" THEN "Proses Pengerjaan"
                        WHEN tb_lembur.status = "S" THEN "Selesai"
                        WHEN tb_lembur.status = "AU" THEN "Ajukan Ubah"
                        ELSE ""
                    END as statuslembur'))
                        ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                        ->where('tb_lembur.status','AU')
                        ->get();
        }else{
            $data = LemburModel::select('tb_lembur.ID','tb_lembur.NIK','tb_lembur.NIKPemberiLembur','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.JamMulai','tb_lembur.PerkiraanJamSelesai','tb_lembur.JamSelesaiAktual','tb_lembur.Kegiatan','tb_lembur.status','tb_datapribadi.Nama',
                 DB::raw('CASE
                        WHEN tb_lembur.status = "M" THEN "Menunggu Persetujuan"
                        WHEN tb_lembur.status = "P" THEN "Proses Pengerjaan"
                        WHEN tb_lembur.status = "S" THEN "Selesai"
                        WHEN tb_lembur.status = "AU" THEN "Ajukan Ubah"
                        ELSE ""
                    END as statuslembur'))
                        ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                        ->where('tb_lembur.NIKPemberiLembur',$nik)
                        ->where('tb_lembur.status','AU')
                        ->get();
        }

        return view('lembur.listeditlembur')->with('data',$data);

    }


    public function FUNC_DETAILEDITLEMBUR($id) {

        $data = LemburModel::select('*','tb_datapribadi.Nama as nama','tb_jabatan.jabatan','tbldivmaster.nama_div_ext as divisi')
                    ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                    ->leftjoin('tb_jabatan','tb_datapribadi.idjabatan','=','tb_jabatan.id')
                    ->leftjoin('tbldivmaster','tb_datapribadi.Divisi','=','tbldivmaster.id')
                    ->where('tb_lembur.ID',$id)->first();
                    // dd($data);
        $id_ubah = $data->id_ubah;

        // dd($id_ubah);
        $databaru = LemburModel::where('ID',$id_ubah)->first();
        // $pem = EmployeeModel::select('tb_datapribadi.atasan1')
        //                      ->leftjoin('tb_lembur','tb_lembur.NIK','=','tb_datapribadi.NIK')
        //                      ->where('tb_lembur.ID',$id)
        //                      ->first();
        $pemberi = EmployeeModel::where('NIK',$data->NIKPemberiLembur)->first();

        return view('lembur.detaileditlembur')->with('data',$data)->with('pemberi',$pemberi)->with('databaru',$databaru);

    }

    public function FUNC_APPROVEUBAH(Request $request,$id) {

        $ubah = LembursModel::where('ID',$id)->first();
        $id_ubah = $ubah->id_ubah;
        $hapus = LembursModel::where('ID',$id_ubah)->first();
        $tglmulaibaru = $hapus->TanggalMulaiLembur;
        $tglselesaibaru = $hapus->TanggalSelesaiLembur;
        $jammulaibaru = $hapus->JamMulai;
        $jamselesaibaru = $hapus->PerkiraanJamSelesai;

        $ubah->TanggalMulaiLembur = $tglmulaibaru;
        $ubah->TanggalSelesaiLembur = $tglselesaibaru;
        $ubah->JamMulai = $jammulaibaru;
        $ubah->PerkiraanJamSelesai = $jamselesaibaru;
        $ubah->status = 'M';
        $ubah->stat = '6';

        $ubah->update();


        $hapus->delete();

        return redirect('listeditlembur')->with('success','Lemburan Sudah Disetujui');
    }

    public function FUNC_REJECTUBAH(Request $request,$id) {

        return view('lembur/alasanrejectlembur')->with('id',$id);

    }

    public function FUNC_SAVEREJECTUBAH(Request $request,$id) {

        // dd($id);

        $query = LembursModel::where('ID',$id)->first();
        $query->status = 'M';
        $query->stat = '8';
        $query->alasan_reject = $request['alasan_reject'];
        $query->update();

        $id_ubah = $query->id_ubah;

        $hapus = LembursModel::where('ID',$id_ubah)->first();
        $hapus->delete();

        return redirect('listeditlembur')->with('success','Lemburan Sudah DiReject');

    }

    public function FUNC_LISTLEMBURANSELESAI() {

        $nik = Session::get('nik');
        $admin = Session::get('admin');

        if ($admin == 1) {
            $data = LemburModel::select('tb_lembur.ID','tb_lembur.NIK','tb_lembur.NIKPemberiLembur','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.JamMulai','tb_lembur.PerkiraanJamSelesai','tb_lembur.JamSelesaiAktual','tb_lembur.Kegiatan','tb_lembur.status',
                DB::raw('(SELECT Nama FROM tb_datapribadi WHERE NIK = tb_lembur.NIK OR old_nik = tb_lembur.NIK) as Nama'),
                 DB::raw('CASE
                        WHEN tb_lembur.status = "M" THEN "Menunggu Persetujuan"
                        WHEN tb_lembur.status = "P" THEN "Proses Pengerjaan"
                        WHEN tb_lembur.status = "S" THEN "Selesai"
                        WHEN tb_lembur.status = "AU" THEN "Ajukan Ubah"
                        ELSE ""
                    END as statuslembur'))
                        ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                        // ->where('tb_lembur.NIKPemberiLembur',$nik)
                        ->where('tb_lembur.status','S')
                        ->get();
        }else{
            $data = LemburModel::select('tb_lembur.ID','tb_lembur.NIK','tb_lembur.NIKPemberiLembur','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.JamMulai','tb_lembur.PerkiraanJamSelesai','tb_lembur.JamSelesaiAktual','tb_lembur.Kegiatan','tb_lembur.status',
                DB::raw('(SELECT Nama FROM tb_datapribadi WHERE NIK = tb_lembur.NIK OR old_nik = tb_lembur.NIK) as Nama'),
                 DB::raw('CASE
                        WHEN tb_lembur.status = "M" THEN "Menunggu Persetujuan"
                        WHEN tb_lembur.status = "P" THEN "Proses Pengerjaan"
                        WHEN tb_lembur.status = "S" THEN "Selesai"
                        WHEN tb_lembur.status = "AU" THEN "Ajukan Ubah"
                        ELSE ""
                    END as statuslembur'))
                        ->leftjoin('tb_datapribadi','tb_datapribadi.NIK','=','tb_lembur.NIK')
                        ->where('tb_lembur.NIKPemberiLembur',$nik)
                        ->where('tb_lembur.status','S')
                        ->get();
        }

        return view('lembur.listlemburanselesai')->with('data',$data);

    }

}
