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
        Detail Data Ubah Lembur
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Lembur</a></li>
        <li class="active">Detail Data Ubah Lembur</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detail Data Ubah Lembur</h3>
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
                    </div><br><br>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{ $data->nama}}" placeholder="Nama" readonly required>
                    </div><br><br>
                    <input type="hidden" name="id" value="{{$data->ID}}">
                    
                    <div class="form-group">
                        <label>Pemberi Lembur</label>
                        <input type="text" class="form-control" placeholder="Pemberi Lembur" value="{{$pemberi->Nama}}" readonly >
                    </div><br><br>              
                    
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" placeholder="Jabatan" value="{{ $data->jabatan}}" readonly >
                    </div><br><br>

                    <div class="form-group">
                        <label>Divisi</label>
                        <input type="text" class="form-control" placeholder="divisi" value="{{$data->divisi}}" readonly >
                    </div><br><br>

                    <div class="form-group"> 
                        <label>Kegiatan</label>   
                        <textarea class="form-control" name="jamselesai" readonly required>{{$data->Kegiatan}}</textarea>
                    </div><br><br><br>                     
                    
                  </div>             



                  <div class="col-md-6">                  
                    <label>Tgl. Mulai Lembur</label>
                    <div class="form-group">
                        <input type="date" class="form-control" name="tglmulai" placeholder="YYYY-MMM-DDD" value="{{$data->TanggalMulaiLembur}}" readonly > 
                    </div><br><br>
                    
                    <label>Tgl. Selesai Lembur</label>
                    <div class="form-group">    
                        <input type="date"  class="form-control" name="tglselesai" placeholder="YYYY-MMM-DDD" value="{{$data->TanggalSelesaiLembur}}" readonly required> 
                    </div><br><br>
                    <div class="form-group">  
                        <label>Jam Mulai</label>  
                        <input type="text"  class="form-control" name="jammulai" value="{{$data->JamMulai}}" readonly required> 
                    </div><br><br>

                    <div class="form-group">    
                        <label>Perkiraan Jam Selesai</label>
                        <input type="text"  class="form-control" name="jamselesai" value="{{$data->PerkiraanJamSelesai}}" readonly required>
                    </div><br><br>
                    
                    
                    </div>
                    <div class="col-md-6"> 
                    <label>Tgl. Mulai Lembur Yang Diajukan</label>
                    <div class="form-group">
                        <input type="date" class="form-control" name="tglmulaibaru" placeholder="YYYY-MMM-DDD" value="{{$databaru->TanggalMulaiLembur}}" readonly > 
                    </div><br><br>   

                    <label>Tgl. Selesai Lembur Yang Diajukan</label>
                    <div class="form-group">    
                        <input type="date"  class="form-control" name="tglselesaibaru" placeholder="YYYY-MMM-DDD" value="{{$databaru->TanggalSelesaiLembur}}" readonly required> 
                    </div><br><br>
                                
                    <div class="form-group"> 
                        <label>Jam Mulai Yang Diajukan</label>   
                        <input type="text"  class="form-control" name="jammulaibaru" value="{{$databaru->JamMulai}}" readonly required> 
                    </div><br><br>
                    
                    
                    
                    <div class="form-group"> 
                        <label>Perkiraan Jam Selesai Yang Diajukan</label>   
                        <input type="text"  class="form-control" name="jamselesaibaru" value="{{$databaru->PerkiraanJamSelesai}}" readonly required>
                    </div><br><br>
              
                    
                  </div>

                </div>


                <div class="box-footer">

                    <a href="{{ url('approveubah',$data->ID) }}" onclick="return confirm('Yakin Approve Perubahan Lembur ini ?')">
                        <span class="btn btn-success">Approve</span>
                    </a>
                    <a href="{{ url('rejectubah',$data->ID) }}" onclick="return confirm('Yakin Reject Perubahan Lembur ini ?')">
                        <span class="btn btn-danger">Reject</span>
                    </a>
                                  
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