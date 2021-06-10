@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Department
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Department</a></li>
        <li class="active">Edit Department</li>
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
              <h3 class="box-title">Edit Department</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('updatesubdivisi', $tampiledit->id) }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
              
                <div class="form-group">
                  <label>Nama Department</label>
                  <input type="text" class="form-control" name="subdivisi" value="{{ $tampiledit->subdivisi }}" placeholder="Nama Department" required>
                </div>

                <div class="form-group">
                  <label>Nama Divisi</label>
                  <select name="iddivisi" class="form-control">
                    @foreach($divisi as $divisis)
                    <option value ="{{$divisis->id}}" @if($tampiledit->divisiid == $divisis->id) selected @endif>{{$divisis->nama_div_ext}}</option>
                    @endforeach
                  </select>
                </div>

                
                <div class="form-group">
                  <label>Type Department</label>
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