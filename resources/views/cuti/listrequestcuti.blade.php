@extends('layouts.index')
@section('content')
<script type="text/javascript">
  function inputpth(id)
  {
    var token = '{{Session::token()}}';
    var inputpth = '{{ url('inputpth') }}';

    $.ajax({
      method: 'POST',
      url : inputpth,
      data : { id:id, _token : token },
    }).done(function(msg) {    
      var query = msg['data'] 
      console.log(query)
      $('#nik').val(query['nik']);
      $('#id').val(query['id']);
      $('#nama').val(query['nama']);
      $('#tgl_awal').val(query['tgl_mulai']);
      $('#tgl_selesai').val(query['tgl_selesai']);
      $('#keterangan').val(query['keterangan']);
      $('#date1').val(query['TanggalMulaiCuti']);
      $('#date2').val(query['TanggalSelesaiCuti']);

    });

    $('#inputpth').modal();
  }
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Request Cuti
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Cuti</a></li>
        <li class="active">List Request Cuti</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Request Cuti</h3>
            </div>
            
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
            <div class="box-footer clearfix">
              <!--   <a href="{{ url('adddivisi') }}">
                <button class="btn btn-success" >
                    <i class="fa fa-edit"></i> Tambah
                </button>
                </a> -->
            </div>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Tanggal Mulai</th>
                      <th>Hari</th>
                      <th>Tanggal Selesai</th>
                      <th>PTH</th>
                      <th>Keterangan/Alasan</th>
                      <th>Alamat Cuti</th> 
                      <th>Status</th>                                       
                      <th>Status 2</th>                    
                    </tr>
                    </thead>
                    <tbody>
                    <?php $pangkat = array(2,3,4,5,6,7) ?>
                    <?php $no=0; ?>
                    @foreach($cutirequest as $cutirequests)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $cutirequests->nik }}</td>
                      <td>{{ $cutirequests->nama }}</td>
                      <td>{{ myDate($cutirequests->tgl_mulai) }}</td>
                      <td>{{ $cutirequests->hari }}</td>                      
                      <td>{{ myDate($cutirequests->tgl_selesai) }}</td>
                      <td>
                        @if(in_array($cutirequests->idpangkat,$pangkat))
                          @if($cutirequests->nama_pengganti == null)
                            <a href="#" onclick="inputpth('{{$cutirequests->id}}')"><label class="label bg-red">PTH Kosong</label></a>
                          @else
                            <label class="label bg-green">{{$cutirequests->nama_pengganti}}</label>
                          @endif
                        @else
                          <label class="label bg-blue">Tidak Butuh PTH</label>
                        @endif
                      </td>
                      <td>{{ $cutirequests->keterangan }}</td>
                      <td>{{ $cutirequests->alamatcuti }}</td>
                      <td>
                        @if ($cutirequests->approve_1 == 'Y')
                          <label class="label bg-green">{{ $cutirequests->statuscuti }}</label>
                        @elseif ($cutirequests->approve_1 == 'R')
                          <label class="label bg-red">{{ $cutirequests->statuscuti }}</label>
                        @elseif ($cutirequests->approve_1 == 'N')
                          <label class="label bg-blue">{{ $cutirequests->statuscuti }}</label>
                        @endif
                      </td>
                      <td>
                        @if ($cutirequests->approve_2 == 'Y')
                          <label class="label bg-green">{{ $cutirequests->statuscuti2222 }}</label>
                        @elseif ($cutirequests->approve_2 == 'R')
                          <label class="label bg-red">{{ $cutirequests->statuscuti2222 }}</label>
                        @elseif ($cutirequests->approve_2 == 'N')
                          <label class="label bg-blue">{{ $cutirequests->statuscuti2222 }}</label>
                        @endif
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
    <div class="modal fade" role="dialog" id="inputpth">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Input PTH</h4>
              </div>
              <div class="modal-body" align="center">
              <form action="{{ url('inputpthsave') }}" method="post">
                {!! csrf_field() !!}
                <div class="col-md-6">
                  <div class="form-group">
                      <label>NIK</label>
                      <input type="hidden" class="form-control" id="id" name="id" readonly="true"> 
                      <input type="text" class="form-control" id="nik" name="nik" readonly="true"> 
                  </div>

                  <div class="form-group">
                      <label>Nama Karyawan</label>
                      <input type="text" class="form-control" id="nama" name="nama" readonly="true"> 
                  </div>

                  <div class="form-group">
                      <label>Nama Pengganti</label>
                      <select class="form-control select2" name="nik_pth" style="width: 100%;">
                        <option value="">---Pilih Karyawan Pengganti--</option>
                        @foreach($listpth as $listpths)
                        <option value="{{ $listpths->NIK }}"> {{$listpths->Nama}} </option>
                        @endforeach              
                      </select>
                  </div>

                </div> 

                <div class="col-md-6">                    

                  <div class="form-group">
                      <label>Tanggal Awal</label>
                      <input type="hidden" class="form-control" id="date1" name="date1" readonly="true"> 
                      <input type="text" class="form-control" id="tgl_awal" name="tgl_awal" readonly="true"> 
                  </div>

                  <div class="form-group">
                      <label>Tanggal Selesai</label>
                      <input type="hidden" class="form-control" id="date2" name="date2" readonly="true"> 
                      <input type="text" class="form-control" id="tgl_selesai" name="tgl_selesai" readonly="true"> 
                  </div>

                  <div class="form-group">
                      <label>Keterangan</label>
                      <input type="text" class="form-control" id="keterangan" name="keterangan" readonly="true">
                  </div>
                </div> 

                <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
              </div>
              <br><br>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div> -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
  @endsection