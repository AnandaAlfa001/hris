@extends('layouts.index')
@section('content')
<script>

  function ceknik() {

  var nik = $('#nikinput').val();
  var token = '{{Session::token()}}';
  var ceknik = '{{ url('ceknik') }}';


  $.ajax({
    method: 'POST',
    url : ceknik,
    data : { nik:nik, _token : token },
  }).done(function (msg) {
    console.log(msg['data']);
    if(msg['data']==true) {
      $('#nikd').show();
      $('#nikc').addClass("has-error");
    } else {
      $('#nikd').hide();
      $('#nikc').removeClass("has-error");
    }
  });
}

  function modal(idlempar) {

    $('#id_nik').val(idlempar);
    $('#bukti').modal();

  }

  function modal_num(idlempar) {

    $('#id_nik_num').val(idlempar);
    $('#gen_num').modal();

  }

  function modal_alih(idlempar) {

    $('#id_nik_alih').val(idlempar);
    $('#gen_alih').modal();

  }

  function checktglsk(value){
    var tglsklama = $('#tgl_sk_lama').val();
    var tglskbaru = value;
    var token = '{{Session::token()}}';
    var cektglsk = '{{ url('cektglsk') }}';

    $.ajax({
      method: 'POST',
      url : cektglsk,
      data : { tglsklama:tglsklama, tglskbaru:tglskbaru, _token : token },
    }).done(function (msg) {
      if(msg['success']==true) {
      } else {
        alert('Tanggal SK yang dimasukkan harus lebih dari Tanggal SK Jabatan terkahir. ( Tanggal SK Jabatan Terakhir = '+tglsklama+' )')
        $('#tgl_sk').val(tglsklama)
      }
    });
  }
