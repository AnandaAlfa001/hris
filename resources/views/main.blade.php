@extends('layouts.index')
@section('content')
@include('layouts.function')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Dashboard</h1>
    <ol class="breadcrumb">
      <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      @if(Session::get('admin') == 1)
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{ $jumlahkaryawan }}</h3>

            <p>Jumlah Karyawan</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>

          <a href="@if(Session::get('admin') == 1){{ url('employeelist') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{ $tetap }}</h3>

            <p>Karyawan Tetap</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="@if(Session::get('admin') == 1){{ url('karttplist') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <!-- <div class="col-lg-3 col-xs-6">
          
          <div class="small-box bg-red">
            <div class="inner">
              

              <p>Karyawan Keluar</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="@if(Session::get('admin') == 1){{ url('outemployeelist') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div> -->
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{ $kontrakemployee }}</h3>

            <p>Karyawan Kontrak</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="@if(Session::get('admin') == 1){{ url('kontrakemplist') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{ $out }}</h3>

            <p>Karyawan Outsource</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="@if(Session::get('admin') == 1){{ url('karoutlist') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      @endif
      <!-- UNTUK KARYAWAN BIASA -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-navy color-palette">
          <div class="inner">
            <h3>{{ $historylemburjum }}</h3>

            <p>Jumlah Jam Lemburan Selesai</p>
          </div>
          <div class="icon">
            <i class="ion ion-hourglass"></i>
          </div>

          <a href="@if($historylemburjum > 0){{ url('historylembur') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
          <div class="inner">
            <h3>{{ $historyizinjum }}</h3>

            <p>Jumlah Izin</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="@if($historyizinjum > 0){{ url('requestijin') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
          <div class="inner">
            <h3>{{ $historykesjum }}</h3>

            <p>Jumlah Klaim Kesehatan</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="@if($historykesjum > 0){{ url('historykesehatan') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-gray">
          <div class="inner">
            <h3>{{ $historycutiatjum }}</h3>

            <p>Jumlah Cuti</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="@if($historycutiatjum > 0){{ url('historycuti') }}@else # @endif" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <section class="col-lg-6 connectedSortable">
        @if(Session::get('statuskar') == 1 or Session::get('statuskar') == 2 or Session::get('statuskar') == 3 or Session::get('statuskar') == 4 or Session::get('admin') == 1)

        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">

            </div>
            <!-- /.box-header -->

            @if(Session::get('idpangkat') == 2 or Session::get('idpangkat') == 3 or Session::get('idpangkat') == 4 or Session::get('idpangkat') == 5 or Session::get('idpangkat') == 6 or Session::get('idpangkat') == 7 or Session::get('idpangkat') == 8 or Session::get('idpangkat') == 9 or Session::get('idpangkat') == 10)
            <div class="box-body">
              <div id="alertz">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ session('success') }}
                </div>
                @endif
              </div>
              <h3>History Cuti Karyawan</h3>
              <div class="table-responsive">
                <div class="col-md-12">

                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <!-- <th>Action</th> -->
                        <th>No.</th>
                        <th>Tgl. Mulai</th>
                        <th>Tgl. Selesai</th>
                        <th>Hari</th>
                      </tr>
                    </thead>
                    <tbody>


                      <?php $a = 0; ?>

                      @foreach($historycutiat as $historycutiats)
                      <?php $a++; ?>
                      <tr>
                        <td><strong>{{ $a }}</strong></td>
                        <td>{{ indonesiaDate($historycutiats->TanggalMulaiCuti) }}</td>
                        <td>{{ indonesiaDate($historycutiats->TanggalSelesaiCuti) }}</td>
                        <td>{{ $historycutiats->RencanaCuti }} hari</td>
                      </tr>
                      @endforeach

                      @if($historycutiatjum == '0')
                      <tr>
                        <td colspan="5"><strong>Belum ada history Cuti</strong></td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                  @if($historycutiatjum != '0')
                  <a href="{{ url('historycuti') }}">
                    <button type="button" class="btn btn-block btn-success btn-flat">Selengkapnya...</button>
                  </a>
                  @endif
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                </div>
              </div>
            </div>
            @endif

          </div>
        </div>

        @endif

        @if(Session::get('statuskar') == 1 or Session::get('statuskar') == 2 or Session::get('statuskar') == 3 or Session::get('statuskar') == 4 or Session::get('admin') == 1)

        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">

            </div>


            <div class="box-body">
              <div id="alertz">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ session('success') }}
                </div>
                @endif
              </div>
              <h3>History Kesehatan Karyawan</h3>
              <div class="table-responsive">
                <div class="col-md-12">

                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Pasien</th>
                        <th>Tgl. Klaim</th>
                        <th>RS / Apotek</th>
                        <th>Total Klaim</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php $a = 0; ?>

                      @foreach($historykes as $historykess)
                      <?php $a++; ?>
                      <tr>
                        <td><strong>{{ $a }}</strong></td>
                        <td>{{ $historykess->nama }}</td>
                        <td>{{ indonesiaDate($historykess->tglklaim) }}</td>
                        <td>{{ $historykess->nama_apotek }}</td>
                        <td>{{ $historykess->total_klaim }}</td>
                      </tr>
                      @endforeach

                      @if($historykesjum == '0')
                      <tr>
                        <td colspan="5"><strong>Belum ada history Kesehatan</strong></td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                  @if($historykesjum != '0')
                  <a href="{{ url('historykesehatan') }}">
                    <button type="button" class="btn btn-block btn-success btn-flat">Selengkapnya...</button>
                  </a>
                  @endif
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                </div>
              </div>
            </div>


          </div>
        </div>
        @endif

        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">

            </div>

            <div class="box-body">
              <div id="alertz">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ session('success') }}
                </div>
                @endif
              </div>
              <h3>History Lembur Karyawan</h3>
              <div class="table-responsive">
                <div class="col-md-12">

                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <!-- <th>Action</th> -->
                        <th>No.</th>
                        <th>Tgl. Lembur</th>
                        <th>Jam Lembur</th>
                        <th>Jam Akhir </th>
                        <th>Kegiatan</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php $a = 0; ?>

                      @foreach($historylembur as $historylemburs)
                      <?php $a++; ?>
                      <tr>
                        <td><strong>{{ $a }}</strong></td>
                        <td>{{ indonesiaDate($historylemburs->TanggalMulaiLembur) }}</td>
                        <td>{{ $historylemburs->JamMulai }}</td>
                        <td>{{ $historylemburs->JamSelesaiAktual }}</td>
                        <td>{{ $historylemburs->Kegiatan }}</td>

                      </tr>
                      @endforeach

                      @if($historylemburjum == '0')
                      <tr>
                        <td colspan="5"><strong>Belum ada history Lembur</strong></td>
                      </tr>
                      @endif

                    </tbody>
                  </table>
                  @if($historylemburjum != '0')
                  <a href="{{ url('historylembur') }}">
                    <button type="button" class="btn btn-block btn-success btn-flat">Selengkapnya...</button>
                  </a>
                  @endif
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>


      </section>

      <section class="col-lg-6 connectedSortable">

        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">

            </div>

            <div class="box-body">
              <div id="alertz">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ session('success') }}
                </div>
                @endif
              </div>
              <h3>History Izin Karyawan</h3>
              <div class="table-responsive">
                <div class="col-md-12">

                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <!-- <th>Action</th> -->
                        <th>No.</th>
                        <th>Tgl. Izin</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php $a = 0; ?>

                      @foreach($historyizin as $historyizins)
                      <?php $a++; ?>
                      <tr>
                        <td><strong>{{ $a }}</strong></td>
                        <td>{{ indonesiaDate($historyizins->tanggal) }}</td>
                        <td>{{ $historyizins->stat }}</td>
                        <td>{{ $historyizins->ket }}</td>

                      </tr>
                      @endforeach
                      @if($historyizinjum == '0')
                      <tr>
                        <td colspan="5"><strong>Belum ada history Izin</strong></td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                  @if($historyizinjum != '0')
                  <a href="{{ url('requestijin') }}">
                    <button type="button" class="btn btn-block btn-success btn-flat">Selengkapnya...</button>
                  </a>
                  @endif
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">

            </div>

            <div class="box-body">
              <div id="alertz">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ session('success') }}
                </div>
                @endif
              </div>
              <h3>History Training Karyawan</h3>
              <div class="table-responsive">
                <div class="col-md-12">

                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <!-- <th>Action</th> -->
                        <th>No.</th>
                        <th>Tgl. Mulai</th>
                        <th>Tgl. Selesai</th>
                        <th>Penyedia </th>
                        <th>Nama Pelatihan</th>
                      </tr>
                    </thead>
                    <tbody>


                      <?php $a = 0; ?>

                      @foreach($historytraining as $historytrainings)
                      <?php $a++; ?>
                      <tr>
                        <td><strong>{{ $a }}</strong></td>
                        <td>{{ indonesiaDate($historytrainings->tgl_mulai) }}</td>
                        <td>{{ indonesiaDate($historytrainings->tgl_akhir) }}</td>
                        <td>{{ $historytrainings->penyedia }}</td>
                        <td>{{ $historytrainings->Nama_Pelatihan }}</td>

                      </tr>
                      @endforeach

                      @if($historytrainingjum == '0')
                      <tr>
                        <td colspan="5"><strong>Belum ada history Training</strong></td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                  @if($historytrainingjum != '0')
                  <a href="{{ url('historytrain') }}">
                    <button type="button" class="btn btn-block btn-success btn-flat">Selengkapnya...</button>
                  </a>
                  @endif
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </section>
      <div class="col-xs-12">

        <div class="box">
          <div class="box-header">

          </div>

          <!-- /.box-header -->

          <div class="box-body">
            <h3>Histori Jabatan</h3>
            <div class="table-responsive">
              <div class="col-md-12">

                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>NO.</th>
                      <th>JABATAN</th>
                      <th>TANGGAL SK</th>
                      <th>TANGGAL TMT</th>
                      <th>TGL. KONTRAK</th>
                      <th>TGL. AKHIR KONTRAK</th>
                      <th>DIVISI</th>
                      <th>LOKASI</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 0; ?>

                    @foreach($tablehistory as $tablehistorys)
                    <?php $no++; ?>
                    <tr>
                      <td>{{ $no }}</td>
                      <td>{{ $tablehistorys->jabatan }}</td>
                      <td>{{ myDate($tablehistorys->tgl_sk_jab) }}</td>
                      <td>{{ myDate($tablehistorys->tgl_sk_gol) }}</td>
                      <td>{{ myDate($tablehistorys->TglKontrak) }}</td>
                      <td>{{ myDate($tablehistorys->TglKontrakEnd) }} </td>
                      <td>{{ $tablehistorys->divisi }}</td>
                      <td>{{ $tablehistorys->lokasi }} </td>

                      @endforeach
                    </tr>
                  </tbody>
                </table>
                <div class="col-md-4">
                  &nbsp;
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      @if(Session::get('admin') == 1)
      <div class="col-xs-12">

        <div class="box">
          <div class="box-header">

          </div>

          <!-- /.box-header -->

          <div class="box-body">
            <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
              </div>
              @endif
            </div>
            <h3>Karyawan Baru <small>Butuh Kelengkapan Data</small></h3>
            <div class="table-responsive">
              <div class="col-md-12">

                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <!-- <th>Action</th> -->
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Tgl. Mulai Kontrak</th>
                      <th>Tgl. Akhir Kontrak</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $a = 0; ?>

                    @foreach($data as $datas)
                    <?php $a++; ?>
                    <tr>
                      <td><strong>{{ $a }}</strong></td>
                      <td>{{ $datas->NIK }}</td>
                      <td>{{ $datas->Nama }}</td>
                      <td>{{ myDate($datas->TglKontrak) }}</td>
                      <td>{{ myDate($datas->TglKontrakEnd) }}</td>
                      <td>
                        <div class="btn-group">
                          <a href="{{ url('editemployee',$datas->NIK) }}">
                            <button class="btn btn-success btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Edit karyawan">
                              <i class="fa fa-fw fa-pencil"></i>
                            </button>
                          </a>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="col-md-4">
                  &nbsp;
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      @endif
    </div>
    <!-- /.row -->
    <!-- Main row -->

    <!-- /.row (main row) -->

  </section>
  <!-- /.content -->
</div>
@endsection