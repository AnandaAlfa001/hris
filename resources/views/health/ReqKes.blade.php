@extends('layouts.index')
@section('content')
<script>

  function getjalan() {
  // var nik = document.
  var pasien = $('#pasien').val();
  var token = '{{Session::token()}}';
  var cekjalan = '{{ url('cekrawatjalan') }}';


  $.ajax({
    method: 'POST',
    url : cekjalan,
    data : { pasien:pasien, _token : token },
  }).done(function (msg) {
    console.log(msg['nik'],msg['benefit'],msg['sisa'],msg['sisak'],msg['status']);
    $('#nikjalan').val(msg['nik']);
    $('#benefit').val(msg['benefit']);
    $('#sisa').val(msg['sisa']);
    $('#sisak').val(msg['sisak']);
    $('#status').val(msg['status']);
    $("#benefit").click();
  });
}
function getgigi() {
  // var nik = document.
  var pasien = $('#pasien2').val();
  var token = '{{Session::token()}}';
  var cekgigi = '{{ url('cekrawatgigi') }}';


  $.ajax({
    method: 'POST',
    url : cekgigi,
    data : { pasien:pasien, _token : token },
  }).done(function (msg) {
    console.log(msg['nik'],msg['benefit'],msg['sisa'],msg['sisak'],msg['status']);
    $('#nikjalan2').val(msg['nik']);
    $('#benefit2').val(msg['benefit']);
    $('#sisa2').val(msg['sisa']);
    $('#sisak2').val(msg['sisak']);
    $('#status2').val(msg['status']);
  });
}
function getkm() {
  // var nik = document.
  var pasien = $('#pasien3').val();
  var token = '{{Session::token()}}';
  var cekjalan = '{{ url('cekrawatkm') }}';


  $.ajax({
    method: 'POST',
    url : cekjalan,
    data : { pasien:pasien, _token : token },
  }).done(function (msg) {
    console.log(msg['nik'],msg['benefit'],msg['sisa'],msg['sisak'],msg['status']);
    $('#nikjalan3').val(msg['nik']);
    $('#benefit3').val(msg['benefit']);
    $('#sisa3').val(msg['sisa']);
    $('#sisak3').val(msg['sisak']);
    $('#status3').val(msg['status']);
  });
}
function getlahir() {
  // var nik = document.
  var pasien = $('#pasien4').val();
  var token = '{{Session::token()}}';
  var cekjalan = '{{ url('cekrawatlahir') }}';


  $.ajax({
    method: 'POST',
    url : cekjalan,
    data : { pasien:pasien, _token : token },
  }).done(function (msg) {
    console.log(msg['nik'],msg['benefit'],msg['sisa'],msg['sisak'],msg['status']);
    $('#nikjalan4').val(msg['nik']);
    $('#benefit4').val(msg['benefit']);
    $('#sisa4').val(msg['sisa']);
    $('#sisak4').val(msg['sisak']);
    $('#status4').val(msg['status']);
  });
}
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Request Data Klaim Kesehatan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Kesehatan</a></li>
        <li class="active">Request Klaim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
      <?php $z=1; if(session('b') || session('c') || session('d')){ $z=0; }  ?>
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li @if(session('a')) class="active" @elseif(isset($z) && $z==1) class="active" @endif><a href="#rawatjalan" data-toggle="tab">Reimbursment Rawat jalan</a></li></button>
              <li @if(session('b')) class="active" @else @endif><a href="#rawatgigi" data-toggle="tab">Reimbursment Rawat Gigi</a></li>
              <li @if(session('c')) class="active" @else @endif><a href="#rawatkaca" data-toggle="tab">Reimbursment Rawat Kacamata</a></li>
              <li @if(session('d')) class="active" @else @endif><a href="#melahirkan" data-toggle="tab">Reimbursment Melahirkan</a></li>
            </ul>
            <div class="tab-content">
              <div class="@if(session('a')) active @elseif(isset($z) && $z==1) active @endif tab-pane" id="rawatjalan">
                @include('health.ReqRawatJalan')
              </div>
              <div class="@if(session('b')) active @else @endif tab-pane" id="rawatgigi">
                @include('health.ReqRawatGigi')
              </div>
              <div class="@if(session('c')) active @else @endif tab-pane" id="rawatkaca">
                @include('health.ReqRawatKacamata')
              </div>
              <div class="@if(session('d')) active @else @endif tab-pane" id="melahirkan">
                @include('health.ReqMelahirkan')
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

  </div>
  <!-- /.content-wrapper -->
@endsection
@section('jq')
  <script type="text/javascript">
  var htmlattri="max-width:200px;max-height:200px;";
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#showgambar').attr('src',e.target.result);
          $('#showgambar').attr('style',htmlattri);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    function readURL2(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#showgambar2').attr('src',e.target.result);
          $('#showgambar2').attr('style',htmlattri);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    function readURL3(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#showgambar3').attr('src',e.target.result);
          $('#showgambar3').attr('style',htmlattri);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    function readURL4(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#showgambar4').attr('src',e.target.result);
          $('#showgambar4').attr('style',htmlattri);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    $("#inputgambar").change(function() {
      readURL(this);
    });
    $("#inputgambar2").change(function() {
      readURL2(this);
    });
    $("#inputgambar3").change(function() {
      readURL3(this);
    });
    $("#inputgambar4").change(function() {
      readURL4(this);
    });
  </script>
@endsection