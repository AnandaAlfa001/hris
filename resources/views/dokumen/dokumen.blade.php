@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tambah Dokumen
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Dokumen</a></li>
        <li class="active">Tambah Dokumen</li>
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
              <h3 class="box-title">Tambah Dokumen</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('savedok') }}" enctype="multipart/form-data" method="POST">
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
                     @foreach ($data as $datas)
                      <option value="{{ $datas->id }}" > {{ $datas->dokumen }} </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>File Dokumen (jpg/png/pdf)</label><br>
                  <div class="col-md-3">
                    <img src="{{ asset('image/max200.png') }}" id="showgambar" class="img-bordered-sm">
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