@extends('layouts.index')
@section('content')
<script>
  
  function getdata() {
  // var nik = document.
  var nik = $('#niki').val();
  var token = '{{Session::token()}}';
  var url = '{{ url('cekaddtrain') }}';


  $.ajax({
    method: 'POST',
    url : url,
    data : { nik:nik, _token : token },
  }).done(function (msg) {
    console.log(msg['nama'],msg['mulai'],msg['jabatan'],msg['divisi'],nik);
    $('#nama').val(msg['nama']);
    $('#mulai').val(msg['mulai']);
    $('#jabatan').val(msg['jabatan']);
    $('#divisi').val(msg['divisi']);
    $('#nik').val(nik);

  });
}

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tambah Training
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Training</a></li>
        <li class="active">Tambah Training</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">

            <div class="box-header with-border">
              <h3 class="box-title">Tambah Training</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" enctype="multipart/form-data" action="{{ url('savetrain') }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
              <div id="alertz2">
                @if (count($errors)>0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                   Mohon data dilengkapi
                </div>
                @endif
              </div>
              <div class="col-md-12">
              <div class="col-md-6">
                <div class="form-group">
                  <label>NIK</label>
                  <input type="text" class="form-control" id="nik" name="NIK" placeholder="NIK" required readonly>
                </div>  
                  <input type="hidden" class="form-control" id="nama" name="Nama">
                <label>Nama</label>
                <div class="input-group input-group-sm">
                  <select class="form-control select2" id="niki" name="niki" style="width: 100%;">
                    <option value="">---Pilih Karyawan--</option>
                      @foreach($data as $datas)
                      <option value="{{$datas->nik}}">{{$datas->nama}}</option>
                      @endforeach                  
                  </select>
                      <span class="input-group-btn">
                        <button type="button" onclick="getdata();" class="btn btn-info btn-flat">Cek!</button>
                      
                      </span>
                </div>

                <div class="form-group">
                  <label>Mulai Bekerja</label>
                  <input type="text" class="form-control" id="mulai" name="TglTetap" placeholder="Mulai bekerja" required readonly>
                </div>

                <div class="form-group">
                  <label>Jabatan</label>
                  <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" required readonly>
                </div>
                
                <div class="form-group">
                  <label>Divisi</label>
                  <input type="text" class="form-control" id="divisi" name="divisi" placeholder="Divisi" required readonly>
                </div>

                </div>
                <div class="col-md-6">

                <div class="form-group">
                  <label>Tanggal Training *</label><br>
                  <div class="col-xs-12">
                  <div class="col-xs-5">
                    <input type="date" id="date1" class="form-control" name="tgl_mulai" placeholder="Tanggal Mulai" required> 
                  </div>
                  <div align="middle" class="col-xs-2">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-5">
                     <div class="form-group">
                        <input type="date" id="date2" class="form-control" name="tgl_akhir" placeholder="Tanggal Selesai" required>
                      </div> 
                  </div>
              </div>

                <div class="form-group">
                  <label>Jenis Penyedia Training *</label>
                  <!-- <input type="text" class="form-control" name="jenis_penyedia" placeholder="Jenis Penyedia Training" required> -->
                  <select class="form-control" name="jenis_penyedia">
                    <option value="">-- Pilih Jenis Penyedia --</option>
                    <option value="0">INHOUSE</option>
                    <option value="1">EKSTERNAL</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Nama Penyedia Training *</label>
                  <input type="text" class="form-control" name="penyedia" placeholder="Penyedia Training" required>
                </div>

                <div class="form-group">
                  <label>Nama Training *</label>
                  <input type="text" class="form-control" name="Nama_Pelatihan" placeholder="Nama Training" required>
                </div>

                <div class="form-group">
                  <label>File Dokumen (jpg/png/pdf)</label><br>
                  <!-- <div class="col-md-3"> -->
                    <img src="{{ asset('image/max200.png') }}" id="showgambar" class="img-bordered-sm">
                  <!-- </div> -->
                </div>
                 <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-camera"></i>
                      </div>
                      <input id="inputgambar" type="file" name="gambar" class="form-control" placeholder="file">
                    </div>  
                    <!-- /.input group -->
                  </div>
                

                <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </div>
                </div>
              </div>
              <!-- /.box-body -->

              
            </form>
            <div class="box-footer">
              </div>
          </div>
          <!-- /.box -->

      <!-- /.row -->
    </section>
    <!-- /.content -->

     
  </div>
  @endsection
  @section('jq')
  <script type="text/javascript">
  var htmlattri="border-radius: 50%;max-width:200px;max-height:200px;";
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#showgambar').attr('src',e.target.result);
          $('#showgambar').attr('style',htmlattri);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    $("#inputgambar").change(function() {
      readURL(this);
    });
  </script>
  @endsection