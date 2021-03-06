@extends('layouts.index')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1> Data Pegawai </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>Data Pegawai</li>
      <li class="active">Semua Pegawai</li>
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
                <!--  -->
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
    html,
    h
  } from "{{ asset('assets/js/gridjs.js') }}";

  let urlData = '<?= url('employee/data') ?>';
  let urlFilter = '?';

  let filterEmployeeStatus = document.getElementById('filterEmployeeStatus');
  let filterManager1 = document.getElementById('filterManager1');
  let filterManager2 = document.getElementById('filterManager2');
  let filterWorkLocation = document.getElementById('filterWorkLocation');
  let filterStartContract = document.getElementById('filterStartContract');
  let filterEndContract = document.getElementById('filterEndContract');

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
        'results': () => 'Hasil'
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
      "Jabatan",
      {
        id: 'actions',
        name: html(`<div class="text-center">Aksi &nbsp;<i class="fa fa-cogs text-info"></i></div>`),
        formatter: (cell, row) => {
          return html(`
            <div class="text-center">
              <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Pilih Aksi <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                  <li>
                    <a href="{{ url('employee') }}/${cell.NIK}">
                      <i class="fa fa-fw fa-info-circle text-info"></i>&nbsp;<b>Lihat Detail</b>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('employee') }}/edit/${cell.NIK}-employeelist">
                      <i class="fa fa-fw fa-pencil-square text-info"></i>&nbsp;<b>Ubah</b>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('employee') }}/resign/${cell.NIK}">
                      <i class="fa fa-fw fa-user-times text-info"></i>&nbsp;<b>Resign</b>
                    </a>
                  </li>
                  <li role="separator" class="divider"></li>
                  <li>
                    <a href="{{ url('mutasi') }}/${cell.NIK}">
                      <i class="fa fa-fw fa-random text-info"></i>&nbsp;<b>Mutasi</b>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('employee') }}/history/${cell.NIK}">
                      <i class="fa fa-fw fa-hourglass-start text-info"></i>&nbsp;<b>Riwayat</b>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('employee') }}/project-experience/${cell.NIK}">
                      <i class="fa fa-fw fa-file text-info"></i>&nbsp;<b>Pengalaman Proyek</b>
                    </a>
                  </li>
                  ${
                    (cell.STATUS == 5 || cell.STATUS == 6) ?
                    `<li>
                      <a href="{{ url('employee') }}/extend-contract/${cell.NIK}">
                        <i class="fa fa-fw fa-calendar-plus-o text-info"></i>&nbsp;<b>Perpanjang Kontrak (Outsource)</b>
                      </a>
                    </li>`
                    : ''
                  }
                </ul>
              </div>
            </div>
          `);
        },
        sort: false,
        width: '15%'
      },
    ],
    server: {
      url: urlData,
      then: data => data.map(employee => [
        employee.NIK,
        employee.NAMA,
        employee.ALAMAT,
        employee.UNIT_KERJA,
        employee.JABATAN,
        {
          NIK: employee.NIK,
          STATUS: employee.STATUS
        }
      ])
    },
    pagination: {
      enabled: true,
      limit: 10,
      summary: true
    }
  }).render(document.getElementById("employeeTable"));

  window.applyFilter = () => {
    let urlQ1 = `filterEmployeeStatus=${filterEmployeeStatus.value}`;
    let urlQ2 = `&filterManager1=${filterManager1.value}`;
    let urlQ3 = `&filterManager2=${filterManager2.value}`;
    let urlQ4 = `&filterWorkLocation=${filterWorkLocation.value}`;
    let urlQ5 = `&filterStartContract=${filterStartContract.value}`;
    let urlQ6 = `&filterEndContract=${filterEndContract.value}`;

    urlFilter = `?${urlQ1}${urlQ2}${urlQ3}${urlQ4}${urlQ5}${urlQ6}`;
    urlData = `<?= url('employee/data') ?>${urlFilter}`

    grid.updateConfig({
      server: {
        url: urlData,
        then: data => data.map(employee => [
          employee.NIK,
          employee.NAMA,
          employee.ALAMAT,
          employee.UNIT_KERJA,
          employee.JABATAN
        ])
      },
    }).forceRender();
  }

  window.resetFilter = () => {
    filterEmployeeStatus.value = '';
    filterManager1.value = '';
    filterManager2.value = '';
    filterWorkLocation.value = '';
    filterStartContract.value = '';
    filterEndContract.value = '';

    urlData = '<?= url('report/employee/data') ?>';

    grid.updateConfig({
      server: {
        url: urlData,
        then: data => data.map(employee => [
          employee.NIK,
          employee.NAMA,
          employee.ALAMAT,
          employee.UNIT_KERJA,
          employee.JABATAN
        ])
      },
    }).forceRender();
  }

  window.exportExcel = () => {
    window.open(urlExport, '_blank');
  }
</script>
@endsection