</script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mutation Employee

      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">Mutation Employee</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <form name="fmutasi" id="fmutasi" action="{{ url('savemutasi') }}" method="post">
        {{ csrf_field() }}
        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Input masks</h3>
            </div>
            <div class="box-body">
              <!-- Date dd/mm/yyyy -->

              <div class="form-group">
                <label>NIK</label>

                <div class="input-group" id="nikc">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                   <input type="text" id="old_nik" class="form-control"  name="old_nik" value="{{ $mutasiquery->nik }}" readonly>
                </div>
                <div id="nikd">
                  <span class="help-block">
                      <strong>NIK sudah digunakan</strong>
                  </span>
                </div><br>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <div class="form-group">
                <label>Nama</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" class="form-control" name="Nama" value="{{ $mutasiquery->nama }}" readonly >
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <!-- phone mask -->
              <div class="form-group">
                <label>Mutasi ke :</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-home"></i>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type1" value="1">
                     Outsource -> Kontrak (EDII)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type1" value="10">
                     Non Golongan -> Outsource
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type1" value="11">
                      Outsource -> Non Golongan
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type4" value="4">
                      Perpanjangan Kontrak (EDII)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type2" value="2" >
                      Kontrak (EDII) -> Tetap (EDII)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type7" value="7"  >
                      Mutasi Karyawan (EDII)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type6" value="6"  >
                      Mutasi Karyawan Outsource
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type5" value="5"  >
                      Promosi (EDII)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type8" value="8"  >
                      Promosi (OutSource)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type5" value="3"  >
                      Demosi (EDII)
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typemutasi" class="type8" value="9"  >
                      Demosi (OutSource)
                    </label>
                  </div>


                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <div class="form-group">
                <label>Nomor SK</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-book"></i>
                  </div>
                  <input type="text" class="form-control" name="no_sk" placeholder="Nomor SK" required>
                </div>
                <!-- /.input group -->
              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Data Karyawan</h3>
            </div>
            <div class="box-body">

            <div id="niklo">
              <small>NIK</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" class="form-control" id="nikinput" name="NIK" value="" placeholder="NIK" required >
              </div><br>
            </div>

              <div id="tk">
              <small>Tanggal Kontrak</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                <input type="date" class="form-control" id="TglKontrak" name="TglKontrak" value="{{ $mutasiquery->tgl_kontrak }}" placeholder="YYYY-MMM-DDD">
              </div><br>
              </div>

              <div id="tak">
              <small>Tanggal Akhir Kontrak</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar-times-o"></i></span>
                  <input type="date" class="form-control" id="TglKontrakEnd" name="TglKontrakEnd" value="{{ $mutasiquery->tgl_akhir_kontrak }}" placeholder="YYYY-MMM-DDD">
              </div><br>
              </div>

              <small>Tanggal SK</small>
              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="hidden" id="tgl_sk_lama" value="{{ $mutasiquery->tanggal1 }}">
                  <input type="date" class="form-control" onblur="checktglsk(this.value)" id="tgl_sk" name="tgl_sk_jab" value="{{ $mutasiquery->tanggal1 }}" placeholder="YYYY-MMM-DDD" required>
              </div><br>

              <small>Tanggal TMT</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="date" class="form-control" id="tgl_sk_gol"  name="tgl_sk_gol" value="{{ $mutasiquery->tanggal2 }}" placeholder="YYYY-MMM-DDD" required>
              </div><br>

              <small>Status Kepegawaian</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check-circle"></i></span>
                <input type="hidden" class="form-control" name="statuskep7" value="{{$mutasiquery->statuskar}}">
                   <select id="statuskep" class="form-control select2" style="width: 100%;" name="statuskar">
                   <option value="">--Pilih Status Karyawan--</option>
                    @foreach($statuskar as $statuskars)
                    <option value="{{$statuskars->id}}" @if($mutasiquery->statuskar == $statuskars->id) selected @endif>{{$statuskars->status_kar}}</option>
                    @endforeach
                  </select>
              </div><br>

              <small>Pangkat</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="hidden" class="form-control" name="pangkat7" value="{{$mutasiquery->pangkat}}">
                  <select id="pangkat" class="form-control select2" style="width: 100%;" name="idpangkat">
                  <option value="">--Pilih pangkat--</option>
                    @foreach($pangkat as $pangkats)
                    <option value="{{$pangkats->id}}" @if($mutasiquery->pangkat == $pangkats->id) selected @endif>{{$pangkats->pangkat}}</option>
                    @endforeach
                  </select>
              </div><br>

              <small>Jabatan</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="hidden" class="form-control" name="jabatan7" value="{{$mutasiquery->jabatanid}}">
                  <select id="jabatan" class="form-control select2" style="width: 100%;" name="idjabatan">
                    <option value="">--Pilih Jabatan--</option>
                    @foreach($jabatan as $jabatans)
                    <option value="{{$jabatans->id}}" @if($mutasiquery->jabatanid == $jabatans->id) selected @endif>{{$jabatans->jabatan}}</option>
                    @endforeach
                  </select>
              </div><br>

              <div id="golongan4">
                <small>Golongan</small>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                  <input type="hidden" class="form-control" name="golongan7" value="{{$mutasiquery->golonganmen}}">
                  <select class="form-control select2" id="golongan" style="width: 100%;" name="Golongan">
                    <option value="">--Pilih Golongan--</option>
                    @foreach($golongan as $golongans )
                    <option value="{{$golongans->id}}" @if($mutasiquery->golonganmen == $golongans->id) selected @endif>{{$golongans->gol}}</option>
                    @endforeach
                  </select>
                </div><br>
              </div>

              <div id="golongan1">
                <small>Golongan Outsource</small>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                  <input type="hidden" class="form-control" name="golonganout7" value="{{$mutasiquery->golonganout}}">
                  <select class="form-control select2" id="golongan_out" style="width: 100%;" name="Golongan_out">
                    <option value="">--Pilih Golongan Outsource--</option>
                    @foreach($golonganout as $golonganouts)
                    <option value="{{$golonganouts->id}}" @if($mutasiquery->golonganout == $golonganouts->id) selected @endif>{{$golonganouts->gol}}</option>
                    @endforeach
                  </select>
                </div><br>
              </div>

                <small>Divisi</small>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-group"></i></span>
                  <select id="divisi" class="form-control select2" style="width: 100%;" name="Divisi">
                  <option value="">--Pilih Divisi--</option>
                    @foreach($divisi as $divisis)
                    <option value="{{$divisis->id}}" @if($mutasiquery->divisi == $divisis->id) selected @endif>{{$divisis->nama_div_ext}}</option>
                    @endforeach
                  </select>
                </div><br>

              <small>Sub Divisi</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <select id="subdivisi" class="form-control select2" style="width: 100%;" name="SubDivisi">
                  <option value="">--Pilih SubDivisi--</option>
                    @foreach($subdivisi as $subdivisis)
                    <option value="{{$subdivisis->id}}" @if($mutasiquery->subdivisi == $subdivisis->id) selected @endif>{{$subdivisis->subdivisi}}</option>
                    @endforeach
                  </select>
              </div><br>

              <small>Atasan 1 (Langsung)</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-hand-pointer-o"></i></span>
                <select id="atasan1" class="form-control select2" style="width: 100%;" name="atasan1" >
                  <option value="">--Pilih Atasan1--</option>
                    @foreach($atasan1 as $atasan1s)
                    <option value="{{$atasan1s->nik}}" @if($mutasiquery->atasan1 == $atasan1s->nik) selected @endif>{{$atasan1s->atasan}}</option>
                    @endforeach
                </select>
              </div><br>
              <small>Atasan 2</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-hand-peace-o"></i></span>
                <select id="atasan2" class="form-control select2" style="width: 100%;" name="atasan2">
                <option value="">--Pilih Atasan2--</option>
                    @foreach($atasan2 as $atasan2s)
                    <option value="{{$atasan2s->nik}}" @if($mutasiquery->atasan2 == $atasan2s->nik) selected @endif>{{$atasan2s->atasan}}</option>
                    @endforeach
                </select>
              </div><br>

              <small>Lokasi Kerja</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-institution "></i></span>

                  <select id="lokasiker" class="form-control select2" style="width: 100%;" name="LokasiKer">
                  <option value="">--Pilih Lokasi Kerja--</option>
                    @foreach($lokasikerja as $lokasikerjas)
                    <option value="{{$lokasikerjas->id}}" @if($mutasiquery->lokasi == $lokasikerjas->id) selected @endif>{{$lokasikerjas->lokasi}}</option>
                    @endforeach
                  </select>
              </div><br>

              <div id="gaji">
              <small>Gaji</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money "></i></span>
                <input type="number" class="form-control" name="gaji" value="{{$gaji}}">
              </div><br>
              </div>

              <div id="tunj_tmr">
              <small>Tunjangan Pokok MMR</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money "></i></span>
                <input type="number" class="form-control" name="tunj_tmr" value="{{$tunj_tmr}}">
              </div><br>
              </div>

              <div id="tunj_jab">
              <small>Tunjangan Jabatan</small>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money "></i></span>
                <input type="number" class="form-control" name="tunj_jab" value="{{$tunj_jab}}">
              </div><br>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>

              </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div>
        </form>


        <!--/.col (left) -->

      </div>
      <div class="box">
            <div class="box-header">
              <h3 class="box-title">TABLE HISTORY JABATAN</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>NO.</th>
                  <th>JABATAN</th>
                  <th>NO. SK</th>
                  <th>TANGGAL SK</th>
                  <th>TANGGAL TMT</th>
                  <th>TGL. KONTRAK</th>
                  <th>TGL. AKHIR KONTRAK</th>
                  <th>DIVISI</th>
                  <th>LOKASI</th>
                  <th>GAJI</th>
                  <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $no=0;
                $array = 0;
                ?>

                @foreach($tablehistory as $tablehistorys)

                <?php
                $no++;
                ?>
                <tr>
                  <td>{{ $no }}</td>
                  <td>{{ $tablehistorys->jabatan }}</td>
                  <td>
                  @if($tablehistorys->no_sk == null OR $tablehistorys->no_sk == '')
                    NULL
                  @else
                    {{ $tablehistorys->no_sk }}
                  @endif
                  </td>
                  <td>{{ myDate($tablehistorys->tgl_sk_jab) }}</td>
                  <td>{{ myDate($tablehistorys->tgl_sk_gol) }}</td>
                  <td>{{ myDate($tablehistorys->TglKontrak) }}</td>
                  <td>{{ myDate($tablehistorys->TglKontrakEnd) }}</td>
                  <td>{{ $tablehistorys->divisi }}</td>
                  <td>{{ $tablehistorys->lokasi }}</td>
                  <td>
                  Rp. {{ $decgaji[$array]['gaji'] }}
                  </td>
                  <td>
                    <div class="btn-group">
                      <!-- <a href="{{ url('GenerateSkExcel',$tablehistorys->id.'-'.$tablehistorys->nik) }}">
                      <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Generate SK Excel">
                      <i class="fa fa-fw fa-file-excel-o"></i>
                      </button>
                      </a> -->
                      <?php $idlempar = $tablehistorys->id.'-'.$tablehistorys->nik; ?>
                      <!-- <a href="#" onclick="modal('{{ $idlempar }}')"> -->
                      <a href="{{ url('GenerateSkExcel',$tablehistorys->id.'-'.$tablehistorys->nik) }}">
                      <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Generate SK Excel">
                      <i class="fa fa-fw fa-file-excel-o"></i>
                      </button>
                      </a>

                      @if($tablehistorys->id_mutasi == '3' or $tablehistorys->id_mutasi == '5')
                      <!-- <a href="#" onclick="modal_num('{{ $idlempar }}')"> -->
                      <a href="{{ url('GenerateSkPdf_Num',$tablehistorys->id.'-'.$tablehistorys->nik) }}" target="_blank">
                      <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Generate SK Promosi & Numerisasi PDF">
                      <i class="fa fa-fw fa-file-pdf-o"></i>
                      </button>
                      </a>
                      @endif
                      @if($tablehistorys->id_mutasi == '1' or $tablehistorys->id_mutasi == '2' or $tablehistorys->id_mutasi == '3' or $tablehistorys->id_mutasi == '4' or $tablehistorys->id_mutasi == '5' or $tablehistorys->id_mutasi == '6' or $tablehistorys->id_mutasi == '7')
                      <!-- <a href="#" onclick="modal_alih('{{ $idlempar }}')"> -->
                      <a href="{{ url('GenerateSkPdf_Alih',$tablehistorys->id.'-'.$tablehistorys->nik) }}" target="_blank">
                      <button class="btn btn-warning btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Generate SK Promosi & Alih Tugas PDF">
                      <i class="fa fa-fw fa-file-pdf-o"></i>
                      </button>
                      </a>
                      @endif


                    </div>
                  </td>

                </tr>
                <?php
                $array++;
                ?>
                @endforeach
                </tbody>
              </table>
            </div>
        </div>
      <!-- /.row -->
    </section>

    <!-- /.content -->
    <div class="modal fade" tabindex="-1" role="dialog" id="bukti">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Input Nomor SK</h4>
              </div>
              <div class="modal-body" align="center">
                <form action="{{ url('GenerateSkExcel') }}">
                  <div class="input-group">
                      <input type="hidden" class="form-control" id="id_nik" name="id_nik" value="" placeholder="id_nik" required >
                  </div>
                  <div class="input-group">
                      <label>No SK LAMPIRAN</label>
                      <input type="text" class="form-control" id="No_SK" name="No_SK" value="" placeholder="No. SK Lampiran" required >
                  </div><br>
                  <!-- <div class="input-group">
                      <label>Tanggal SK</label>
                      <input type="date" class="form-control" id="tanggalsk" name="tanggalSK" value="" placeholder="YYYY-MMM-DDD">
                  </div> -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Generate</button>
                  </div>
                </form>
              </div>
              <br><br>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div> -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="gen_num">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Input Nomor SK PROMOSI & NUMERISASI KARYAWAN</h4>
              </div>
              <div class="modal-body" align="center">
                <form action="{{ url('GenerateSkPdf_Num') }}">
                  <div class="input-group">
                      <input type="hidden" class="form-control" id="id_nik_num" name="id_nik_num" value="" placeholder="id_nik" required >
                  </div>
                  <div class="input-group">
                      <label>No SK LAMPIRAN PROMOSI & NUMERISASI KARYAWAN</label>
                      <input type="text" class="form-control" id="No_SK_num" name="No_SK_num" value="" placeholder="No. SK Lampiran" required >
                  </div><br>
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Generate</button>
                  </div>
                </form>
              </div>
              <br><br>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div> -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="gen_alih">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Input Nomor SK PROMOSI & ALIH TUGAS KARYAWAN</h4>
              </div>
              <div class="modal-body" align="center">
                <form action="{{ url('GenerateSkPdf_Alih') }}">
                  <div class="input-group">
                      <input type="hidden" class="form-control" id="id_nik_alih" name="id_nik_alih" value="" placeholder="id_nik" required >
                  </div>
                  <div class="input-group">
                      <label>No SK LAMPIRAN PROMOSI & ALIH TUGAS KARYAWAN</label>
                      <input type="text" class="form-control" id="No_SK_alih" name="No_SK_alih" value="" placeholder="No. SK Lampiran" required >
                  </div><br>
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Generate</button>
                  </div>
                </form>
              </div>
              <br><br>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div> -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>

