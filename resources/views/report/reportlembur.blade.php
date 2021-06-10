@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report Lembur
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Report</a></li>
        <li class="active">Lembur</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Report Lembur</h3>
            </div>
            <!-- /.box-header -->
            
            <div class="box-body">
            <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
            <div id="alertz2">
              @if(session('error'))
              <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('error') }}
              </div>
              @endif
            </div>
            <form role="form" action="{{ url('getexportlembur') }}" method="GET" target="_blank">
              <div class="box-body">
                <div class="row">
                 <div class="col-xs-3">
                  <label>Tahun</label>
                  <select class="form-control" name="tahun">
                    <option value="">--Pilih Tahun--</option>
                    @foreach($tahun as $nuhat)
                    <option value="{{ $nuhat->tahun }}">{{ $nuhat->tahun }}</option>  
                    @endforeach                  
                  </select>
                </div>
                  <div class="col-xs-3">
                  <label>Bulan</label>
                    <select class="form-control" name="bulan">
                      <option value="">--Pilih Bulan--</option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">Nopember</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <div class="col-xs-3">
                  <label>&nbsp;</label><br>
                    <button class="btn btn-success">export</button>
                  </div>
                </div>
                <br>
              </div>
              </form>
            </div>
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