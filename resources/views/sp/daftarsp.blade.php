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
        List Surat Peringatan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Surat Peringatan</a></li>
        <li class="active">List Surat Peringatan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Surat Peringatan</h3>
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
                      <!-- <th>Action</th> -->
                      <th>Lihat</th>
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Pemberi SP</th>
                      <th>Tgl. SK</th>
                      <th>Type - Jenis SP</th>
                      <th>Alasan SP</th>
                      @if(Session::get('admin') == '1' or Session::get('admin') == '2')
                      <th>Action</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($daftarsp as $datas)
                    <?php $a++; 

                    $pdf = $datas->photosp;
                    $dat = substr($pdf,-3);
                    if($dat=="pdf") {
                        $type = "pdf";
                    } else {
                        $type = "img";
                    } 

                    ?>
                    <tr>
                      <td>
                        <a href="{{ url('downloadsp',$datas->id) }}">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="download" data-original-title="Download Sertifikat">
                          <i class="fa fa-fw fa-download"></i>
                          </button>
                        </a>
                        @if($type == 'pdf')
                        <a target="_blank" href="{{ url('previewsp',$datas->id) }}">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                        </a>
                        @else
                        <a href="#" onclick="modal(this)" id="{{ $datas->id }}">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                        </a>
                        @endif
                      </td>
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $datas->nik }}</td>
                      <td>{{ $datas->nama }}</td>
                      <td>{{ $datas->nama_pemberisp }}</td>
                      <td>{{ $datas->tgl_sk }}</td>
                      <td>{{ $datas->type_sp }} - {{ $datas->jenis_sp }}</td>
                      <td>{{ $datas->keterangan }}</td>
                      @if(Session::get('admin') == '1' or Session::get('admin') == '2')
                      <td>
                        <a href="{{ url('editsp',$datas->id) }}"><button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="edit" data-original-title="Edit Data"><i class="fa fa-fw fa-pencil" ></i>Edit</button></a>
                        <a href="{{ url('deletesp',$datas->id) }}" onclick="return confirm('Yakin Delete data ini ?')"><button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="hapus" data-original-title="Hapus Data"><i class="fa fa-fw fa-trash" ></i>Delete</button></a>
                      </td>
                      @endif
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