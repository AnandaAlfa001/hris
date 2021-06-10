@extends('layouts.index')
@section('content')
<!-- @include('layouts.function') -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Proses Ijin 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Ijin</a></li>
        <li class="active">Proses Ijin </li>
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
              <h3 class="box-title">Proses Ijin </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="">
            {!! csrf_field() !!}
              <div class="box-body">
              
                 <div class="form-group">
                  <label>NIK</label>
                  <input type="hidden" class="form-control" name="id" value="{{ $query->idijin }}" placeholder="ID" readonly>
                  <input type="text" class="form-control" name="nik" value="{{ $query->nik }}" placeholder="NIK" readonly>
                </div>

                <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" name="nama" value="{{ $query->nama }}" placeholder="Nama Karyawan" readonly>
                </div> 

                <div class="form-group">
                  <label>Tanggal Ijin</label>
                 <input type="date" data-date-split-input="true" class="form-control" name="tglLahir" placeholder="YYYY-MMM-DDD" id="tglLahir" value="{{ $query->tanggal }}" readonly required="true">
                </div>

                <div class="form-group">
                  <label>Jam Mulai Ijin</label>
                 <input type="text" class="form-control" name="JamMulaiIzin" value="{{ $query->jam_mulai }}" readonly required="true">
                </div>

                <div class="form-group">
                  <label>Jam Selesai Ijin</label>
                 <input type="text" class="form-control" name="JamSelesaiIzin" value="{{ $query->jam_selesai }}" readonly required="true">
                </div>

                <div class="form-group">
                  <label>Status Ijin</label>
                  <input type="text" class="form-control" name="status" value="{{ $query->stat }}" placeholder="Jumlah Hari"  readonly>
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" name="keterangan" value="{{ $query->ket }}" placeholder="Alamat Cuti"  readonly>
                </div>            
                        
               
              </div>
            </form>
              <!-- /.box-body -->

              <div class="box-footer">
              <!-- <a href="{{ url('saveapproveijin',$query->idijin) }}" onclick="return confirm('Yakin Approve data ini ?')">
                  <button type="submit" class="btn btn-success">Approve</button>
              </a> -->
              <a href="#" onclick="approve_izin('{{$query->idijin}}')">
                  <button type="submit" class="btn btn-success">Approve</button>
              </a>
              <a href="{{ url('rejectijin',$query->idijin) }}" onclick="return confirm('Yakin mau Reject data ini ?')">
                  <button type="submit" class="btn btn-danger">Reject</button>
              </a>
              </div>
            
          </div>
          <!-- /.box -->


        </div>
        <!--/.col (left) -->
      
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <div class="modal fade" tabindex="-1" role="dialog" id="approve_izin">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Approve Izin</h4>
              </div>
              <form action="{{url('saveapproveijin')}}" method="POST">
                {!! csrf_field() !!}
                <div class="col-md-12">
                  <br><br>
                  <div class="form-group">
                      <label>Catatan Approve Izin</label>
                      <input type="hidden" class="form-control" id="id_ijin" name="id_ijin" readonly="true"> 
                      <!-- <input type="hidden" class="form-control" id="nik_ijin" name="nik_ijin" readonly="true">
                      <input type="hidden" class="form-control" id="tgl_ijin" name="tgl_ijin" readonly="true"> -->
                      <textarea type="text" class="form-control" id="catatan_approve" name="catatan_approve"></textarea> 
                  </div>
                  <div class="form-group">
                      <label>Potong Cuti ?</label>
                      <input type="checkbox" name="potong_cuti" id="potong_cuti" value="y">
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Approve</button>
                </div>
              </form>
              <br><br>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  </div>

  <script type="text/javascript">
    function approve_izin(id){
      $('#approve_izin').modal();
      $('#id_ijin').val(id)
      // $('#nik_ijin').val(nik)
      // $('#tgl_ijin').val(tgl_ijin)
    }
  </script>
  @endsection
