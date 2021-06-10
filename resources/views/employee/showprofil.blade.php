@extends('layouts.index')
@section('content')
@include('layouts.function')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Your Profile
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <!-- <li><a href="{{ url('employeelist') }}">All Employee</a></li> -->
        <li class="active">Your Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <?php
            $nik = Session::get('nik'); ?>
            <a href="{{ url('editemployee',$nik.'-profilemployee') }}">
            <button type="button" style="width: 10%" class="btn btn-block btn-success">Edit Profile</button>
            </a>
              <span><br></span>
            <div class="input-group col-md-12">
              <div class="col-md-5">
                <div class="form-group">
                    @if($data->photo)
                    <img src="{{ asset('image/Photo/'.$data->photo) }}" id="showgambar" class="img-circle img-bordered-sm" style="border-radius: 50%;max-width:400px;max-height:400px;float:left;">
                    @else
                    <img src="http://placehold.it/400X400" id="showgambar" class="img-circle img-bordered-sm">
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
                        <th>IPK/Nilai</th>
                        <th>Ijazah</th>
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
                        <td>{{ $ripens-> ipk}}</td>
                        <td>
                          @if($ripens->id_dokumen)
                              @if(strpos($ripens->FileDokumen, "Pendidikan/") !== false)
                                <?php
                                    $path_file = str_replace("public/", "", $ripens->FileDokumen);
                                ?>
                                <a href="http://192.168.5.110/storage/{{$path_file}}" target="_blank">Click to Download</a>
                              @else
                                <a href="{{ asset('image/Dokumen/'.$ripens->FileDokumen) }}" target="_blank">Click to Download</a>
                              @endif
                          @endif
                        </td>
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
                        <th>Sertifikat</th>
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
                        <td>
                          @if($nons->FileSertifikat)
                            <a href="{{ asset('image/Sertifikat/'.$nons->FileSertifikat) }}" target="_blank">Click to Download</a>
                          @endif
                        </td>
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
              <?php
              $nik = Session::get('nik'); ?>
              <a href="{{ url('editemployee',$nik.'-profilemployee') }}">
              <button type="button" class="btn btn-block btn-success">Edit Profile</button>
              </a>
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