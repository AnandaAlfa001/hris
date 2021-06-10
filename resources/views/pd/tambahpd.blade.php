@extends('layouts.index')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tambah Data Perjalanan Dinas
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Perjalanan Dinas</a></li>
        <li class="active">Tambah Data Perjalanan Dinas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Data Perjalanan Dinas</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <div class="control-group" >
            <form role="form" autocomplete="off" method="POST" action="{{ url('/savetambahpd') }}">
              {{ csrf_field() }}
              <div class="box-body">
                <div id="alertz9">
                  @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i> {{ session('success') }}
                    </div>
                  @endif
                </div>
<!--                   <div class="controls6">
                    <div class="entry input-group col-md-9">
                        <div class="col-md-8">
                            <label class="control-label">Pilih Karyawan</label>
                            <select class="form-control select2" name="nama[]">
                              @foreach($query as $data)
                              <option value="{{$data->NIK}}">{{$data->tampil_drop}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                        <label class="control-label">&nbsp; </label>
                        <span class="input-group-btn"> 
                            <button class="btn btn-success btn-add-hm6" type="button" >
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </span>
                        </div>
                        <span><br><br><br><br></span>
                    </div>  
                  </div> -->


                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Karyawan</label>
                      <select class="form-control select2" name="karyawan[]" multiple="multiple" data-placeholder="Pilih Karyawan" style="width: 100%;">
                        <option value="">--Pilih Karyawan--</option>                    
                        @foreach($query as $data)
                          <option value="{{$data->NIK}}">{{$data->tampil_drop}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Awal</label>
                        <input type="date" class="form-control" name="tgl_awal" placeholder="YYYY-MM-DD" data-date-split-input="true" required> 
                    </div>

                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true" required> 
                    </div>

                    <div class="form-group">
                        <label>No. Surat Perjalanan Dinas</label>
                        <input type="text" class="form-control" name="no_surat" placeholder="No. Surat Perjalanan Dinas" required> 
                    </div>

                  </div> 

                  <div class="col-md-6">                    
                    <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Jam Mulai Perjalanan Dinas:</label>

                        <div class="input-group">
                          <input type="text" class="form-control timepicker" name="JamMulai" required>

                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                        </div>
                        <!-- /.input group -->
                      </div>
                      <!-- /.form group -->
                    </div>

                    <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Jam Selesai Perjalanan Dinas:</label>

                        <div class="input-group">
                          <input type="text" class="form-control timepicker" name="JamSelesai" required>

                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                        </div>
                        <!-- /.input group -->
                      </div>
                      <!-- /.form group -->
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea type="text" class="form-control" name="keterangan" placeholder="Keterangan/Alasan" required=""></textarea>                  
                    </div> 
                  </div> 

              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
        </div>     
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  @endsection
