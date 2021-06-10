@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Data Employee Report
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Report Employee</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Employee Report</h3>
            </div>

            <form role="form" action="{{ url('filteremployee') }}" method="GET">
            <div class="box-body">
            <label>--- Advance Searching ---</label><br>
              <div class="row">
                <div class="col-xs-3">
                  <label>Status Karyawan</label>
                  <select class="form-control select2" style="width: 100%;" name="statuskaryawan">
                    <option value="">--Pilih Status Karyawan--</option>
                    @foreach($statuskar as $statuskars)
                    <option value="{{ $statuskars->id }}">{{ $statuskars->status_kar }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-xs-4">
                <label>Atasan Langsung</label>
                  <select class="form-control select2" style="width: 100%;" name="atasan1">
                    <option value="">--Pilih Atasan Langsung--</option>
                    @foreach($atasan1 as $atasan1s)
                    <option value="{{ $atasan1s->nik }}">{{ $atasan1s->atasan }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-xs-5">
                  <label>Atasan Tidak Langsung</label>
                  <select class="form-control select2" style="width: 100%;" name="atasan2">
                    <option value="">--Pilih Status Karyawan--</option>
                    @foreach($atasan2 as $atasan2s)
                    <option value="{{$atasan2s->nik}}">{{$atasan2s->atasan}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-xs-3">
                  <label>Lokasi Kerja</label>
                  <select class="form-control select2" style="width: 100%;" name="lokasiker">
                    <option value="">--Pilih Status Karyawan--</option>
                   @foreach($lokasikerja as $lokasikerjas)
                    <option value="{{$lokasikerjas->id}}">{{$lokasikerjas->lokasi}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-xs-4">
                  <label>Tanggal Kontrak</label>
                  <input type="date" class="form-control" id="datepicker" name="tgl_kontrak" placeholder="YYYY-MM-DD" data-date-split-input="true">
                </div>
                <div class="col-xs-5">
                  <label>Tanggal Akhir Kontrak</label>
                  <input type="date" class="form-control" id="datepicker" name="tgl_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true">
                </div>
              </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </form>


            
            <!-- /.box-header -->
            
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