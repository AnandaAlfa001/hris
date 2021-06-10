@extends('layouts.index')
@section('content')
<script type="text/javascript">

  function cektglcuti(value){

    var tglcuti = value;
    var token = '{{Session::token()}}';
    var cektglcuti = '{{ url('cektglcuti') }}';

    $.ajax({
      method: 'POST',
      url : cektglcuti,
      data : { tglcuti:tglcuti, _token : token },
    }).done(function (msg) {
      if(msg['success']==true) {
        $('#pth1').val(value)
      } else {
        alert('Maaf, cuti hanya bisa diinput 5 hari sebelum tanggal cuti.')
        $('#date1').val("")
      }
    });
  }

  function blurtglcuti2(value){
    $('#pth2').val(value)
  }

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Request Cuti        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Cuti</a></li>
        <li class="active">Request Cuti</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Request Cuti</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('saverequestcuti') }}" method="post">
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
                  <label>NIK*</label>
                  <input type="text" class="form-control" id="qwe" name="nik" value="{{Session::get('nik')}}" placeholder="NIK" readonly required>
                  <input type="hidden" class="form-control" name="status" value="{{$status}}" readonly required>
                   <input type="hidden" class="form-control" id="qwe" name="atasan1" value="{{$data->atasan1}}" placeholder="NIK">
              </div>

              <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" id="qwe" name="nama" value="{{Session::get('nama')}}" placeholder="Nama" readonly required>
              </div>

              <div class="form-group">
                  <label>Mulai Bekerja</label>
                  <input type="text" class="form-control" id="mulai_bekerja" value="{{$data->mulai_bekerja}}" name="mulai_bekerja" placeholder="Mulai Bekerja" readonly required>
              </div>

              <div class="form-group">
                  <label>Sisa Cuti Tahun Sebelumnya</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="sisa_cuti_seb" value="{{$data->sisa_cuti}}" name="sisa_cuti_seb" placeholder="Sisa Cuti Th Sebelum" readonly required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>
              </div>
              <br>
              <br>

              <div class="form-group">
                  <label>Hak Cuti Tahun 2016</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="hak_cuti" name="hak_cuti" value="{{$data->hak_cuti}}" placeholder="Hak Cuti Tahun ini" readonly required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>

              </div>
              <br><br>

              <div class="form-group">
                  <label>Cuti yang Sudah Diambil</label><br>
                  <div class="col-xs-5">
                    <input type="text" class="form-control" id="cuti_ambil" name="cuti_ambil" value="{{$data->cuti_ambil}}" placeholder="Cuti yang Sudah Diambil" readonly required>
                  </div>
                  <div class="col-xs-3">
                    <label>Hari Kerja</label>
                  </div>

              </div>
              <br><br>
              

              <div class="form-group">
                  <label>Sisa Cuti</label>
                  <input type="text" class="form-control" id="sisa_cuti" name="sisa_cuti" value="{{$sisa_cuti}}" placeholder="Sisa Cuti" readonly required>
              </div>
             

              <div class="form-group">
                  <label>Tanggal Cuti</label><br>
                  <div class="col-xs-4">
                    <input type="date" id="date1" onblur="cektglcuti(this.value)" class="form-control" name="tanggal_awal" placeholder="YYYY-MM-DD" data-date-split-input="true" required> 
                  </div>
                  <div class="col-xs-2">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-4">
                     <div class="form-group">
                        <input type="date" id="date2" onblur="blurtglcuti2(this.value)" class="form-control" name="tanggal_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true" required>
                      </div> 
                  </div><br>
              </div>
              <br>

              <div class="form-group">
                  <label>Rencana Cuti yang akan Diambil *</label>
                  <input id="rencanacuti" type="text" class="form-control" name="rencanacuti" placeholder="Rencana Cuti yang akan Diambil" readonly required>
              </div>
              <div class="form-group">
                  <label>Sisa Cuti s/d Tahun 2016</label>
                  <input id="sisacuti16" type="text" class="form-control" name="sisacuti16" placeholder="Sisa Cuti Tahun 2016" readonly required>
              </div>
              <div class="form-group">
                  <label>Alamat Selama Cuti</label>
                  <textarea type="text" class="form-control" name="alamat_cuti" placeholder="Alamat Selama Cuti" required></textarea> 
              </div>
              <div class="form-group">
                  <label>Keterangan / Alasan Cuti</label>
                  <textarea type="text" class="form-control" name="alasan_cuti" placeholder="Alasan" required></textarea>
              </div>



              <!-- /.form-group -->
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
               <div class="form-group">
                  <label>Jabatan</label>
                  <input type="text" class="form-control" id="jabatan" value="{{Session::get('jabatan')}}" name="jabatan" placeholder="Jabatan" readonly required>
              </div>
              <!-- /.form-group -->
               <div class="form-group">
                  <label>Divisi</label>
                  <input type="text" class="form-control" id="divisi" value="{{Session::get('divisi')}}" name="divisi" placeholder="Divisi" readonly required>
              </div>
              <br><br><br>
              <?php
              $idpangkat = array(2,3,4,5,6,7);
              ?>
              @if(in_array(Session::get('idpangkat'),$idpangkat))
              <h2>Input PTH</h2>
              <div class="form-group">
                  <label>Karyawan PTH</label>
                  <select class="form-control select2" id="nik_pth" name="nik_pth" style="width: 100%;">
                    <option value="">---Pilih Karyawan PTH--</option>
                    @foreach($listpth as $listpths)
                    <option value="{{$listpths->NIK}}">{{$listpths->Nama}}</option>
                    @endforeach                  
                  </select>
              </div>
              <div class="form-group">
                  <label>Tanggal PTH</label><br>
                  <div class="col-xs-4">
                    <input type="date" id="pth1" class="form-control" name="pth_awal" placeholder="YYYY-MM-DD" data-date-split-input="true"> 
                  </div>
                  <div class="col-xs-2">
                      <label>s/d</label>
                  </div>
                  <div class="col-xs-4">
                     <div class="form-group">
                        <input type="date" id="pth2" class="form-control" name="pth_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true">
                      </div> 
                  </div><br>
              </div>
              @endif
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