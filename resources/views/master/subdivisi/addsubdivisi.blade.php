@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Department
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Department</a></li>
        <li class="active">Add Department</li>
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
              <h3 class="box-title">Add Department</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('savesubdivisi') }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
              
                <div class="form-group">
                  <label>Nama Department</label>
                  <input type="text" class="form-control" name="subdivisi" placeholder="Nama Department" required>
                </div>

                <div class="form-group">
                  <label>Nama Divisi</label>
                  <select name="iddivisi" class="form-control select2" style="width: 100%;">
                  <option value="">--Pilih Divisi--</option>
                    @foreach($divisi as $divisis)
                    <option value ="{{$divisis->id}}">{{$divisis->nama_div_ext}}</option>
                    @endforeach
                  </select>
                </div>                
                
                <div class="form-group">
                  <label>Status Department</label>
                  <select name="disabled" class="form-control select2" style="width: 100%;">
                    <option value="">--Pilih Status Department--</option>
                    <option value ="1">Enabled</option>
                    <option value ="0">Disabled</option>
                    
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