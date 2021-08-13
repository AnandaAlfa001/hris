@extends('layouts.index')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1> Form Subdivisi </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#">Master</a></li>
      <li><a href="#">Subdivisi</a></li>
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
                <label>Nama Subdivisi</label>
                <input type="text" class="form-control" name="subdivisi" placeholder="Nama Subdivisi" required value="{{ $subdivision?->subdivisi }}">
              </div>
              <div class="form-group">
                <label>Nama Divisi</label>
                <select name="iddivisi" class="form-control select2" style="width: 100%;">
                  <option value="" @if($subdivision?->divisiID != '') disabled @endif>--Pilih Divisi--</option>
                  @foreach($division as $v)
                  <option value="{{$v->id}}" @if($subdivision?->divisiID == $v->id) selected @endif>{{$v->nama_div_ext}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Status Divisi</label>
                <select name="disabled" class="form-control">
                  <option value="" @if($subdivision?->disabled != '') hidden @endif>--Pilih Status Subdivisi--</option>
                  <option value="1" @if($subdivision?->disabled == "1") selected @endif>Enabled</option>
                  <option value="0" @if($subdivision?->disabled == "0") selected @endif>Disabled</option>
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