@extends('layouts.index')
@include('layouts.function')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <div class="row">
        <div class="col-xs-12">
          
           <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Profil Pegawai</h3>
            </div>
            <!-- /.box-header -->
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="col-md-6">
                  <table>
                    <tr>
                      <td><label>NIK</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>{{ $datapegawai->NIK }}</label></td>
                    </tr>
                    <tr>
                      <td><label>Nama</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->Nama }} @endif</label></td>
                    </tr>
                    <tr>
                      <td><label>Tempat Tanggal Lahir</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->TempatLahir }} {{ $datapegawai->TanggalLahir }} @endif</label></td>
                    </tr>
                    <tr>
                      <td valign="top"><label>Alamat</label></td>
                      <td valign="top"><label>&nbsp;&nbsp;:</label></td>
                      <td valign="top"><label>@if($datapegawai->NIK) {{ $datapegawai->Alamat }} @endif</label></td>
                    </tr>
                    <tr>
                      <td><label>No HP</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->NoHp }} @endif</label></td>
                    </tr>
                    <tr>
                      <td><label>Email</label></td>
                      <td><label>&nbsp;&nbsp;:</label></td>
                      <td><label>@if($datapegawai->NIK) {{ $datapegawai->email }} @endif</label></td>
                    </tr>
                  </table>
                 <!--  <div class="form-group">
                    <label>NIK</label>
                    <label>: </label>
                  </div>

                  <div class="form-group">
                    <label>Nama Karyawan</label>
                    <label>: </label>
                  </div>

                  <div class="form-group">
                    <label>Tempat Tanggal Lahir</label>
                    <label>: </label>
                  </div>

                  <div class="form-group">
                    <label>Alamat</label>
                    <label>: </label>
                  </div>

                  <div class="form-group">
                    <label>No HP</label>
                    <label>: </label>
                  </div>

                  <div class="form-group">
                    <label>Email</label>
                    <label>: </label>
                  </div> -->

                  
                </div>
                
                        
               
              </div>
              <!-- /.box-body -->

            
          </div>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Tabel Absen</h3>
            </div>
            <br><br>
            <table style="margin-left:10px">
              <tr>
                <td>
                    <?php 
                    use App\EmployeeModel; 

                        $nik = Session::get('nik');
                        $idpangkat = Session::get('idpangkat');

                        if($idpangkat == 2 && $idpangkat == 3){
                            $datapegawai = EmployeeModel::select('*')->get();
                        }else if ($idpangkat == 5) {
                            $datapegawai = EmployeeModel::select('*')
                                            ->where('atasan1',$nik)
                                            ->where('atasan2',$nik)
                                            ->get();
                        }else{
                            $datapegawai = EmployeeModel::select('*')
                                            ->where('atasan1',$nik)
                                            ->get();
                        }
                    ?>
                  <select name="pegawai" id="pegawai" style="height: 40px" class="form-control">
                    <option>Pilih Pegawai</option>
                    @foreach($datapegawai as $pegawai)
                    <option value="{{ $pegawai->NIK }}">{{ $pegawai->Nama }}</option>
                    @endforeach
                  </select>
                </td>
                <td>&nbsp;</td>
                <td>
                  <select name="bulan" id="bulan" style="height: 40px" class="form-control">
                    <?php $bln=array(1=>"Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"); $bulan=date('m');?>
                    @for ($x = 1; $x <= 12; $x++)
                      <option value="{{ $x }}" @if($x == $bulan) selected @endif>{{ $bln[$x] }}</option>
                    @endfor
                  </select>
                </td>
                <td>&nbsp;</td>
                <td>
                  <select name="tahun" id="tahun" style="height: 40px" class="form-control">
                    <?php $tahun=date('Y');?>
                    @for ($x = $tahun; $x >= 1997; $x--)
                      <option value="{{ $x }}" >{{ $x }}</option>
                    @endfor
                  </select>
                </td>
                <td>&nbsp;</td>
                <td><button type="button" class="btn btn-primary" onclick="cari_absen()">Search</button></td>
              </tr>
            </table>
            
            
            <form role="form" action="{{ url('saveabsen') }}" method="POST" id="formid">
            {!! csrf_field() !!}
            <div class="box-body">
              <div class="table-responsive">
                <div class="col-md-12">
                  <table id="tabel" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <!-- <th width="70">Action</th> -->
                      <th>No.</th>
                      <th>Tanggal</th>
                      <th>In</th>
                      <th>Out</th>
                      <th>Selisih</th>
                      <th>Status</th>
                      <th>Keterangan</th>                                      
                    </tr>
                    </thead>
                    <tbody id="daftar">
                    <?php $no=0; ?>
                    @foreach($data as $absen)
                    <?php $no++; ?>
                    @if($absen->selisih>0)
                      <tr style="color: red;">
                      <input type="hidden" name="id[]" value="{{ $absen->absen_rekap_id }}">
                    @else
                      <tr>
                    @endif
                      <td>{{ $no }}</td>
                      <td>{{ myDate($absen->tgl) }}</td>
                      <td>{{ $absen->time_in }}</td>
                      <td>{{ $absen->time_out }}</td>
                      <td>{{ $absen->selisih }}</td> 
                      <td>{{ $absen->stat }}</td>                     
                      <td>{{ $absen->keterangan }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    
                  </table>
                </div>
             <div class="box-footer" align="right">
              
              </div>
              </div>
            </div>
          </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <script type="text/javascript">

    function cari_absen(){
      bulan =  $("#bulan").val();
      tahun =  $("#tahun").val();
      pegawai =  $("#pegawai").val();
     
      location.href="{{ url('searchabsenpeg') }}/"+pegawai+"/"+bulan+"/"+tahun;
      
    }
  </script>
  
  @endsection