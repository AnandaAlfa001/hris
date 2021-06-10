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
  var nik = $('#nik').val();
  var token = '{{Session::token()}}';
  var cekaddcuti = '{{ url('cekaddcuti') }}';


  $.ajax({
    method: 'POST',
    url : cekaddcuti,
    data : { nik:nik, _token : token },
  }).done(function (msg) {
    $('#qwe').val(msg['tes']);
    $('#mulai_bekerja').val(msg['mulai_bekerja']);
    $('#jabatan').val(msg['jabatan']);
    $('#divisi').val(msg['divisi']);
    $('#sisa_cuti_seb').val(msg['sisa_cuti_seb']);
    $('#hak_cuti').val(msg['hak_cuti']);
    $('#cuti_ambil').val(msg['cuti_ambil']);
    $('#sisa_cuti').val(msg['sisa_cuti']);
    $('#status').val(msg['status']);

    if (msg['status'] == 'Y') {
      $('#showpth').show();
    }

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
        Add Cuti        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Cuti</a></li>
        <li class="active">Add Cuti</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Add Cuti</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('saveaddcuti') }}" method="post">
        {!! csrf_field() !!}
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>NIK*</label>
                  <input type="hidden" class="form-control" id="status" name="status" readonly required>
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
                    <!-- <a href="{{ url('cekaddcuti') }}"> -->
                      <button type="button" onclick="getdata();" class="btn btn-info btn-flat">Cek!</button>
                    
                    </span>
              </div>
              <br>

              <div class="form-group">
                  <label>Mulai Bekerja</label>
                  <input type="text" class="form-control" id="mulai_bekerja" name="mulai_bekerja" placeholder="Mulai Bekerja" readonly required>
              </div>

              <div class="form-group">
                  <label>Sisa Cuti Tahun Sebelumnya</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="sisa_cuti_seb" name="sisa_cuti_seb" placeholder="Sisa Cuti Th Sebelum" readonly required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>
              </div>
              <br>
              <br>

              <div class="form-group">
                  <label>Hak Cuti Tahun 2016</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="hak_cuti" name="hak_cuti" placeholder="Hak Cuti Tahun ini" readonly required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>

              </div>
              <br><br>

              <div class="form-group">
                  <label>Cuti yang Sudah Diambil</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="cuti_ambil" name="cuti_ambil" placeholder="Cuti yang Sudah Diambil" readonly required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>

              </div>
              <br><br>
              

              <div class="form-group">
                  <label>Sisa Cuti</label>
                  <input type="text" class="form-control" id="sisa_cuti" name="sisa_cuti" placeholder="Sisa Cuti" readonly required>
              </div>
             

              <div class="form-group">
                  <label>Tanggal Cuti</label><br>
                  <div class="col-xs-4">
                    <input type="date" id="date1" class="form-control" onblur="cektglcuti(this.value)" name="tanggal_awal" placeholder="YYYY-MM-DD" data-date-split-input="true" required> 
                  </div>
                  <div class="col-xs-2">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-4">
                     <div class="form-group">
                        <input type="date" id="date2" class="form-control" onblur="blurtglcuti2(this.value)" name="tanggal_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true" required>
                      </div> 
                  </div><br>
              </div>
              <br>

              <div class="form-group">
                  <label>Rencana Cuti yang akan Diambil *</label>
                  <input id="rencanacuti" type="text" class="form-control" name="rencanacuti" placeholder="Rencana Cuti yang akan Diambil" readonly required>
              </div>
              <div class="form-group">
                  <label>Sisa Cuti s/d Tahun 2016</label>
                  <input id="sisacuti16" type="text" class="form-control" name="sisacuti16" placeholder="Sisa Cuti Tahun 2016" readonly required>
              </div>
              <div class="form-group">
                  <label>Alamat Selama Cuti</label>
                  <textarea type="text" class="form-control" name="alamat_cuti" placeholder="Alamat Selama Cuti" required></textarea> 
              </div>
              <div class="form-group">
                  <label>Keterangan / Alasan Cuti</label>
                  <textarea type="text" class="form-control" name="alasan_cuti" placeholder="Alasan" required></textarea>
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
              <br><br><br>
              <div id="showpth">
                <h2>Input PTH</h2>
                <div class="form-group">
                    <label>Karyawan PTH</label>
                    <select class="form-control select2" id="nik_pth" name="nik_pth" style="width: 100%;">
                      <option value="">---Pilih Karyawan PTH--</option>
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
  $(document).ready(function () {
    $('#showpth').hide();
  });
  </script>
  @endsection
  @endsection