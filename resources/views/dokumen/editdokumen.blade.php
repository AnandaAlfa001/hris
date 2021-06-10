@extends('layouts.index')
@section('content')
<script>
  function modal($this) {
    var data = $this.id;
    var link = '{{ asset('image/Dokumen/') }}';
    var link2 = '{{ asset('image/') }}';
  
  var token = '{{Session::token()}}';
  var url = '{{ url('previewimg') }}';
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
        Edit Dokumen
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Dokumen</a></li>
        <li class="active">Edit Dokumen</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Dokumen</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('updatedok',$data->id) }}" enctype="multipart/form-data" method="POST">
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label>NIK</label><br>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-user"></i>
                    </div>
                    <input type="text" class="form-control"  name="nik" value="{{ Session::get('nik') }}" placeholder="NIK" readonly>
                  </div>
                </div>
                 <div class="form-group">
                  <label>Nama</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-odnoklassniki"></i>
                    </div>
                    <input type="text" class="form-control" name="nama" value="{{ Session::get('nama') }}" placeholder="Nama" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label>Jenis Dokumen</label>
                  <select class="form-control select2" name="jenis" style="width: 100%;">
                     @foreach ($jenis as $jeniss)
                      <option value="{{ $jeniss->id }}" @if($jeniss->id == $data->Jenis) selected @endif > {{ $jeniss->dokumen }} </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>File Dokumen (jpg/png/pdf)</label><br>
                  <div class="col-md-3">
                    @if($data->File)
                    <img src="{{ asset('image/Dokumen/'.$data->File) }}" id="showgambar" class="img-bordered-sm" style="max-width:200px;max-height:200px;float:left;">
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
                  	$pdf = $data->File;
                        $dat = substr($pdf,-3);
                        if($dat=="pdf") {
                          $type = "pdf";
                        } else {
                          $type = "img";
                        }
                    if($type=="pdf") { ?>

                    <div class="form-group">
                        <label>Lihat preview </label> <br>
                        <a target="_blank" href="{{ url('preview',$data->id) }}">preview</a>
                    </div>

                   <?php
                        } else {
                    ?>
                    <div class="form-group">
                        <label>Lihat preview </label> <br>
                        <a href="#" onclick="modal(this)" id="{{ $data->id }}">lihat gambar</a>
                    </div>
                    <?php
                        }
                   ?>
                  
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" value="reset" class="btn btn-danger">Reset</button>
              </div>
            </form>
            
          </div>
          <!-- /.box -->

          
          <!-- /.box -->

          <!-- Input addon -->
          
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
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
  </script>
@endsection