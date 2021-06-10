@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Employee List
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Employee</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        $url_prev = explode('/',$_SERVER['REQUEST_URI']);
        $urlmen = end($url_prev);
      ?>
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Employee Table</h3>
            </div>

            <form role="form" action="{{ url('searchemployee') }}" method="GET">
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
                    <option value="">--Pilih Lokasi Kerja--</option>
                   @foreach($lokasikerja as $lokasikerjas)
                    <option value="{{$lokasikerjas->id}}">{{$lokasikerjas->lokasi}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-xs-4">
                  <label>Tanggal Kontrak</label>
                  <input type="date" class="form-control" id="datepicker" name="tgl_kontrak" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-xs-5">
                  <label>Tanggal Akhir Kontrak</label>
                  <input type="date" class="form-control" id="datepicker" name="tgl_akhir" placeholder="YYYY-MM-DD">
                </div>
              </div>
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
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th width="200">Action</th>
                      <th width="30">NIK</th>
                      <th>Name</th>
                      <th>Address</th>
                      <th>Division</th>
                      <th>Position</th>
                      <th>Gol</th>
                      <th>No. HP</th>
                      <th>E-mail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Result as $Results)
                    <tr>
                      <td>
                        <div class="btn-group">
                        
                          
                          <a href="{{ url('detailemployee',$Results-> NIK) }}">
                          <button class="btn btn-info btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Lihat Detail">
                          <i class="fa fa-fw fa-search"></i>
                          </button>
                          </a>
                          <a href="{{ url('editemployee',$Results-> NIK.'-'.$urlmen) }}">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Edit Karyawan">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                          </a>
                          <a href="{{ url('mutasi',$Results->NIK) }}">
                          <button class="btn btn-warning btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Mutasi Karyawan">
                          <i class="fa fa-fw fa-random"></i>
                          </button>
                          </a>
                          <a href="{{ url('historyjabatan',$Results->NIK) }}">
                          <button class="btn btn-primary btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="History Karyawan">
                          <i class="fa fa-fw fa-hourglass-start"></i>
                          </button>
                          </a>
                          <a href="{{ url('perpanjangkontrak',$Results->NIK) }}">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Perpanjangan Kontrak (OutSource)">
                          <i class="fa fa-fw fa-calendar-plus-o"></i>
                          </button>
                          </a>
                          <a href="{{ url('resignemployee',$Results->NIK) }}">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Resign Karyawan">
                          <i class="fa fa-fw fa-user-times"></i>
                          </button>
                          </a>
                          <a href="{{ url('headerpe',$Results->NIK) }}">
                          <button class="btn btn-info btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Project Experience">
                          <i class="fa fa-fw fa-file"></i>
                          </button>
                          </a>
                        </div>
                      </td>
                      <td>{{ $Results-> nikFormat}}</td>
                      <td>{{ $Results-> Nama}}</td>
                      <td>{{ $Results-> Alamat}}</td>
                      <td>{{ $Results-> Divisi}}</td>
                      <td>{{ $Results-> Jabatan}}</td>
                      <td>{{ $Results-> Gol}}</td>
                      <td>{{ $Results-> NoHp}}</td>
                      <td>{{ $Results-> email}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    
                  </table>
                </div>
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