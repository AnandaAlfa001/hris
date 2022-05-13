@extends('layouts.index')
@section('content')
<?php

use App\Models\EmployeeModel; 
use App\Models\KesehatanModel;

$nik = Session::get('nik');
$hakakses = EmployeeModel::select('userpriv as akses')->where('NIK',$nik)->first();
$userpriv = $hakakses->akses;
$userprivs = substr($userpriv,1,-2);

$url_prev = explode('/',$_SERVER['REQUEST_URI']);
$urlmen = end($url_prev);

$explode = explode('-',$urlmen);
$url = $explode[1];
if ($url == 'profilemployee') {
  $urlback = $url.'/'.$nik;
  $url = $url;
}elseif(preg_match('/searchemployee/',$url)){
  $url = 'employeelist';
  $urlback = $url;
}else{
  $url = $url;
  $urlback = $url;
}

?>
<!--END QUERY -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Employee Data
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if ($userprivs=='1') { ?>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <?php } ?>
        <li class="active">Edit Employee Data</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="box-body">
      <div class="row">
      <?php if(session('b') || session('c') || session('d') || session('e') || session('f') || session('g') || session('h') || session('i')){ $z=0; }  ?>
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li @if(session('a')) class="active" @elseif(isset($z) && $z==1) class="active" @endif><a href="#datapribadi" data-toggle="tab">Data Pribadi</a></li></button>
              <li @if(session('b')) class="active" @else @endif><a href="#picture" data-toggle="tab">Picture</a></li>
              <li @if(session('c')) class="active" @else @endif><a href="#datakeluarga" data-toggle="tab">Data Keluarga</a></li>
              <li @if(session('d')) class="active" @else @endif><a href="#dataripek" data-toggle="tab">Pekerjaan</a></li>
              <li @if(session('e')) class="active" @else @endif><a href="#dataripen" data-toggle="tab">Pendidikan</a></li>
              <li @if(session('f')) class="active" @else @endif><a href="#dataripennon" data-toggle="tab">Riwayat Pendidikan Non-Formal</a></li>
              <li @if(session('g')) class="active" @else @endif><a href="#datakegiatan" data-toggle="tab">Organisasi</a></li>
              <li @if(session('i')) class="active" @else @endif><a href="#orangterdekat" data-toggle="tab">Orang Terdekat</a></li>
              <li @if(session('j')) class="active" @else @endif><a href="#riwayatpenyakit" data-toggle="tab">Riwayat Penyakit</a></li>
              <?php if ($userprivs=='1') { ?>
              <li @if(session('h')) @else class="disabled" @endif><a href="#fasilitas" data-toggle="tab disabled">Fasilitas Karyawan</a></li>
              <?php } ?>
              
            </ul>
            <div class="tab-content">
              <div class="@if(session('a')) active @elseif(isset($z) && $z==1) active @endif tab-pane" id="datapribadi">
                @include('employee.datapribadi')
              </div>
              <div class="@if(session('b')) active @else @endif tab-pane" id="picture">
                @include('employee.datapicture')
              </div>
              <div class="@if(session('c')) active @else @endif tab-pane" id="datakeluarga">
                @include('employee.datakeluarga')
              </div>
              <div class="@if(session('d')) active @else @endif tab-pane" id="dataripek">
                @include('employee.dataripek')
              </div>
              <div class="@if(session('e')) active @else @endif tab-pane" id="dataripen">
                @include('employee.dataripen')
              </div>
              <div class="@if(session('f')) active @else @endif tab-pane" id="dataripennon">
                @include('employee.dataripennon')
              </div>
              <div class="@if(session('g')) active @else @endif tab-pane" id="datakegiatan">
                @include('employee.datakegiatan')
              </div>
              <div class="@if(session('i')) active @else @endif tab-pane" id="orangterdekat">
                @include('employee.orangterdekat')
              </div>
              <div class="@if(session('j')) active @else @endif tab-pane" id="riwayatpenyakit">
                @include('employee.riwayatpenyakit')
              </div>
              <?php if ($userprivs=='1') { ?>
              <div class="@if(session('h')) active @else @endif tab-pane" id="fasilitas">
                @include('employee.fasilitas')
              </div>
              <?php } ?>
              
              

              
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @section('jq')
  <script>
      $(document).ready(function () {
        // $("#npwp").mask("99.999.999.9-999.999");
        
        $('#nikd').hide();
        $('#gol').show();
        $('#golout').hide();
        $('#vendor').hide();
        $("#Vendor").prop('required',false);
        $('#proyekinput').hide();
        byproyekchange('{{$data->byproyek}}')

        $('#status').change(function(){
          if($(this).val() == '5' || $(this).val() == '6'){
            $('#vendor').show();
            $("#Vendor").prop('required',true);
            $('#golout').show();
            $('#gol').hide();
          }
          else{
            $('#vendor').hide();
            $("#Vendor").prop('required',false);
            $('#gol').show();
            $('#golout').hide();
            $('#proyekinput').hide();
          } 
        });

        $('#pangkat').change(function(){
          if($(this).val() == '2' || $(this).val() == '3'){
            $('#jabatan').prop('disabled',true);
            $('#divisi').prop('disabled',true);
            $('#subdivisi').prop('disabled',true);
          } else if($(this).val() == '6' || $(this).val() == '7'){
            $('#jabatan').prop('disabled',true);
            $('#divisi').prop('disabled',false);
            $('#subdivisi').prop('disabled',false);
          } else if($(this).val() == '5'){
            $('#jabatan').prop('disabled',true);
            $('#divisi').prop('disabled',false);
            $('#subdivisi').prop('disabled',true);
            $('#subdivisi').val() == '-';
          }
        });

        var htmlattri="border-radius: 50%;max-width:200px;max-height:200px;";
  
        $("#inputgambar").change(function() {
          readURL(this);
        });

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
      });

      function byproyekchange(value)
      {
        if (value == 0) {
          $('#proyekinput').hide();
        }else{
          $('#proyekinput').show();
        }
      }
  </script>
  @endsection
@endsection