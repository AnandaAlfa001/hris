@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Approve Cuti
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Approve Cuti</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Approve Cuti</h3>
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
                      <th>Keterangan (Alamat Cuti)</th>
                      <th>Action</th>                     
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($approves as $approve)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $approve->nik }}</td>
                      <td>{{ $approve->nama }}</td>
                      <td>{{ myDate($approve->tgl_mulai) }}</td>
                      <td>{{ $approve->hari }}</td>                      
                      <td>{{ myDate($approve->tgl_selesai) }}</td>
                      <td>{{ $approve->alamatcuti }}</td> 
                      <td>
                        <a href="{{ url('actionapprove',$approve->id) }}" onclick="return confirm('Yakin Action data ini ?')">
                        <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Process">
                          <i class="fa fa-fw fa-search"></i>Proccess
                        </button>
                        </a>
                      </td>               
                      <!-- <td>
                       
                        <a href="{{ url('saveapprovecuti',$approve->id) }}" onclick="return confirm('Yakin Approve data ini ?')">
                        <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Approve">
                          <i class="fa fa-fw fa-check"></i>
                        </button>
                        </a>
                        <a href="{{ url('rejectcuti',$approve->id) }}" onclick="return confirm('Yakin mau Reject data ini ?')">
                        <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Reject">
                          <i class="fa fa-fw fa-remove"></i>
                          </button>
                        </a>
                        
                      </td> -->
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