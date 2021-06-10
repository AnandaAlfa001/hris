@extends('layouts.index')
@section('content')
@include('layouts.function')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        History Izin
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Izin</a></li>
        <li class="active">History Izin</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">History Izin</h3>
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
                      <th>No</th>
                      <th>Nama</th>
                      <th>Tanggal Izin</th>
                      <th>Status</th>                  
                      <th>Alasan</th>
                      <th>Status Izin</th>
                      <th>Alasan Reject</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php $a=0; ?>
                      @foreach($hisizin as $hisizins)
                      <?php $a++; ?>
                      <tr>
                        <td>{{ $a }}</td>
                        <td>{{ $hisizins->nama }}</td>
                        <td>{{ myDate($hisizins->tanggal) }}</td>                
                        <td>{{ $hisizins->stat }}</td>
                        <td>{{ $hisizins->ket }} </td>
                        <td>
                          @if ($hisizins->statusApp == '1')
                            <label class="label bg-green">{{ $hisizins->statusizin }} </label>
                          @elseif ($hisizins->statusApp == '2')
                            <label class="label bg-red">{{ $hisizins->statusizin }} </label>
                          @elseif ($hisizins->statusApp == '0')
                            <label class="label bg-blue">{{ $hisizins->statusizin }} </label>
                          @endif
                        </td>
                        <td>{{ $hisizins->alasan_reject }} </td>
                      @endforeach  
                      </tr>
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