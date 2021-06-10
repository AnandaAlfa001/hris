@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reject IJIN 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Ijin</a></li>
        <li class="active">Reject Ijin </li>
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
              <h3 class="box-title">Reject Ijin </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('saverejectijin', $id_ijin) }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">                          

                <div class="form-group">
                  <label>Alasan Reject</label>
                  <input type="hidden" class="form-control" name="id" value="{{$id_ijin}}" placeholder="Alasan Reject"  required>
                  <textarea type="text" class="form-control" name="alasan_reject" value="" placeholder="Alasan Reject"  required></textarea>
                </div>
                
                        
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-danger">Reject</button>
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