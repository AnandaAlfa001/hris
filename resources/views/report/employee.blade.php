@extends('layouts.index')
@section('content')
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

<div class="content-wrapper">
    <section class="content-header">
        <h1> Laporan </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Laporan Data Pegawai</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Pegawai</h3>
                    </div>
                    <form role="form" action="{{ url('filteremployee') }}" method="GET">
                        <div class="box-body">
                            <h5>Filter Pencarian</h5><br>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Status Karyawan</label>
                                    <select class="form-control select2" style="width: 100%;" name="statuskaryawan">
                                        <option value="">--Pilih Status Karyawan--</option>
                                        @foreach($employeeStatus as $data)
                                        <option value="{{ $data->id }}">{{ $data->status_kar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <label>Atasan Langsung</label>
                                    <select class="form-control select2" style="width: 100%;" name="atasan1">
                                        <option value="">--Pilih Atasan Langsung--</option>
                                        @foreach($manager as $data)
                                        <option value="{{ $data->NIK }}">{{ $data->NAMA }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-5">
                                    <label>Atasan Tidak Langsung</label>
                                    <select class="form-control select2" style="width: 100%;" name="atasan2">
                                        <option value="">--Pilih Status Karyawan--</option>
                                        @foreach($manager as $data)
                                        <option value="{{$data->NIK}}">{{$data->NAMA}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Lokasi Kerja</label>
                                    <select class="form-control select2" style="width: 100%;" name="lokasiker">
                                        <option value="">--Pilih Status Karyawan--</option>
                                        @foreach($workLocation as $data)
                                        <option value="{{$data->id}}">{{$data->lokasi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <label>Tanggal Kontrak</label>
                                    <input type="date" class="form-control" id="datepicker" name="tgl_kontrak" placeholder="YYYY-MM-DD" data-date-split-input="true">
                                </div>
                                <div class="col-xs-5">
                                    <label>Tanggal Akhir Kontrak</label>
                                    <input type="date" class="form-control" id="datepicker" name="tgl_akhir" placeholder="YYYY-MM-DD" data-date-split-input="true">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="employeeTable"></div>
    </section>
</div>

<script type="module">
    import {
        Grid,
        html
    } from "https://unpkg.com/gridjs?module";

    let url = '<?= url('report/employee/data') ?>';

    let grid = new Grid({
        search: true,
        columns: [
            "NIK",
            "Nama",
            "Alamat",
            "Unit Kerja",
            "Jabatan"
        ],
        server: {
            url: url,
            then: data => data.map(employee => [
                employee.NIK,
                employee.NAMA,
                employee.ALAMAT,
                employee.UNIT_KERJA,
                employee.JABATAN
            ])
        },
        pagination: {
            enabled: true,
            limit: 10,
            summary: true
        }
    }).render(document.getElementById("employeeTable"));

    // grid.updateConfig().forceRender()
</script>
@endsection