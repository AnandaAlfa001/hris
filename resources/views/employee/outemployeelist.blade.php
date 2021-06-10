@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Out Employee List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Employee</a></li>
        <li class="active">All Out Employee</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Out Employee Table</h3>
            </div>
            
            <!-- /.box-header -->
            <div class="box-body">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th width="30">NIK</th>
                      <th>Name</th>
                      <th>Address</th>
                      <th>Division</th>
                      <th>Position</th>
                      <th>Gol</th>
                      <th>No. HP</th>
                      <th>E-mail</th>
                      <th>Alasan</th>
                      <th>Tgl Out</th>
                      <th>Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($CallEmp as $CallEmps)
                    <tr>
                      <!-- <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-danger">Action</button>
                          <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Lihat Detail</a></li>
                            <li><a href="{{ url('editemployee',$CallEmps-> NIK) }}">Edit Data</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ url('mutasi',$CallEmps->NIK) }}">Mutasi</a></li>
                            <li><a href="{{ url('historyjabatan',$CallEmps->NIK) }}">History Karyawan</a></li>
                            <li><a href="{{ url('perpanjangkontrak',$CallEmps->NIK) }}">Perpanjang Kontrak (Outsource)</a></li>
                            <li><a href="{{ url('resignemployee',$CallEmps->NIK) }}">Pemutusan hubungan kerja</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ url('projectexperience',$CallEmps->NIK) }}">Project Experience</a></li>
                          </ul>
                        </div>
                      </td> -->
                      <td>{{ $CallEmps-> nikFormat}}</td>
                      <td>{{ $CallEmps-> Nama}}</td>
                      <td>{{ $CallEmps-> Alamat}}</td>
                      <td>{{ $CallEmps-> Divisi}}</td>
                      <td>{{ $CallEmps-> Jabatan}}</td>
                      <td>{{ $CallEmps-> Gol}}</td>
                      <td>{{ $CallEmps-> NoHp}}</td>
                      <td>{{ $CallEmps-> email}}</td>
                      <td>{{ $CallEmps-> alasan_out}}</td>
                      <td>{{ mydate($CallEmps-> tgl_out)}}</td>
                      <td>
                        <div class="btn-group">                          
                          <a href="{{ url('detailemployeeout',$CallEmps-> NIK) }}">
                          <button class="btn btn-info btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Lihat Detail">
                          <i class="fa fa-fw fa-search"></i>
                          </button>
                          </a>

                          <a href="{{ url('cetakSK_PHK',$CallEmps-> NIK) }}" target="_blank">
                          <button class="btn btn-warning btn-xs ng-scope"  type="button" data-toggle="tooltip" title="" data-original-title="Cetak SK">
                          <i class="fa fa-fw fa-print"></i>
                          </button>
                          </a>
                          
                        </div>
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