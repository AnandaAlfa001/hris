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
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Master Data Pangkat</h3>
          </div>
          <div class="box-body">
            <div id="gradeList"></div>
          </div>
        </div>
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
      "NO",
      "Pangkat",
      "Action"
    ],
    server: {
      url: urlData,
      then: data => data.map(v => [
        v.id,
        v.pangkat,
        v.id,
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