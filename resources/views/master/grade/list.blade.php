@extends('layouts.index')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Master Data Pangkat
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#">Master</a></li>
      <li class="active">Pangkat</li>
    </ol>
  </section>

  <section class="content">
    <div class="text-right">
      <a href="{{ url('master/grade/new') }}" class="btn btn-success"><i class="fa fa-plus"></i>&emsp;Tambah</a>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div id="gradeList"></div>
      </div>
    </div>
  </section>
</div>

<script type="module">
  import {
    Grid,
    html
  } from "{{ asset('assets/js/gridjs.js') }}";

  let urlData = '<?= url('master/grade/data') ?>';

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
        'results': () => 'Pangkat'
      }
    },
    fixedHeader: true,
    height: '500px',
    search: true,
    sort: true,
    columns: [
      "Pangkat",
      {
        id: 'actions',
        name: html(`<div class="text-center">Aksi &nbsp;<i class="fa fa-cogs text-info"></i></div>`),
        formatter: (cell, row) => html(`
          <div class="text-center">
            <a class="btn btn-default" href="{{ url('master/grade') }}/${cell}/edit" title="Ubah">
              <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger" href="{{ url('master/grade') }}/${cell}/delete" title="Hapus">
              <i class="fa fa-remove"></i>
            </a>
          </div>
        `),
        sort: false,
        width: '15%'
      },
    ],
    server: {
      url: urlData,
      then: data => data.map(v => [
        v.pangkat,
        v.id
      ])
    },
    pagination: {
      enabled: true,
      limit: 10,
      summary: true
    }
  }).render(document.getElementById("gradeList"));
</script>
@endsection