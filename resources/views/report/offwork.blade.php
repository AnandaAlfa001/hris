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
                                <select class="form-control select2" style="width: 100%;" id="filterYear" name="filterYear">
                                    <option value="">--Pilih Tahun--</option>
                                    @for ($i = 1995; $i <= date('Y'); $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label>Bulan</label>
                                <select class="form-control select2" style="width: 100%;" id="filterMonth" name="filterMonth">
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

        <div id="offworkTable"></div>
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

    let urlData = '<?= url('report/offwork/data') ?>';
    let urlExport = '<?= url('report/offwork/export') ?>';
    let urlFilter = '?';

    let filterYear = document.getElementById('filterYear');
    let filterMonth = document.getElementById('filterMonth');

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
            "Awal Cuti",
            "Akhir Cuti",
            "Jumlah Hari",
            "Alamat Cuti",
            "Status",
        ],
        server: {
            url: urlData,
            then: data => data.map(val => [
                val.NIK,
                val.NAMA,
                val.AWAL_CUTI,
                val.AKHIR_CUTI,
                val.JUMLAH_CUTI,
                val.ALAMAT_CUTI,
                val.STATUS,
            ])
        },
        pagination: {
            enabled: true,
            limit: 10,
            summary: true
        }
    }).render(document.getElementById("offworkTable"));

    window.applyFilter = () => {
        let urlQ1 = `filterYear=${filterYear.value}`;
        let urlQ2 = `&filterMonth=${filterMonth.value}`;

        urlFilter = `?${urlQ1}${urlQ2}`;
        urlData = `<?= url('report/offwork/data') ?>${urlFilter}`
        urlExport = `<?= url('report/offwork/export') ?>${urlFilter}`

        grid.updateConfig({
            server: {
                url: urlData,
                then: data => data.map(val => [
                    val.NIK,
                    val.NAMA,
                    val.AWAL_CUTI,
                    val.AKHIR_CUTI,
                    val.JUMLAH_CUTI,
                    val.ALAMAT_CUTI,
                    val.STATUS,
                ])
            },
        }).forceRender();
    }

    window.resetFilter = () => {
        filterYear.value = '';
        filterMonth.value = '';

        urlData = '<?= url('report/offwork/data') ?>';
        urlExport = '<?= url('report/offwork/export') ?>';

        grid.updateConfig({
            server: {
                url: urlData,
                then: data => data.map(val => [
                    val.NIK,
                    val.NAMA,
                    val.AWAL_CUTI,
                    val.AKHIR_CUTI,
                    val.JUMLAH_CUTI,
                    val.ALAMAT_CUTI,
                    val.STATUS,
                ])
            },
        }).forceRender();
    }

    window.exportExcel = () => {
        window.open(urlExport, '_blank');
    }
</script>
@endsection