@extends('layouts.index')
@section('content')
@include('layouts.function')
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
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{ $data->namaagamakar}}</td>
                      </tr>
                      <tr>
                        <td>Alamat Rumah</td>
                        <td>:</td>
                        <td>{{ $data->Alamat}}</td>
                      </tr>
                      <tr>
                        <td>NPWP</td>
                        <td>:</td>
                        <td>{{ $data->npwp}}</td>
                      </tr>
                      <tr>
                        <td>No. KTP/SIM</td>
                        <td>:</td>
                        <td>{{ $data->ktp_sim}}</td>
                      </tr>
                      <tr>
                        <td>No. Rekening</td>
                        <td>:</td>
                        <td>{{ $data->norek}}</td>
                      </tr>
                      <tr>
                        <td>Jamsostek</td>
                        <td>:</td>
                        <td>{{ $data->jamsostek}}</td>
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
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
              <span><br></span>
              <h4 class="box-title"><strong>&nbsp;History Karyawan</strong></h4>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>NO</th>
                        <th>JABATAN</th>
                        <th>GOLONGAN</th>
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
                  <table id="example1" class="table table-bordered table-striped">
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
                  <table id="example1" class="table table-bordered table-striped">
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
                  <table id="example1" class="table table-bordered table-striped">
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
                  <table id="example1" class="table table-bordered table-striped">
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
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  @endsection