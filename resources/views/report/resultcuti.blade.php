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
        <li class="active">Result Cuti Search</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Cuti</h3>
            </div>

            <form role="form" action="{{ url('filtercuti') }}" method="GET">
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
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Tanggal Mulai</th>
                      <th>Hari</th>
                      <th>Tanggal Selesai</th>
                      <th>Keterangan (Alamat Cuti)</th>
                      <th>Status</th>                                      
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($Result as $Results)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $Results->nik }}</td>
                      <td>{{ $Results->nama }}</td>
                      <td>{{ myDate($Results->tgl_mulai) }}</td>
                      <td>{{ $Results->hari }} hari</td>                      
                      <td>{{ myDate($Results->tgl_selesai) }}</td>
                      <td>{{ $Results->alamatcuti }}</td>
                      <td>{{ $Results->status }}</td>
                      
                    </tr>
                    @endforeach
                    </tbody>
                    
                  </table>
                </div>

                <form role="form" action="{{ url('getexportcuti') }}" method="GET">                    
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