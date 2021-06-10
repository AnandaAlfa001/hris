@extends('layouts.index')
@section('content')
<script>
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
        History Kesehatan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Health</a></li>
        <li class="active">History</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Kesehatan</h3>
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
                      <th>No.</th>
                      <th>Nama Pasien</th>
                      <th>Jenis</th>
                      <th>Tgl. Berobat</th>
                      <th>Tgl. Klaim</th>
                      <th>Apotek / RS</th>
                      <th>Diagnosa</th>
                      <th>Status</th>
                      <th>Total Klaim</th>
                      <th>Total Approve</th>
                      <th>Kwitansi</th>
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($kes as $kess)
                     <?php $a++; ?>
                    <tr>
                      <!-- <td>
                        <div class="btn-group">
                          <a href="#">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Approve Klaim">
                          <i class="fa fa-fw fa-search"></i>
                          </button>
                          </a>
                          <a href="#">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Reject Klaim">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                          </a>
                        </div>
                      </td> -->
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $kess->nama }}</td>
                      <td>{{ $kess->remb }}</td>
                      <td>{{ myDate($kess->tglberobat) }}</td>
                      <td>{{ myDate($kess->tglklaim) }}</td>
                      <td>{{ $kess->nama_apotek }}</td>
                      <td>{{ $kess->diagnosa }}</td>
                      <td>@if ($kess->approve == 'Y') <span class="label label-success">Disetujui</span> @elseif ($kess->approve == 'N') <span class="label label-info">Menunggu</span>@else <span class="label label-danger">Ditolak</span> @endif</td>
                      <td>Rp. {{number_format ($kess->total_klaim,0,",",".") }}</td>
                      <td>Rp. {{number_format ($kess->total_apprv,0,",",".") }}</td>
                      <td>
                        {{-- <a href="#" onclick="modal(this)" id="{{ $kess->idkes }}">lihat bukti</a> --}}
                        <a href="#" onclick="modal(this)" id="{{ $kess->idkes }}" type="button" class="btn btn-xs btn-success">
                            Lihat Bukti
                          </a>
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
  @endsection