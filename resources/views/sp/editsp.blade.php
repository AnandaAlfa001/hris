@extends('layouts.index')
@section('content')
<script>
  function modal($this) {
    var data = $this.id;
    var link = '{{ asset('image/SuratPeringatan/') }}';
    var link2 = '{{ asset('image/') }}';
    var link3 = 'downloadsp';
    var link4 = link3+'/'+data;
    console.log(link4);
  
  var token = '{{Session::token()}}';
  var url = '{{ url('previewimgsp') }}';
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
        Edit Surat Peringatan        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('daftarsp') }}">Surat Peringatan</a></li>
        <li class="active">Edit Surat Peringatan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Surat Peringatan</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('updateperingatan') }}" enctype="multipart/form-data" method="post">
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
                  <label>NIK</label>
                  <input type="hidden" class="form-control" name="id" value="{{$editsp->id}}" placeholder="NIK" readonly required>
                  <input type="text" class="form-control" name="nik" value="{{$editsp->nik}}" placeholder="NIK" readonly required>
              </div>

              <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" name="nama" value="{{$editsp->nama}}" placeholder="NIK" readonly required>
              </div>

              <div class="form-group">
                  <label>Nama Pemberi SP</label>
                  <input type="text" class="form-control" name="nik_pemberilembur" value="{{$editsp->nama_pemberisp}}" placeholder="NIK" readonly required>
              </div>
              
              <div class="form-group">
                  <label>Tanggal SK</label>
                  <input type="date" class="form-control" name="TanggalSK" value="{{$editsp->tgl_sk}}" placeholder="Tanggal SK" required>
              </div>

              <div class="form-group">
                  <label>Type SP</label>
                  <select class="form-control" name="type_sp">
                  <option value="">--Pilih Type SP--</option>
                  <option value="0" @if($editsp->type_sp == '0') selected @endif>INHOUSE</option>
                  <option value="1" @if($editsp->type_sp == '1') selected @endif>EKSTERNAL</option>
                  </select>
              </div>

              <div class="form-group">
                  <label>Jenis SP</label>
                  <select class="form-control" name="jenis_sp">
                  <option value="">--Pilih Jenis SP--</option>
                  @foreach($jenisSp as $jenisSps)
                  <option value="{{$jenisSps->id}}" @if($editsp->jenis_sp == $jenisSps->id) selected @endif>{{$jenisSps->jenis_sp}}</option>
                  @endforeach
                  </select>

              </div>

              <div class="form-group">
                  <label>Alasan SP</label>
                  <textarea type="text" class="form-control" name="AlasanSP" placeholder="Alasan SP" required>{{$editsp->keterangan}}</textarea>
              </div>



              <!-- /.form-group -->
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
               <div class="form-group">
                  <label>Jabatan</label>
                  <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{$editsp->jabatan}}" placeholder="Jabatan" readonly required>
              </div>
              <!-- /.form-group -->
               <div class="form-group">
                  <label>Divisi</label>
                  <input type="text" class="form-control" id="divisi" name="divisi" value="{{$editsp->nama_div_ext}}" placeholder="Divisi" readonly required>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>File Dokumen (jpg/png/pdf)</label><br>
                <div class="col-md-3">
                  @if($editsp->photosp)
                  <img src="{{ asset('image/SuratPeringatan/'.$editsp->photosp) }}" id="showgambar" class="img-bordered-sm" style="max-width:200px;max-height:200px;float:left;">
                  @else
                  <img src="{{ asset('image/max200.png') }}" id="showgambar" class="img-bordered-sm">
                  @endif
                </div>
              </div>

              <br><br><br><br>
              <br><br><br><br>
              <br><br><br><br>
               
                <?php
                  $pdf = $editsp->photosp;
                      $dat = substr($pdf,-3);
                      if($dat=="pdf") {
                        $type = "pdf";
                      } else {
                        $type = "img";
                      }
                  if($type=="pdf") { ?>

                  <div class="form-group">
                      <label>Lihat preview </label> <br>
                      <a target="_blank" href="{{ url('preview',$editsp->id) }}">preview</a>
                  </div>

                  <?php
                      } else {
                  ?>
                  <div class="form-group">
                      <label>Lihat preview </label> <br>
                      <a href="#" onclick="modal(this)" id="{{ $editsp->id }}">lihat gambar</a>
                  </div>
                  <?php
                      }
                  ?>
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
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
        </form>
        
      </div>
      <!-- /.box -->
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