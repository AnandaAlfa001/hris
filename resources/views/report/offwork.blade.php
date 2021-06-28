@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1> Laporan </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Laporan Cuti</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Cuti</h3>
                    </div>
                    <div class="box-body">
                        <h5>Filter Pencarian</h5><br>
                        <div class="row">
                            <div class="col-xs-3">
                                <label>Tahun</label>
                                <select class="form-control select2" style="width: 100%;" id="filterOffWorkYear" name="filterOffWorkYear">
                                    <option value="">--Pilih Tahun--</option>
                                    @for ($i = 1995; $i <= date('Y'); $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label>Bulan</label>
                                <select class="form-control select2" style="width: 100%;" id="filterOffWorkMonth" name="filterOffWorkMonth">
                                    <option value="">--Pilih Bulan--</option>
                                    @foreach($months as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-xs-3">
                                <button onclick="applyFilter()" class="btn btn-primary">Terapkan Filter</button>
                                <a href="javascript:void(0)" onclick="resetFilter()" style="margin-left: 10px; margin-right: 10px;">Hapus Filter</a>
                            </div>
                            <div class="col-xs-5">
                            </div>
                            <div class="col-xs-4 text-right">
                                <button onclick="exportExcel()" class="btn btn-success"><i class="fa fa-fw fa-file-excel-o"></i> Ekspor ke Excel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection