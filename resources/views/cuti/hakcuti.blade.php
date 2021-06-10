@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hak Cuti
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Hak Cuti</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Hak Cuti</h3>
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
                      <th>Tanggal Kontrak</th>
                      <th>Sisa Cuti Tahun Lalu</th>
                      <th>Hak Cuti</th>
                      <th>Cuti Sudah Diambil</th>
                      <th>Sisa Cuti</th>
                      <!-- <th>Action</th>                      -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($hakcutiquery as $hakcutiquerys)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $hakcutiquerys->nik }}</td>
                      <td>{{ $hakcutiquerys->nama }}</td>
                      <td>{{ date("d F Y" , strtotime($hakcutiquerys->tgl_kontrak)) }} </td>
                      <td>{{ $hakcutiquerys->sisa_cuti }} Hari Kerja</td>
                      <td>{{ $hakcutiquerys->hak_cuti }} hari Kerja</td>                      
                      <td>{{ $hakcutiquerys->cuti_ambil }} Hari Kerja</td>                      
                      <td>{{ ($hakcutiquerys->sisa_cuti + $hakcutiquerys->hak_cuti) - $hakcutiquerys->cuti_ambil }} Hari Kerja</td>
                      <!-- <td>
                       
                        <a href="{{ url('edithakcuti', $hakcutiquerys->id) }}">
                        <button class="btn btn-success" title="Edit Hak Cuti" >
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        </a>
                        
                      </td> -->
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