@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Divisi
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Divisi</a></li>
        <li class="active">Edit Divisi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Divisi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('updatedivisi', $tampiledit->id) }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
              
                <div class="form-group">
                  <label>Nama Jabatan</label>
                  <input type="text" class="form-control" name="nama_div_ext" value="{{ $tampiledit->nama_div_ext }}" placeholder="Nama Divisi" required>
                </div>
                
                <div class="form-group">
                  <label>Status Divisi</label>
                  <select name="disabled" class="form-control">
                    
                    <option value ="1" @if($tampiledit->disabled == "1") selected @endif>Enabled</option>
                    <option value ="0" @if($tampiledit->disabled == "0") selected @endif>Disabled</option>
                  </select>
                </div>           
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
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
  @endsection