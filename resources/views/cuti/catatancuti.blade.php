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
        <li><a href="#">DiviCutisi</a></li>
        <li class="active">Approve Cuti </li>
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
              <h3 class="box-title">Catatan Approve Cuti </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('saveapprovecuti', $id_cuti) }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">                          

                <div class="form-group">
                  <label>Alasan Approve</label>
                  <input type="hidden" class="form-control" name="id" value="{{$id_cuti}}"  required>
                  <input type="text" class="form-control" name="catatan_approve" value="" placeholder="Catatan Approve"  required>
                </div>
                
                        
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-success">Approve</button>
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