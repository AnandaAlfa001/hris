@extends('layouts.index')
@section('content')
<script>
  function modal($this) {
    var data = $this.id;
    var link = '{{ asset('image/Sertifikat/') }}';
    var link2 = '{{ asset('image/') }}';
    var link3 = 'downloadtrain';
    var link4 = link3+'/'+data;
    console.log(link4);
  
  var token = '{{Session::token()}}';
  var url = '{{ url('previewimgtrain') }}';
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
          $('#down').attr('href',link4);
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
        Edit Training
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Training</a></li>
        <li class="active">Edit Training</li>
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
              <h3 class="box-title">Edit Training</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('updatetrain', $his->ID) }}" enctype="multipart/form-data" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
              <div id="alertz">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                   {{ session('success') }}
                </div>
                @endif
              </div>
              <div id="alertz2">
                @if (count($errors)>0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                   Mohon data dilengkapi
                </div>
                @endif
              </div>
                <div class="form-group">
                  <label>NIK</label>
                  <input type="text" class="form-control" name="NIK" value="{{ $his->NIK }}" placeholder="NIK" required readonly>
                </div>
                
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="Nama" value="{{ $his->Nama }}" placeholder="Nama Kryawan" required readonly>
                </div>     

                <div class="form-group">
                  <label>Mulai Bekerja</label>
                  <input type="text" class="form-control" name="TglTetap" value="{{ $his->TglTetap }}" placeholder="Mulai bekerja" required readonly>
                </div>

                <div class="form-group">
                  <label>Jabatan</label>
                  <input type="text" class="form-control" name="jabatan" value="{{ $his->jabatan }}" placeholder="Jabatan" required readonly>
                </div>
                
                <div class="form-group">
                  <label>Divisi</label>
                  <input type="text" class="form-control" name="divisi" value="{{ $his->divisi }}" placeholder="Divisi" required readonly>
                </div>

                <div class="form-group">
                  <label>Tanggal Training *</label><br>
                  <div class="col-xs-12">
                  <div class="col-xs-3">
                    <input type="date" id="date1" class="form-control" name="tgl_mulai" value="{{ $his->tgl_mulai }}" placeholder="Tanggal Mulai" required> 
                  </div>
                  <div align="middle" class="col-xs-1">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-3">
                     <div class="form-group">
                        <input type="date" id="date2" class="form-control" name="tgl_akhir" value="{{ $his->tgl_akhir }}" placeholder="Tanggal Selesai" required>
                      </div> 
                  </div>
                  <div class="col-xs-5">&nbsp;</div>
                  </div>
              </div>

                <div class="form-group">
                  <label>Jenis Penyedia Training *</label>
                  <!-- <input type="text" class="form-control" name="jenis_penyedia" placeholder="Jenis Penyedia Training" required> -->
                  <select class="form-control" name="jenis_penyedia">
                    <option value="" @if($his->jenis_penyedia == '' OR $his->jenis_penyedia == NULL) selected @endif>-- Pilih Jenis Penyedia --</option>
                    <option value="0" @if($his->jenis_penyedia == '0') selected @endif>INHOUSE</option>
                    <option value="1" @if($his->jenis_penyedia == '1') selected @endif>EKSTERNAL</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Nama Penyedia Training *</label>
                  <input type="text" class="form-control" name="penyedia" value="{{ $his->penyedia }}" placeholder="Penyedia Training" required>
                </div>

                <div class="form-group">
                  <label>Nama Training *</label>
                  <input type="text" class="form-control" name="Nama_Pelatihan" value="{{ $his->Nama_Pelatihan }}" placeholder="Nama Training" required>
                </div>

                <div class="form-group">
                  <label>File Dokumen (jpg/png/pdf)</label><br>
                  <div class="col-md-3">
                    @if($his->phototrain)
                    <img src="{{ asset('image/Sertifikat/'.$his->phototrain) }}" id="showgambar" class="img-bordered-sm" style="max-width:200px;max-height:200px;float:left;">
                    @else
                    <img src="{{ asset('image/max200.png') }}" id="showgambar" class="img-bordered-sm">
                    @endif
                  </div>
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
                  <?php
                    $pdf = $his->phototrain;
                        $dat = substr($pdf,-3);
                        if($dat=="pdf") {
                          $type = "pdf";
                        } else {
                          $type = "img";
                        }
                    if($type=="pdf") { ?>

                    <div class="form-group">
                        <label>Lihat preview </label> <br>
                        <a target="_blank" href="{{ url('preview',$his->ID) }}">preview</a>
                    </div>

                   <?php
                        } else {
                    ?>
                    <div class="form-group">
                        <label>Lihat preview </label> <br>
                        <a href="#" onclick="modal(this)" id="{{ $his->ID }}">lihat gambar</a>
                    </div>
                    <?php
                        }
                   ?>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
          <!-- /.box -->


        </div>
        <!--/.col (left) -->
      
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <div class="modal fade" tabindex="-1" role="dialog" id="bukti">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Preview Gambar</h4>
            </div>
            <div class="modal-body" align="center">
              <form>
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
  </script>
@endsection