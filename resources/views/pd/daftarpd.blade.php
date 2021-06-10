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

  function modal_detail(id_edit) {
    // var nik = document.
    var id_edit = id_edit;
    var token = '{{Session::token()}}';
    var callquerydetail = '{{ url('callquerydetail') }}';


    $.ajax({
      method: 'POST',
      url : callquerydetail,
      data : { id_edit:id_edit, _token : token },
    }).done(function (msg) {    
      var query = msg['query'] 

      var myTable= "<table id='example2' class='table table-bordered table-striped'>";
      myTable+= "<thead><tr><th>NO.</th><th>NIK</th><th>NAMA</th></tr></thead>";
      myTable+= "<tbody>";
      var a = 0;
      query.forEach(function(e){
        a = a+1;
        myTable+="<tr><td><strong>" + a + "</strong></td>";
        myTable+="<td>" + e['nik'] + "</td>";
        myTable+="<td>" + e['nama_karyawan'] + "</td>";
        myTable+="</tr>";
        console.log(e)
      });
      myTable+="</tbody></table>";
      console.log(query[0]['tgl_mulai'])
      $('#jam_awal').val(query[0]['jam_awal']);
      $('#jam_akhir').val(query[0]['jam_akhir']);
      $('#tgl_mulai').val(query[0]['tgl_mulai']);
      $('#tgl_selesai').val(query[0]['tgl_selesai']);
      $('#keterangan').val(query[0]['keterangan']);
      $('#no_surat').val(query[0]['no_surat']);

      $('#detail_table').html(myTable);
     // document.write( myTable);

    });
    
    $('#detailpd').modal();

  }
</script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Perjalanan Dinas
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Perjalanan Dinas</a></li>
        <li class="active">List Perjalanan Dinas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Perjalanan Dinas</h3>
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
                      <th>NO.</th>
                      <!-- <th>NIK</th>
                      <th>NAMA</th> -->
                      <th>NO SURAT</th>
                      <th>TGL. AWAL</th>
                      <th>TGL. AKHIR</th>
                      <th>JAM MULAI</th>
                      <th>JAM SELESAI</th>
                      <th>KETERANGAN</th>
                      @if(Session::get('admin') == '1')
                      <th>Action</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                    <?php $a=0; ?>
                
                    @foreach($daftarpd as $datas)
                    <?php 
                    $a++; 
                    ?>
                    <tr>                      
                      <td><strong>{{ $a }}</strong></td>
                      <!-- <td>{{ $datas->nik }}</td>
                      <td>{{ $datas->nama_kar }}</td> -->
                      <td>{{ $datas->no_surat }}</td>
                      <td>{{ $datas->tgl_awal }}</td>
                      <td>{{ $datas->tgl_akhir }}</td>
                      <td>{{ $datas->jam_awal }}</td>
                      <td>{{ $datas->jam_akhir }}</td>
                      <td>{{ $datas->keterangan }}</td>

                      @if(Session::get('admin') == '1')
                      <td>
                        <a href="#" onclick="modal_detail('{{ $datas->id_edit }}')"><span class="label label-info"><i class="fa fa-fw fa-search" ></i>Detail</span></a>
                        <a href="{{ url('editpd',$datas->id_edit) }}"><span class="label label-success"><i class="fa fa-fw fa-pencil" ></i>Edit</span></a>
                        <a href="{{ url('deletepd',$datas->id_edit) }}" onclick="return confirm('Yakin Delete data ini ?')"><span class="label label-danger"><i class="fa fa-fw fa-trash" ></i>Delete</span></a>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="detailpd">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Perjalanan Dinas</h4>
              </div>
              <div class="modal-body" align="center">
                <div class="table-responsive">
                  <div class="col-md-12">
                    <div id="detail_table"></div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Awal</label>
                        <input type="text" class="form-control" id="tgl_mulai" name="tgl_awal" readonly="true"> 
                    </div>

                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="text" class="form-control" id="tgl_selesai" name="tgl_akhir" readonly="true"> 
                    </div>

                    <div class="form-group">
                        <label>No. Surat Perjalanan Dinas</label>
                        <input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="No. Surat Perjalanan Dinas" readonly="true"> 
                    </div>

                  </div> 

                  <div class="col-md-6">                    
                    <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Jam Mulai Perjalanan Dinas:</label>

                        <div class="input-group">
                          <input type="text" class="form-control" id="jam_awal" name="JamMulai" readonly="true">

                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                        </div>
                        <!-- /.input group -->
                      </div>
                      <!-- /.form group -->
                    </div>

                    <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Jam Selesai Perjalanan Dinas:</label>

                        <div class="input-group">
                          <input type="text" class="form-control" id="jam_akhir" name="JamSelesai" readonly="true">

                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                        </div>
                        <!-- /.input group -->
                      </div>
                      <!-- /.form group -->
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" readonly="true">
                    </div> 
                  </div> 
                </div>
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