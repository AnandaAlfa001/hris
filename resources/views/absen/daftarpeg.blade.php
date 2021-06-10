@extends('layouts.index')
@include('layouts.function')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
        Proses Cuti 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Cuti</a></li>
        <li class="active">Proses Cuti </li>
      </ol>
    </section> -->


    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <!-- /.box -->
          <div class="box" id="hiscut">
            <div class="box-header">
              <h3 class="box-title">Approve Absen</h3>
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
                      <th>Periode</th>
                      <th>Selisih</th>     
                      <th>Action</th>                                
                    </tr>
                    </thead>
                    <tbody id="daftar">
                    <?php $no=0; ?>
                    @foreach($data as $pegawai)
                    <?php $no++; ?>
                      <td>{{ $no }}</td>
                      <td>{{ $pegawai->nik }}</td>
                      <td>{{ $pegawai->nama }}</td>
                      <td>{{ $pegawai->bulan }}/{{ $pegawai->tahun }}</td>
                      <td>{{ $pegawai->selisih }}</td>
                      <td>
                        <a href="{{ url('apprabsen',['id'=>$pegawai->nik, 'bulan'=>$pegawai->bulan, 'tahun'=>$pegawai->tahun]) }}" onclick="return confirm('Yakin mau approve data ini?')"><button type="button" class="btn btn-success">Approve</button></a>
                        <a href="{{ url('rejectabsen',['id'=>$pegawai->nik, 'bulan'=>$pegawai->bulan, 'tahun'=>$pegawai->tahun]) }}" onclick="return confirm('Yakin mau reject data ini?')"><button type="button" class="btn btn-danger">Reject</button></a>
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
        <!--/.col (left) -->
      
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

     
  </div>
  <script type="text/javascript">
    function hide_show() {
      var x = document.getElementById("hiscut");
      if (x.style.display === "block") {
          x.style.display = "none";
          document.getElementById("hiscutbutton").value = "Show History";
      } else {
          x.style.display = "block";
          document.getElementById("hiscutbutton").value = "Hide History";
      }
    } 
  </script>
  @endsection