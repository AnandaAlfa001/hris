@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Perjalanan Dinas        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('daftarpd') }}">Perjalanan Dinas</a></li>
        <li class="active">Edit Perjalanan Dinas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Perjalanan Dinas</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('updatepd',$editpd[0]->id_edit) }}" method="post">
        {!! csrf_field() !!}
        <div class="box-body">
        <div id="alertz">
          @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> {{ session('success') }}
            </div>
          @endif
        </div>
          <div class="row">
            <div class="col-md-6">

              <div class="form-group">
                  <label>Nama Karyawan Yang Ikut</label><br>
                  <div class="col-md-12">
                     <table class="table table-bordered table-striped">
                      <thead>
                      <tr>                      
                        <th>NO.</th>
                        <th>KARYAWAN</th>
                        <th>JABATAN</th>
                        <th>DIVISI</th>
                        <th>ACTION</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php $a=0; ?>
                      @foreach($editpd as $editpds)
                      <?php 
                      $a++; 
                      ?>
                      <tr>                      
                        <td><strong>{{ $a }}</strong></td>
                        <td>({{ $editpds->nik }}) {{ $editpds->nama }}</td>
                        <td>{{ $editpds->jabatan }}</td>
                        <td>{{ $editpds->nama_div_ext }}</td>
                        <td>
                          <a href="{{ url('delete_pd_kar',$editpds->id.'-'.$editpds->id_edit) }}" onclick="return confirm('Yakin Delete data ini ?')"><span class="label label-danger"><i class="fa fa-fw fa-trash" ></i></span></a>
                        </td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
              </div>

              <div class="form-group">
                  <input type="checkbox" name="checkboxBaru" class="minimal" id="checkboxBaru">Tambah Baru ?
              </div>

              <div id="new_kar" style="display: none;">
                <div class="form-group">
                  <label>Karyawan</label>
                  <select class="form-control select2" name="karyawan_new[]" multiple="multiple" data-placeholder="Pilih Karyawan" style="width: 100%;">
                    <option value="">--Pilih Karyawan--</option>                    
                    @foreach($query as $data)
                      <option value="{{$data->NIK}}">{{$data->tampil_drop}}</option>
                    @endforeach
                  </select>
                </div>
              </div>


              <div class="form-group">
                  <label>Tanggal Awal</label>
                  <input type="hidden" class="form-control" name="bu_tgl_awal" value="{{$editpd[0]->tgl_awal}}" placeholder="Tanggal Awal" required>
                  <input type="date" class="form-control" name="tgl_awal" value="{{$editpd[0]->tgl_awal}}" placeholder="Tanggal Awal" required>
              </div>

              <div class="form-group">
                  <label>Tanggal Akhir</label>
                  <input type="hidden" class="form-control" name="bu_tgl_akhir" value="{{$editpd[0]->tgl_akhir}}" placeholder="Tanggal Akhir" required>
                  <input type="date" class="form-control" name="tgl_akhir" value="{{$editpd[0]->tgl_akhir}}" placeholder="Tanggal Akhir" required>
              </div>

                           
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">

              <div class="form-group">
                  <label>No. Surat</label>
                  <input type="text" class="form-control" name="no_surat" value="{{$editpd[0]->no_surat}}" placeholder="No. Surat" required>
              </div> 
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Jam Mulai Perjalanan Dinas:</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="jam_awal" value="{{$jam_awal}}" required>

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>


              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Jam Selesai Perjalanan Dinas:</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="jam_akhir" value="{{$jam_akhir}}" required>

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>

              <div class="form-group">
                  <label>Keterangan</label>
                  <textarea type="text" class="form-control" name="keterangan" placeholder="Alasan SP" required>{{$editpd[0]->keterangan}}</textarea>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
        </form>
        
      </div>
      <!-- /.box -->
      <!-- /.row -->

    </section>
    <!-- /.content -->
    </div>
  @endsection

@section('jq')
<script type="text/javascript">
$(document).ready(function () {

  $('#checkboxBaru').click(function() {
      $("#new_kar").toggle(this.checked);
  });

});
</script>
@endsection
