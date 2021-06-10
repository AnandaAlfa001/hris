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
        History Training
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Training</a></li>
        <li class="active">History</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Training</h3>
            </div>
            
            <!-- /.box-header -->
            <div class="box-body">
            <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @elseif(session('success'))
              <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('error') }}
              </div>
              @endif
            </div>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Lihat</th>
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Tgl. Mulai</th>
                      <th>Tgl. Selesai</th>
                      <th>Jenis - Nama Penyedia</th>
                      <th>Nama pelatihan</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($his as $hiss)
                     <?php 
                     $a++;
                     $pdf = $hiss->phototrain;
                      $dat = substr($pdf,-3);
                      if($dat=="pdf") {
                        $type = "pdf";
                      } else {
                        $type = "img";
                      } 

                     ?>
                    <tr>
                      <td>
                        <!-- <a href="{{ url('downloadtrain',$hiss->ID) }}">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="download" data-original-title="Download Sertifikat">
                          <i class="fa fa-fw fa-download"></i>
                          </button>
                        </a> -->
                        @if($type == 'pdf')
                        <a target="_blank" href="{{ url('previewtrain',$hiss->ID) }}">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                        </a>
                        @else
                        <a href="#" onclick="modal(this)" id="{{ $hiss->ID }}">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                        </a>
                        @endif
                      </td>
                      <?php if ($hiss->jenis_penyedia == '0') {
                        $jenis_penyedia = 'INHOUSE';
                      } elseif($hiss->jenis_penyedia == '1') {
                        $jenis_penyedia = 'EKSTERNAL';
                      } else {
                        $jenis_penyedia = 'NULL';
                      } ?>
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $hiss->NIK }}</td>
                      <td>{{ $hiss->Nama }}</td>
                      <td>{{ myDate($hiss->tgl_mulai) }}</td>
                      <td>{{ myDate($hiss->tgl_akhir) }}</td>
                      <td>({{ $jenis_penyedia }}) {{ $hiss->penyedia }}</td>
                      <td>{{ $hiss->Nama_Pelatihan }}</td>
                      <td>
                        <div class="btn-group">
                          <a href="{{ url('edittrain',$hiss->ID) }}">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Edit Training">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                          </a>
                          <a href="{{ url('deletetrain',$hiss->ID) }}">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Delete Training">
                          <i class="fa fa-fw fa-trash"></i>
                          </button>
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