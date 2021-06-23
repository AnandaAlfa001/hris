<script>
  var login = '{{ url('login') }}';
</script>
<?php
  if(!Session::get('login') or Session::get('login')==false)
  {
    echo "<script>window.location = login; </script>";
  }
?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>HRIS 2.0</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/font-awesome-4.6.3/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('plugins/ionicons-2.0.1/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
  <!-- Morris chart -->
  <!-- <link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}"> -->
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{asset('plugins/datepicker/datepicker3.css')}}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
   <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
  <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

  @section('css')

  @show

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!-- <body class="hold-transition skin-blue sidebar-mini"> -->
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  -->
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

@section('header')
    @include('layouts.header')
@show

@section('sidebar')
    @include('layouts.sidebar')
@show

@yield('content')

@section('footer')
    @include('layouts.footer')
@show


<!-- Control Sidebar -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
 <script src="{{asset('plugins/jQuery/jQuery-2.1.3.min.js')}}"></script>
<!-- <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script> -->
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jQueryUI/jquery-ui.min.js')}}"></script>
<script src="{{ asset('plugins/webshim/polyfiller.js') }}"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshims.setOptions('forms-ext', {types: 'date'});
  webshims.polyfill('forms forms-ext');
</script>
@yield('jqmutasi')
@yield('jqhistory')
@yield('jq')
@section('appjs')
    @include('layouts.appjs')
@show
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>

<!-- Mask Moneu -->
<script type="text/javascript" src="{{ asset('jquery-maskmoney-master/dist/jquery.maskMoney.js') }}"></script>

<!-- bootstrap datepicker -->
<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- bootstrap color picker -->
<script src="{{asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{asset('dist/js/raphael-min.js')}}"></script>
<!-- <script src="{{asset('plugins/morris/morris.min.js')}}"></script> -->
<!-- Sparkline -->
<!-- <script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script> -->
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    $(".timepicker").timepicker({
      showInputs: false

    });
  });
</script>

<!-- page script -->
<script>
  $(function () {
    $("[data-mask]").inputmask();
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });

    $("#example3").DataTable();
    $("#example4").DataTable();
    $("#example5").DataTable();
    $("#example6").DataTable();
    $("#example7").DataTable();
    $("#example8").DataTable();
    $("#example9").DataTable();
    $("#example10").DataTable();
    $("#example11").DataTable();
    $("#example12").DataTable();
    $("#example13").DataTable();
    $("#example14").DataTable();
    $("#example15").DataTable();
  });
  </script>
<!-- jvectormap -->
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/knob/jquery.knob.js')}}"></script>



<!-- daterangepicker -->
<script src="{{asset('dist/js/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/app.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{asset('dist/js/pages/dashboard.js')}}"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>

@section('js')

@show
</body>
</html>
