@extends('layouts.index')
@section('content')

<script>

function getdata() {
  // var nik = document.
  var nik = $('#nik').val();
  var token = '{{Session::token()}}';
  var cektambahsp = '{{ url('cektambahsp') }}';

  $.ajax({
    method: 'POST',
    url : cektambahsp,
    data : { nik:nik, _token : token },
  }).done(function (msg) {
    console.log(msg['tes'],msg['mulai_bekerja'],msg['jabatan'],msg['divisi']);
    // console.log(msg['jabatan']);
    // console.log(msg['divisi']);
    $('#qwe').val(msg['tes']);
    $('#mulai_bekerja').val(msg['mulai_bekerja']);
    $('#jabatan').val(msg['jabatan']);
    $('#divisi').val(msg['divisi']); 

  });
}

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tambah Surat Peringatan        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('daftarsp') }}">Surat Peringatan</a></li>
        <li class="active">Tambah Surat Peringatan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Surat Peringatan</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('saveperingatan') }}" enctype="multipart/form-data" method="post">
        {!! csrf_field() !!}
        <div class="box-body">
        <div id="alertz">
          @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> {{ session('success') }}
            </div>
          @endif
        </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>NIK*</label>
                  <input type="text" class="form-control" id="qwe" name="tes" placeholder="NIK" readonly required>
              </div>

              <label>Nama</label>
              <div class="input-group input-group-sm">
                <select class="form-control select2" id="nik" name="nik" style="width: 100%;">
                  <option value="">---Pilih Karyawan--</option>
                    @foreach($tampilnama as $tampilnamas)
                    <option value="{{$tampilnamas->nik}}">{{$tampilnamas->uhuy}}</option>
                    @endforeach                  
                </select>
                    <span class="input-group-btn">
                      <button type="button" onclick="getdata();" class="btn btn-info btn-flat">Cek!</button>
                    
                    </span>
              </div>
              <br>
              @if(Session::get('admin') == 1)
              <div class="form-group">
                  <label>Pemberi Surat Peringatan</label>
                  <select class="form-control select2" id="pemberi_sp" name="pemberi_sp" style="width: 100%;" required>
                    <option value="">---Pilih Karyawan Pemberi SP---</option>
                      @foreach($tampilnama as $tampilnamas)
                      <option value="{{$tampilnamas->nik}}">{{$tampilnamas->uhuy}}</option>
                      @endforeach                  
                  </select>
              </div>
              @endif

              <div class="form-group">
                  <label>Tanggal SK</label>
                  <input type="date" class="form-control" name="TanggalSK" placeholder="Tanggal SK" required>
              </div>

              <div class="form-group">
                  <label>Tanggal Mulai SP</label>
                  <input type="date" class="form-control" id="TanggalMulai" name="TanggalMulai" placeholder="Tanggal Mulai SP" required>
              </div>

              <div class="form-group">
                  <label>Tanggal Selesai SP</label>
                  <input type="date" class="form-control" id="TanggalSelesai" name="TanggalSelesai" placeholder="Tanggal Selesai SP" required>
              </div>

              <div class="form-group">
                  <label>Type SP</label>
                  <select class="form-control" name="type_sp">
                    <option value="">--Pilih Type SP--</option>
                    <option value="0">MEMO</option>
                    <option value="1">TEGURAN</option>
                  </select>
              </div>

              <div class="form-group">
                  <label>Jenis SP</label>
                  <select id="jenis_sp" class="form-control" name="jenis_sp">
                  <option value="">--Pilih Jenis SP--</option>
                  @foreach($jenisSp as $jenisSps)
                  <option value="{{$jenisSps->id}}">{{$jenisSps->jenis_sp}}</option>
                  @endforeach
                  </select>
              </div>

              <div class="form-group">
                  <label>Alasan SP</label>
                  <textarea type="text" class="form-control" name="AlasanSP" placeholder="Alasan SP" required></textarea>
              </div>



              <!-- /.form-group -->
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
               <div class="form-group">
                  <label>Jabatan</label>
                  <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" readonly required>
              </div>
              <!-- /.form-group -->
               <div class="form-group">
                  <label>Divisi</label>
                  <input type="text" class="form-control" id="divisi" name="divisi" placeholder="Divisi" readonly required>
              </div>
              <!-- /.form-group -->

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

            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                <button id="button_submit" type="submit" class="btn btn-primary">Simpan</button>
              </div>
        </form>
        
      </div>
      <!-- /.box -->

      
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  @endsection

  @section('jq')
  <script type="text/javascript">
  var htmlattri="max-width:200px;max-height:200px;";
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

  $("#jenis_sp").change(function() {
    var nik = $('#qwe').val()
    var tgl_mulai_new = $('#TanggalMulai').val();
    var tgl_selesai_new = $('#TanggalSelesai').val();
    var jenis_sp_new = $('#jenis_sp').val()
    var token = '{{Session::token()}}';
    var ceklastsp = '{{ url('ceklastsp') }}';

    $.ajax({
      method: 'POST',
      url : ceklastsp,
      data : { nik:nik, _token : token, tgl_mulai_new:tgl_mulai_new, tgl_selesai_new:tgl_selesai_new, jenis_sp_new:jenis_sp_new },
    }).done(function (msg) {
      if (msg['status'] == 'harus meningkat') {
        alert('SP sebelumnya Baru Habis Pada '+msg['tgl_selesai_old']+', Jenis SP Harus Meningkat dari SP '+msg['jenis_sp_old'])
        $('#button_submit').attr('disabled',true)
      } else {
        // alert('mantap')
        $('#button_submit').attr('disabled',false)
      }
      // console.log(msg['tes'],msg['mulai_bekerja'],msg['jabatan'],msg['divisi']);


    });

  });
  </script>
  @endsection