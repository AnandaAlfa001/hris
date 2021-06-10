@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        History Cuti
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">History Cuti</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Cuti</h3>
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
                      <th>Keterangan/Alasan</th>
                      <th>Alamat Cuti</th> 
                      <th>Status</th>                     
                      <?php if ($pangkats=='7' or $pangkats=='8' or $pangkats=='9' or $pangkats=='10') { ?>
                      <th>Status 2</th>
                      <?php } ?>                      
                      <th>Action</th>                     
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($historycutis as $cutihis)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $cutihis->nik }}</td>
                      <td>{{ $cutihis->nama }}</td>
                      <td>{{ myDate($cutihis->tgl_mulai) }}</td>
                      <td>{{ $cutihis->hari }}</td>                      
                      <td>{{ myDate($cutihis->tgl_selesai) }}</td>
                      <td>{{ $cutihis->keterangan }}</td>
                      <td>{{ $cutihis->alamatcuti }}</td>
                      <!-- <td>{{ $cutihis->uhuy }}</td> -->
                      <td>
                        @if ($cutihis->approve_1 == 'Y')
                          <label class="label bg-green">{{ $cutihis->statuscuti }}</label>
                        @elseif ($cutihis->approve_1 == 'R')
                          <label class="label bg-red">{{ $cutihis->statuscuti }}</label>
                        @elseif ($cutihis->approve_1 == 'N')
                          <label class="label bg-blue">{{ $cutihis->statuscuti }}</label>
                        @endif
                      </td>
                      <?php if ($pangkats=='7' or $pangkats=='8' or $pangkats=='9' or $pangkats=='10') { ?>
                      <td>
                        @if ($cutihis->approve_2 == 'Y')
                          <label class="label bg-green">{{ $cutihis->statuscuti2222 }}</label>
                        @elseif ($cutihis->approve_2 == 'R')
                          <label class="label bg-red">{{ $cutihis->statuscuti2222 }}</label>
                        @elseif ($cutihis->approve_2 == 'N')
                          <label class="label bg-blue">{{ $cutihis->statuscuti2222 }}</label>
                        @endif
                      </td>
                      <?php } ?>
                      @if(Session::get('admin') == 1)
                      <td>
                       
                        <a href="{{ url('detailhistorycuti',$cutihis->nik) }}">
                        <button class="btn btn-info" title="Detail" >
                            <i class="fa fa-search"></i> Detail
                        </button>
                        </a>
                        
                      </td>
                      @endif
                      @if(Session::get('admin') == 2 or Session::get('admin') == 3)
                      <td width="10%">
                      
                        <div class="btn-group">
                          <a href="{{ url('editcuti',$cutihis->id) }}" type="button" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                          </a>
                          <a href="{{ url('deletecuti',$cutihis->id) }}" onclick="return confirm('Yakin mau hapus data ini ?')" type="button" class="btn btn-danger">
                            <i class="fa fa-remove"></i>
                          </a>
                        </div>
                        
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