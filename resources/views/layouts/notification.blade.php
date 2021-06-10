
<?php

use App\Models\EmployeeModel; 
use App\Models\KesehatanModel;
use App\Models\AbsenIjinModel;
use App\Models\LemburModel;
use App\Models\AbsenRekapModel;

                $nikatasan = Session::get('nik');
                $hakakses = EmployeeModel::select('userpriv as akses')->where('NIK',$nikatasan)->first();
                $userpriv = $hakakses->akses;
                $userprivs = substr($userpriv,1,-2);
                $notif1 = EmployeeModel::select('tb_cuti.ID as idcuti','tb_datapribadi.Nama as nama','tb_cuti.Keterangan as keterangan','tb_datapribadi.photo','tb_cuti.TanggalMulaiCuti as tgl_mulai')
                                  ->leftjoin('tb_cuti','tb_datapribadi.NIK','=','tb_cuti.NIK')
                                  ->whereRaw(DB::raw('CASE WHEN tb_cuti.approve_1 = "N" and tb_cuti.approve_2 IS NULL THEN tb_datapribadi.atasan1 = "'.$nikatasan.'" 
                                    WHEN tb_cuti.approve_2 = "N" and tb_cuti.approve_1 = "Y" THEN tb_datapribadi.atasan2 = "'.$nikatasan.'" END'))                                  
                                  ->get();

                $sql = EmployeeModel::select('*')
                                  ->leftjoin('tb_cuti','tb_datapribadi.NIK','=','tb_cuti.NIK')
                                  ->whereRaw(DB::raw('CASE WHEN tb_cuti.approve_1 = "N" and tb_cuti.approve_2 IS NULL THEN tb_datapribadi.atasan1 = "'.$nikatasan.'" 
                                    WHEN tb_cuti.approve_2 = "N" and tb_cuti.approve_1 = "Y" THEN tb_datapribadi.atasan2 = "'.$nikatasan.'" END'))                                  
                                  ->count();
                                  #tb_datapribadi.Nama as nama','diagnosa','total_klaim','tglklaim
                $jumlahkes = KesehatanModel::select('*')
                            ->leftjoin('tb_datapribadi','tb_kesehatan.NIK','=','tb_datapribadi.NIK')
                            ->where('tb_kesehatan.approve','N')
                            ->count();

                $notifkes = KesehatanModel::select('tb_kesehatan.ID','tb_datapribadi.Nama as nama','diagnosa','total_klaim','tglklaim','tb_datapribadi.photo','tb_kesehatan.status')
                            ->leftjoin('tb_datapribadi','tb_kesehatan.NIK','=','tb_datapribadi.NIK')
                            ->where('tb_kesehatan.approve','N')
                            ->get();

                $jumlahijin = EmployeeModel::select('*')
                              ->leftjoin('absen_izin','tb_datapribadi.NIK','=','absen_izin.nik')
                              ->where('tb_datapribadi.atasan1',$nikatasan)
                              ->where('statusApp','0')
                              ->count();

                $daftarijin = EmployeeModel::select('tb_datapribadi.photo','absen_izin.id as id_izin','absen_izin.nama','absen_izin.stat','absen_izin.ket','absen_izin.tanggal as tanggalijin')
                              ->leftjoin('absen_izin','tb_datapribadi.NIK','=','absen_izin.nik')
                              ->where('tb_datapribadi.atasan1',$nikatasan)
                              ->where('statusApp','0')
                              ->get();

                // LEMBURRRR //     
                // $nikatasan hanya nama saja           
                $jumlahlembur = LemburModel::where('status','M')->where('NIK',$nikatasan)->count();
                $daftarlembur = LemburModel::select('tb_lembur.ID','tb_datapribadi.photo','tb_datapribadi.Nama as nama','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.Kegiatan')
                                ->leftjoin('tb_datapribadi','tb_lembur.NIKPemberiLembur','=','tb_datapribadi.NIK')
                                ->where('tb_lembur.status','M')
                                ->where('tb_lembur.NIK',$nikatasan)
                                ->get();

                //QUERY LEMBUR UNTUK ATASAN//
                $jumlahlemburan = LemburModel::where('status','AU')
                                            // ->orWhere('status','S')
                                            ->where('NIKPemberiLembur',$nikatasan)->count();
                $daftarlemburan = LemburModel::select('tb_lembur.ID','tb_datapribadi.photo','tb_datapribadi.Nama as nama','tb_lembur.TanggalMulaiLembur','tb_lembur.TanggalSelesaiLembur','tb_lembur.Kegiatan','tb_lembur.status as status',
                  DB::raw('CASE 
                    WHEN tb_lembur.status = "S" THEN "Selesai"  
                    WHEN tb_lembur.status = "AU" THEN "Ajukan Ubah"                  
                    ELSE "" 
                  END as statuslembur'))
                                ->leftjoin('tb_datapribadi','tb_lembur.NIKPemberiLembur','=','tb_datapribadi.NIK')
                                ->where('tb_lembur.status','AU')
                                // ->orWhere('tb_lembur.status','S')
                                ->where('tb_lembur.NIKPemberiLembur',$nikatasan)
                                ->get();

                  $daftarabsen = DB::select("SELECT 
                                  A.NIK, B.NAMA, B.PHOTO, ANY_VALUE(MONTH(A.TGL)) AS BULAN, 
                                  ANY_VALUE(YEAR(A.TGL)) AS TAHUN, SUM(A.SELISIH) AS SELISIH, ANY_VALUE(A.ID) AS ID_ABSEN 
                                  FROM absen_rekap A 
                                  LEFT JOIN tb_datapribadi AS B ON A.NIK = B.NIK
                                  WHERE 
                                  CASE
                                    WHEN A.approved1 = 'M' and A.approved2 IS NULL THEN B.atasan1 = 182656
                                    WHEN A.approved2 = 'M' and A.approved1 = 'Y' THEN B.atasan2 = 182656
                                  END
                                  GROUP BY A.NIK");

                                                   
                                                
                  $count = count($daftarabsen);

                ?>
                <!--END QUERY -->

<!-- Notifications CUTI -->
@if(Session::get('admin') == 2)

          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-odnoklassniki"></i> Cuti

              <span class="label label-success"><?php echo $sql; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <strong><?php echo $sql; ?></strong> Cuti Request</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                
                @foreach($notif1 as $notif1s)
                  <li><!-- start message -->
                    <a href="{{url('actionapprove',$notif1s->idcuti)}}">
                      <div class="pull-left">
                        <img src="{{ asset('image/Photo/'.$notif1s->photo) }}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$notif1s->nama}}
                        <small><i class="fa fa-clock-o"></i> {{$notif1s->tgl_mulai}} </small>
                      </h4>
                      <p>{{$notif1s->keterangan}}</p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
            </ul>
          </li>

<!-- Notifications Absen -->

<li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-book"></i>Absen

              <span class="label label-success"><?php echo $count; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <strong><?php echo $count; ?></strong> Absen Request</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                
                @foreach($daftarabsen as $daftarabsens)
                  <li><!-- start message -->
                    <a href="{{url('approveabsen',['id'=>$daftarabsens->nik, 'bulan'=>$daftarabsens->bulan, 'tahun'=>$daftarabsens->tahun])}}">
                      <div class="pull-left">
                        <img src="{{ asset('image/Photo/'.$daftarabsens->photo) }}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$daftarabsens->nama}}
                      </h4>
                      <p>Pengumpulan Absen</p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
            </ul>
          </li>


<!-- Notifications IJIN -->

          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-hand-paper-o"></i> Izin

              <span class="label label-success"><?php echo $jumlahijin; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <strong><?php echo $jumlahijin; ?></strong> Izin Request</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                
                @foreach($daftarijin as $daftarijins)
                  <li><!-- start message -->
                    <a href="{{url('prosesijin',$daftarijins->id_izin)}}">
                      <div class="pull-left">
                        <img src="{{ asset('image/Photo/'.$daftarijins->photo) }}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$daftarijins->nama}}
                        <small><i class="fa fa-clock-o"></i> {{$daftarijins->tanggalijin}} </small>
                      </h4>
                      <p>{{$daftarijins->stat}}</p>
                      <p>{{$daftarijins->ket}}</p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
            </ul>
          </li>
          @endif

<!-- NOTIFIKASI KESEHATAN -->
  <?php if ($userprivs=='1') { ?>
    
    <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-wheelchair"></i> Kesehatan
              <span class="label label-success">{{$jumlahkes}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{$jumlahkes}} Rembuistment Request</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($notifkes as $notifkess)
                  <li><!-- start message -->
                    <a href="{{ url('detailreq',$notifkess->ID) }}">
                      <div class="pull-left">
                        <img src="{{ asset('image/Photo/'.$notifkess->photo) }}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$notifkess->nama}} [ {{$notifkess->status}} ]
                        <small><i class="fa fa-clock-o"></i> {{$notifkess->tglklaim}}</small>
                      </h4>
                      <p>{{$notifkess->diagnosa}}</p>
                      <p>Rp. {{number_format ($notifkess->total_klaim,0,",",".") }},00</p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->                  
                </ul>
              </li>
              <li class="footer"><a href="{{ url('daftarreq') }}">See All Messages</a></li>
            </ul>
          </li>
  <?php } ?>

  <!-- NOTIFIKASI LEMBUR -->
  <?php if (Session::get('admin') == 1 or Session::get('admin') == 3) { ?>

     <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-hourglass-2"></i> Lembur
              <span class="label label-success">{{$jumlahlembur}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{$jumlahlembur}} Lembur Request</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($daftarlembur as $daftarlemburs)
                  <li><!-- start message -->
                    <a href="{{ url('detaillembur',$daftarlemburs->ID) }}">
                      <div class="pull-left">
                        <img src="{{ asset('image/Photo/'.$daftarlemburs->photo) }}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$daftarlemburs->nama}} 
                        <small><i class="fa fa-clock-o"></i> {{$daftarlemburs->TanggalMulaiLembur}}</small>
                      </h4>
                      <p>{{$daftarlemburs->Kegiatan}}</p>                      
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->                  
                </ul>
              </li>
              <li class="footer"><a href="{{ url('daftarreq') }}">See All Messages</a></li>
            </ul>
          </li>
    
  <?php } ?>

    <!-- NOTIFIKASI LEMBUR ATASAN-->
  <?php if (Session::get('admin') == 2) { ?>

     <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-hourglass-2"></i> Lembur
              <span class="label label-success">{{$jumlahlemburan}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{$jumlahlemburan}} Lembur Request</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($daftarlemburan as $daftarlemburans)
                  <li><!-- start message -->
                    <a href="{{ url('detaileditlembur',$daftarlemburans->ID) }}">
                      <div class="pull-left">
                        <img src="{{ asset('image/Photo/'.$daftarlemburans->photo) }}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$daftarlemburans->nama}} 
                        <small><i class="fa fa-clock-o"></i> {{$daftarlemburans->TanggalMulaiLembur}}<br><br>
                      
                        @if ($daftarlemburans->status == 'AU')
                          <label class="label bg-red">{{ $daftarlemburans->statuslembur }} </label>
                        @elseif ($daftarlemburans->status == 'S')
                          <label class="label bg-green">{{ $daftarlemburans->statuslembur }} </label>
                        @endif
                      
                        </small>
                      </h4>
                      <p>{{$daftarlemburans->Kegiatan}}</p>
                                            
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->                  
                </ul>
              </li>
              <li class="footer"><a href="{{ url('listeditlembur') }}">See All Messages</a></li>
            </ul>
          </li>
    
  <?php } ?>
