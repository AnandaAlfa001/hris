@extends('layouts.index')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1> Form Divisi </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#">Master</a></li>
      <li><a href="#">Divisi</a></li>
      <li class="active">Form</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <form role="form" action="{{ url($formAction) }}" method="POST">
            {!! csrf_field() !!}
            <div class="box-body">
              <div class="form-group">
                <label>Nama Divisi</label>
                <input type="text" class="form-control" name="divisi" placeholder="Nama Divisi" required value="{{ $division?->nama_div_ext }}">
              </div>
              <div class="form-group">
                <label>Status Divisi</label>
                <select name="disabled" class="form-control">
                  <option value="" @if($division?->disabled != '') hidden @endif>--Pilih Status Divisi--</option>
                  <option value="1" @if($division?->disabled == "1") selected @endif>Enabled</option>
                  <option value="0" @if($division?->disabled == "0") selected @endif>Disabled</option>
                </select>
              </div>
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection