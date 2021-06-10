@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Project Experience
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">Project Experience</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#headerpe" data-toggle="tab">Header PE</a></li></button>
              <li class="disabled"><a href="#projectex" data-toggle="tab disabled">Project Experience</a></li>              
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="headerpe">
                <h3 class="title">Project Experience Karyawan</h3>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{ url('saveheaderpe') }}">
                {{ csrf_field() }}
                <div class="input-group col-md-12">
                  <div class="col-md-12">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Posisi yang diusulkan</label> 
                          <input type="hidden" class="form-control" name="nik" placeholder="" value="{{$nik}}" required>                     
                          <input type="text" class="form-control" name="posisi" placeholder="Posisi yang diusulkan" value="" required>
                        </div><br>
                        <div class="form-group">
                          <label>Nama Perusahaan</label>
                          <input type="text" class="form-control" name="nama_perusahaan" placeholder="Nama Perusahaan" value="PT. EDI Indonesia" readonly required>
                        </div><br>
                        <div class="form-group">
                          <label>Nama Personil</label>
                          <input type="text"  class="form-control" name="nama_personil" placeholder="Nama Personil" value="{{$showdata->Nama}}" required>
                        </div><br>
                        <div class="form-group">
                          <label>Tempat Lahir</label>
                          <input type="text"  class="form-control" name="tempat_lahir" placeholder="Tempat Lahir" value="{{$showdata->TempatLahir}}" readonly required>
                        </div><br>
                        <div class="form-group">
                          <label>Tanggal Lahir</label>
                          <input type="date" class="form-control" data-date-split-input="true" class="tgl_lahir" name="akhir_kontrak" placeholder="YYYY-MMM-DDD" value="{{$showdata->TanggalLahir}}" readonly required>
                        </div><br>
                        <div class="form-group">
                          <label>Pendidikan (Lembaga Pendidikan, tempat, dan tahun tamat belajar, dilampirkan rekaman ijazah)</label>
                          <textarea type="text" class="form-control" name="pendidikan_formal" placeholder="Pendidikan Formal" required></textarea>
                        </div><br>
                        <div class="form-group">
                          <label>Pendidikan Non Formal</label>
                          <textarea type="text" class="form-control" name="pendidikan_nonformal" placeholder="Pendidikan Non Formal" required></textarea>
                        </div>
                        <div class="form-group">
                          <label>Penguasaan Bahasa Inggris dan Bahasa Indonesia  </label>
                          <select name="penguasaanbhs" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                          </select>
                        </div>
                        <!-- <div class="form-group">
                            <label>Bulan Tahun Awal</label><br>
                            <div class="col-xs-5">
                              <select class="form-control" name="bulan_awal">
                                  <option value="">--Pilih Bulan Awal--</option>
                                  <option value="Januari">Januari</option>
                                  <option value="Februari">Februari</option>
                                  <option value="Maret">Maret</option>
                                  <option value="April">April</option>
                                  <option value="Mei">Mei</option>
                                  <option value="Juni">Juni</option>
                                  <option value="Juli">Juli</option>
                                  <option value="Agustus">Agustus</option>
                                  <option value="September">September</option>
                                  <option value="Oktober">Oktober</option>
                                  <option value="November">November</option>                                
                                  <option value="Desember">Desember</option>
                                </select>
                            </div>
                            <div class="col-xs-2">
                                <label>s/d</label>
                            </div>
                            <div class="col-xs-5">
                               <div class="form-group">
                                  <select name="tahun_awal" class="form-control">
                                    <option value="">--Pilih Tahun Awal--</option>
                                    @foreach($tahundropdowns as $tahun)
                                    <option value="{{$tahun->tahun}}">{{$tahun->tahun}}</option>
                                    @endforeach
                                  </select> 
                                  
                                </div> 
                            </div><br>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Bulan Tahun Akhir</label><br>
                            <div class="col-xs-5">
                              <select class="form-control" name="bulan_akhir">
                                    <option value="">--Pilih Bulan Awal--</option>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>                                
                                  <option value="Desember">Desember</option>
                                </select>
                            </div>
                            <div class="col-xs-2">
                                <label>s/d</label>
                            </div>
                            <div class="col-xs-5">
                               <div class="form-group">
                                  <select name="tahun_akhir" class="form-control">
                                    <option value="">--Pilih Tahun Akhir--</option>
                                    @foreach($tahundropdowns as $tahun)
                                    <option value="{{$tahun->tahun}}">{{$tahun->tahun}}</option>
                                    @endforeach
                                  </select>
                                </div>  
                            </div><br> -->
                        </div>
                        <br>                                     
                    </div>
                    <!-- /.box-body -->
                  </div>                  
                </div>
                  <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <button type="reset" class="btn btn-danger">Reset</button>
                </div>
                </form>
                <div class="box-body">
                <h3><strong>History Project Experience</strong></h3>
                <br>
                <div class="table-responsive">
                  <div class="col-md-12">

                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>NO</th>
                        <th>ID Head</th>
                        <th>NIK</th>
                        <th>Posisi</th>
                        <th>Didikan</th>
                        <th>Didikan Non Formal</th>
                        <th>Bahasa</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php $a=0; ?>
                      @foreach($historyprex as $historyprex)
                      <?php $a++; ?>
                      <tr>
                        <td>{{ $a }}</td>
                        <td>{{ $historyprex->id_head }}</td>
                        <td>{{ $historyprex->nik }}</td>
                        <td>{{ $historyprex->posisi }}</td>
                        <td>{{ $historyprex->didikan }}</td>
                        <td>{{ $historyprex->didikannf }}</td>
                        <td>
                          <?php if($historyprex->bhs == 1) { ?>
                            Aktif  
                          <?php } else { ?>
                            Tidak Aktif
                          <?php } ?>

                        </td>
                        <td>
                          <div class="btn-group">                          
                          <a href="{{ url('cvlelang',$historyprex->id_head) }}" target="_blank">
                          <button class="btn btn-info btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Cetak CV">
                          <i class="fa fa-fw fa-print"></i>
                          </button>
                          </a>

                          <a href="{{ url('projectexperience',$historyprex->id_head) }}" >
                          <button class="btn btn-warning btn-xs ng-scope" type="button" data-toggle="tooltip" title="" data-original-title="Edit Project Experience">
                          <i class="fa fa-fw fa-pencil"></i>
                          </button>
                          </a>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                      
                      </tbody>
                      
                    </table>
                  </div>
                </div>
              </div>
                </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
