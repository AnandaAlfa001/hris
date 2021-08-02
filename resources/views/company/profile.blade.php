@extends('layouts.index')
@section('content')
@include('layouts.function')
<div class="content-wrapper">
  <section class="content-header">
    <h1>-- Nama Company --</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Perusahaan</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Company Profile</h3>
          </div>
          <div class="box-body">
            
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection