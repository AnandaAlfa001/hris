@extends('layouts.index')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit PTH        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PTH</a></li>
        <li class="active">Edit PTH</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Edit PTH</h3>
        </div>
        <!-- /.box-header -->
        <form action="{{ url('updatepth') }}" method="post">
        {!! csrf_field() !!}
        <div class="box-body">
          <div id="alertz">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               {{ session('success') }}
            </div>
            @endif
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>NIK Karyawan</label>
                  <input type="hidden" class="form-control" id="id" name="id" value="{{$query->id}}" readonly="true"> 
                  <input type="text" class="form-control" id="nik" name="nik" value="{{$query->nik}}" readonly="true"> 
              </div>

              <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" id="nama_karyawan" value="{{$query->nama_karyawan}}" name="nama_karyawan" readonly="true"> 
              </div>

              <div class="form-group">
                  <label>Jenis PTH</label>
                  <input type="text" class="form-control" id="status" value="{{$query->status}}" name="jenis" readonly="true"> 
              </div>
            </div> 

            <div class="col-md-6">
              <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" value="{{$query->keterangan}}" id="keterangan" name="keterangan" readonly="true">
              </div> 

              <div class="form-group">
                  <label>Karyawan Pengganti</label>
                  <select class="form-control select2" name="nik_pth" style="width: 100%;">
                    <option value="">---Pilih Karyawan Pengganti--</option>
                    @foreach($listpth as $listpths)
                    <option value="{{ $listpths->NIK }}" @if($listpths->NIK == $query->nik_pengganti) selected @endif> {{$listpths->Nama}} </option>
                    @endforeach              
                  </select>
              </div>
              <div class="form-group">
                <label>Tanggal Awal PTH</label>
                <input type="date" id="tgl_mulai" class="form-control" value="{{$query->tgl_mulai}}" name="pth_awal" placeholder="YYYY-MM-DD" data-date-split-input="true"> 
              </div>
              <div class="form-group">
                <label>Tanggal Akhir PTH</label>
                <input type="date" id="tgl_selesai" class="form-control" value="{{$query->tgl_selesai}}" name="pth_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true">
              </div> 
            </div> 

          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
        
      </div>
    </section>
    <!-- /.content -->
</div>
@section('jq')
<script type="text/javascript">
// $(document).ready(function () {
//   $('#showpth').hide();
// });
</script>
@endsection
@endsection