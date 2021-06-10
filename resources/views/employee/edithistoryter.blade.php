@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit History Jabatan
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">History Jabatan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
            <div class="box-header with-border">
              <h3 class="box-title">Edit History Jabatan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('updatehistoryter') }}" method="POST">
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label>NIK</label><br>
                  <input type="hidden" class="form-control"  name="id" value="{{ $tampilhistory->id }}" placeholder="NIK">
                  <input type="text" class="form-control"  name="nik_baru" value="{{ $tampilhistory->nik }}" placeholder="NIK" readonly>
                </div>
                 <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="Nama" value="{{ $tampilhistory->nama }}" placeholder="Nama" readonly>
                </div>

                <div class="form-group">
                  <label>Pilih Golongan</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="radiogol" id="radiogol1" value="1" @if($tampilhistory->statuskar == '5' OR $tampilhistory->statuskar == '6') checked="true" @endif>
                      Golongan Outsource
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="radiogol" id="radiogol2" value="2" @if($tampilhistory->statuskar != '5' OR $tampilhistory->statuskar != '6') checked="true" @endif>
                      Golongan Organik
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label>NIK Lama</label>
                  <label><i>*Isi dengan NIK saat ini, jika sama dengan NIK saat ini</i></label>
                  <input type="text" class="form-control" name="nik_lama" value="{{ $tampilhistory->nik_lama }}" placeholder="NIK LAMA">
                </div>
                
                <div class="form-group">
                  <label>Tanggal SK</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_sk" value="{{ $tampilhistory->tanggal1 }}" placeholder="YYYY-MMM-DDD" required>
                </div>
                <div class="form-group">
                  <label>Tanggal TMT</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_tmt" value="{{ $tampilhistory->tanggal2 }}" placeholder="YYYY-MMM-DDD" required>
                </div>

                <div class="form-group">
                  <label>Tanggal Kontrak</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_kontrak" value="{{ $tampilhistory->tgl_kontrak }}" placeholder="YYYY-MMM-DDD">
                </div>
                <div class="form-group">
                  <label>Tanggal Akhir Kontrak</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_akhir_kontrak" value="{{ $tampilhistory->tgl_akhir_kontrak }}" placeholder="YYYY-MMM-DDD">
                </div>

                <div class="form-group">
                  <label>Status Kepegawaian</label>
                  <select class="form-control" name="statuskar" required>
                    @foreach($statuskar as $statuskars)
                    <option value="{{$statuskars->id}}" @if($tampilhistory->statuskar == $statuskars->id) selected @endif>{{$statuskars->status_kar}}</option>
                    @endforeach
                  </select>
                </div>
                
                @if($tampilhistory->typepangkat == 'his')
                <div class="form-group">
                  <label>Pangkat</label><br>
                  <div class="col-xs-4">
                   <select class="form-control" name="idpangkat" id="droppangkat" disabled>
                   <option value="">--Pilih Pangkat--</option>
                      @foreach($pangkat as $pangkats)
                    <option value="{{$pangkats->id}}" @if($tampilhistory->pangkat == $pangkats->id) selected @endif>{{$pangkats->pangkat}}</option>
                    @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain1" id="lainnyapangkat" value="lainpangkat" checked>
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="pangkatbaru" value="{{ $tampilhistory->nama_pangkat }}" id="pangkatlain" placeholder="Pangkat Lainnya"  required>
                      </div> 
                  </div><br>
                </div>
                @endif
                <br>
                @if($tampilhistory->typepangkat == null)
                <div class="form-group">
                  <label>Pangkat</label><br>
                  <div class="col-xs-4">
                   <select class="form-control" name="idpangkat" id="droppangkat">
                   <option value="">--Pilih Pangkat--</option>
                      @foreach($pangkat as $pangkats)
                    <option value="{{$pangkats->id}}" @if($tampilhistory->pangkat == $pangkats->id) selected @endif>{{$pangkats->pangkat}}</option>
                    @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain1" id="lainnyapangkat" value="lainpangkat">
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="pangkatbaru" value="" id="pangkatlain" placeholder="Pangkat Lainnya" disabled  required>
                      </div> 
                  </div><br>
                </div>
                @endif
                <br>

                @if($tampilhistory->typejabatan == 'his')
                <div class="form-group">
                  <label>Jabatan</label><br>
                  <div class="col-xs-4">
                   <select class="form-control" name="idjabatan" id="dropjabatan" disabled>
                   <option value="">--Pilih Jabatan--</option>
                      @foreach($jabatan as $jabatans)
                    <option value="{{$jabatans->id}}" @if($tampilhistory->jabatanid == $jabatans->id) selected @endif>{{$jabatans->jabatan}}</option>
                    @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain2" id="lainnyajabatan" value="lainjabatan" checked>
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="jabatanbaru" value="{{ $tampilhistory->nama_jabatan }}" id="jabatanlain" placeholder="Jabatan Lainnya"  required>
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif


                @if($tampilhistory->typejabatan == null)
                <div class="form-group">
                  <label>Jabatan</label><br>
                  <div class="col-xs-4">
                   <select class="form-control" name="idjabatan" id="dropjabatan">
                   <option value="">--Pilih Jabatan--</option>
                      @foreach($jabatan as $jabatans)
                    <option value="{{$jabatans->id}}" @if($tampilhistory->jabatanid == $jabatans->id) selected @endif>{{$jabatans->jabatan}}</option>
                    @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain2" id="lainnyajabatan" value="lainjabatan">
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="jabatanbaru" value="" id="jabatanlain" placeholder="Jabatan Lainnya" disabled required>
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif

                <div id="gb">

                @if($tampilhistory->typegolongan == 'his')
                  <div class="form-group">
                  <label>Golongan</label><br>
                  <div class="col-xs-4">
                   <select class="form-control" name="Golongan" id="dropgolongan" disabled>
                      <option value="">--Pilih Golongan--</option>
                      @foreach($golongan as $golongans )
                      <option value="{{$golongans->id}}" @if($tampilhistory->golonganid == $golongans->id) selected @endif>{{$golongans->gol}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain3" id="lainnyagolongan" value="laingolongan" checked>
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="golonganbaru" value="{{ $tampilhistory->nama_golongan }}" id="golonganlain" placeholder="Golongan Lainnya" required >
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif

                @if($tampilhistory->typegolongan == null)
                  <div class="form-group">
                  <label>Golongan</label><br>
                  <div class="col-xs-4">
                   <select class="form-control" name="Golongan" id="dropgolongan">
                      <option value="">--Pilih Golongan--</option>
                      @foreach($golongan as $golongans )
                      <option value="{{$golongans->id}}" @if($tampilhistory->golonganid == $golongans->id) selected @endif>{{$golongans->gol}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain3" id="lainnyagolongan" value="laingolongan">
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="golonganbaru" value="" id="golonganlain" placeholder="Golongan Lainnya" disabled required >
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif
                  
                </div>

                <div id="go">

                  @if($tampilhistory->typegolonganout == 'his')
                  <div class="form-group">
                  <label>Golongan OutSource</label><br>
                  <div class="col-xs-4">
                    <select class="form-control" name="Golongan_out" id="dropgolonganout" disabled>
                      <option value="">--Pilih Golongan Outsource--</option>
                      @foreach($golonganout as $golonganouts)
                      <option value="{{$golonganouts->id}}" @if($tampilhistory->golonganoutid == $golonganouts->id) selected @endif>{{$golonganouts->gol}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain4" id="lainnyagolonganout" value="laingolonganout" checked>
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="golonganoutbaru" value="{{ $tampilhistory->nama_golonganout }}" id="golonganoutlain" placeholder="Golongan Out Lainnya" required >
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif

                @if($tampilhistory->typegolonganout == null)
                  <div class="form-group">
                  <label>Golongan OutSource </label><br>
                  <div class="col-xs-4">
                    <select class="form-control" name="Golongan_out" id="dropgolonganout">
                      <option value="">--Pilih Golongan Outsource--</option>
                      @foreach($golonganout as $golonganouts)
                      <option value="{{$golonganouts->id}}" @if($tampilhistory->golonganoutid == $golonganouts->id) selected @endif>{{$golonganouts->gol}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain4" id="lainnyagolonganout" value="laingolonganout">
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="golonganoutbaru" value="" id="golonganoutlain" placeholder="Golongan Out Lainnya" disabled required >
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif
              </div>

              @if($tampilhistory->typedivisi == 'his')
              <div class="form-group">
                  <label>Divisi</label><br>
                  <div class="col-xs-4">
                    <select class="form-control" name="Divisi" id="dropdivisi" disabled>
                    <option value="">--Pilih Divisi--</option>
                    @foreach($divisi as $divisis)
                    <option value="{{$divisis->id}}" @if($tampilhistory->divisi == $divisis->id) selected @endif>{{$divisis->nama_div_ext}}</option>
                    @endforeach
                  </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain5" id="lainnyadivisi" value="laindivisi" checked>
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="divisibaru" value="{{ $tampilhistory->nama_divisi }}" id="divisilain" placeholder="Divisi Lainnya"  required>
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif

              @if($tampilhistory->typedivisi == null)
              <div class="form-group">
                  <label>Divisi</label><br>
                  <div class="col-xs-4">
                    <select class="form-control" name="Divisi" id="dropdivisi">
                    <option value="">--Pilih Divisi--</option>
                    @foreach($divisi as $divisis)
                    <option value="{{$divisis->id}}" @if($tampilhistory->divisi == $divisis->id) selected @endif>{{$divisis->nama_div_ext}}</option>
                    @endforeach
                  </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain5" id="lainnyadivisi" value="laindivisi">
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="divisibaru" value="" id="divisilain" placeholder="Divisi Lainnya"  disabled required>
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif
                
               @if($tampilhistory->typesubdivisi == 'his')
                <div class="form-group">
                  <label>Sub Divisi</label><br>
                  <div class="col-xs-4">
                      <select class="form-control" name="SubDivisi" id="dropsubdivisi" disabled>
                        <option value="">--Pilih SubDivisi--</option>
                        @foreach($subdivisi as $subdivisis)
                        <option value="{{$subdivisis->id}}" @if($tampilhistory->subdivisi == $subdivisis->id) selected @endif>{{$subdivisis->subdivisi}}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain6" id="lainnyasubdivisi" value="lainsubdivisi" checked>
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="subdivisibaru" value="{{ $tampilhistory->nama_subdivisi }}" id="subdivisilain" placeholder="SubDivisi Lainnya" required>
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif

                @if($tampilhistory->typesubdivisi == null)
                <div class="form-group">
                  <label>Sub Divisi</label><br>
                  <div class="col-xs-4">
                      <select class="form-control" name="SubDivisi" id="dropsubdivisi">
                        <option value="">--Pilih SubDivisi--</option>
                        @foreach($subdivisi as $subdivisis)
                        <option value="{{$subdivisis->id}}" @if($tampilhistory->subdivisi == $subdivisis->id) selected @endif>{{$subdivisis->subdivisi}}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="col-xs-2">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="radiolain6" id="lainnyasubdivisi" value="lainsubdivisi">
                          Lainnya
                        </label>
                      </div>
                  </div>
                  <div class="col-xs-2">
                     <div class="form-group">
                        <input type="text" class="form-control" name="subdivisibaru" value="" id="subdivisilain" placeholder="SubDivisi Lainnya" disabled required>
                      </div> 
                  </div><br>
                </div>
                <br>
                @endif
                
                <br>

                <div class="form-group">
                  <label>Atasan 1 (Langsung)</label>
                  <select class="form-control select2" style="width: 100%;" name="atasan1" >
                    <option value="">--Pilih Atasan1(Langsung)--</option>
                    @foreach($atasan1 as $atasan1s)
                    <option value="{{$atasan1s->nik}}" @if($tampilhistory->atasan1 == $atasan1s->nik) selected @endif>{{$atasan1s->atasan}}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                  <label>Atasan 2</label>
                  <select class="form-control select2" style="width: 100%;" name="atasan2">
                    <option value="">---Pilih Atasan2--</option>
                    @foreach($atasan2 as $atasan2s)
                    <option value="{{$atasan2s->nik}}" @if($tampilhistory->atasan2 == $atasan2s->nik) selected @endif>{{$atasan2s->atasan}}</option>
                    @endforeach
                </select>
                </div>
                
                <div class="form-group">
                  <label>Lokasi Kerja</label>
                  <select class="form-control" name="LokasiKer">
                    @foreach($lokasikerja as $lokasikerjas)
                    <option value="{{$lokasikerjas->id}}" @if($tampilhistory->lokasi == $lokasikerjas->id) selected @endif>{{$lokasikerjas->lokasi}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Gaji</label>
                  <input type="text" class="form-control" name="gaji" value="{{$gaji}}" placeholder="Gaji">
                </div>

                <div class="form-group">
                  <label>Tunjangan TMR</label>
                  <input type="text" class="form-control" name="tunj_tmr" value="{{$tunj_tmr}}" placeholder="Tunjangan TMR">
                </div>

                <div class="form-group">
                  <label>Tunjangan Jabatan</label>
                  <input type="text" class="form-control" name="tunj_jab" value="{{$tunj_jab}}" placeholder="Tunjangan Jabatan">
                </div>

                <div class="form-group">
                  <label>Nomor SK</label>
                  <input type="text" class="form-control" name="no_sk" value="{{$tampilhistory->no_sk}}" placeholder="Nomor SK">
                </div>
              
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" value="reset" class="btn btn-danger">Reset</button>
              </div>
            </form>

          
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

@section('jqhistory')
<script type="text/javascript">
$(document).ready(function () {

  $('#radiogol1').click(function() {
      $('#go').show();
      $('#gb').hide();
      
  });
  $('#radiogol2').click(function() {
     $('#go').hide();
      $('#gb').show();
      
  });



  $('#lainnyapangkat').click(function() {
        if($(this).is(':checked')) {
            // alert('pangkat checked');
          $('#pangkatlain').prop('disabled',false);
          $('#droppangkat').prop('disabled',true);
        }          

        else {
          $('#pangkatlain').prop('disabled',true);
          $('#droppangkat').prop('disabled',false);
        }
          
    });

    $('#lainnyajabatan').click(function() {
        if($(this).is(':checked')) {
            // alert('pangkat checked');
          $('#jabatanlain').prop('disabled',false);
          $('#dropjabatan').prop('disabled',true);
        }          

        else {
          $('#jabatanlain').prop('disabled',true);
          $('#dropjabatan').prop('disabled',false);
        }
          
    });

    $('#lainnyagolongan').click(function() {
        if($(this).is(':checked')) {
            // alert('pangkat checked');
          $('#golonganlain').prop('disabled',false);
          $('#dropgolongan').prop('disabled',true);
        }          

        else {
          $('#golonganlain').prop('disabled',true);
          $('#dropgolongan').prop('disabled',false);
        }
          
    });

    $('#lainnyagolonganout').click(function() {
        if($(this).is(':checked')) {
            // alert('pangkat checked');
          $('#golonganoutlain').prop('disabled',false);
          $('#dropgolonganout').prop('disabled',true);
        }          

        else {
          $('#golonganoutlain').prop('disabled',true);
          $('#dropgolonganout').prop('disabled',false);
        }
          
    });

    $('#lainnyadivisi').click(function() {
        if($(this).is(':checked')) {
            // alert('pangkat checked');
          $('#divisilain').prop('disabled',false);
          $('#dropdivisi').prop('disabled',true);
        }          

        else {
          $('#divisilain').prop('disabled',true);
          $('#dropdivisi').prop('disabled',false);
        }
          
    });

    $('#lainnyasubdivisi').click(function() {
        if($(this).is(':checked')) {
            // alert('pangkat checked');
          $('#subdivisilain').prop('disabled',false);
          $('#dropsubdivisi').prop('disabled',true);
        }          

        else {
          $('#subdivisilain').prop('disabled',true);
          $('#dropsubdivisi').prop('disabled',false);
        }
          
    });

  // $('#lainnyapangkat').click(function() {
  //    $('#pangkatlain').prop('disabled',false);
  //    $('#droppangkat').prop('disabled',true);
      
  // });

  // $('#lainnyajabatan').click(function() {
  //    $('#jabatanlain').prop('disabled',false);
  //    $('#dropjabatan').prop('disabled',true);
      
  // });

  // $('#lainnyagolongan').click(function() {
  //    $('#golonganlain').prop('disabled',false);
  //    $('#dropgolongan').prop('disabled',true);
      
  // });

  // $('#lainnyagolonganout').click(function() {
  //    $('#golonganoutlain').prop('disabled',false);
  //    $('#dropgolonganout').prop('disabled',true);
      
  // });

  // $('#lainnyadivisi').click(function() {
  //    $('#divisilain').prop('disabled',false);
  //    $('#dropdivisi').prop('disabled',true);
      
  // });

  // $('#lainnyasubdivisi').click(function() {
  //    $('#subdivisilain').prop('disabled',false);
  //    $('#dropsubdivisi').prop('disabled',true);
      
  // });

});
</script>

@endsection