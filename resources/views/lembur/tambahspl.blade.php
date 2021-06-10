@extends('layouts.index')
@section('content')

<script>

function getdata() {
  // var nik = document.
  var nik = $('#nik').val();
  var token = '{{Session::token()}}';
  var cektambahspl = '{{ url('cektambahspl') }}';


  $.ajax({
    method: 'POST',
    url : cektambahspl,
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
//   var date2 = $('#date2').val();
    // var diff = $('#date1').datepicker("getDate") - $('#date2').datepicker("getDate");
    // $('#diff').text(diff / (1000*60*60*24) * -1);





</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tambah Perintah Lembur        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Lembur</a></li>
        <li class="active">Tambah Perintah Lembur</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Perintah Lembur</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('saveperintahlembur') }}" method="post">
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

              <div class="form-group">
                  <label>Tanggal Mulai Lembur</label>
                  <input type="date" class="form-control" name="TanggalMulaiLembur" placeholder="Tanggal Mulai Lembur"  required>
              </div>

              <div class="form-group">
                  <label>Tanggal Selesai Lembur</label>
                  <input type="date" class="form-control" name="TanggalSelesaiLembur" placeholder="Tanggal Selesai Lembur"  required>
              </div>

              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Jam Mulai Lembur:</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="JamMulai" required>

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>

              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Jam Perkiraan Selesai Lembur:</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="PerkiraanJamSelesai" required>

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>

             <!--  <div class="form-group">
                  <label>Jam Mulai Lembur</label>
                  <input type="text" class="form-control" name="JamMulai" placeholder="Jam Mulai Lembur" required>
              </div>

              <div class="form-group">
                  <label>Jam Perkiraan Selesai Lembur</label>
                  <input type="text" class="form-control" name="PerkiraanJamSelesai" placeholder="Jam Perkiraan Selesai Lembur" required>
              </div>               -->

              <div class="form-group">
                  <label>Kegiatan</label>
                  <textarea type="text" class="form-control" name="Kegiatan" placeholder="Kegiatan" required></textarea>
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
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
        </form>
        
      </div>
      <!-- /.box -->

      
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  @endsection