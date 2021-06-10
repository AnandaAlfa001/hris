@extends('layouts.index')
@section('content')
<script>
  
  
  function ceknik() {
  
  var nik = $('#nik').val();
  var token = '{{Session::token()}}';
  var ceknik = '{{ url('ceknik') }}';


  $.ajax({
    method: 'POST',
    url : ceknik,
    data : { nik:nik, _token : token },
  }).done(function (msg) {
    console.log(msg['data']);
    if(msg['data']==true) {
      $('#nikd').show();
      $('#nikc').addClass("has-error");
    } else {
      $('#nikd').hide();
      $('#nikc').removeClass("has-error");
    }
  });
}
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Employee Data
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">Add Employee Data</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="box-body">
      <div class="row">

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#datapribadi" data-toggle="tab">Data Pribadi</a></li></button>
              <li class="disabled"><a href="#picture" data-toggle="tab disabled">Picture</a></li>
              <li class="disabled"><a href="#datakeluarga" data-toggle="tab disabled">Data Keluarga</a></li>
              <li class="disabled"><a href="#dataripek" data-toggle="tab disabled">Data Riwayat Pekerjaan</a></li>
              <li class="disabled"><a href="#dataripen" data-toggle="tab disabled">Data Riwayat Pendidikan</a></li>
              <li class="disabled"><a href="#dataripennon" data-toggle="tab disabled">Data Riwayat Pendidikan Non-Formal</a></li>
              <li class="disabled"><a href="#datakegiatan" data-toggle="tab disabled">Data Kegiatan Organisasi</a></li>
              <li class="disabled"><a href="#orangterdekat" data-toggle="tab disabled">Orang Terdekat</a></li>
              <li class="disabled"><a href="#riwayatpenyakit" data-toggle="tab disabled">Riwayat Penyakit</a></li>
              <li class="disabled"><a href="#fasilitas" data-toggle="tab disabled">Fasilitas Karyawan</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="datapribadi">
                <h3 class="title">Data Pribadi Karyawan</h3>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{ url('saveemployee') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="input-group col-md-12">
                <div class="col-md-6">
                <h4>Data Pribadi</h4>
                  <div class="box-body">
                    <div id="nikc" class="form-group ">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </div>
                        <input type="text" id="nik" name="nik" class="form-control" placeholder="NIK" onchange="ceknik()" required >
                          
                      </div>
                      <!-- /.input group -->
                      <div id="nikd">
                          <span class="help-block">
                              <strong>NIK sudah digunakan</strong>
                          </span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </div>
                        <input type="text" id="nama" name="nama"  class="form-control" placeholder="Nama Lengkap" required>
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Tempat Lahir</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-home"></i>
                        </div>
                        <input type="text" id="tempatlahir" class="form-control" name="TempatLahir" placeholder="Tempat Lahir" required>
                      </div>
                      <!-- /.input group -->
                    </div>

                   

                    <small>Tanggal Lahir</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" name="tglLahir" placeholder="YYYY-MM-DD" class="form-control pull-right" id="tglLahir" required="true">
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>Golongan Darah</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-tint"></i>
                        </div>
                        <!-- <input type="text" id="golongandarah" class="form-control" name="GolonganDarah" placeholder="Golongan Darah" required> -->
                        <select class="form-control" name="GolonganDarah">
                          <option value="">-- Pilih Golongan Darah --</option>
                          <option value="A">A</option>
                          <option value="AB">AB</option>
                          <option value="B">B</option>
                          <option value="O">O</option>
                        </select>
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>Status (Pernikahan)</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-heart"></i>
                        </div>
                        <select class="form-control" name="status_pernikahan">
                          <option value="">-- Pilih Status Pernikahan --</option>
                          <option value="1">Menikah</option>
                          <option value="0">Belum Menikah</option>
                          <option value="2">Cerai</option>
                        </select>
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>Agama</small>
                    <div class="form-group">
                      <select class="form-control select2" name="agama" style="width: 100%;">
                        @foreach ($agama as $agamas)
                          <option value="{{ $agamas->idagama }}"> {{ $agamas->nama_agama }} </option>
                        @endforeach
                      </select>
                    </div>
                    <small>Jenis Kelamin</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="radio">
                        <label>
                          <input type="radio" name="jk" value="L" >
                          Laki Laki
                        </label>
                        </div>
                        <div class="radio">
                        <label>
                          <input type="radio" name="jk" value="P" >
                          Perempuan
                        </label>
                        </div>
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>Alamat Tinggal</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-home"></i>
                        </div>
                        <textarea class="form-control" rows="3" name="alamat"  placeholder="Alamat Tinggal" required></textarea>
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>No. KTP / SIM</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-credit-card"></i>
                        </div>
                        <input type="number" id="ktp" class="form-control" name="ktp" placeholder="No. KTP / SIM" required>
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>Alamat KTP / SIM</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-home"></i>
                        </div>
                        <textarea class="form-control" rows="3" name="alamat_ktp"  placeholder="Alamat KTP / SIM"></textarea>
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>Dokumen KTP/SIM</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-file"></i>
                        </div>
                        <input class="form-control" type="file" name="file_ktp" id="file_ktp">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>No. NPWP</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-credit-card"></i>
                        </div>
                        <input id="npwp" type="text" class="form-control" placeholder="NPWP" data-inputmask='"mask": "99.999.999.9-999.999"' data-mask>
                        <input id="npwp2" type="hidden" name="npwp" class="form-control" placeholder="NPWP">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Nama NPWP</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </div>
                        <input type="text" id="nama_npwp" class="form-control" name="nama_npwp" placeholder="Nama NPWP">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Alamat NPWP</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-home"></i>
                        </div>
                        <textarea type="text" id="alamat_npwp" class="form-control" name="alamat_npwp" placeholder="Alamat NPWP"></textarea>
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Dokumen NPWP</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-file"></i>
                        </div>
                        <input class="form-control" type="file" name="file_npwp" id="file_npwp">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>No. Rekening</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-money"></i>
                        </div>
                        <input type="number" id="norek" class="form-control" name="norek" placeholder="No. Rekening">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Dokumen Buku Tabungan (Halaman No. Rek) </small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-file"></i>
                        </div>
                        <input class="form-control" type="file" name="file_no_rek" id="file_no_rek">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>No. Jamsostek</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-credit-card"></i>
                        </div>
                        <input type="text" id="jamsostek" class="form-control" name="jamsostek" placeholder="Jamsostek">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Dokumen Jamsostek (BPJS Ketenagakerjaan) </small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-file"></i>
                        </div>
                        <input class="form-control" type="file" name="file_jamsostek" id="file_jamsostek">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>No. BPJS Kesehatan</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-credit-card"></i>
                        </div>
                        <input type="text" id="bpjs" class="form-control" name="bpjs" placeholder="BPJS">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Dokumen BPJS Kesehatan </small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-file"></i>
                        </div>
                        <input class="form-control" type="file" name="file_bpjs" id="file_bpjs">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>No. Telepon</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-phone"></i>
                        </div>
                        <input type="number" id="notelepon" class="form-control" name="notelepon" placeholder="Telephone">
                      </div>
                      <!-- /.input group -->
                    </div>

                    <small>No. Handphone</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-mobile-phone"></i>
                        </div>
                        <input type="number" id="NoHp" class="form-control" name="NoHp" placeholder="Handphone" required="true" >
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <!-- /.box-body -->
                  </div>
                  <div class="col-md-6">
                <h4>Data Karyawan</h4>
                
                  <div class="box-body">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-envelope"></i>
                        </div>
                        <input type="text" id="emailreg" name="emailreg" class="form-control" placeholder="Email Regular" required>
                      </div>
                      <!-- /.input group -->
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-envelope"></i>
                        </div>
                        <input type="text" id="email" name="email" class="form-control"  placeholder="Email EDII (Internal)" required>
                        <span class="input-group-addon">@edi-indonesia.co.id</span>
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Status Kepegawaian</small>
                    <div class="form-group">
                      <select id="status" class="form-control select2" name="status" style="width: 100%;">
                        <option value="">--Status Kepegawaian--</option>
                        @foreach ($statuskar as $statuskar)
                          <option value="{{ $statuskar->id }}"> {{ $statuskar->status_kar }} </option>
                          @endforeach
                      </select>
                    </div>
                    
                    <div id="vendor">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-user"></i>
                          </div>
                          <input type="text" class="form-control" name="Vendor" placeholder="Vendor" class="form-control" id="Vendor" value="" >
                        </div>
                        <!-- /.input group -->
                      </div>
                    </div>
                    <small>Tanggal Kontrak</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" name="tglKontrak" placeholder="YYYY-MMM-DDD" class="form-control pull-right" id="tglKontrak" required="true" >
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Tanggal Selesai Kontrak</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" name="tglKontrakEnd" placeholder="YYYY-MMM-DDD" class="form-control pull-right" id="tglKontrak" required="true" >
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Pangkat</small>
                    <div class="form-group">
                      <select id="pangkat" class="form-control select2" name="pangkat" style="width: 100%;">
                      <option value="">--Pilih Pangkat--</option>
                        @foreach ($pangkat as $pangkat)
                          <option value="{{ $pangkat->id }}"> {{ $pangkat->pangkat }} </option>
                          @endforeach
                      </select>
                    </div>
                    <small>Jabatan</small>
                    <div class="form-group">
                      <select id="jabatan" class="form-control select2" name="jabatan" style="width: 100%;">
                      <option value="">--Pilih Jabatan--</option>
                        @foreach ($jabatan as $jabatan)
                          <option value="{{ $jabatan->id }}"> {{ $jabatan->jabatan }} </option>
                          @endforeach
                      </select>
                    </div>
                    <small>Divisi</small>
                    <div class="form-group">
                      <select id="divisi" class="form-control select2" name="divisi" style="width: 100%;">
                        <option value="">--Pilih Divisi--</option>
                          @foreach ($divisi as $divisi)
                          <option value="{{ $divisi->id }}"> {{ $divisi->nama_div_ext }} </option>
                          @endforeach
                      </select>
                    </div>
                    <small>Sub Divisi</small>
                    <div class="form-group">
                      <select id="subdivisi" class="form-control select2" name="subdivisi" style="width: 100%;">
                          <option value="">--Pilih SubDivisi--</option>
                          @foreach ($subdivisi as $subdivisi)
                          <option value="{{ $subdivisi->id }}"> {{ $subdivisi->subdivisi }} </option>
                          @endforeach
                      </select>
                    </div>
                    <div id="gol">
                    <small>Golongan</small>
                      <div class="form-group">
                        <select class="form-control select2" name="Golongan" style="width: 100%;">
                            <option value="">--Pilih Golongan--</option>
                            @foreach ($golongan as $golongan)
                            <option value="{{ $golongan->id }}"> {{ $golongan->gol }} </option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div id="golout">
                      <small>Golongan Outsource</small>
                      <div class="form-group">
                        <select class="form-control select2" name="Golongan_out" style="width: 100%;">
                            <option value="">--Golongan Outsource--</option>
                            @foreach ($golonganout as $golonganout)
                            <option value="{{ $golonganout->id }}"> {{ $golonganout->gol }} </option>
                            @endforeach
                        </select>
                      </div>
                      <small>By Proyek ?</small>
                      <div class="form-group">
                        <select class="form-control select2" name="byproyek" id="byproyek" onchange="byproyekchange(this.value)" style="width: 100%;">
                            <option value="">--Pilih By Proyek--</option>
                            <option value="1">Proyek</option>
                            <option value="0">Non Proyek</option>
                        </select>
                      </div>
                    </div>
                    <div id="proyekinput">
                      <small>Proyek</small>
                      <div class="form-group">
                      <input type="text" id="proyek" class="form-control" name="proyek" placeholder="Proyek">  
                      </div>
                    </div>
                    <small>Tanggal Golongan</small>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control" name="TglGol" placeholder="YYYY-MMM-DDD" class="form-control pull-right" id="tglGolongan" required="true">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <small>Atasan 1 (Langsung)</small>
                    <div class="form-group">
                        <select class="form-control select2" name="atasan1" style="width: 100%;">
                            <option value="">--Pilih Atasan Langsung--</option>
                          @foreach ($atasan1 as $atasan1)
                            <option value="{{ $atasan1->nik }}"> {{ $atasan1->atasan }} </option>
                          @endforeach
                        </select>
                      </div>
                    <small>Atasan 2</small>
                    <div class="form-group">
                        <select class="form-control select2" name="atasan2" style="width: 100%;">
                            <option value="">--Pilih Atasan Tidak Langsung--</option>
                          @foreach ($atasan2 as $atasan2)
                            <option value="{{ $atasan2->nik }}"> {{ $atasan2->atasan }} </option>
                          @endforeach
                        </select>
                      </div>
                    <small>Lokasi Kerja</small>
                    <div class="form-group">
                      <select class="form-control select2" name="LokasiKer" style="width: 100%;">
                          <option value="">--Pilih Lokasi Kerja--</option>
                          @foreach ($lokker as $lokker)
                            <option value="{{ $lokker->id }}"> {{ $lokker->lokasi }} </option>
                          @endforeach
                        </select>
                    </div>
                    <small>Gaji</small>
                    <div class="form-group">
                      <input type="number" class="form-control" name="gaji" placeholder="Gaji">
                    </div>

                    <small>Tunjangan TMR</small>
                    <div class="form-group">
                      <input type="number" class="form-control" name="tunj_tmr" placeholder="Tunjangan TMR">
                    </div>

                    <small>Tunjangan Jabatan</small>
                    <div class="form-group">
                      <input type="number" class="form-control" name="tunj_jab" placeholder="Tunjangan Jabatan">
                    </div>
                  </div>
                  </div>
                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>
                  </div>
                </form>
                </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
</div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
@section('jq')
<script>
    $(document).ready(function () {
      $('#nikd').hide();
      $('#gol').show();
      $('#golout').hide();
      $('#vendor').hide();
      $("#Vendor").prop('required',false);
      $('#proyekinput').hide();

      $('#status').change(function(){
        if($(this).val() == '5' || $(this).val() == '6'){
          $('#vendor').show();
          $("#Vendor").prop('required',true);
          $('#golout').show();
          $('#gol').hide();
        }
        else{
          $('#vendor').hide();
          $("#Vendor").prop('required',false);
          $('#gol').show();
          $('#golout').hide();
          $('#proyekinput').hide();
        } 
      });

      $('#pangkat').change(function(){
        if($(this).val() == '2' || $(this).val() == '3'){
          $('#jabatan').prop('disabled',true);
          $('#divisi').prop('disabled',true);
          $('#subdivisi').prop('disabled',true);
        } else if($(this).val() == '6' || $(this).val() == '7'){
          $('#jabatan').prop('disabled',true);
          $('#divisi').prop('disabled',false);
          $('#subdivisi').prop('disabled',false);
        } else if($(this).val() == '5'){
          $('#jabatan').prop('disabled',true);
          $('#divisi').prop('disabled',false);
          $('#subdivisi').prop('disabled',true);
          $('#subdivisi').val() == '-';
        }
      });
    });

    function byproyekchange(value)
    {
    	if (value == 0) {
    		$('#proyekinput').hide();
    	}else{
    		$('#proyekinput').show();
    	}
    }
</script>
@endsection