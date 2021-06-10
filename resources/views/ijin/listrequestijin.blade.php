@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Request Izin
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Izin</a></li>
        <li class="active">List Request Izin</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Request Izin</h3>
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
                      <th>No</th>
                      <th>Nama</th>
                      <th>Tanggal Izin</th>
                      <th>Jam Mulai Izin</th>
                      <th>Jam Selesai Izin</th>
                      <th>Status</th>                  
                      <th>Alasan</th>
                      <th>Status Izin</th>                 
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($query as $querys)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $querys->nama }}</td>
                      <td>{{ myDate($querys->tanggal) }}</td>                
                      <td>{{ $querys->jam_mulai }}</td>
                      <td>{{ $querys->jam_selesai }}</td>
                      <td>{{ $querys->stat }}</td>
                      <td>{{ $querys->ket }} </td>
                      <td>
                        @if ($querys->statusApp == '1')
                          <label class="label bg-green">{{ $querys->statusizin }} </label>
                        @elseif ($querys->statusApp == '2')
                          <label class="label bg-red">{{ $querys->statusizin }} </label>
                        @elseif ($querys->statusApp == '0')
                          <label class="label bg-blue">{{ $querys->statusizin }} </label>
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
  </div>
  @endsection