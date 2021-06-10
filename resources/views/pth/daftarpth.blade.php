@extends('layouts.index')
@section('content')
<script>
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

    });
    
    $('#detailpd').modal();

  }

  function editpth(id)
  {
    var token = '{{Session::token()}}';
    var editpth = '{{ url('editpth') }}';

    $.ajax({
      method: 'POST',
      url : editpth,
      data : { id:id, _token : token },
    }).done(function (msg) {    
      var query = msg['query'] 
      console.log(query['nama_karyawan'])
      $('#nik').val(query['nik']);
      $('#nama_karyawan').val(query['nama_karyawan']);
      $('#tgl_mulai').val(query['tgl_mulai']);
      $('#tgl_selesai').val(query['tgl_selesai']);
      $('#keterangan').val(query['keterangan']);
      document.getElementById("nik_pth").value= query['nik_pengganti']; 
      $('#status').val(query['status']);
    });
    
    $('#editpth').modal();
    
  }

</script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List PTH
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PTH</a></li>
        <li class="active">List PTH</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List PTH</h3>
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
                      <th>NAMA PEGAWAI</th>
                      <th>NAMA PENGGANTI</th>
                      <th>TGL. AWAL</th>
                      <th>TGL. AKHIR</th>
                      <th>STATUS</th>
                      <th>KETERANGAN</th>
                      @if(Session::get('admin') == '1')
                      <th>ACTION</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                    <?php $a=0; ?>
                
                    @foreach($data as $datas)
                    <?php 
                    $a++; 
                    ?>
                    <tr>                      
                      <td><strong>{{ $a }}</strong></td>
                      <td>({{ $datas->nik }}) {{ $datas->nama_pegawai }}</td>
                      <td>({{ $datas->nik_pengganti }}) {{$datas->nama_pengganti }}</td>
                      <td>{{ $datas->tgl_mulai }}</td>
                      <td>{{ $datas->tgl_selesai }}</td>
                      <td>{{ $datas->status }}</td>
                      <td>{{ $datas->keterangan }}</td>

                      @if(Session::get('admin') == '1')
                      <td>
                        <a href="{{ url('editpth',$datas->id) }}"><span class="label label-success"><i class="fa fa-fw fa-pencil" ></i>Edit</span></a>
                        <a href="{{ url('deletepth',$datas->id) }}" onclick="return confirm('Yakin Delete data ini ?')"><span class="label label-danger"><i class="fa fa-fw fa-trash" ></i>Delete</span></a>
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

  </div>
  @endsection