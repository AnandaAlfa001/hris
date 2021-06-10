@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Resign Employee
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Employee</a></li>
        <li class="active">Resign Employee</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Resign Employee</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ url('saveresign') }}" method="post">
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label>NIK</label>
                  <input type="text" class="form-control"  name="NIK" value="{{ $resigndata->nik }}" placeholder="NIK" readonly>
                </div>
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="Nama" value="{{ $resigndata->nama }}" placeholder="Nama" readonly>
                </div>
                <br>

                <div class="form-group">
                  <label>NO SK Pengakhiran Hubungan Kerja</label>
                  <input type="text" class="form-control" name="no_sk" value="" placeholder="NO SK Pengakhiran Hubungan Kerja">
                </div>
               
                <div class="form-group">
                  <label>Tanggal Pengajuan Resign</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_pengajuan_out" value="" placeholder="YYYY-MMM-DDD">
                </div>

                <div class="form-group">
                  <label>Tanggal Pengakhiran Hubungan Kerja</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_out" value="" placeholder="YYYY-MMM-DDD">
                </div>
               
                <div class="form-group">
                  <label>Alasan Pengakhiran Hubungan Kerja</label>
                  <select id="alasan_out" name="alasan_out" class="form-control">
                    <option value="">-- Pilih Pengakhiran Hubungan Kerja --</option>
                    <option value="Mengundurkan Diri">Mengundurkan Diri</option>
                    <option value="Habis Kontrak">Habis Kontrak</option>
                    <option value="Pemutusan Hubungan Kerja (PHK)">Pemutusan Hubungan Kerja (PHK)</option>
                    <option value="Meninggal Dunia">Meninggal Dunia</option>
                    <option value="Pensiun">Pensiun</option>
                    <option value="Pensiun Dini">Pensiun Dini</option>
                  </select>
                </div>

                <!-- <div class="form-group">
                  <label>Detail Resign</label>
                  <textarea type="text" class="form-control" name="detailresign" value="" placeholder="detail pengunduran diri"></textarea> 
                </div> -->
                <div id="detail_pengun">
                  <div class="form-group col-md-4">
                    <label>Detail</label>
                    <select name="detail_out" class="form-control">
                      <option value="">-- Pilih Detail Mengundurkan Diri --</option>
                      <option value="a">a</option>
                      <option value="b">b</option>
                      <option value="c">c</option>
                      <option value="d">d</option>
                    </select>
                  </div>
                  <div class="form-group col-md-1" style="margin-top: 27px;">
                    <label>&nbsp;</label>
                    <label>
                      <input type="checkbox" class="minimal" id="checkboxlain">
                    </label>
                    Lainnya
                  </div>
                  <div id="ta_lain" style="display: none;">                  
                    <div class="form-group col-md-6" style="margin-top: 25px;">
                      <label>&nbsp;</label>
                      <label>
                        <textarea class="form-control" name="detail_out2"></textarea>
                      </label>
                    </div>
                  </div>
                  </div>
              
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="submit" class="btn btn-danger">Reset</button>
              </div>
            </form>
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
@section('jq')
<script type="text/javascript">
$(document).ready(function () {
  $('#detail_pengun').hide();
  $('#alasan_out').change(function(){
    // alert($(this).val());
    if($(this).val() == 'Mengundurkan Diri'){       // direktur
      $('#detail_pengun').show();
    } else {
      $('#detail_pengun').hide();
    } 
  });

  $('#checkboxlain').click(function() {
      $("#ta_lain").toggle(this.checked);
  });

});
</script>
@endsection