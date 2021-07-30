<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('/')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>HR</b>IS</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Hadirkoe</b> HRIS</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- if (Session::get('login') or Session::get('login') == true)
        include('layouts.notification') -->


        <!-- DROPDOWN FOR USWE -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if(Session::get('photo') == 'notfound.png')
            <img src="{{ asset('image/aw.svg') }}" class="user-image" alt="User Image">
            @else
            <img src="{{ asset('image/Photo/'.Session::get('photo')) }}" class="user-image" alt="User Image">
            @endif
            <span class="hidden-xs">{{ Session::get('nama') }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              @if(Session::get('photo') == 'notfound.png')
              <img src="{{ asset('image/aw.svg') }}" class="img-circle" alt="User Image">
              @else
              <img src="{{ asset('image/Photo/'.Session::get('photo')) }}" class="img-circle" alt="User Image">
              @endif


              <p>
                {{ Session::get('nama') }} <br>
                {{ Session::get('jabatan') }}
                <small>{{ Session::get('divisi') }} - {{ Session::get('subdivisi') }}</small>
              </p>
            </li>
            <!-- Menu Footer-->
            <?php $nik = Session::get('nik'); ?>
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ url('profilemployee',$nik) }}" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <a href="{{ url('logout') }}" class="btn btn-default btn-flat">Sign out</a>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        {{-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> --}}
      </ul>
    </div>
  </nav>
</header>