@extends('layouts.index')
@section('content')
<!-- <script>
   function ubah() {
  $('#ubah').val('valubah');
  $('#submit').click();
 }
 function selesai() {
  $('#selesai').val('valselesai');
  $('#submit').click();
 }
</script> -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail Data Perintah Lembur
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Lembur</a></li>
        <li class="active">Detail Perintah</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detail Lembur</h3>
            </div>

        <form role="form" method="POST" action="">
          {{ csrf_field() }}
            <div class="box-body">
              <div id="alertz">
                @if (count($errors)>0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                   Mohon data dilengkapi
                </div>
                @endif
              </div>
              <div id="alertz2">
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                   {{ session('error') }}
                </div>
                @endif
              </div>
              <div class="input-group col-md-12">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control" name="nik" value="{{ $data->NIK}}" placeholder="NIK" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{ $data->nama}}" placeholder="Nama" readonly required>
                    </div>
                    <input type="hidden" name="id" value="{{$data->ID}}">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" placeholder="Jabatan" value="{{ $data->jabatan}}" readonly >
                    </div>

                    <div class="form-group">
                        <label>Divisi</label>
                        <input type="text" class="form-control" placeholder="divisi" value="{{$data->divisi}}" readonly >
                    </div>
                    <div class="form-group">
                        <label>Pemberi Lembur</label>
                        <input type="text" class="form-control" placeholder="Pemberi Lembur" value="{{$pem->Nama}}" readonly >
                    </div>
                    <label>Tgl. Mulai Lembur</label>
                    <div class="form-group">
                        <input type="date" class="form-control" name="tglmulai" placeholder="YYYY-MMM-DDD" value="{{$data->TanggalMulaiLembur}}" readonly > 
                    </div>
                    <label>Tgl. Selesai Lembur</label>
                    <div class="form-group">    
                        <input type="date"  class="form-control" name="tglselesai" placeholder="YYYY-MMM-DDD" value="{{$data->TanggalSelesaiLembur}}" readonly required> 
                    </div>
                    
                    </div>
                    <div class="col-md-6">
                     <label>Jam Mulai</label>
                    <div class="form-group">    
                        <input type="text"  class="form-control" name="jammulai" value="{{$data->JamMulai}}" readonly required> 
                    </div>
                     <label>Perkiraan Jam Selesai</label>
                    <div class="form-group">    
                        <input type="text"  class="form-control" name="jamselesai" value="{{$data->PerkiraanJamSelesai}}" readonly required> 
                    </div>
                    <label>Jam Selesai Aktual</label>
                    <div class="form-group">    
                        <input type="text"  class="form-control" name="jamselesaiak" value="{{$data->JamSelesaiAktual}}" readonly required> 
                    </div>
                    <label>Kegiatan</label>
                    <div class="form-group">    
                        <textarea class="form-control" name="jamselesai" readonly required>{{$data->Kegiatan}}</textarea>
                    </div>
                    <br><br>
                    <label>Alasan Reject</label>
                    <div class="form-group">    
                        <textarea class="form-control" name="alasan_reject" readonly required>{{$data->alasan_reject}}</textarea>
                    </div>
                    <br><br><br>
                    @if($data->status == "S")
                    <label>Status</label>
                    <label class="label bg-red">SELESAI</label>
                    <br><br>
                    @endif
                    <div class="box-footer">
                    @if($data->status == "M")  
                    <a href="{{ url('saveapprovelembur',$data->ID) }}" onclick="return confirm('Yakin Approve Lembur ini ?')">
                        <span class="btn btn-success">Approve</span>
                    </a>
                    <a href="{{ url('editlembur',$data->ID) }}" onclick="return confirm('Yakin mau Ubah data Lembur ini ?')">
                        <span class="btn btn-info">Ubah</span>
                    </a>
                    @endif
                    
                    @if($data->status == 'P')
                    <a href="{{ url('selesailembur',$data->ID) }}" onclick="return confirm('Yakin Lemburan ini Sudah Selesai ?')">
                        <span class="btn btn-danger">Selesai</span>
                    </a>
                    @endif
                    </div>
                    
                  </div>
                </div>
          <span><br><br></span>
                </div>
                </form>

                

              </div>
          </div>

        </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection