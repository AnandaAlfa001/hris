@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Kontrak
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Employee</a></li>
        <li class="active">Kontrak</li>
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
              <h3 class="box-title">Edit Kontrak</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('savekontrak', $tampilkontrak->id) }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label>Tgl Kontrak</label>
                  <input type="hidden" class="form-control" name="id" placeholder="Tanggal Kontrak" value="{{ $tampilkontrak->id }}" required>
                  <input type="hidden" class="form-control" name="nik" placeholder="Tanggal Kontrak" value="{{ $tampilkontrak->nik }}" required>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_kontrak" placeholder="YYYY-MMM-DDD" value="{{ $tampilkontrak->tgl_kontrak }}" required>
                </div>
                <div class="form-group">
                  <label>Akhir Kontrak</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="akhir_kontrak" placeholder="YYYY-MMM-DDD" value="{{ $tampilkontrak->akhir_kontrak }}" required>
                </div>
                <div class="form-group">
                  <label>Level</label>
                  <select name="level" class="form-control" >
                  <option value ="1" @if($tampilkontrak->level == 1) selected @endif>Level 1</option>
                  <option value ="2" @if($tampilkontrak->level == 2) selected @endif>Level 2</option>
                  <option value ="3" @if($tampilkontrak->level == 3) selected @endif>Level 3</option>
                  <option value ="4" @if($tampilkontrak->level == 4) selected @endif>Level 4</option>
                  <option value ="5" @if($tampilkontrak->level == 5) selected @endif>Level 5</option>
                  <option value ="6" @if($tampilkontrak->level == 6) selected @endif>Level 6</option>
                 
                  </select>
                </div>
                <div class="form-group">
                  <label>Gaji</label>
                  <input type="text" class="form-control" name="gaji" placeholder="Gaji" value="{{ $tampilkontrak->gaji }}" required>
                </div>
               

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
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