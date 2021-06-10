@extends('layouts.index')
@section('content')

<script>

function cektglcuti(value){

  var tglcuti = value;
  var token = '{{Session::token()}}';
  var cektglcuti = '{{ url('cektglcuti') }}';

  $.ajax({
    method: 'POST',
    url : cektglcuti,
    data : { tglcuti:tglcuti, _token : token },
  }).done(function (msg) {
    if(msg['success']==true) {
      $('#pth1').val(value)
    } else {
      alert('Maaf, cuti hanya bisa diinput 5 hari sebelum tanggal cuti.')
      $('#date1').val("")
    }
  });
}

function blurtglcuti2(value){
  $('#pth2').val(value)
}

function getdata() {
  // var nik = document.
  var id = $('#id').val();
  var token = '{{Session::token()}}';
  var cekaddpth = '{{ url('cekaddpth') }}';

  $.ajax({
    method: 'POST',
    url : cekaddpth,
    data : { id:id, _token : token },
  }).done(function (msg) {
    $('#nik').val(msg['nik']);
    $('#jabatan').val(msg['jabatan']);
    $('#divisi').val(msg['divisi']);
    $('#no_surat').val(msg['no_surat']);
    $('#date1').val(msg['tgl_awal']);
    $('#date2').val(msg['tgl_akhir']);
    $('#pth1').val(msg['date1']);
    $('#pth2').val(msg['date2']);
    $('#jam_awal').val(msg['jam_awal']);
    $('#jam_akhir').val(msg['jam_akhir']);
    $('#keterangan').val(msg['keterangan']);
    // if (msg['status'] == 'Y') {
    //   $('#showpth').show();
    // }

  });
}

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tambah PTH        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PTH</a></li>
        <li class="active">Tambah PTH</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah PTH (khusus perjalanan dinas)</h3>
        </div>
        <!-- /.box-header -->
        <form action="{{ url('saveaddpth') }}" method="post">
        {!! csrf_field() !!}
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>NIK*</label>
                  <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" readonly required>
              </div>

              <label>Nama</label>
              <div class="input-group input-group-sm">
                <select class="form-control select2" id="id" name="id" style="width: 100%;">
                  <option value="">---Pilih Karyawan--</option>
                    @foreach($listpd as $listpds)
                    <option value="{{$listpds->id}}">{{$listpds->tampil}}</option>
                    @endforeach
                </select>
                    <span class="input-group-btn">
                    <!-- <a href="{{ url('cekaddcuti') }}"> -->
                      <button type="button" onclick="getdata();" class="btn btn-info btn-flat">Cek!</button>
                    </span>
              </div>
              <br>

              <div class="form-group">
                <label>No Surat Perjalanan Dinas</label><br>
                <input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="no_surat" readonly required>
              </div>
              <br>

              <div class="form-group">
                  <label>Tanggal Perjalanan Dinas</label><br>
                  <div class="col-xs-4">
                    <input type="text" id="date1" class="form-control" name="tanggal_awal" readonly required> 
                  </div>
                  <div class="col-xs-2">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-4">
                     <div class="form-group">
                        <input type="text" id="date2" class="form-control" name="tanggal_akhir" readonly required>
                      </div> 
                  </div><br>
              </div>
              <br>

              

              <div class="form-group">
                  <label>Jam Perjalanan Dinas</label><br>
                  <div class="col-xs-4">
                    <input type="text" id="jam_awal" class="form-control" name="jam_awal" readonly required> 
                  </div>
                  <div class="col-xs-2">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-4">
                     <div class="form-group">
                        <input type="text" id="jam_akhir" class="form-control" name="jam_akhir" readonly required>
                      </div> 
                  </div><br>
              </div>
              <br>

              <div class="form-group">
                  <label>Keterangan Perjalanan Dinas</label>
                  <textarea type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" readonly required></textarea>
              </div>
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
              <br><br><br>
              <div id="showpth">
                <h2>Input Pengganti</h2>
                <div class="form-group">
                    <label>Karyawan Pengganti</label>
                    <select class="form-control select2" id="nik_pth" name="nik_pth" style="width: 100%;">
                      <option value="">---Pilih Karyawan Pengganti--</option>
                      @foreach($listpth as $listpths)
                      <option value="{{$listpths->NIK}}">{{$listpths->Nama}}</option>
                      @endforeach                  
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal PTH</label><br>
                    <div class="col-xs-4">
                      <input type="date" id="pth1" class="form-control" name="pth_awal" placeholder="YYYY-MM-DD" data-date-split-input="true"> 
                    </div>
                    <div class="col-xs-2">
                        <label>s/d</label>
                    </div>
                    <div class="col-xs-4">
                       <div class="form-group">
                          <input type="date" id="pth2" class="form-control" name="pth_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true">
                        </div> 
                    </div><br>
                </div>
              </div>
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
    </section>
    <!-- /.content -->
</div>
@section('jq')
<script type="text/javascript">
// $(document).ready(function () {
//   $('#showpth').hide();
// });
</script>
@endsection
@endsection