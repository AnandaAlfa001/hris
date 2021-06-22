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
                    <div class="box-body">
                        <h5>Filter Pencarian</h5><br>
                        <div class="row">
                            <div class="col-xs-3">
                                <label>Status Karyawan</label>
                                <select class="form-control select2" style="width: 100%;" id="filterEmployeeStatus" name="filterEmployeeStatus">
                                    <option value="">--Pilih Status Karyawan--</option>
                                    @foreach($employeeStatus as $data)
                                    <option value="{{ $data->id }}">{{ $data->status_kar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <label>Atasan Langsung</label>
                                <select class="form-control select2" style="width: 100%;" id="filterManager1" name="filterManager1">
                                    <option value="">--Pilih Atasan Langsung--</option>
                                    @foreach($manager as $data)
                                    <option value="{{ $data->NIK }}">{{ $data->NAMA }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-5">
                                <label>Atasan Tidak Langsung</label>
                                <select class="form-control select2" style="width: 100%;" id="filterManager2" name="filterManager2">
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
                                <select class="form-control select2" style="width: 100%;" id="filterWorkLocation" name="filterWorkLocation">
                                    <option value="">--Pilih Status Karyawan--</option>
                                    @foreach($workLocation as $data)
                                    <option value="{{$data->id}}">{{$data->lokasi}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <label>Tanggal Kontrak</label>
                                <input type="date" class="form-control" id="filterStartContract" name="filterStartContract" placeholder="YYYY-MM-DD" data-date-split-input="true">
                            </div>
                            <div class="col-xs-5">
                                <label>Tanggal Akhir Kontrak</label>
                                <input type="date" class="form-control" id="filterEndContract" name="filterEndContract" placeholder="YYYY-MM-DD" data-date-split-input="true">
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
                                <button onclick="exportExcel()" class="btn btn-success"><i class="fa fa-fw  fa-file-excel-o"></i> Ekspor ke Excel</button>
                            </div>
                        </div>
                    </div>
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


    (function() {
        // document ready
    })();

    let url = '<?= url('report/employee/data') ?>';

    let grid = new Grid({
        language: {
            'search': {
                'placeholder': 'Ketik disini untuk mencari ...'
            },
            'pagination': {
                'previous': 'Sebelumnya',
                'next': 'Selanjutnya',
                'showing': 'Menampilkan',
                of: 'dari',
                to: 'sampai',
                'results': () => 'Pegawai'
            }
        },
        fixedHeader: true,
        height: '500px',
        search: true,
        sort: true,
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

    window.applyFilter = () => {
        let filterEmployeeStatus = document.getElementById('filterEmployeeStatus').value;
        let filterManager1 = document.getElementById('filterManager1').value;
        let filterManager2 = document.getElementById('filterManager2').value;
        let filterWorkLocation = document.getElementById('filterWorkLocation').value;
        let filterStartContract = document.getElementById('filterStartContract').value;
        let filterEndContract = document.getElementById('filterEndContract').value;

        let urlQ1 = `filterEmployeeStatus=${filterEmployeeStatus}`;
        let urlQ2 = `&filterManager1=${filterManager1}`;
        let urlQ3 = `&filterManager2=${filterManager2}`;
        let urlQ4 = `&filterWorkLocation=${filterWorkLocation}`;
        let urlQ5 = `&filterStartContract=${filterStartContract}`;
        let urlQ6 = `&filterEndContract=${filterEndContract}`;

        let urlFilter = `?${urlQ1}${urlQ2}${urlQ3}${urlQ4}${urlQ5}${urlQ6}`;
        url = `<?= url('report/employee/data') ?>${urlFilter}`

        grid.updateConfig({
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
        }).forceRender()
    }
</script>
@endsection