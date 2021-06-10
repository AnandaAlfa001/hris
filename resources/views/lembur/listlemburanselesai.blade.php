@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Lemburan Selesai
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Lembur</a></li>
        <li class="active">List Lemburan Selesai</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Lemburan Selesai</h3>
            </div>
              @include('layouts.function')
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
                      <th>Nama</th>
                      <th>Tgl. Mulai Lembur</th>
                      <th>Tgl. Selesai Lembur</th>
                      <th>Jam Mulai</th>
                      <th>Perkiraan Jam Selesai</th>
                      <th>Jam Selesai</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     <?php $a=0; ?>
                
                    @foreach($data as $datas)
                     <?php $a++; ?>
                    <tr>
                      <!-- <td>
                        <div class="btn-group">
                          <a href="#">
                          <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Approve Klaim">
                          <i class="fa fa-fw fa-search"></i>
                          </button>
                          </a>
                          <a href="#">
                          <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Reject Klaim">
                          <i class="fa fa-fw fa-edit"></i>
                          </button>
                          </a>
                        </div>
                      </td> -->
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $datas->NIK }}</td>
                      <td>{{ $datas->Nama }}</td>
                      <td>{{ $datas->TanggalMulaiLembur }}</td>
                      <td>{{ $datas->TanggalSelesaiLembur }}</td>
                      <td>{{ $datas->JamMulai }}</td>
                      <td>{{ $datas->PerkiraanJamSelesai }}</td>
                      <td>{{ $datas->JamSelesaiAktual }}</td>
                      <td>
                        @if ($datas->status == 'M')
                          <label class="label bg-red">{{ $datas->statuslembur }} </label>
                        @elseif ($datas->status == 'P')
                          <label class="label bg-blue">{{ $datas->statuslembur }} </label>
                        @elseif ($datas->status == 'S')
                          <label class="label bg-green">{{ $datas->statuslembur }} </label>
                        @elseif ($datas->status == 'AU')
                          <label class="label bg-blue">{{ $datas->statuslembur }} </label>  
                        @endif
                      </td>
                      <td><a href="{{ url('detaillembur',$datas->ID) }}" onclick="return confirm('Yakin Action data ini ?')"><span class="label label-info"><i class="fa fa-fw fa-search" ></i>Detail</span></a></td>
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