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
    html,
    h
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
        'results': () => 'Hasil'
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
        formatter: (cell, row) => {
          return h('div', {
              className: 'text-center'
            },
            h('a', {
                className: 'btn btn-default',
                style: 'margin-right: 5px',
                role: 'button',
                title: 'Ubah',
                onClick: () => window.open(`{{ url('master/grade') }}/${cell}/edit`, '_self')
              },
              h('i', {
                className: 'fa fa-edit'
              })
            ),
            h('a', {
                className: 'btn btn-danger',
                role: 'button',
                title: 'Hapus',
                onClick: () => {
                  if (confirm('Apakah Anda yakin ?')) {
                    return window.open(`{{ url('master/grade') }}/${cell}/delete`, '_self')
                  }
                }
              },
              h('i', {
                className: 'fa fa-remove'
              })
            ),
          );
        },
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