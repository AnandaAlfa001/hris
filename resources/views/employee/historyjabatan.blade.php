@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        History Jabatan
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">History Jabatan</li>
      </ol>
    </section>
    @include('layouts.function')

    <!-- Main content -->
    <section class="content">
    <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">History Jabatan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('savehistory') }}" method="POST">
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label>NIK</label><br>
                  
                  <input type="text" class="form-control"  name="nik_baru" value="{{ $historyquery->nik }}" placeholder="NIK" readonly>
                </div>
                 <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="Nama" value="{{ $historyquery->nama }}" placeholder="Nama" readonly>
                </div>

                <div class="form-group">
                  <label>Pilih Golongan</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="radiogol" id="radiogol1" value="1">
                      Golongan Outsource
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="radiogol" id="radiogol2" value="2">
                      Golongan Biasa
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label>NIK Lama</label>
                  <label><i>*Isi dengan NIK saat ini, jika sama dengan NIK saat ini</i></label>
                  <input type="text" class="form-control" name="nik_lama" value="" placeholder="NIK LAMA">
                </div>
                
                <div class="form-group">
                  <label>Tanggal SK</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_sk" value="" placeholder="Tanggal SK" required>
                </div>
                <div class="form-group">
                  <label>Tanggal TMT</label>
                  <input type="date" data-date-split-input="true" class="form-control" name="tgl_tmt" value="" placeholder="tanggal TMT" required>
                </div>
                <div class="form-group">
                  <label>Status Kepegawaian</label>
                  <select class="form-control select2" style="width: 100%;" name="statuskar" required>
                    @foreach($statuskar as $statuskars)
                    
                    <option value="{{$statuskars->id}}">{{$statuskars->status_kar}}</option>
                    @endforeach
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Pangkat</label><br>
                  <div class="col-xs-4">
                   <select class="form-control select2" style="width: 100%;" name="idpangkat" id="droppangkat">
                   <option value="">--Pilih Pangkat--</option>
                      @foreach($pangkat as $pangkats)
                      <option value="{{$pangkats->id}}">{{$pangkats->pangkat}}</option>
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
                        <input type="text" class="form-control" name="pangkatbaru" value="" id="pangkatlain" placeholder="Pangkat Lainnya" disabled required>
                      </div> 
                  </div><br>
                </div>
                <br>

                <div class="form-group">
                  <label>Jabatan</label><br>
                  <div class="col-xs-4">
                   <select class="form-control select2" style="width: 100%;" name="idjabatan" id="dropjabatan">
                   <option value="">--Pilih Jabatan--</option>
                      @foreach($jabatan as $jabatans)
                      <option value="{{$jabatans->id}}">{{$jabatans->jabatan}}</option>
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
                        <input type="text" class="form-control" name="jabatanbaru" id="jabatanlain" placeholder="Jabatan Lainnya" disabled required>
                      </div> 
                  </div><br>
                </div>
                <br>

                <div id="gb">
                  <div class="form-group">
                  <label>Golongan</label><br>
                  <div class="col-xs-4">
                   <select class="form-control select2" style="width: 100%;" name="Golongan" id="dropgolongan">
                      <option value="">--Pilih Golongan--</option>
                      @foreach($golongan as $golongans )
                      <option value="{{$golongans->id}}">{{$golongans->gol}}</option>
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
                        <input type="text" class="form-control" name="golonganbaru" value="" id="golonganlain" placeholder="Golongan Lainnya" disabled >
                      </div> 
                  </div><br>
                </div>
                <br>
                  
                </div>

                <div id="go">
                  <div class="form-group">
                  <label>Golongan OutSource</label><br>
                  <div class="col-xs-4">
                    <select class="form-control select2" style="width: 100%;" name="Golongan_out" id="dropgolonganout">
                      <option value="">--Pilih Golongan Outsource--</option>
                      @foreach($golonganout as $golonganouts)
                      <option value="{{$golonganouts->id}}">{{$golonganouts->gol}}</option>
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
                        <input type="text" class="form-control" name="golonganoutbaru" value="" id="golonganoutlain" placeholder="Golongan Out Lainnya" disabled >
                      </div> 
                  </div><br>
                </div>
                <br>
              </div>

              <div class="form-group">
                  <label>Divisi</label><br>
                  <div class="col-xs-4">
                    <select class="form-control select2" style="width: 100%;" name="Divisi" id="dropdivisi">
                    @foreach($divisi as $divisis)
                    <option value="{{$divisis->id}}">{{$divisis->nama_div_ext}}</option>
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
                        <input type="text" class="form-control" name="divisibaru" value="" id="divisilain" placeholder="Divisi Lainnya" disabled required>
                      </div> 
                  </div><br>
                </div>
                <br>
                
               
                <div class="form-group">
                  <label>Sub Divisi</label><br>
                  <div class="col-xs-4">
                      <select class="form-control select2" style="width: 100%;" name="SubDivisi" id="dropsubdivisi">
                        @foreach($subdivisi as $subdivisis)
                        <option value="{{$subdivisis->id}}">{{$subdivisis->subdivisi}}</option>
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
                

                <div class="form-group">
                  <label>Atasan 1 (Langsung)</label>
                  <select class="form-control select2" style="width: 100%;" name="atasan1" >
                    <option value="">--Pilih Atasan1(Langsung)--</option>
                    @foreach($atasan1 as $atasan1s)
                    <option value="{{$atasan1s->nik}}">{{$atasan1s->atasan}}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                  <label>Atasan 2</label>
                  <select class="form-control select2" style="width: 100%;" name="atasan2">
                    <option value="">---Pilih Atasan2--</option>
                    @foreach($atasan2 as $atasan2s)
                    <option value="{{$atasan2s->nik}}">{{$atasan2s->atasan}}</option>
                    @endforeach
                </select>
                </div>
                
                <div class="form-group">
                  <label>Lokasi Kerja</label>
                  <select class="form-control select2" style="width: 100%;" name="LokasiKer">
                    @foreach($lokasikerja as $lokasikerjas)
                    <option value="{{$lokasikerjas->id}}">{{$lokasikerjas->lokasi}}</option>
                    @endforeach
                  </select>
                </div>

                
                <div class="form-group">
                  <label>Gaji</label>
                  <input type="text" class="form-control" id="gaji1" name="gaji1" value="" placeholder="Gaji">
                  <input type="hidden" class="form-control" id="gaji2" name="gaji2" value="" placeholder="Gaji">
                </div>

                <div class="form-group">
                  <label>Tunjangan TMR</label>
                  <input type="text" class="form-control" id="tunj_tmr1" name="tunj_tmr1" value="" placeholder="Tunjangan TMR">
                  <input type="hidden" class="form-control" id="tunj_tmr2" name="tunj_tmr2" value="" placeholder="Tunjangan TMR">
                </div>

                <div class="form-group">
                  <label>Tunjangan Jabatan</label>
                  <input type="text" class="form-control" id="tunj_jab1" name="tunj_jab1" value="" placeholder="Tunjangan Jabatan">
                  <input type="hidden" class="form-control" id="tunj_jab2" name="tunj_jab2" value="" placeholder="Tunjangan Jabatan">
                </div>

                <div class="form-group">
                  <label>Nomor SK</label>
                  <input type="text" class="form-control" name="no_sk" value="" placeholder="Nomor SK" required>                
                </div>


              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" value="reset" class="btn btn-danger">Reset</button>
              </div>
            </form>
            <div class="box">
            <div class="box-header">
              <h3 class="box-title">TABLE HISTORY JABATAN</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>JABATAN</th>
                  <th>NO. SK</th>
                  <th>TANGGAL SK</th>
                  <th>TANGGAL TMT</th>                  
                  <th>DIVISI</th>
                  <th>LOKASI</th>
                  <th>NIK LAMA</th>
                  <th>GAJI</th>
                  <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $a=0; 
                $array = 0;
                ?>
                @foreach($tablehistory as $tablehistorys)
                <?php $a++; ?>
                <tr>
                  <td>{{ $a }}</td>
                  <td>{{ $tablehistorys->jabatan }}</td>
                  <td>{{ $tablehistorys->no_sk }}</td>
                  <!-- date('d F Y', strtotime($tanggal )) -->
                  <td>{{ myDate($tablehistorys->tgl_sk_jab) }}</td>
                  <td>{{ myDate($tablehistorys->tgl_sk_gol) }}</td>                  
                  <td>{{ $tablehistorys->divisi }}</td>
                  <td>{{ $tablehistorys->lokasi }} </td>
                  <td>{{ $tablehistorys->nik_lama }} </td>
                  <td>
                  Rp. {{ $decgaji[$array]['gaji'] }}
                  </td>
                   <td>
                    <div class="btn-group">
                      @if($a == 1)
                      <a href="{{ url('edithistoryter', $tablehistorys->id) }}"><button type="button" class="btn btn-warning">Edit</button></a>
                      @else
                      <a href="{{ url('edithistory', $tablehistorys->id) }}"><button type="button" class="btn btn-warning">Edit</button></a>
                      @endif
                      <a href="{{ url('deletehistory', $tablehistorys->id) }}" onclick="return confirm('Yakin mau hapus data ini ?')"><button type="button" class="btn btn-danger" href="">Delete</button>
                    </div>
                  </td> 
                </tr>
                <?php $array++; ?>
                @endforeach  

                </tbody>
                
              </table>
            </div>
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