@extends('layouts.index')
@section('content')
<script>
  function modal($this) {
    var data = $this.id;
    var link = '{{ asset('image/Dokumen/') }}';
    var link2 = '{{ asset('image/') }}';
    var link3 = 'download';
    var link4 = link3+'/'+data;
    console.log(link4);
  
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
        List Dokumen
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Dokumen</a></li>
        <li class="active">List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Dokumen</h3>
            </div>
              @include('layouts.function')
            <!-- /.box-header -->
            <div class="box-body">
            <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Jenis</th>
                      <th>Download</th>
                      <th>Preview</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($data as $datas)
                      <?php $a++; 
                        $pdf = $datas->File;
                        $dat = substr($pdf,-3);
                        if($dat=="pdf") {
                          $type = "pdf";
                        } else {
                          $type = "img";
                        }
                      ?>
                    <tr>
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $datas->NIK }}</td>
                      <td>{{ $datas->Nama }}</td>
                      <td>{{ $datas->dok }}</td>
                      <td>
                        {{-- <a href="{{ url('download',$datas->id) }}" id="{{ $datas->id }}">download</a> --}}
                        <a href="{{ url('download',$datas->id) }}" id="{{ $datas->id }}" type="button" class="btn btn-xs btn-danger">
                          <span><i class="fa fa-fw fa-download" ></i>Download</span>
                        </a>
                      </td>
                      @if($type=='pdf')
                      <td>
                        {{-- <a target="_blank" href="{{ url('preview',$datas->id) }}">preview</a> --}}
                        <a target="_blank" href="{{ url('preview',$datas->id) }}" type="button" class="btn btn-xs btn-primary">
                          <span><i class="fa fa-fw fa-search" ></i>Preview</span>
                        </a>
                      </td>
                      @else
                      <td>
                        {{-- <a href="#" onclick="modal(this)" id="{{ $datas->id }}">preview</a> --}}
                        <a href="#" onclick="modal(this)" id="{{ $datas->id }}" type="button" class="btn btn-xs btn-primary">
                          <span><i class="fa fa-fw fa-search" ></i>Preview</span>
                        </a>
                      </td>
                      @endif
                      <td>
                        <div class="btn-group">
                          <a href="{{ url('editdok',$datas->id) }}" type="button" class="btn btn-sm btn-success">
                            <i class="fa fa-fw fa-edit" data-toggle="tooltip" title="" data-original-title="Edi Dokument"></i>
                          </a>
                          <a onclick="return confirm('Apakah anda yakin akan menghapus ini?')" href="{{ url('deletedok',$datas->id) }}" type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-original-title="Delete Dokumen">
                            <i class="fa fa-fw fa-close"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
              <a id="down"><button type="button" class="btn btn-info">download</button></a>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  </div>
  @endsection