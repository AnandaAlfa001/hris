<style type="text/css">
  .main-sidebar .sidebar {
    overflow-y: auto;
  }
</style>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if(Session::get('photo') == 'notfound.png')
        <img src="{{ asset('image/aw.svg') }}" class="img-circle" alt="User Image">
        @else
        <img src="{{ asset('image/Photo/'.Session::get('photo')) }}" class="img-circle" alt="User Image">

        @endif

      </div>
      <div class="pull-left info">
        <p>{{Session::get('nama')}}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->


    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
      <li class="active treeview">
        <a href="{{ url('/') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>

      <li class="treeview">
        <a href="{{ url('/company') }}">
          <i class="fa fa-building"></i> <span>Perusahaan</span>
        </a>
      </li>

      @if(Session::get('admin') == 1)
      <li class="treeview">
        <a href="#">
          <i class="fa fa-users"></i>
          <span>Data Pegawai</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('employee') }}"><i class="fa fa-circle-o"></i> Semua Pegawai</a></li>
          <li><a href="{{ url('addemployee') }}"><i class="fa fa-circle-o"></i> Tambah Baru</a></li>
          <li><a href="{{ url('outemployeelist') }}"><i class="fa fa-circle-o"></i> Keluarkan Pegawai</a></li>

        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-database"></i>
          <span>Master Data</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('jabatanlist') }}"><i class="fa fa-circle-o"></i> Master Jabatan</a></li>
          <li><a href="{{ url('pangkatlist') }}"><i class="fa fa-circle-o"></i> Master Pangkat</a></li>
          <li><a href="{{ url('divisilist') }}"><i class="fa fa-circle-o"></i> Master Divisi</a></li>
          <li><a href="{{ url('subdivisilist') }}"><i class="fa fa-circle-o"></i> Master Department</a></li>
          <li><a href="{{ url('golonganlist') }}"><i class="fa fa-circle-o"></i> Master Golongan</a></li>
          <li><a href="{{ url('golonganoutlist') }}"><i class="fa fa-circle-o"></i> Master Golongan Outsource</a></li>

        </ul>
      </li>
      @endif
      @if(Session::get('statuskar') == 1 or Session::get('statuskar') == 2 or Session::get('statuskar') == 3 or Session::get('statuskar') == 4 or Session::get('admin') == 1)
      <li class="treeview">
        <a href="#">
          <i class="fa fa-hourglass-o"></i>
          <span>Cuti</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          @if(Session::get('admin') == 1)
          <li><a href="{{ url('listrequestcuti') }}"><i class="fa fa-circle-o"></i> List Request Cuti</a></li>
          <li><a href="{{ url('addcuti') }}"><i class="fa fa-circle-o"></i> Tambah Data Cuti</a></li>
          <li><a href="{{ url('hakcuti') }}"><i class="fa fa-circle-o"></i> Hak Cuti</a></li>
          @endif
          @if(Session::get('statuskar') == 1 or Session::get('statuskar') == 2 or Session::get('statuskar') == 3 or Session::get('statuskar') == 4)
          <li><a href="{{ url('requestcuti') }}"><i class="fa fa-circle-o"></i> Request Cuti</a></li>
          @endif
          <li><a href="{{ url('historycuti') }}"><i class="fa fa-circle-o"></i> History Cuti</a></li>
          @if(Session::get('admin') == 2)
          <li><a href="{{ url('approvecuti') }}"><i class="fa fa-circle-o"></i> Approve Cuti</a></li>
          @endif


        </ul>
      </li>
      @endif
      <li class="treeview">
        <a href="#">
          <i class="fa fa-envelope"></i>
          <span>Izin </span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          @if(Session::get('admin') == 1)
          <li><a href="{{ url('listrequestizin') }}"><i class="fa fa-circle-o"></i>List Request Izin</a></li>
          @endif
          <li><a href="{{ url('requestijin') }}"><i class="fa fa-circle-o"></i> Request Izin</a></li>
          @if(Session::get('admin') == 2)
          <li><a href="{{ url('approveijin') }}"><i class="fa fa-circle-o"></i> Approve Izin</a></li>
          @endif
        </ul>
      </li>
      @if(Session::get('statuskar') == 1 or Session::get('statuskar') == 2 or Session::get('statuskar') == 3 or Session::get('statuskar') == 4)
      <li class="treeview">
        <a href="#">
          <i class="fa fa-ambulance"></i>
          <span>Kesehatan (Klaim)</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          @if(Session::get('admin') == 1)
          <li><a href="{{ url('daftarreq') }}"><i class="fa fa-circle-o"></i> Daftar Pengajuan Klaim</a></li>
          <li><a href="{{ url('addkesehatan') }}"><i class="fa fa-circle-o"></i> Tambah Data Klaim</a></li>
          <li><a href="{{ url('hakkesehatan') }}"><i class="fa fa-circle-o"></i> Hak Kesahatan</a></li>
          @endif
          <li><a href="{{ url('historykesehatan') }}"><i class="fa fa-circle-o"></i> History Klaim Sudah Dibayar</a></li>
          <li><a href="{{ url('reqkesehatan') }}"><i class="fa fa-circle-o"></i> Request Klaim</a></li>


        </ul>
      </li>
      @endif

      <li class="treeview">
        <a href="#">
          <i class="fa fa-hourglass-2"></i>
          <span>Lembur </span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <!-- <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Daftar Pengajuan Upah Lembur</a></li> -->
          @if(Session::get('admin') == 1 or Session::get('admin') == 2)
          <li><a href="{{url('tambahspl')}}"><i class="fa fa-circle-o"></i> Tambah Perintah Lembur</a></li>
          <li><a href="{{url('listeditlembur')}}"><i class="fa fa-circle-o"></i> List Ubah Lemburan</a></li>
          <li><a href="{{url('listlemburanselesai')}}"><i class="fa fa-circle-o"></i> List Lemburan Selesai</a></li>
          @endif
          <li><a href="{{ url('historylembur') }}"><i class="fa fa-circle-o"></i> History Lembur</a></li>
        </ul>
      </li>


      @if(Session::get('admin') == 1 or Session::get('admin') == 2)

      <li class="treeview">
        <a href="#">
          <i class="fa fa-line-chart"></i>
          <span>Training </span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">

          <li><a href="{{ url('historytrain') }}"><i class="fa fa-circle-o"></i> History Training</a></li>
          <li><a href="{{ url('addtrain') }}"><i class="fa fa-circle-o"></i> Tambah Data Training</a></li>
        </ul>
      </li>


      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-excel-o"></i>
          <span>Laporan</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          @if(Session::get('admin') == 1 or Session::get('admin') == 2)
          <li><a href="{{ url('report/employee') }}"><i class="fa fa-circle-o"></i> Pegawai</a></li>
          <li><a href="{{ url('report/offwork') }}"><i class="fa fa-circle-o"></i> Cuti</a></li>
          <li><a href="{{ url('reportkesehatan') }}"><i class="fa fa-circle-o"></i> Kesahatan</a></li>
          <li><a href="{{ url('reporttraining') }}"><i class="fa fa-circle-o"></i> Training</a></li>
          <li><a href="{{ url('reportlembur') }}"><i class="fa fa-circle-o"></i> Lembur Pegawai</a></li>
          @endif

        </ul>

      </li>
      @endif

      @if(Session::get('admin') != 1)

      <li class="treeview">
        <a href="#">
          <i class="fa fa-book"></i>
          <span>Absen</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('daftarabsen') }}"><i class="fa fa-circle-o"></i>Daftar Absen</a></li>
          @if(Session::get('idpangkat') == 2 || Session::get('idpangkat') == 3 ||Session::get('idpangkat') == 4 ||Session::get('idpangkat') == 5 || Session::get('idpangkat') == 6 || Session::get('idpangkat') == 7)
          <li><a href="{{ url('viewabsenpeg/0/0/0',['id'=>'', 'bulan'=>'', 'tahun'=>'']) }}"><i class="fa fa-circle-o"></i>Daftar Absen Pegawai</a></li>
          <li><a href="{{ url('listappabsen') }}"><i class="fa fa-circle-o"></i>Daftar Approve Absen</a></li>
          @endif
        </ul>
      </li>

      @endif



      <li class="treeview">
        <a href="#">
          <i class="fa fa-gavel"></i>
          <span>Surat Peringatan</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('daftarsp') }}"><i class="fa fa-circle-o"></i>Daftar Surat Peringatan</a></li>
          @if(Session::get('admin') == 1 or (Session::get('admin') == 2 and (Session::get('idpangkat') == 2 or Session::get('idpangkat') == 3 or Session::get('idpangkat') == 4 or Session::get('idpangkat') == 5)))
          <li><a href="{{ url('tambahsp') }}"><i class="fa fa-circle-o"></i> Tambah Surat Peringatan</a></li>
          @endif
        </ul>
      </li>

      @if(Session::get('admin') == 1)

      <li class="treeview">
        <a href="#">
          <i class="fa fa-bus"></i>
          <span>Perjalanan Dinas</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('daftarpd') }}"><i class="fa fa-circle-o"></i>Daftar Perjalanan Dinas</a></li>
          <li><a href="{{ url('tambahpd') }}"><i class="fa fa-circle-o"></i> Tambah Perjalanan Dinas</a></li>
        </ul>
      </li>


      <li class="treeview">
        <a href="#">
          <i class="fa fa-bus"></i>
          <span>PTH</span><i class="fa fa-angle-left pull-right"></i>

        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('daftarpth') }}"><i class="fa fa-circle-o"></i>Daftar PTH</a></li>
          <li><a href="{{ url('tambahpth') }}"><i class="fa fa-circle-o"></i> Tambah PTH</a></li>
        </ul>
      </li>

      @endif

      <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i>
            <span>Dokumen </span><i class="fa fa-angle-left pull-right"></i>
            
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('tambahdok') }}"><i class="fa fa-circle-o"></i> Tambah Dokumen</a></li>
            <li><a href="{{ url('listdok') }}"><i class="fa fa-circle-o"></i> List Dokumen</a></li>            
          </ul>
        </li> -->


    </ul>
  </section>
  <!-- /.sidebar -->
</aside>