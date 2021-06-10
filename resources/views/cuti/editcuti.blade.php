@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Cuti 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Divisi</a></li>
        <li class="active">Edit Cuti </li>
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
              <h3 class="box-title">Edit Cuti </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('updatecuti', $tampiledit->id) }}" method="POST">
            {!! csrf_field() !!}
              <div class="box-body">
              
                 <div class="form-group">
                  <label>NIK</label>
                  
                  <input type="text" class="form-control" name="nik" value="{{ $tampiledit->nik }}" placeholder="Nama Divisi" readonly>
                </div>

                <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" name="nama" value="{{ $tampiledit->nama }}" placeholder="Nama Divisi" readonly>
                </div> 

                <div class="form-group">
                  <label>Tanggal Mulai</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_mulai" value="{{ $tampiledit->tgl_mulai }}" placeholder="tanggal Mulai"  required>
                </div>

                <div class="form-group">
                  <label>Tanggal Selesai</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_selesai" value="{{ $tampiledit->tgl_selesai }}" placeholder="tanggal Selesai"  required>
                </div>

                <div class="form-group">
                  <label>Jumlah hari Cuti</label>
                  <input type="text" class="form-control" name="hari" value="{{ $tampiledit->hari }}" placeholder="Jumlah Hari"  required>
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" name="keterangan" value="{{ $tampiledit->keterangan }}" placeholder="Keterangan"  required>
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