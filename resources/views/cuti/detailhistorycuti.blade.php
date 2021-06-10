@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail History Cuti From <strong>{{ $buatnama->formatnama }}</strong>  
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
                      <th>Mulai Cuti</th>
                      <th>Selesai Cuti</th>
                      <th>Hari Cuti</th>
                      <?php
                      $pangkat = array(2,3,4,5,6,7);
                      ?>
                      @if(in_array($idpangkat,$pangkat))
                      <th>PTH</th>
                      @endif
                      <th>Keterangan</th> 
                      <th>Status</th>                      
                      <th>Status2</th>
                      <th>Action</th>                     
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($historycutis as $cutihis)
                    <?php $no++; ?>
                    <tr>
                    
                      
                      <td>{{ $no }}</td>
                      <td>{{ myDate($cutihis->tgl_mulai) }}</td>
                      <td>{{ myDate($cutihis->tgl_selesai) }}</td>
                      <td>{{ $cutihis->hari }}</td>
                      @if(in_array($idpangkat,$pangkat))
                      <td>
                        @if($cutihis->nama_pengganti == null)
                          <label class="label bg-red">Belum Ada PTH</label>
                        @else
                          <label class="label bg-green">{{$cutihis->nama_pengganti}}</label>
                        @endif
                      </td>
                      @endif
                      <td>{{ $cutihis->keterangan }}</td>
                      <td>
                        @if($cutihis->approve_1 == 'Y')
                          <label class="label bg-green">{{ $cutihis->status }}</label>
                        @elseif ($cutihis->approve_1 == 'R')
                          <label class="label bg-red">{{ $cutihis->status }}</label>
                        @elseif ($cutihis->approve_1 == 'N')
                          <label class="label bg-blue">{{ $cutihis->status }}</label>
                        @endif
                      </td>
                      <td>
                        @if ($cutihis->approve_2 == 'Y')
                          <label class="label bg-green">{{ $cutihis->status2 }}</label>
                        @elseif ($cutihis->approve_2 == 'R')
                          <label class="label bg-red">{{ $cutihis->status2 }}</label>
                        @elseif ($cutihis->approve_2 == 'N')
                          <label class="label bg-blue">{{ $cutihis->status2 }}</label>
                        @endif
                      </td>
                      <td>
                       
                        <a href="{{ url('editcuti',$cutihis->id) }}">
                        <button class="btn btn-success" title="Edit" >
                            <i class="fa fa-edit"></i>
                        </button>
                        </a>
                        <a href="{{ url('deletecuti',$cutihis->id) }}" onclick="return confirm('Yakin mau hapus data ini ?')">
                        <button class="btn btn-danger" title="Delete" >
                            <i class="fa fa-remove"></i>
                        </button>
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
  </div>
  @endsection