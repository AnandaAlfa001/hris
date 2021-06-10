@extends('layouts.index')
@include('layouts.function')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Proses Cuti 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Cuti</a></li>
        <li class="active">Proses Cuti </li>
      </ol>
    </section>


    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Proses Cuti </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="col-md-6">
                  
                  <div class="form-group">
                    <label>NIK</label>
                    <input type="hidden" class="form-control" name="id" value="{{ $showdetail->id }}" placeholder="ID" readonly>
                    <input type="text" class="form-control" name="nik" value="{{ $showdetail->nik }}" placeholder="Nama Divisi" readonly>
                  </div>

                  <div class="form-group">
                    <label>Nama Karyawan</label>
                    <input type="text" class="form-control" name="nama" value="{{ $showdetail->nama }}" placeholder="Nama Divisi" readonly>
                  </div> 

                  <div class="form-group">
                      <label>Sisa Cuti Tahun Sebelumnya</label><br>
                      <div class="col-xs-5">
                        <input type="text" class="form-control" id="sisa_cuti_seb" value="{{$showdetail->sisa_cuti}}" name="sisa_cuti_seb" placeholder="Sisa Cuti Th Sebelum" readonly required>
                      </div>
                      <div class="col-xs-3">
                        <label>Hari Kerja</label>
                      </div>
                  </div>
                  <br>
                  <br>

                  <div class="form-group">
                      <label>Hak Cuti Tahun <?php echo date('Y'); ?></label><br>
                      <div class="col-xs-5">
                        <input type="text" class="form-control" id="hak_cuti" name="hak_cuti" value="{{$showdetail->hak_cuti}}" placeholder="Hak Cuti Tahun ini" readonly required>
                      </div>
                      <div class="col-xs-3">
                        <label>Hari Kerja</label>
                      </div>

                  </div>
                  <br><br>

                  <div class="form-group">
                      <label>Cuti yang Sudah Diambil</label><br>
                      <div class="col-xs-5">
                        <input type="text" class="form-control" id="cuti_ambil" name="cuti_ambil" value="{{$showdetail->cuti_ambil}}" placeholder="Cuti yang Sudah Diambil" readonly required>
                      </div>
                      <div class="col-xs-3">
                        <label>Hari Kerja</label>
                      </div>

                  </div>
                  <br><br>
                  

                  <div class="form-group">
                      <label>Sisa Cuti</label>
                      <input type="text" class="form-control" id="sisa_cuti" name="sisa_cuti" value="{{$sisa_cuti}}" placeholder="Sisa Cuti" readonly required>
                  </div>

                </div>

                <div class="col-md-6">
                  <div class="form-group">
                      <label>Tanggal Cuti</label><br>
                      <div class="col-xs-5">
                        <input type="date" data-date-split-input="true" class="form-control" name="tgl_mulai" value="{{ $showdetail->tgl_mulai }}" placeholder="tanggal Mulai"  readonly>
                      </div>
                      <div class="col-xs-2">
                          <label>s/d</label>
                      </div>
                      <div class="col-xs-5">
                         <div class="form-group">
                            <input type="date" data-date-split-input="true" class="form-control" name="tgl_selesai" value="{{ $showdetail->tgl_selesai }}" placeholder="tanggal Selesai"  readonly>
                          </div> 
                      </div><br>
                  </div>
                  
                  <div class="form-group">
                    <label>Jumlah hari Cuti</label>
                    <input type="text" class="form-control" name="hari" value="{{ $showdetail->hari }}" placeholder="Jumlah Hari"  readonly>
                  </div>

                  <div class="form-group">
                    <label>Alamat CUTI</label>
                    <input type="text" class="form-control" name="keterangan" value="{{ $showdetail->alamat_cuti }}" placeholder="Alamat Cuti"  readonly>
                  </div>

                  <div class="form-group">
                    <label>Keterangan / Alasan</label>
                    <input type="text" class="form-control" name="keterangan" value="{{ $showdetail->keterangan }}" placeholder="Keterangan / Alasan"  readonly>
                  </div>
                </div>
                
                        
               
              </div>
            </form>
              <!-- /.box-body -->

              <div class="box-footer">
              <a href="{{ url('catatancuti',$showdetail->id) }}" onclick="return confirm('Yakin Approve data ini ?')">
                  <button type="submit" class="btn btn-success">Approve</button>
              </a>
              <a href="{{ url('rejectcuti',$showdetail->id) }}" onclick="return confirm('Yakin mau Reject data ini ?')">
                  <button type="submit" class="btn btn-danger">Reject</button>
              </a>
              <input type="button" class="btn btn-primary" onclick="hide_show()" value="Show History" id="hiscutbutton">
              </div>
            
          </div>
          <!-- /.box -->
          <div class="box" id="hiscut"  style="display: none;">
            <div class="box-header">
              <h3 class="box-title">History Cuti</h3>
            </div>
            
            <!-- /.box-header -->
            <div class="box-body">
              <div id="alertz">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ session('success') }}
              </div>
              @endif
            </div>
            <div class="box-footer clearfix">
              <!--   <a href="{{ url('adddivisi') }}">
                <button class="btn btn-success" >
                    <i class="fa fa-edit"></i> Tambah
                </button>
                </a> -->
            </div>
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Tanggal Mulai</th>
                      <th>Hari</th>
                      <th>Tanggal Selesai</th>
                      <th>Keterangan/Alasan</th>
                      <th>Alamat Cuti</th> 
                      <th>Status</th>                     
                      <?php if ($pangkats=='7' or $pangkats=='8' or $pangkats=='9' or $pangkats=='10') { ?>
                      <th>Status 2</th>
                      <?php } ?>                                          
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0; ?>
                    @foreach($historycuti as $cutihis)
                    <?php $no++; ?>
                    <tr>
                    
                      <td>{{ $no }}</td>
                      <td>{{ $cutihis->nik }}</td>
                      <td>{{ $cutihis->nama }}</td>
                      <td>{{ myDate($cutihis->tgl_mulai) }}</td>
                      <td>{{ $cutihis->hari }}</td>                      
                      <td>{{ myDate($cutihis->tgl_selesai) }}</td>
                      <td>{{ $cutihis->keterangan }}</td>
                      <td>{{ $cutihis->alamatcuti }}</td>
                      <!-- <td>{{ $cutihis->uhuy }}</td> -->
                      <td>
                        @if ($cutihis->approve_1 == 'Y')
                          <label class="label bg-green">{{ $cutihis->statuscuti }}</label>
                        @elseif ($cutihis->approve_1 == 'R')
                          <label class="label bg-red">{{ $cutihis->statuscuti }}</label>
                        @elseif ($cutihis->approve_1 == 'N')
                          <label class="label bg-blue">{{ $cutihis->statuscuti }}</label>
                        @endif
                      </td>
                      <?php if ($pangkats=='7' or $pangkats=='8' or $pangkats=='9' or $pangkats=='10') { ?>
                      <td>
                        @if ($cutihis->approve_2 == 'Y')
                          <label class="label bg-green">{{ $cutihis->statuscuti2222 }}</label>
                        @elseif ($cutihis->approve_2 == 'R')
                          <label class="label bg-red">{{ $cutihis->statuscuti2222 }}</label>
                        @elseif ($cutihis->approve_2 == 'N')
                          <label class="label bg-blue">{{ $cutihis->statuscuti2222 }}</label>
                        @endif
                      </td>
                      <?php } ?>
                      @if(Session::get('admin') == 1)
                      <td>
                       
                        <a href="{{ url('detailhistorycuti',$cutihis->nik) }}">
                        <button class="btn btn-info" title="Detail" >
                            <i class="fa fa-search"></i> Detail
                        </button>
                        </a>
                        
                      </td>
                      @endif
                    </tr>
                    @endforeach
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
      
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

     
  </div>
  <script type="text/javascript">
    function hide_show() {
      var x = document.getElementById("hiscut");
      if (x.style.display === "block") {
          x.style.display = "none";
          document.getElementById("hiscutbutton").value = "Show History";
      } else {
          x.style.display = "block";
          document.getElementById("hiscutbutton").value = "Hide History";
      }
    } 
  </script>
  @endsection