@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Data Training Report
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Report Training</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Training Report</h3>
            </div>

            <form role="form" action="{{ url('filtertraining') }}" method="GET">
            <div class="box-body">
            <label>--- Advance Searching ---</label><br>
              <div class="row">
                <div class="col-xs-3">
                  <label>Tahun</label>
                  <select class="form-control select2" style="width: 100%;" name="tahun">
                    <option value="">--Pilih Tahun--</option>
                    @foreach($tahundropdowns as $nuhat)
                    <option value="{{ $nuhat->tahun }}">{{ $nuhat->tahun }}</option>  
                    @endforeach                  
                  </select>
                </div>
                <div class="col-xs-4">
                <label>Bulan</label>
                  <select class="form-control select2" style="width: 100%;" name="bulan">
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
               
              </div>
              <br>
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