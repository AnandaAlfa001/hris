@extends('layouts.index')
@section('content')
<script>
 function reject() {
  $('#rj').val('hm');
  $('#submit').click();
 }

  function modal($this) {
    var link = '{{ asset('image/Kesehatan/') }}';
    var link2 = '{{ asset('image/') }}';
  var data = $this.id;
  var token = '{{Session::token()}}';
  var url = '{{ url('buktikw') }}';
  var out = null;
  document.getElementById("pic").innerHTML = null;
  console.log("data: " + data);

  $.ajax({
    method: 'POST',
    url : url,
    data : { data : data , _token : token },
  }).done(function (msg) {
    console.log(msg['out']);
    out = msg['out'];
    if(out == '' || out == null)
    {
      out = null;
      document.getElementById("pic").innerHTML = '<img src="'+link2+'/notfound.png" class="img-bordered-sm" width="400" height="400">';
    } else {
      out.forEach(function(entry) {
          document.getElementById("pic").innerHTML += '<img src="'+link+'/'+entry+'" class="img-bordered-sm" width="400" height="400">';
    });
    }

    $('#bukti').modal();
  });
}
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Approve Data Klaim Kesehatan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">kesehatan</a></li>
        <li class="active">Approve Klaim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detail Data Klaim</h3>
            </div>

        <form role="form" method="POST" action="@if($data->jn_remb == 1) {{ url('/saverawatjalan') }} @elseif($data->jn_remb == 2){{ url('/saverawatgigi') }} @elseif($data->jn_remb == 3){{ url('/saverawatkm') }} @else {{ url('/saverawatlahir') }} @endif ">
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
                        <label>NIK *</label>
                        <input type="text" class="form-control" id="nikjalan" name="nik" value="{{ $data->NIK}}" placeholder="NIK" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Nama Pasien *</label>
                        <input type="text" class="form-control" id="pasien" name="pasien" value="{{ $data->nama}}" placeholder="Nama Pasien" readonly required>
                    </div>
                    <input type="hidden" name="status" value="{{$data->status2}}">
                    <input type="hidden" name="id" value="{{$data->idkes}}">
                    <div class="form-group">
                        <label>Nama Apotek/RS *</label>
                        <input type="text" class="form-control" name="nama" placeholder="Nama Apotek" value="{{ $data->nama_apotek}}" readonly required>
                    </div>

                    <div class="form-group">
                        <label>Diagnosa *</label>
                        <input type="text" class="form-control" name="diagnosa" placeholder="Diagnosa" value="{{$data->diagnosa}}" readonly required>
                    </div>
                    <label>Tgl. Berobat *</label>
                    <div class="form-group">
                        <input type="date" class="form-control" name="obat" placeholder="YYYY-MMM-DDD" value="{{$data->tglberobat}}" readonly required> 
                    </div>
                    <label>Tgl. Klaim *</label>
                    <div class="form-group">    
                        <input type="date" class="form-control" name="klaim" placeholder="YYYY-MMM-DDD" value="{{$data->tglklaim}}" readonly required> 
                    </div>
                    

                    <div class="form-group">
                        <label>Jumlah Benefit *</label>
                        <!-- <input type="hidden" class="form-control" name="benefit" id="benefit_1" placeholder="Jumlah Benefit" readonly required> -->
                        <input type="text" class="form-control" id="benefit" name="benefit" placeholder="Jumlah Benefit" value="{{$juben}}" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Sisa Benefit *</label>
                        <input type="text" class="form-control" id="sisa" name="sisa" placeholder="Sisa Benefit" value="{{$siben}}" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Sisa Benefit Keluarga *</label>
                        <input type="text" class="form-control" id="sisak" name="sisak" placeholder="Sisa Benefit Keluarga" value="{{$tot}}" readonly required>
                    </div>
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        <label>Jenis Klaim</label>
                        <input type="text" class="form-control" name="jenis" value="TOTAL BIAYA {{$data->remb}}" disabled required>
                    </div>
                     <div class="form-group">
                        <label>Jumlah Klaim *</label>
                        <input type="hidden" class="form-control" id="jklaim_1" name="jklaim" placeholder="Jumlah Klaim" value="{{$data->total_klaim}}" required>
                        <input type="text" class="form-control" id="jklaim" placeholder="Jumlah Klaim" value="{{$data->total_klaim}}" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Disetujui </label><small>*(hanya untuk approve)</small>
                        <input type="hidden" class="form-control" id="jsetuju_1" name="jsetuju" placeholder="Jumlah Disetujui">
                        <input type="text" class="form-control" id="jsetuju" placeholder="Jumlah Disetujui">
                    </div>
                    <div class="form-group">
                        <label>Alasan Reject </label><small>*(hanya untuk reject)</small>
                        <textarea class="form-control" name="alasan" placeholder="Alasan Reject"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Lihat Bukti / Kwitansi </label> <br>
                        <a href="#" onclick="modal(this)" id="{{ $data->idkes }}">lihat bukti</a>
                    </div>
                    <div class="box-footer">
                      <input id="rj" type="hidden" name="rejet">
                      <button id="submit" type="submit" onclick="return confirm('Apakah anda yakin?')" class="btn btn-primary">Approve</button>
                      <span type="text" name="reject" onclick="reject()" class="btn btn-danger">Reject</span>
                      
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

    <div class="modal fade" tabindex="-1" role="dialog" id="bukti">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Bukti Kwitansi</h4>
            </div>
            <div class="modal-body" align="center">
              <form>
              <?php  ?>
                <div class="form-group" id="pic">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  </div>
  <!-- /.content-wrapper -->
@endsection