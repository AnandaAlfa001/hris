@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Hak Cuti 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Hak Cuti</a></li>
        <li class="active">Edit Hak Cuti </li>
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
              <h3 class="box-title">Edit Hak Cuti </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('updatehakcuti', $tampiledit->id) }}" method="POST">
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
                  <label>Sisa Cuti Tahun Sebelumnya</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="sisa_cuti_seb" name="sisa_cuti_seb" value="{{ $tampiledit->sisa_cuti_seb }}" placeholder="Sisa Cuti Th Sebelum"  required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>
              </div>
              <br>
              <br>

              <div class="form-group">
                  <label>Hak Cuti Tahun 2016</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="hak_cuti" name="hak_cuti" value="{{ $tampiledit->hak_cuti }}" placeholder="Hak Cuti Tahun ini"  required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>

              </div>
              <br><br>

              <div class="form-group">
                  <label>Cuti yang Sudah Diambil</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="cuti_ambil" name="cuti_ambil" value="{{ $tampiledit->cuti_ambil }}" placeholder="Cuti yang Sudah Diambil"  required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>

              </div>
              <br><br>
                
                        
               
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