@endsection

@section('jqmutasi')
<script type="text/javascript">
$(document).ready(function () {
  $('#nikd').hide();
  // Outsource -> Kontrak (EDII)
  $('.type1').click(function() {
      var value_type = document.querySelector('input[name="typemutasi"]:checked').value;
      $('#niklo').show();
      $('#nikinput').prop('required',true);
      $('#statuskep').prop('disabled',false);
      $('#pangkat').prop('disabled',false);
      $('#jabatan').prop('disabled',false);
      if (value_type == '1' || value_type == '11'){
        $('#golongan').prop('disabled',false);
        $('#golongan_out').prop('disabled',true);
      }else{
        $('#golongan').prop('disabled',true);
        $('#golongan_out').prop('disabled',false);
      }

      $('#TglKontrak').prop('disabled',false);
      $('#TglKontrakEnd').prop('disabled',false);
      $('#tk').show();
      $('#tak').show();
      if (value_type == '1' || value_type == '11'){
        $('#golongan1').hide();
        $('#golongan4').show();
      }else{
        $('#golongan1').show();
        $('#golongan4').hide();
      }

      $('#gaji').hide();
      $('#tunj_tmr').hide();
      $('#tunj_jab').hide();
  });

  // Kontrak (EDII) -> Tetap (EDII)
  $('.type2').click(function() {
      $('#niklo').hide();
      $('#nikinput').prop('required',false);
      $('#statuskep').prop('disabled',false);
      $('#pangkat').prop('disabled',false);
      $('#jabatan').prop('disabled',false);
      $('#golongan').prop('disabled',false);
      $('#golongan_out').prop('disabled',true);
      $('#TglKontrak').prop('disabled',true);
      $('#TglKontrakEnd').prop('disabled',true);
      $('#tk').hide();
      $('#tak').hide();
      $('#golongan1').hide();
      $('#golongan4').show();
      $('#gaji').hide();
      $('#tunj_tmr').hide();
      $('#tunj_jab').hide();
  });
  // $('.type3').click(function() {
  //     $('#niklo').hide();
  //     $('#nikinput').prop('required',false);
  //     $('#golongan_out').prop('disabled',true);
  //     $('#tak').show();
  //     $('#golongan1').hide();
  //     $('#golongan4').show();
  // });

  // Perpanjangan Kontrak (EDII)
  $('.type4').click(function() {
      $('#niklo').hide();
      $('#nikinput').prop('required',false);
      $('#statuskep').prop('disabled',false);
      $('#pangkat').prop('disabled',false);
      $('#jabatan').prop('disabled',false);
      $('#golongan').prop('disabled',false);
      $('#golongan_out').prop('disabled',true);
      $('#TglKontrak').prop('disabled',false);
      $('#TglKontrakEnd').prop('disabled',false);
      $('#tk').show();
      $('#tak').show();
      $('#golongan1').hide();
      $('#golongan4').show();
      $('#gaji').show();
      $('#tunj_tmr').show();
      $('#tunj_jab').show();
  });
  // Promosi & DEmosi (EDII)
  $('.type5').click(function() {
      $('#niklo').hide();
      $('#nikinput').prop('required',false);
      $('#TglKontrak').prop('disabled',false);
      $('#TglKontrakEnd').prop('disabled',false);
      $('#TglKontrak').prop('required',false);
      $('#TglKontrakEnd').prop('required',false);
      $('#statuskep').prop('disabled',false);
      $('#pangkat').prop('disabled',false);
      $('#jabatan').prop('disabled',false);
      $('#golongan').prop('disabled',false);
      $('#golongan_out').prop('disabled',true);
      $('#golongan1').hide();
      $('#golongan4').show();
      $('#tak').hide();
      $('#tk').hide();
      $('#gaji').show();
      $('#tunj_tmr').show();
      $('#tunj_jab').show();
  });
  // Promosi & DEmosi (Outsource)
  $('.type8').click(function() {
      $('#niklo').hide();
      $('#nikinput').prop('required',false);
      $('#TglKontrak').prop('disabled',false);
      $('#TglKontrakEnd').prop('disabled',false);
      $('#TglKontrak').prop('required',false);
      $('#TglKontrakEnd').prop('required',false);
      $('#statuskep').prop('disabled',false);
      $('#pangkat').prop('disabled',false);
      $('#jabatan').prop('disabled',false);
      $('#golongan').prop('disabled',true);
      $('#golongan_out').prop('disabled',false);
      $('#golongan1').show();
      $('#golongan4').hide();
      $('#tak').hide();
      $('#tk').hide();
      $('#gaji').show();
      $('#tunj_tmr').show();
      $('#tunj_jab').show();
  });
  // Mutasi Karyawan Outsource
  $('.type6').click(function() {
      $('#niklo').hide();
      $('#nikinput').prop('required',false);
      $('#TglKontrak').prop('disabled',false);
      $('#TglKontrakEnd').prop('disabled',false);
      $('#tk').hide();
      $('#tak').hide();
      $('#statuskep').prop('disabled',true);
      $('#pangkat').prop('disabled',true);
      $('#jabatan').prop('disabled',true);
      $('#golongan_out').prop('disabled',true);
      $('#golongan1').show();
      $('#golongan4').hide();
      $('#gaji').hide();
      $('#tunj_tmr').hide();
      $('#tunj_jab').hide();
  });
  //Mutasi Karyawan
  $('.type7').click(function() {
      $('#niklo').hide();
      $('#nikinput').prop('required',false);
      $('#statuskep').prop('disabled',true);
      $('#pangkat').prop('disabled',true);
      $('#jabatan').prop('disabled',true);
      $('#golongan').prop('disabled',true);
      $('#golongan_out').prop('disabled',true);
      $('#tk').hide();
      $('#tak').hide();
      $('#golongan1').hide();
      $('#golongan4').show();
      $('#gaji').hide();
      $('#tunj_tmr').hide();
      $('#tunj_jab').hide();
  });


  $('#pangkat').change(function(){
        if($(this).val() == '2' || $(this).val() == '3'){       // direktur
          $('#jabatan').prop('disabled',true);
          $('#divisi').prop('disabled',true);
          $('#subdivisi').prop('disabled',true);
        } else if($(this).val() == '6' || $(this).val() == '7'){ // svp dan vp
          $('#jabatan').prop('disabled',true);
          $('#divisi').prop('disabled',false);
          $('#subdivisi').prop('disabled',false);
        } else if($(this).val() == '5'){                        // avp
          $('#jabatan').prop('disabled',true);
          $('#divisi').prop('disabled',false);
          $('#subdivisi').prop('disabled',true);
        }
      });

  // $("#fmutasi").submit(function(){
  //    $('#pangkat').change(function(){
  //       if($(this).val() == '2' || $(this).val() == '3'){       // direktur
  //         $('#jabatan').prop('disabled',false);
  //         $('#jabatan').val('-');
  //         $('#divisi').prop('disabled',false);
  //         $('#divisi').val('-');
  //         $('#subdivisi').prop('disabled',false);
  //         $('#subdivisi').val('-');
  //       } else if($(this).val() == '6' || $(this).val() == '7'){ // svp dan vp
  //         $('#jabatan').prop('disabled',false);
  //         $('#jabatan').val('-');
  //         $('#divisi').prop('disabled',false);
  //         $('#subdivisi').prop('disabled',false);
  //       } else if($(this).val() == '5'){                        // avp
  //         $('#jabatan').prop('disabled',false);
  //         $('#jabatan').val('-');
  //         $('#divisi').prop('disabled',false);
  //         $('#subdivisi').prop('disabled',false);
  //         $('#subdivisi').val('-');
  //       }
  //     });
  //     // $("#YourInputId").prop('disabled', 'false');
  // });


});
</script>

@endsection
