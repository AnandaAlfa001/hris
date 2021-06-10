@extends('layouts.index')
@section('content')
@include('layouts.function')

<script type="text/javascript">
  var base_url = window.location.origin
  function modal($this) {
      var data = $this.id;
      var link = '{{ asset('image/Sertifikat/') }}';
      var link2 = '{{ asset('image/') }}';
      var link3 = 'downloadtrain';
      var link4 = link3+'/'+data;
      
      console.log(base_url);
      // var url 
    
    var token = '{{Session::token()}}';
    var url = '{{ url('previewimgtrain') }}';
    var out = null;
    document.getElementById("pic").innerHTML = null;
    console.log("data: " + data);
    $.ajax({
      method: 'POST',
      url : url,
      data : { data : data , _token : token },
    }).done(function (msg) {
      console.log(msg['out']);
      out = msg['out'];
      if(out == '' || out == null)
      {
        out = null;
        document.getElementById("pic").innerHTML = '<img src="'+link2+'/notfound.png" class="img-bordered-sm" width="400" height="400">';
      } else {
        out.forEach(function(entry) {
            document.getElementById("pic").innerHTML += '<img src="'+link+'/'+entry+'" class="img-bordered-sm" width="400" height="400">';
            $('#down').attr('href',base_url+'/'+link4);
      });
      }

      $('#bukti').modal();
    });
  }

  function modal2($this) {
      var data = $this.id;
      var link = '{{ asset('image/SuratPeringatan/') }}';
      var link2 = '{{ asset('image/') }}';
      var link3 = 'downloadsp';
      var link4 = link3+'/'+data;
      // var base_url = window.location.origin
      console.log(link4);
    
    var token = '{{Session::token()}}';
    var url = '{{ url('previewimgsp') }}';
    var out = null;
    document.getElementById("pic2").innerHTML = null;
    console.log("data: " + data);
    $.ajax({
      method: 'POST',
      url : url,
      data : { data : data , _token : token },
    }).done(function (msg) {
      console.log(msg['out']);
      out = msg['out'];
      if(out == '' || out == null)
      {
        out = null;
        document.getElementById("pic2").innerHTML = '<img src="'+link2+'/notfound.png" class="img-bordered-sm" width="400" height="400">';
      } else {
        out.forEach(function(entry) {
            document.getElementById("pic2").innerHTML += '<img src="'+link+'/'+entry+'" class="img-bordered-sm" width="400" height="400">';
            $('#down2').attr('href',base_url+'/'+link4);
      });
      }

      $('#photo_sp').modal();
    });
  }

  function modal3($this) {
      var link = '{{ asset('image/Kesehatan/') }}';
      var link2 = '{{ asset('image/') }}';
    var data = $this.id;
    var token = '{{Session::token()}}';
    var url = '{{ url('buktikw') }}';
    var out = null;
    document.getElementById("pic3").innerHTML = null;
    console.log("data: " + data);

    $.ajax({
      method: 'POST',
      url : url,
      data : { data : data , _token : token },
    }).done(function (msg) {
      console.log(msg['out']);
      out = msg['out'];
      if(out == '' || out == null)
      {
        out = null;
        document.getElementById("pic3").innerHTML = '<img src="'+link2+'/notfound.png" class="img-bordered-sm" width="400" height="400">';
      } else {
        out.forEach(function(entry) {
            document.getElementById("pic3").innerHTML += '<img src="'+link+'/'+entry+'" class="img-bordered-sm" width="400" height="400">';
      });
      }

      $('#buktikes').modal();
    });
  }
</script>

