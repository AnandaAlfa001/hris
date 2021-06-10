@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Golongan OutSource List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Golongan OutSource List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Golongan OutSource List</h3>
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
                <a href="{{ url('addgolonganout') }}">
                <button class="btn btn-success" >
                    <i class="fa fa-edit"></i> Tambah
                </button>
                </a>
            </div>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th>No.</th>
                      <th>Nama Golongan OutSource</th>
                      <th>Status</th>
                      <th>Action</th>                     
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($golonganoutlist as $golonganoutlists)
                    <?php $no++; ?>
                    <tr>
                      
                      <td>{{ $no }}</td>
                      <td>{{ $golonganoutlists->gol }}</td>
                      <td>
                        @if ($golonganoutlists->disabled == '0')
                          <label class="label bg-red">Disabled </label>
                        @elseif ($golonganoutlists->disabled == '1')
                          <label class="label bg-green">Enabled</label>
                        @endif
                      </td>                      
                      <td>
                        <!-- <div class="btn-group">
                          <button type="button" class="btn btn-danger">Action</button>
                          <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('editgolonganout', $golonganoutlists->id) }}">Edit</a></li>
                            <li><a href="{{ url('deletegolonganout', $golonganoutlists->id) }}" onclick="return confirm('Yakin mau hapus data ini ?')">Delete</a></li>
                            
                          </ul>
                        </div> -->
                        <a href="{{ url('editgolonganout', $golonganoutlists->id) }}">
                        <button class="btn btn-success" title="Edit Golongan OutSource" >
                            <i class="fa fa-edit"></i> 
                        </button>
                        </a>
                        <a href="{{ url('deletegolonganout', $golonganoutlists->id) }}" onclick="return confirm('Yakin mau hapus data ini ?')">
                        <button class="btn btn-danger" title="Delete Golongan OutSource" >
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