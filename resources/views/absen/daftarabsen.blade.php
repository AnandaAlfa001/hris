@extends('layouts.index')
@include('layouts.function')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <div class="row">
        <div class="col-xs-12">
      
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Tabel Absen</h3>
            </div>
            <br><br>
            <table style="margin-left:10px">
              <tr>
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
                      <th>Action</th>                                
                    </tr>
                    </thead>
                    <tbody id="daftar">
                    <?php $no=0; $ket = array(1); $app1 = array(1); $app2 = array(1);?>
                    @foreach($data as $absen)
                    <?php 
                    if ($absen->ket_tamp!=NULL) {
                      $ket[0]=$absen->ket_tamp;
                      $app1[0]=$absen->approved1;
                      $app2[0]=$absen->approved2;
                    }
                    $no++; ?>
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
                      <td style="width: 200px">
                      @if($absen->ket_tamp)
                        Keterangan : {{ $absen->ket_tamp }}
                      @else
                          @if($absen->selisih>0)
                            <select class="form-control keterangan" name="keterangan[]">
                              <option value="">Keterangan</option>
                              <option>Lupa Absen</option>
                              <option>Telat</option>
                            </select><br>
                            <input type="text" name="keterangan2[]"><br>*Isi keterangan lain
                          @endif

                      @endif
                      </td>   
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
             <div class="box-footer" align="right">
               @if($app1[0]=='Y' AND $app2[0]=='Y')
                <div class="btn btn-disable">Sudah Disetujui Atasan</div>
               @elseif($ket[0]!=1)
                <div class="btn btn-disable">Proses Approve</div>
               @else
                <button type="button" class="btn btn-primary" onclick="cek('#formid')">Simpan Absen</button>
               @endif
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
    function cek(formid) {
      //alert('test');
      //nama = document.getElementsByName('keterangan[]')[].value;
      var count=0;
      $('.keterangan').each(function() {
          count++;
      });
      
      if (count>3) {
        alert('Batas Lupa Absen Hanya 3');
      }else{
        $.ajax(
            {
                type: "POST",
                url: $(formid).attr('action'),
                data:$(formid).serialize(),
                success: function(data){
                  location.href="{{ url('daftarabsen') }}";
                }
            }
          );
      }
    }

    function cari_absen(){
      bulan =  $("#bulan").val();
      tahun =  $("#tahun").val();
     
      location.href="{{ url('searchabsen') }}/"+bulan+"/"+tahun;
      
    }
  </script>
  
  @endsection