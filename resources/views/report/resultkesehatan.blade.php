@extends('layouts.index')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Result Cuti Search
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Result Kesehatan Search</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Kesehatan</h3>
            </div>

            <form role="form" action="{{ url('filterkesehatan') }}" method="GET">
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
            <div class="box-body">
              <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
            <div class="box-footer clearfix">
              <!--   <a href="{{ url('adddivisi') }}">
                <button class="btn btn-success" >
                    <i class="fa fa-edit"></i> Tambah
                </button>
                </a> -->
            </div>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th>No.</th>
                      <th>Nama Pasien</th>
                      <th>Jenis</th>
                      <th>Tgl. Berobat</th>
                      <th>Tgl. Klaim</th>
                      <th>Apotek / RS</th>
                      <th>Diagnosa</th>
                      <th>Total Klaim</th>                                     
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($Result as $Results)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $Results->nama }}</td>
                      <td>{{ $Results->remb }}</td>
                      <td>{{ $Results->tglberobat }}</td>
                      <td>{{ $Results->tglklaim }}</td>
                      <td>{{ $Results->nama_apotek }}</td>
                      <td>{{ $Results->diagnosa }}</td>
                      <td>Rp. {{number_format ($Results->total_klaim,0,",",".") }}</td>
                      
                    </tr>
                    @endforeach
                    </tbody>
                    
                  </table>
                </div>

                <form role="form" action="{{ url('getexportkesehatan') }}" method="GET">                    
                  <input type="hidden" class="form-control" name="tahuninput" value="{{ $tahun }}">
                  <input type="hidden" class="form-control" name="bulaninput" value="{{ $bulan }}">

                  <div class="box-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-fw  fa-file-excel-o"></i> Export Excel</button> 
                  </div>
                </form>
              </div>
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