<!--END QUERY -->
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail Karyawan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">Detail Employee</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#detaildata" data-toggle="tab">Detail Data</a></li>
              <li><a href="#historyjab" data-toggle="tab">History Jabatan</a></li>
              <li><a href="#historygaji" data-toggle="tab">History Gaji</a></li>
              <li><a href="#historytraining" data-toggle="tab">History Training</a></li>
              <li><a href="#historysp" data-toggle="tab">History SP</a></li>
              <li><a href="#historylembur" data-toggle="tab">History Lembur</a></li>
              <li><a href="#historycuti" data-toggle="tab">History Cuti</a></li>
              <li><a href="#historyrembursement" data-toggle="tab">History Rembursement</a></li>
              <li><a href="#historyizin" data-toggle="tab">History Izin</a></li>
              <li><a href="#historypd" data-toggle="tab">History Perjalanan Dinas</a></li>
            </ul>
            <div class="tab-content">
              <!-- Font Awesome Icons -->
              <div class="tab-pane active" id="detaildata">

                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                  <div class="input-group col-md-12">
                    <div class="col-md-5">
                      <div class="form-group">
                          @if($data->photo)
                          <img src="{{ asset('image/Photo/'.$data->photo) }}" id="showgambar" class="img-circle img-bordered-sm" style="border-radius: 50%;max-width:400px;max-height:400px;float:left;">
                          @else
                          <img src="http://placehold.it/400?text=not+found" id="showgambar" class="img-circle img-bordered-sm">
                          @endif
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="table-responsive">
                          <table class="table no-border">
                            <tbody>
                            <tr>
                              <td colspan="3"><strong>Data Pribadi</strong></td>
                            </tr>
                            <tr>
                              <td>NIK</td>
                              <td>:</td>
                              <td>{{ $data->NIK}}</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>{{ $data->Nama}}</td>
                            </tr>
                            <tr>
                              <td>Tempat, Tanggal Lahir</td>
                              <td>:</td>
                              <td>{{ $data->TempatLahir}}, {{ TglLahir($data->TanggalLahir) }}</td>
                            </tr>
                            <tr>
                              <td>Jenis Kelamin</td>
                              <td>:</td>
                              <td>
                                @if($data->jk == 'L')
                                  Laki-Laki
                                @elseif($data->jk == 'P')
                                  Perempuan
                                @else
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Agama</td>
                              <td>:</td>
                              <td>{{ $data->namaagamakar}}</td>
                            </tr>
                            <tr>
                              <td>Golongan Darah</td>
                              <td>:</td>
                              <td>{{ $data->gol_darah}}</td>
                            </tr>
                            <tr>
                              <td>Status Pernikahan</td>
                              <td>:</td>
                              <td>
                                @if($data->status_pernikahan == '0')
                                  Belum Menikah
                                @elseif($data->status_pernikahan == '1')
                                  Menikah
                                @elseif($data->status_pernikahan == '2')
                                  Cerai
                                @else

                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td>Alamat Tinggal</td>
                              <td>:</td>
                              <td>{{ $data->Alamat}}</td>
                            </tr>
                            <tr>
                              <td colspan="3"><strong>KTP Karyawan</strong></td>
                            </tr>
                            <tr>
                              <td>No. KTP/SIM</td>
                              <td>:</td>
                              <td>{{ $data->ktp_sim}}</td>
                            </tr>
                            <tr>
                              <td>Alamat KTP</td>
                              <td>:</td>
                              <td>{{ $data->Alamat_KTP}}</td>
                            </tr>
                            <tr>
                              <td>Dokumen KTP</td>
                              <td>:</td>
                              <td>
                                @if($data->file_ktp)
                                  <a href="{{ asset('image/Dokumen/'.$data->file_ktp) }}" target="_blank"><span class="label label-info">Download KTP</span></a>
                                @else
                                  <span class="label label-danger">File KTP Belum di Upload</span>
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3"><strong>NPWP Karyawan</strong></td>
                            </tr>
                            <tr>
                              <td>NPWP</td>
                              <td>:</td>
                              <td>{{ $data->npwp}}</td>
                            </tr>
                            <tr>
                              <td>Nama NPWP</td>
                              <td>:</td>
                              <td>{{ $data->nama_npwp}}</td>
                            </tr>
                            <tr>
                              <td>Alamat NPWP</td>
                              <td>:</td>
                              <td>{{ $data->alamat_npwp}}</td>
                            </tr>
                            <tr>
                              <td>Dokumen NPWP</td>
                              <td>:</td>
                              <td>
                                @if($data->file_npwp)
                                  <a href="{{ asset('image/Dokumen/'.$data->file_npwp) }}" target="_blank"><span class="label label-info">Download NPWP</span></a>
                                @else
                                  <span class="label label-danger">File NPWP Belum di Upload</span>
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3"><strong>Rekening Karyawan</strong></td>
                            </tr>
                            <tr>
                              <td>No. Rekening</td>
                              <td>:</td>
                              <td>{{ $data->norek}}</td>
                            </tr>
                            <tr>
                              <td>Dokumen Buku Tabungan (No. Rek)</td>
                              <td>:</td>
                              <td>
                                @if($data->file_no_rek)
                                  <a href="{{ asset('image/Dokumen/'.$data->file_no_rek) }}" target="_blank"><span class="label label-info">Download Buku Tabungan (No. Rek)</span></a>
                                @else
                                  <span class="label label-danger">File Nomor Rekening Belum di Upload</span>
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3"><strong>Jamsostek Karyawan</strong></td>
                            </tr>
                            <tr>
                              <td>Jamsostek</td>
                              <td>:</td>
                              <td>{{ $data->jamsostek}}</td>
                            </tr>
                            <tr>
                              <td>Dokumen Jamsostek</td>
                              <td>:</td>
                              <td>
                                @if($data->file_jamsostek)
                                  <a href="{{ asset('image/Dokumen/'.$data->file_jamsostek) }}" target="_blank"><span class="label label-info">Download Jamsostek</span></a>
                                @else
                                  <span class="label label-danger">File Jamsostek Belum di Upload</span>
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3"><strong>BPJS Kesehatan Karyawan</strong></td>
                            </tr>
                            <tr>
                              <td>BPJS Kesehatan</td>
                              <td>:</td>
                              <td>{{ $data->bpjs}}</td>
                            </tr>
                            <tr>
                              <td>Dokumen BPJS Kesehatan</td>
                              <td>:</td>
                              <td>
                                @if($data->file_bpjs)
                                  <a href="{{ asset('image/Dokumen/'.$data->file_bpjs) }}" target="_blank"><span class="label label-info">Download BPJS Kesehatan</span></a>
                                @else
                                  <span class="label label-danger">File BPJS Kesehatan Belum di Upload</span>
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3"><strong>Kontak Karyawan</strong></td>
                            </tr>
                            <tr>
                              <td>No. Telp. Rumah</td>
                              <td>:</td>
                              <td>{{ $data->NoTelepon}}</td>
                            </tr>
                            <tr>
                              <td>Handphone</td>
                              <td>:</td>
                              <td>{{ $data->NoHp}}</td>
                            </tr>
                            <tr>
                              <td>e-Mail EDII</td>
                              <td>:</td>
                              <td>{{ $data->email}}</td>
                            </tr>
                            <tr>
                              <td>e-Mail Regular</td>
                              <td>:</td>
                              <td>{{ $data->emailreg}}</td>
                            </tr>
                            @if($data->byproyek == 1 && ($data->statuskar == '5' || $data->statuskar == '6'))
                            <tr>
                              <td>Proyek</td>
                              <td>:</td>
                              <td>{{ $data->proyek}}</td>
                            </tr>
                            @endif
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>  
                    <span><br></span>
                    <div class="input-group col-md-12">
                      <div class="table-responsive">
                        <div class="col-md-5">
                          <table class="table no-border">
                            <tbody>
                            <tr>
                              <td><strong>Nama Bapak</strong></td>
                              <td>:</td>
                              <td>{{ $data->nama_bapak}}</td>
                            </tr>
                            <tr>
                              <td>Tempat, Tanggal Lahir</td>
                              <td>:</td>
                              <td>{{ $data->tmplhr_bapak}}, {{ TglLahir($data->tgllhr_bapak) }}</td>
                            </tr>
                            <tr>
                              <td>Alamat Rumah</td>
                              <td>:</td>
                              <td>{{ $data->alamat_bapak}}</td>
                            </tr>
                            <tr>
                              <td>Pendidikan Terakhir</td>
                              <td>:</td>
                              <td>{{ $data->pnddk_bapak}}</td>
                            </tr>
                            <tr>
                              <td>Agama</td>
                              <td>:</td>
                              <td>
                                @foreach($agama as $agamas)
                                @if ($data->agama_bapak == $agamas->idagama)
                                {{ $agamas->nama_agama }}
                                @endif
                                @endforeach
                              </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                              <td><strong>Nama Ibu</strong></td>
                              <td>:</td>
                              <td>{{ $data->nama_ibu}}</td>
                            </tr>
                            <tr>
                              <td>Tempat, Tanggal Lahir</td>
                              <td>:</td>
                              <td>{{ $data->tmplhr_ibu}}, {{ TglLahir($data->tgllhr_ibu) }}</td>
                            </tr>
                            <tr>
                              <td>Alamat Rumah</td>
                              <td>:</td>
                              <td>{{ $data->alamat_ibu}}</td>
                            </tr>
                            <tr>
                              <td>Pendidikan Terakhir</td>
                              <td>:</td>
                              <td>{{ $data->pnddk_ibu}}</td>
                            </tr>
                            <tr>
                              <td>Agama</td>
                              <td>:</td>
                              <td>
                                @foreach($agama as $agamas)
                                @if ($data->agama_ibu == $agamas->idagama)
                                {{ $agamas->nama_agama }}
                                @endif
                                @endforeach
                              </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                              <td><strong>Nama Istri / Suami</strong></td>
                              <td>:</td>
                              <td>{{ $data->NamaPasangan}}</td>
                            </tr>
                            <tr>
                              <td>Tempat, Tanggal Lahir</td>
                              <td>:</td>
                              <td>{{ $data->TmpLahirPasangan}}, {{ TglLahir($data->TanggalLahirPasangan) }}</td>
                            </tr>
                            <tr>
                              <td>Pendidikan Terakhir</td>
                              <td>:</td>
                              <td>{{ $data->pndkpasangan}}</td>
                            </tr>
                            <tr>
                              <td>Agama</td>
                              <td>:</td>
                              <td>
                                @foreach($agama as $agamas)
                                @if ($data->agamapasangan == $agamas->idagama)
                                {{ $agamas->nama_agama }}
                                @endif
                                @endforeach
                              </td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Anak</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>Nama Anak</th>
                              <th>Tgl. Lahir</th>
                              <th>Jenis Kelamin</th>
                              <th>Pendidikan Terakhir</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($anak as $anaks)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $anaks-> NamaAnak}}</td>
                              <td>{{ TglLahir($anaks-> TanggalAnak) }}</td>
                              <td>@if( $anaks-> jk==1) Laki - Laki @else Perempuan @endif</td>
                              <td>{{ $anaks-> didikan}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Riwayat pekerjaan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example3" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>Nama Perusahaan</th>
                              <th>Jabatan</th>
                              <th>Divisi</th>
                              <th>Tanggal Keluar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($ripek as $ripeks)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $ripeks-> NamaPerusahaan}}</td>
                              <td>{{ $ripeks-> Jabatansblm}}</td>
                              <td>{{ $ripeks-> Divsblm}}</td>
                              <td>{{ myDate($ripeks-> TanggalKeluarsblm) }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Pendidikan Formal</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example4" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>Jenjang</th>
                              <th>Nama Sekolah</th>
                              <th>Jurusan</th>
                              <th>Th. Masuk</th>
                              <th>Th. Lulus</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($ripen as $ripens)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $ripens-> jenjang}}</td>
                              <td>{{ $ripens-> Sekolah_Institut}}</td>
                              <td>{{ $ripens-> Jurusan}}</td>
                              <td>{{ $ripens-> PeriodeIn}}</td>
                              <td>{{ $ripens-> PeriodeOut}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Pendidikan Non-Formal</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example5" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>Nama Kursus/Seminar</th>
                              <th>Bidang Keahlian</th>
                              <th>Tahun</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($non as $nons)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $nons-> nama_kursus}}</td>
                              <td>{{ $nons-> keahlian}}</td>
                              <td>{{ $nons-> thikut}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Riwayat Organisasi</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example6" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>Nama Organisasi</th>
                              <th>Kedudukan</th>
                              <th>Tahun</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($kegiatan as $kegiatans)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $kegiatans-> nama_organisasi}}</td>
                              <td>{{ $kegiatans-> kedudukan}}</td>
                              <td>{{ $kegiatans-> th_gabung}} - {{ $kegiatans-> th_berhenti}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Orang Terdekat</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Nama</th>
                              <th>Status</th>
                              <th>Telp</th>
                              <th>Alamat</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($orangter as $orangters)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $orangters->nama}}</td>
                              <td>{{ $orangters->status}}</td>
                              <td>{{ $orangters->no_telp}}</td>
                              <td>{{ $orangters->alamat}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp; Data Riwayat Penyakit</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Nama Penyakit</th>
                              <th>Tahun</th>
                              <th>Dirawat</th>
                              <th>Lama Rawat</th>
                              <th>Cacat</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($riwayatpenyakit as $riwayatpenyakits)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $riwayatpenyakits->nama_penyakit}}</td>
                              <td>{{ $riwayatpenyakits->tahun}}</td>
                              <td>
                                @if($riwayatpenyakits->dirawat == 1)
                                  <span class="label label-success">Dirawat</span>
                                @else
                                  <span class="label label-danger">Tidak Dirawat</span>
                                @endif
                              </td>
                              <td>{{ $riwayatpenyakits->lama_rawat}}</td>
                              <td>{{ $riwayatpenyakits->cacat}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <span><br></span>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->

              </div>
              <!-- glyphicons-->
              <div class="tab-pane" id="historyjab">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Jabatan Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example7" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>JABATAN</th>
                              <th>GOLONGAN</th>
                              <th>NO. SK</th>
                              <th>TGL. SK. </th>
                              <th>TGL. TMT. </th>
                              <th>STATUS</th>
                              <th>DIVISI</th>
                              <th>LOKASI</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $a=0; ?>
                            @foreach($historyjab as $historyjabs)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $historyjabs-> Jabatan}}</td>
                              <td>{{ $historyjabs-> Gol}}</td>
                              <td>{{ $historyjabs-> no_sk}}</td>
                              <td>{{ myDate($historyjabs-> tgl_sk_jab) }}</td>
                              <td>{{ myDate($historyjabs-> tgl_sk_gol) }}</td>
                              <td>{{ $historyjabs-> statuskar}}</td>
                              <td>{{ $historyjabs-> divisi}}</td>
                              <td>{{ $historyjabs-> lokasi}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>
              <!-- /#ion-icons -->

              <div class="tab-pane" id="historygaji">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Gaji Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example8" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>JABATAN</th>
                              <th>GOLONGAN</th>
                              <th>DIVISI</th>
                              <th>STATUS KARYAWAN</th>
                              <th>GAJI</th>
                              <th>TUNJANGAN TMR</th>
                              <th>TUNJANGAN JABATAN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            $array = 0;
                            ?>
                            @foreach($historyjab as $historyjabs)
                            <?php $a++; ?>
                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $historyjabs-> Jabatan}}</td>
                              <td>{{ $historyjabs-> Gol}}</td>
                              <td>{{ $historyjabs-> divisi}}</td>
                              <td>{{ $historyjabs-> statuskar}}</td>
                              <td>Rp. {{ $arraygaji[$array]['gaji'] }}</td>
                              <td>Rp. {{ $arraygaji[$array]['tunj_tmr'] }}</td>
                              <td>Rp. {{ $arraygaji[$array]['tunj_jab'] }}</td>
                            </tr>
                            <?php $array++; ?>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historytraining">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Training Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example9" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>TANGGAL MULAI</th>
                              <th>TANGGAL SELESAI</th>
                              <th>JENIS - NAMA PENYEDIA</th>
                              <th>NAMA PELATIHAN</th>
                              <!-- <th>KETERANGAN</th>   -->
                              <th>FOTO / SERTIFIKAT</th>         
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            $array = 0;
                            ?>
                            @foreach($trainingdet as $trainingdets)
                            <?php 
                            $a++; 

                            if ($trainingdets->jenis_penyedia == '0') {
                              $jenis_penyedia = "INHOUSE";
                            }elseif ($trainingdets->jenis_penyedia == '1') {
                              $jenis_penyedia = "EKSTERNAL";
                            }else{
                              $jenis_penyedia = "NULL";
                            }

                            $pdf = $trainingdets->photo;
                            $dat = substr($pdf,-3);
                            if($dat=="pdf") {
                              $type = "pdf";
                            } elseif ($dat == NULL or $dat == '') {
                              $type = "null";
                            } else {
                              $type = "img";
                            } 

                            $tglmulai = date_create($trainingdets->tgl_mulai);
                            $tglmulai = date_format($tglmulai, 'd F Y');

                            $tglakhir = date_create($trainingdets->tgl_akhir);
                            $tglakhir = date_format($tglakhir, 'd F Y');
                            ?>

                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $tglmulai }}</td>
                              <td>{{ $tglakhir }}</td>
                              <td>{{ $jenis_penyedia }} - {{ $trainingdets->penyedia }}</td>
                              <td>{{ $trainingdets->Nama_Pelatihan }}</td>
                              <!-- <td>{{ $trainingdets->Keterangan }}</td> -->
                              <td>                                
                                @if($type == 'pdf')
                                <a target="_blank" href="{{ url('previewtrain',$trainingdets->ID) }}">
                                  <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                                  <i class="fa fa-fw fa-edit"></i>
                                  </button>
                                </a>
                                @elseif($type == 'null')
                                <label class="label bg-red">Tidak Ada Sertifikat</label>
                                @else
                                <a href="#" onclick="modal(this)" id="{{ $trainingdets->ID }}">
                                  <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                                  <i class="fa fa-fw fa-edit"></i>
                                  </button>
                                </a>
                                @endif
                              </td>
                            
                            </tr>
                            <?php $array++; ?>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historysp">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Surat Peringatan Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example10" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>PEMBERI SP</th>
                              <th>JENIS SP</th>
                              <th>TYPE SP</th>
                              <th>TANGGAL SK</th>
                              <th>KETERANGAN</th>  
                              <th>PHOTO</th>  
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            $array = 0;
                            ?>
                            @foreach($spdet as $spdets)
                            <?php 
                            $a++; 

                            if ($spdets->type_sp == '0') {
                              $type_sp = "MEMO";
                            }elseif ($spdets->type_sp == '1') {
                              $type_sp = "TEGURAN";
                            }else{
                              $type_sp = "NULL";
                            }

                            $pdf = $spdets->photo;
                            $dat = substr($pdf,-3);
                            if($dat=="pdf") {
                              $type = "pdf";
                            } elseif ($dat == NULL or $dat == '') {
                              $type = "null";
                            } else {
                              $type = "img";
                            } 

                            $tglsk = date_create($spdets->tgl_sk);
                            $tglsk = date_format($tglsk, 'd F Y');

                            ?>

                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $spdets->nama_pemberisp }}</td>
                              <td>{{ $spdets->jenisSp }}</td>
                              <td>{{ $type_sp }}</td>
                              <td>{{ $tglsk }}</td>
                              <td>{{ $spdets->keterangan }}</td>
                              <td>
                                @if($type == 'pdf')
                                <a target="_blank" href="{{ url('previewsp',$spdets->id) }}">
                                  <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                                  <i class="fa fa-fw fa-edit"></i>
                                  </button>
                                </a>
                                @elseif($type == 'null')
                                <label class="label bg-red">Tidak Ada Gambar</label>
                                @else
                                <a href="#" onclick="modal2(this)" id="{{ $spdets->id }}">
                                  <button class="btn btn-danger btn-xs ng-scope" type="button" data-toggle="tooltip" title="preview" data-original-title="Preview Sertifikat">
                                  <i class="fa fa-fw fa-edit"></i>
                                  </button>
                                </a>
                                @endif
                              </td>
                            
                            </tr>
                            <?php $array++; ?>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historylembur">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Lembur Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example11" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>PEMBERI LEMBUR</th>
                              <th>TANGGAL MULAI</th>
                              <th>TANGGAL SELESAI</th>
                              <th>JAM MULAI</th>
                              <th>PERKIRAAN JAM SELESAI</th>  
                              <th>JAM SELESAI AKTUAL</th>  
                              <th>KEGIATAN</th> 
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            ?>
                            @foreach($lemburdet as $lemburdets)
                            <?php 
                            $a++; 

                            $tglmulai = date_create($lemburdets->TanggalMulaiLembur);
                            $tglmulai = date_format($tglmulai, 'd F Y');

                            $tglselesai = date_create($lemburdets->TanggalSelesaiLembur);
                            $tglselesai = date_format($tglselesai, 'd F Y');                            

                            ?>

                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $lemburdets->NamaPemberiLembur }}</td>
                              <td>{{ $tglmulai }}</td>
                              <td>{{ $tglselesai }}</td>
                              <td>{{ $lemburdets->JamMulai }}</td>
                              <td>{{ $lemburdets->PerkiraanJamSelesai }}</td>
                              <td>{{ $lemburdets->JamSelesaiAktual }}</td>                              
                              <td>{{ $lemburdets->Kegiatan }}</td>                              
                            
                            </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historycuti">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Cuti Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example12" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>                              
                              <th>TANGGAL MULAI</th>
                              <th>TANGGAL SELESAI</th>
                              <th>ALAMAT CUTI</th>
                              <th>KETERANGAN</th>  
                              <th>RENCANA CUTI</th>                               
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            ?>
                            @foreach($cutidet as $cutidets)
                            <?php 
                            $a++; 

                            $tglmulai = date_create($cutidets->TanggalMulaiCuti);
                            $tglmulai = date_format($tglmulai, 'd F Y');

                            $tglselesai = date_create($cutidets->TanggalSelesaiCuti);
                            $tglselesai = date_format($tglselesai, 'd F Y');                            

                            ?>

                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $tglmulai }}</td>
                              <td>{{ $tglselesai }}</td>
                              <td>{{ $cutidets->AlamatSelamaCuti }}</td>                              
                              <td>{{ $cutidets->Keterangan }}</td>
                              <td>{{ $cutidets->RencanaCuti }} <strong>Hari</strong></td>
                            
                            </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historyrembursement">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Rembursement Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example13" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO.</th>
                              <th>NAMA PASIEN</th>
                              <th>JENIS</th>
                              <th>TGL. BEROBAT</th>
                              <th>TGL. KLAIM</th>
                              <th>APOTEK / RS</th>
                              <th>DIAGNOSA</th>
                              <th>STATUS</th>
                              <th>TOTAL KLAIM</th>
                              <th>TOTAL APPROVE</th>
                              <th>KWITANSI</th>                               
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            ?>
                            @foreach($kesehatandet as $kesehatandets)
                            <?php 
                            $a++; 

                            $tglberobat = date_create($kesehatandets->tglberobat);
                            $tglberobat = date_format($tglberobat, 'd F Y');

                            $tglklaim = date_create($kesehatandets->tglklaim);
                            $tglklaim = date_format($tglklaim, 'd F Y');                            

                            ?>

                            <tr>
                              <td><strong>{{ $a }}</strong></td>
                              <td>{{ $kesehatandets->nama }}</td>
                              <td>{{ $kesehatandets->remb }}</td>
                              <td>{{ $tglberobat }}</td>
                              <td>{{ $tglklaim }}</td>
                              <td>{{ $kesehatandets->nama_apotek }}</td>
                              <td>{{ $kesehatandets->diagnosa }}</td>
                              <td>@if ($kesehatandets->approve == 'Y') <span class="label label-success">Disetujui</span> @elseif ($kesehatandets->approve == 'N') <span class="label label-info">Menunggu</span>@else <span class="label label-danger">Ditolak</span> @endif</td>
                              <td>Rp. {{number_format ($kesehatandets->total_klaim,0,",",".") }}</td>
                              <td>Rp. {{number_format ($kesehatandets->total_apprv,0,",",".") }}</td>
                              <td><a href="#" onclick="modal3(this)" id="{{ $kesehatandets->idkes }}">lihat bukti</a></td>
                            </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historyizin">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Izin Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example14" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>TANGGAL IZIN</th>
                              <th>JAM MULAI IZIN</th>
                              <th>JAM SELESAI IZIN</th>
                              <th>STATUS</th>                  
                              <th>ALASAN</th>
                              <th>STATUS IZIN</th>                               
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            ?>
                            @foreach($izindet as $izindets)
                            <?php 
                            $a++; 

                            $tglizin = date_create($izindets->tanggal);
                            $tglizin = date_format($tglizin, 'd F Y');                          

                            ?>

                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $tglizin }}</td>
                              <td>{{ $izindets->jam_mulai }}</td>
                              <td>{{ $izindets->jam_selesai }}</td>
                              <td>{{ $izindets->stat }}</td>
                              <td>{{ $izindets->ket }} </td>
                              <td>
                                @if ($izindets->statusApp == '1')
                                  <label class="label bg-green">{{ $izindets->statusizin }} </label>
                                @elseif ($izindets->statusApp == '2')
                                  <label class="label bg-red">{{ $izindets->statusizin }} </label>
                                @elseif ($izindets->statusApp == '0')
                                  <label class="label bg-blue">{{ $izindets->statusizin }} </label>
                                @endif
                              </td>
                            
                            </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

              <div class="tab-pane" id="historypd">
                <div class="box">
                  <!-- /.box-header -->
                  <div class="box-body">
                    <span><br></span>
                    <h4 class="box-title"><strong>&nbsp;History Perjalanan Dinas Karyawan</strong></h4>
                    <div class="table-responsive">
                      <div class="col-md-12">
                        <table id="example15" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th>TANGGAL MULAI</th>
                              <th>TANGGAL SELESAI</th>
                              <th>JAM MULAI</th>
                              <th>JAM SELESAI</th>
                              <th>KETERANGAN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $a=0; 
                            ?>
                            @foreach($pddet as $pddets)
                            <?php 
                            $a++;                        

                            ?>

                            <tr>
                              <td>{{ $a }}</td>
                              <td>{{ $pddets->tgl_awal }}</td>
                              <td>{{ $pddets->tgl_akhir }}</td>
                              <td>{{ $pddets->jam_awal }}</td>
                              <td>{{ $pddets->jam_akhir }}</td>
                              <td>{{ $pddets->keterangan }} </td>
                            </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                      </div>
                    </div>
                                  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  <div class="modal fade" tabindex="-1" role="dialog" id="bukti">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Preview Gambar</h4>
            </div>
            <div class="modal-body" align="center">
              <form>
                <div class="form-group" id="pic">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <a id="down"><button type="button" class="btn btn-info">download</button></a>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <div class="modal fade" tabindex="-1" role="dialog" id="photo_sp">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Preview Gambar</h4>
            </div>
            <div class="modal-body" align="center">
              <form>
                <div class="form-group" id="pic2">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <a id="down2"><button type="button" class="btn btn-info">download</button></a>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <div class="modal fade" tabindex="-1" role="dialog" id="buktikes">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Bukti Kwitansi</h4>
            </div>
            <div class="modal-body" align="center">
              <form>
              <?php  ?>
                <div class="form-group" id="pic3">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  </div>
  <!-- /.content-wrapper -->
@endsection