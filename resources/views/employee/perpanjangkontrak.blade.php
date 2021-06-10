@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Perpanjang Kontrak (OUTSOURCE)
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Employee</a></li>
        <li class="active">Perpanjang Kontrak</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Perpanjang Kontrak (OUTSOURCE)</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" action="{{ url('saveperpanjangkontrak') }}">
            {{ csrf_field() }}
              <div class="box-body">
              <div id="alertz">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i> {{ session('success') }}
                    </div>
                @endif
              </div>
                <div class="form-group">
                  <label>NIK</label>
                  <input type="text" class="form-control"  name="NIK" value="{{ $resigndata->nik }}" placeholder="NIK" readonly>
                </div>
                 <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="Nama" value="{{ $resigndata->nama }}" placeholder="Nama" readonly >
                </div>
                <br>
                <label><strong>Kontrak Baru :</strong></label>
                <div class="form-group">
                  <label>Tanggal Kontrak</label>
                  <input type="date" class="form-control" name="tgl_kontrak" value="" placeholder="YYYY-MMM-DDD" required>
                </div>
                <div class="form-group">
                  <label>Tanggal Berkakhir Kontrak</label>
                  <input type="date" class="form-control" name="akhir_kontrak" value="" placeholder="YYYY-MMM-DDD" required>
                </div>
                <!-- <div class="form-group">
                  <label>Level</label>
                  <select name="level" class="form-control" required>
                    <option value="1"> Level 1 </option>
                    <option value="2"> Level 2 </option>
                    <option value="3"> Level 3 </option>
                    <option value="4"> Level 4 </option>
                    <option value="5"> Level 5 </option>
                    <option value="6"> Level 6 </option>
                  </select>
                </div> -->
                <div class="form-group">
                  <label>Golongan Outsource</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>            
                    <select class="form-control select2" id="golongan_out" style="width: 100%;" name="golongan_out">
                      <option value="">--Pilih Golongan Outsource--</option>
                      @foreach($golonganouts as $golonganout)
                      <option value="{{$golonganout->id}}">{{$golonganout->gol}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>


                
                <div class="form-group">
                  <label>Gaji</label>
                  <input type="text" class="form-control" id="gaji1" name="gaji1" value="" placeholder="Gaji" required>
                  <input type="hidden" class="form-control" id="gaji2" name="gaji2" value="" placeholder="Gaji" required>
                </div>
                
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="submit" class="btn btn-danger">Reset</button>
              </div>
            </form>
          </div>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">TABLE KONTRAK</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <!-- <th>ID</th> -->
                  <th>NIK</th>
                  <th>TANGGAL KONTRAK</th>
                  <th>TANGGAL AKHIR KONTRAK</th>
                  <th>GOLONGAN</th>
                  
                  <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tablekontrak as $tablekontraks)       
                <tr>
                  <!-- <td>{{ $tablekontraks->id }}</td> -->
                  <td>{{ $tablekontraks->nik }}</td>
                  <td>{{ myDate($tablekontraks->tgl_kontrak) }}</td>
                  <td>{{ myDate($tablekontraks->akhir_kontrak) }}</td>
                  <td>{{ $tablekontraks->golongan_out }}</td>
                  
                  <td>
                    <div class="btn-group">
                      
                      <a href="{{ url('editkontrak', $tablekontraks->id) }}"><button type="button" class="btn btn-warning">Edit</button></a>
                      <a href="{{ url('deletekontrak', $tablekontraks->id) }}" onclick="return confirm('Yakin mau hapus data ini ?')"><button type="button" class="btn btn-danger" href="">Delete</button>
                    </div>
                  </td>
                @endforeach  
                </tr>
                </tbody>

              </table>
            </div>
        </div>
          <!-- /.box -->

          
          <!-- /.box -->

          <!-- Input addon -->
          
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

@endsection