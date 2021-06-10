  <!-- /.box-header -->
  <!-- form start -->
  <h3 class="title">Data Pribadi Karyawan</h3>
  <form role="form" method="POST" action="{{ url('/updateemployee',$data->NIK.'-'.$url) }}" enctype="multipart/form-data">
  {{ csrf_field() }}
    <div class="box-body">
    <div id="alertz">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-check"></i> {{ session('success') }}
        </div>
    @endif
    </div>
    <div class="box-footer">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ url($urlback) }}"><span type="button" class="btn btn-warning">Kembali</span></a>
    </div>
      <div class="input-group col-md-12">
      <div class="col-md-6">
      
      <h4>Data Pribadi</h4>
        <small>NIK</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>			
            <input type="text" id="nik" name="nik" class="form-control" placeholder="NIK" value="{{ $data->NIK }}" readonly="true" >
          </div>  
          <!-- /.input group -->
        </div>
        <small>Nama Lengkap</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="nama" name="nama"  class="form-control" placeholder="Nama Lengkap" value="{{ $data->Nama }}" required>
          </div>
          <!-- /.input group -->
        </div>
        <small>Tempat Lahir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <input type="text" id="tempatlahir" class="form-control" name="TempatLahir" placeholder="Tempat Lahir" value="{{ $data->TempatLahir }}" required>
          </div>
          <!-- /.input group -->
        </div>
        <small>Tanggal Lahir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control" name="tglLahir" placeholder="YYYY-MMM-DDD" id="tglLahir" value="{{ $data->TanggalLahir }}" required="true">
          </div>
          <!-- /.input group -->
        </div>

        <small>Golongan Darah</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-tint"></i>
            </div>
            <!-- <input type="date" class="form-control" name="tglLahir" placeholder="YYYY-MMM-DDD" id="tglLahir" value="{{ $data->TanggalLahir }}" required="true"> -->
            <select class="form-control" name="GolonganDarah">
              <option value="">-- Pilih Golongan Darah --</option>
              <option value="A" @if($data->gol_darah == 'A') selected @endif>A</option>
              <option value="AB" @if($data->gol_darah == 'AB') selected @endif>AB</option>
              <option value="B" @if($data->gol_darah == 'B') selected @endif>B</option>
              <option value="O" @if($data->gol_darah == 'O') selected @endif>O</option>
            </select>
          </div>
          <!-- /.input group -->
        </div>

        <small>Status (Pernikahan)</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-heart"></i>
            </div>
            <!-- <input type="date" class="form-control" name="tglLahir" placeholder="YYYY-MMM-DDD" id="tglLahir" value="{{ $data->TanggalLahir }}" required="true"> -->
            <select class="form-control" name="status_pernikahan">
              <option value="">-- Pilih Status Pernikahan --</option>
              <option value="1" @if($data->status_pernikahan == '1') selected @endif>Menikah</option>
              <option value="0" @if($data->status_pernikahan == '0') selected @endif>Belum Menikah</option>
              <option value="2" @if($data->status_pernikahan == '2') selected @endif>Cerai</option>
            </select>
          </div>
          <!-- /.input group -->
        </div>

        <small>Agama</small>
        <div class="form-group">
          <select class="form-control select2" name="agama" style="width: 100%;">
             @foreach ($agama as $agama)
              <option value="{{ $agama->idagama }}" @if($agama->idagama == $data->Agama) selected @endif> {{ $agama->nama_agama }} </option>
            @endforeach
          </select>
        </div>		
        <small>Jenis Kelamin</small>
        <div class="form-group">
          <div class="input-group">
            
            <div class="radio">
            <label>
              <input type="radio" name="jk" value="L" @if($data->jk == 'L') checked="true" @endif>
              Laki Laki
            </label>
            </div>
            <div class="radio">
            <label>
              <input type="radio" name="jk" value="P" @if($data->jk == 'P') checked="true" @endif>
              Perempuan
            </label>
            </div>
          </div>
          <!-- /.input group -->
        </div>

        <small>Alamat Tinggal</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <textarea class="form-control" rows="3" name="alamat"  placeholder="Alamat Tinggal" required>{{$data->Alamat}}</textarea>
          </div>
          <!-- /.input group -->
        </div>
      
        <small>No. KTP / SIM</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="ktp" class="form-control" name="ktp" placeholder="No. KTP / SIM" value="{{ $data->ktp_sim }}">
          </div>
          <!-- /.input group -->
        </div>

        <small>Alamat KTP</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <textarea class="form-control" rows="3" name="alamat_ktp"  placeholder="Alamat KTP">{{$data->Alamat_KTP}}</textarea>
          </div>
          <!-- /.input group -->
        </div>

        <small>Dokumen KTP/SIM</small>
        <br>
        @if($data->file_ktp)
          <a href="{{ asset('image/Dokumen/'.$data->file_ktp) }}" target="_blank"><span class="label label-info">Download KTP</span></a>
        @else
          <span class="label label-danger">File KTP Belum di Upload</span>
        @endif
        <br>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-file"></i>
            </div>
            <input class="form-control" type="file" name="file_ktp" id="file_ktp">
          </div>
          <!-- /.input group -->
        </div>

        <small>NPWP</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-credit-card"></i>
            </div>
            <input id="npwp" type="text" class="form-control" name="npwp" value="{{ $data->npwp }}" placeholder="NPWP" data-inputmask='"mask": "99.999.999.9-999.999"' data-mask>
            <!-- <input id="npwp2" type="text" name="npwp" class="form-control" placeholder="NPWP"> -->
          </div>
          <!-- /.input group -->
        </div>
        <small>Nama NPWP</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="nama_npwp" class="form-control" name="nama_npwp" placeholder="Nama NPWP" value="{{ $data->nama_npwp }}">
          </div>
          <!-- /.input group -->
        </div>
        <small>Alamat NPWP</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <textarea type="text" id="alamat_npwp" class="form-control" name="alamat_npwp" placeholder="Alamat NPWP"">{{ $data->alamat_npwp }}</textarea>
          </div>
          <!-- /.input group -->
        </div>
        <small>Dokumen NPWP</small>
        <br>
        @if($data->file_npwp)
          <a href="{{ asset('image/Dokumen/'.$data->file_npwp) }}" target="_blank"><span class="label label-info">Download NPWP</span></a>
        @else
          <span class="label label-danger">File NPWP Belum di Upload</span>
        @endif
        <br>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-file"></i>
            </div>
            <input class="form-control" type="file" name="file_npwp" id="file_npwp">
          </div>
          <!-- /.input group -->
        </div>
        <small>No. Rekening</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-money"></i>
            </div>
            <input type="number" id="norek" class="form-control" name="norek" placeholder="No. Rekening" value="{{ $data->norek }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Dokumen Buku Tabungan (Halaman No. Rek)</small>
        <br>
        @if($data->file_no_rek)
          <a href="{{ asset('image/Dokumen/'.$data->file_no_rek) }}" target="_blank"><span class="label label-info">Download Buku Tabungan (No. Rek)</span></a>
        @else
          <span class="label label-danger">File Buku Tabungan (No. Rek) Belum di Upload</span>
        @endif
        <br>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-file"></i>
            </div>
            <input class="form-control" type="file" name="file_no_rek" id="file_no_rek">
          </div>
          <!-- /.input group -->
        </div>
        <small>No. Jamsostek</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-credit-card"></i>
            </div>
            <input type="text" id="jamsostek" class="form-control" name="jamsostek" placeholder="Jamsostek" value="{{ $data->jamsostek }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Dokumen Jamsostek</small>
        <br>
        @if($data->file_jamsostek)
          <a href="{{ asset('image/Dokumen/'.$data->file_jamsostek) }}" target="_blank"><span class="label label-info">Download Jamsostek</span></a>
        @else
          <span class="label label-danger">File Jamsostek Belum di Upload</span>
        @endif
        <br>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-file"></i>
            </div>
            <input class="form-control" type="file" name="file_jamsostek" id="file_jamsostek">
          </div>
          <!-- /.input group -->
        </div>
        <small>No. BPJS Kesehatan</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-credit-card"></i>
            </div>
            <input type="text" id="bpjs" class="form-control" name="bpjs" placeholder="BPJS Kesehatan" value="{{ $data->bpjs }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Dokumen BPJS Kesehatan</small>
        <br>
        @if($data->file_bpjs)
          <a href="{{ asset('image/Dokumen/'.$data->file_bpjs) }}" target="_blank"><span class="label label-info">Download BPJS Kesehatan</span></a>
        @else
          <span class="label label-danger">File BPJS Kesehatan Belum di Upload</span>
        @endif
        <br>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-file"></i>
            </div>
            <input class="form-control" type="file" name="file_bpjs" id="file_bpjs">
          </div>
          <!-- /.input group -->
        </div>
        <small>Telephone</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-phone"></i>
            </div>
            <input type="text" id="notelepon" class="form-control" name="notelepon" placeholder="Telephone" value="{{ $data->NoTelepon }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Handphone</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-mobile-phone"></i>
            </div>
            <input type="text" id="NoHp" class="form-control" name="NoHp" placeholder="Handphone" required="true" value="{{ $data->NoHp }}">
          </div>
          <!-- /.input group -->
        </div>
      </div>

      <div class="col-md-6">
    <h4>Data Karyawan</h4>
      <small>Email Regular</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-envelope"></i>
            </div>
            <input type="text" id="emailreg" name="emailreg" class="form-control" placeholder="Email Regular" value="{{ $data->emailreg }}" required>
          </div>
          <!-- /.input group -->
        </div>
        <small>Email EDII (Internal) @edi-indonesia.co.id</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-envelope"></i>
            </div>
            <!-- <input type="text" id="email" name="email" class="form-control"  placeholder="Email EDII (Internal)" value="{{ str_replace('@edi-indonesia.co.id', '',$data->email) }}@edi-indonesia.co.id" required> -->
            @if(Session::get('admin') == '1')
            <input type="text" id="email" name="email" class="form-control"  placeholder="Email EDII (Internal)" value="{{ $emailedi }}" required>
            @else
            <input type="text" id="email" name="email" class="form-control"  placeholder="Email EDII (Internal)" value="{{ $emailedi }}" readonly="readonly" required>
            @endif
            <span class="input-group-addon">@edi-indonesia.co.id</span>
          </div>
          <!-- /.input group -->
        </div>
        <small>Extention Number</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-phone"></i>
            </div>
            <input type="text" id="noext" name="noext" class="form-control"  placeholder="Email EDII (Internal)" value="{{ $data->noext }}" required>
          </div>
          <!-- /.input group -->
        </div>
        <small>Status Kepegawaian</small>
        <div class="form-group">
          <select id="status" class="form-control select2" name="status" style="width: 100%;" disabled>
              <option value="">--Pilih Status Karyawan--</option>
             @foreach ($statuskar as $statuskar)
              <option value="{{ $statuskar->id }}" @if($statuskar->id == $data->statuskar) selected @endif> {{ $statuskar->status_kar }} </option>
              @endforeach
          </select>
        </div>
        <div id="vendor">
        <small>Vendor</small>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" class="form-control" name="Vendor" placeholder="Vendor" class="form-control" id="Vendor"  value="{{ $data->vendor }}" disabled>
            </div>
            <!-- /.input group -->
          </div>
        </div>
        @if($data->statuskar == 5 or $data->statuskar == 6)
        <small>Tanggal Kontrak</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control" name="tglKontrak" placeholder="YYYY-MMM-DDD" class="form-control pull-right" id="tglKontrak"  value="{{ $data->TglKontrak }}" disabled >
          </div>
          <!-- /.input group -->
        </div>
        <small>Akhir Tanggal Kontrak</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control" name="tglKontrakEnd" placeholder="YYYY-MMM-DDD" class="form-control pull-right" id="tglKontrak" value="{{ $data->TglKontrakEnd }}" disabled >
          </div>
          <!-- /.input group -->
        </div>
        @endif
        <small>Pangkat</small>
        <div class="form-group">
          <select id="pangkat" class="form-control select2" name="pangkat" style="width: 100%;" disabled>
          <option value="">--Pilih Pangkat--</option>
             @foreach ($pangkat as $pangkat)
              <option value="{{ $pangkat->id }}" @if($pangkat->id == $data->idpangkat) selected @endif> {{ $pangkat->pangkat }} </option>
              @endforeach
          </select>
        </div>
        <small>Jabatan</small>
        <div class="form-group">
          <select id="jabatan" class="form-control select2" name="jabatan" style="width: 100%;" disabled>
          <option value="">--Pilih Jabatan--</option>
              @foreach ($jabatan as $jabatan)
              <option value="{{ $jabatan->id }}" @if($jabatan->id == $data->idjabatan) selected @endif> {{ $jabatan->jabatan }} </option>
              @endforeach
          </select>
        </div>
        <small>Divisi</small>
        <div class="form-group">
          <select id="divisi" class="form-control select2" name="divisi" style="width: 100%;" disabled>
          <option value="">--Pilih Divisi--</option>
              @foreach ($divisi as $divisi)
              <option value="{{ $divisi->id }}" @if($divisi->id == $data->Divisi) selected @endif> {{ $divisi->nama_div_ext }} </option>
              @endforeach
          </select>
        </div>
        <small>Sub Divisi</small>
        <div class="form-group">
          <select id="subdivisi" class="form-control select2" name="subdivisi" style="width: 100%;" disabled >
          <option value="">--Pilih SubDivisi--</option>
              @foreach ($subdivisi as $subdivisi)
              <option value="{{ $subdivisi->id }}" @if($subdivisi->id == $data->SubDivisi) selected @endif> {{ $subdivisi->subdivisi }} </option>
              @endforeach
          </select>
        </div>
        <div id="gol">
        <small>Golongan</small>
          <div class="form-group">
            <select class="form-control select2" name="Golongan" style="width: 100%;" disabled>
            <option value="">--Pilih Golongan--</option>
                @foreach ($golongan as $golongan)
                <option value="{{ $golongan->id }}" @if($golongan->id == $data->Golongan) selected @endif> {{ $golongan->gol }} </option>
                @endforeach
            </select>
          </div>
        </div>
        <div id="golout">
        <small>Golongan Outsource</small>
          <div class="form-group">
            <select class="form-control select2" name="Golongan_out" style="width: 100%;" disabled>
            <option value="">--Golongan Outsource--</option>
                @foreach ($golonganout as $golonganout)
                <option value="{{ $golonganout->id }}" @if($golonganout->id == $data->Golongan_out) selected @endif> {{ $golonganout->gol }} </option>
                @endforeach
            </select>
          </div>
        <small>By Proyek ?</small>
          <div class="form-group">
            <select class="form-control select2" name="byproyek" id="byproyek" onchange="byproyekchange(this.value)" style="width: 100%;">
                <option value="">--Pilih By Proyek--</option>
                <option value="1" @if($data->byproyek == '1') selected @endif>Proyek</option>
                <option value="0" @if($data->byproyek == '2') selected @endif>Non Proyek</option>
            </select>
          </div>
        </div>
        <div id="proyekinput">
          <small>Proyek</small>
          <div class="form-group">
          <input type="text" id="proyek" class="form-control" name="proyek" placeholder="Proyek" value="{{$data->proyek}}" disabled>  
          </div>
        </div>
        <small>Tanggal Golongan</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control" name="TglGol" placeholder="YYYY-MMM-DDD" class="form-control pull-right" id="tglGolongan" value="{{ $data->tgl_sk_gol }}" disabled>
          </div>
          <!-- /.input group -->
        </div>
        <small>Atasan 1 (Langsung)</small>
        <div class="form-group">
          <select id="atasan1" class="form-control select2" name="atasan1" style="width: 100%;" disabled >
          <option value="">--Pilih Atasan Langsung--</option>
              @foreach ($atasan1 as $atasan1)
                <option value="{{ $atasan1->nik }}" @if($atasan1->nik == $data->atasan1) selected @endif> {{ $atasan1->atasan }} </option>
              @endforeach
          </select>
        </div>
        <small>Atasan 2</small>
        <div class="form-group">
          <select id="atasan2" class="form-control select2" name="atasan2" style="width: 100%;" disabled >
          <option value="">--Pilih Atasan Tidak Langsung--</option>
              @foreach ($atasan2 as $atasan2)
                <option value="{{ $atasan2->nik }}" @if($atasan2->nik == $data->atasan2) selected @endif> {{ $atasan2->atasan }} </option>
              @endforeach
          </select>
        </div>
        <small>Lokasi Kerja</small>
        <div class="form-group">
          <select id="lokker" class="form-control select2" name="LokasiKer" style="width: 100%;" disabled >
          <option value="">--Pilih Lokasi Kerja--</option>
              @foreach ($lokker as $lokker)
                <option value="{{ $lokker->id }}" @if($lokker->id == $data->LokasiKer) selected @endif> {{ $lokker->lokasi }} </option>
              @endforeach
          </select>
        </div>
        <small>Gaji</small>
        <div class="form-group">
          <input type="text" class="form-control" readonly="true" name="Gaji" value="{{ $gajifix }}" placeholder="Gaji">
        </div>
        <br>
        <br>
        <small>Tunjangan TMR</small>
        <div class="form-group">
          <input type="text" class="form-control" readonly="true" name="Tunj_tmr" value="{{ $tunj_tmrfix }}" placeholder="Tunjangan TMR">
        </div>
        <br>
        <br>
        <small>Tunjangan Jabatan</small>
        <div class="form-group">
          <input type="text" class="form-control" readonly="true" name="Tunj_jab" value="{{ $tunj_jabfix }}" placeholder="Tunjangan Jabatan">
        </div>
      </div>
      </div>
    </div>
    <div class="box-footer"> </div>
    <!-- /.box-body -->
      <div class="box-header">
        <h3 class="title">Histori Jabatan</h3>
      </div>
       @include('layouts.function')
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <div class="col-md-12">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>NO</th>
                <th>JABATAN</th>
                <th>NO.SK</th>
                <th>TGL. SK. JABATAN</th>
                <th>GOLONGAN</th>
                <th>TGL. SK. GOLONGAN</th>
                <th>DIVISI</th>
                <th>LOKASI</th>
              </tr>
              </thead>
              <tbody>
              <?php $a=0; ?>
              @foreach($historyjab as $historyjabs)
              <?php $a++; ?>
              <tr>
                <td><strong>{{ $a }}</strong></td>
                <td><strong>{{ $historyjabs-> Jabatan}}</strong></td>
                <td><strong>{{ $historyjabs-> no_sk}}</strong></td>
                <td><strong>{{ myDate($historyjabs-> tgl_sk_jab) }}</strong></td>
                <td><strong>{{ $historyjabs-> Gol}}</strong></td>
                <td><strong>{{ myDate($historyjabs->tgl_sk_gol) }}</strong></td>
                <td><strong>{{ $historyjabs-> divisi}}</strong></td>
                <td><strong>{{ $historyjabs-> lokasi}}</strong></td>
              </tr>
              @endforeach
              </tbody>
              
            </table>
          </div>
        </div>
      </div>
      <!-- /.box-body -->
  </form>

  
  
  