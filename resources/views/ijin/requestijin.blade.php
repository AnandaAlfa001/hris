@extends('layouts.index')
@section('content')

<script>

function getdata() {
  // var nik = document.
  var nik = $('#nik').val();
  var token = '{{Session::token()}}';
  var cekrequestijin = '{{ url('cekrequestijin') }}';


  $.ajax({
    method: 'POST',
    url : cekrequestijin,
    data : { nik:nik, _token : token },
  }).done(function (msg) {
    console.log(msg['tes'],msg['mulai_bekerja'],msg['jabatan'],msg['divisi']);
    
    $('#nama_kar').val(msg['nama']);
    $('#qwe').val(msg['tes']);
    $('#mulai_bekerja').val(msg['mulai_bekerja']);
    $('#jabatan').val(msg['jabatan']);
    $('#divisi').val(msg['divisi']); 

  });
}

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Request Izin   
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Izin</a></li>
        <li class="active">Request Izin </li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Request Izin</h3>

          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div> -->
        </div>
        <!-- /.box-header -->
        <form action="{{ url('saverequestijin') }}" method="post">
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
                  <label>NIK*</label>
                  <input type="text" class="form-control" id="qwe" name="nikfix" placeholder="NIK" readonly required>
                  <input type="hidden" class="form-control" id="nama_kar" name="nama" readonly required>
              </div>

              <label>Nama</label>
              <div class="form-group">
                <select class="form-control select2" onchange="getdata();" id="nik" name="nik" style="width: 100%;">
                  <option value="">---Pilih Karyawan--</option>
                    @foreach($query as $querys)
                    <option value="{{$querys->NIK}}">{{$querys->tampil_drop}}</option>
                    @endforeach                  
                </select>
                    {{-- <span class="input-group-btn">
                      <button type="button" onclick="getdata();" class="btn btn-info btn-flat">Cek!</button>
                    
                    </span> --}}
              </div>
              <br>

              <div class="form-group">
                  <label>Tanggal Izin</label>
                  <input type="date" class="form-control" name="tanggal" placeholder="YYYY-MM-DD" data-date-split-input="true" required> 
              </div>

              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Jam Mulai Izin:</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="JamMulaiIzin" required>

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
                  <label>Jam Selesai Izin:</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="JamSelesaiIzin" required>

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>

              <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control select2" style="width: 100%;">
                      <option value="">--Pilih Status--</option>
                      <option value="Sakit">Sakit</option>
                      <option value="Terlambat">Izin Terlambat masuk kantor</option>
                      <option value="Meninggalkan Kantor">Izin Meninggalkan Kantor</option>
                      <option value="Tidak melakukan Absen">Tidak melakukan Absen</option>  
                  </select>
              </div>

              <div class="form-group">
                  <label>Keterangan</label>
                  <textarea type="text" class="form-control" name="keterangan" placeholder="Keterangan/Alasan" required=""></textarea>                  
              </div>  



              <!-- /.form-group -->
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
               <div class="form-group">
                  <label>Jabatan</label>
                  <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" readonly required>
              </div>
              <!-- /.form-group -->
               <div class="form-group">
                  <label>Divisi</label>
                  <input type="text" class="form-control" id="divisi" name="divisi" placeholder="Divisi" readonly required>
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
        <div class="box">
            <div class="box-header">
              <h3 class="box-title">TABLE HISTORY IZIN</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
                  <td>{{ $hisizins->tanggal }}</td>                
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
      <!-- /.box -->

      
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  @endsection