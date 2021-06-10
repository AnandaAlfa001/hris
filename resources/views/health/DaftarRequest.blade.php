@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daftar Klaim Kesehatan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Health</a></li>
        <li class="active">Daftar Klaim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Daftar Klaim Kesehatan</h3>
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
                      <th>Total Klaim</th>
                      <th>Kwitansi</th>
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($data as $datas)
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
                      <td>{{ $datas->nama }}</td>
                      <td>{{ $datas->remb }}</td>
                      <td>{{ myDate($datas->tglberobat) }}</td>
                      <td>{{ myDate($datas->tglklaim) }}</td>
                      <td>{{ $datas->nama_apotek }}</td>
                      <td>{{ $datas->diagnosa }}</td>
                      <td>Rp. {{number_format ($datas->total_klaim,0,",",".") }}</td>
                      <td><a href="{{ url('detailreq',$datas->idkes) }}" onclick="return confirm('Yakin Proses data ini ?')"><span class="label label-info"><i class="fa fa-fw fa-search" ></i>Process</span></a></td>
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