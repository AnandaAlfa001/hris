@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hak Kesehatan (Benefit Kesehatan)
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Health</a></li>
        <li class="active">Hak</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Kesehatan</h3>
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
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th>Action</th> -->
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama Karyawan</th>
                      <th>Status</th>
                      <th>Hak Rawat Jalan</th>
                      <th>Sisa Rawat Jalan</th>
                      <th>Hak Rawat Gigi</th>
                      <th>Sisa Rawat Gigi</th>
                      <th>Hak Rawat Kaca Mata</th>
                      <th>Sisa Rawat Kaca Mata</th>
                      <th>Hak Melahirkan</th>
                      <th>Sisa Melahirkan</th>
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($hak as $haks)
                     <?php $a++; ?>
                    <tr>
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $haks->NIK }}</td>
                      <td>{{ $haks->nama_pas }}</td>
                      <td>@if( $haks->status == 'P') Pegawai @elseif ( $haks->status == 'I') Suami/Istri @elseif ( $haks->status == 'A1') Anak ke 1 @elseif ( $haks->status == 'A2') Anak ke 2 @elseif ( $haks->status == 'A3') Anak ke 3 @endif</td>
                      <td>Rp. {{number_format ($haks->rawat_jalan,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->sisa_rawat_jalan,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->gigi,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->sisa_gigi,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->kacamata,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->sisa_kacamata,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->melahirkan,0,",",".") }}</td>
                      <td>Rp. {{number_format ($haks->sisa_melahirkan,0,",",".") }}</td>
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