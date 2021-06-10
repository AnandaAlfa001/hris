@extends('layouts.index')
@include('layouts.function')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Profil Pegawai</h3>
            </div>
            <!-- /.box-header -->
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="col-md-6">
                  <table>
                    <tr>
                      <td><label>NIK</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>{{ $datapegawai->NIK }}</label></td>
                    </tr>
                    <tr>
                      <td><label>Nama</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->Nama }} @endif</label></td>
                    </tr>
                    <tr>
                      <td><label>Tempat Tanggal Lahir</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->TempatLahir }} {{ $datapegawai->TanggalLahir }} @endif</label></td>
                    </tr>
                    <tr>
                      <td valign="top"><label>Alamat</label></td>
                      <td valign="top"><label>&nbsp;&nbsp;:</label></td>
                      <td valign="top"><label>@if($datapegawai->NIK) {{ $datapegawai->Alamat }} @endif</label></td>
                    </tr>
                    <tr>
                      <td><label>No HP</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->NoHp }} @endif</label></td>
                    </tr>
                    <tr>
                      <td><label>Email</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->email }} @endif</label></td>
                    </tr>
                  </table>
                </div>
              </div>
              <!-- /.box-body -->

            
          </div>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Tabel Absen</h3>
            </div>
            <br><br>
            <form role="form" action="{{ url('appabsen',['id'=>$nik, 'bulan'=>$bulan, 'tahun'=>$tahun]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="box-body">
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="tabel" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th>No.</th>
                      <th>Tanggal</th>
                      <th>In</th>
                      <th>Out</th>
                      <th>Selisih</th>
                      <th>Status</th>
                      <th>Keterangan</th>          
                      <th>Keterangan Tertulis</th>                                
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $no=0; 
                      //$id_absen = array;
                    ?>
                    @foreach($data as $absen)
                    @if($absen->selisih>0)
                      <?php $id_absen[]=$absen->absen_rekap_id; ?>
                      <tr style="color: red;">
                      <input type="hidden" name="id[]" value="{{ $absen->absen_rekap_id }}">
                      <input type="hidden" name="app1[]" value="{{ $absen->approved1 }}">
                      <input type="hidden" name="app2[]" value="{{ $absen->approved2 }}">
                      <input type="hidden" name="ket[]" value="{{ $absen->ket_tamp }}">
                    @else
                      <tr>
                    @endif
                     <?php
                      $no++; 
                    ?>
                      <td>{{ $no }}</td>
                      <td>{{ myDate($absen->tgl) }}</td>
                      <td>{{ $absen->time_in }}</td>
                      <td>{{ $absen->time_out }}</td>
                      <td>{{ $absen->selisih }}</td> 
                      <td>{{ $absen->stat }}</td>                     
                      <td>{{ $absen->keterangan }}</td>
                      <td>{{ $absen->ket_tamp }}</td>   
                    </tr>
                    @endforeach
                    </tbody>
                    
                  </table>
                </div>
             <div class="box-footer" align="right">
                  <button type="submit" class="btn btn-success" onclick="return confirm('Yakin approve data ini ?')">Approve</button> 
            </form>
                  <a href="{{ url('rejectabsen',['id'=>$nik, 'bulan'=>$bulan, 'tahun'=>$tahun]) }}" onclick="return confirm('Yakin mau reject data ini?')">
                  <button type="button" class="btn btn-danger">Reject</button>
                </a>
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