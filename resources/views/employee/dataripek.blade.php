<h3 class="title">Riwayat Kerja (3 (Tiga) Perusahaan Sebelumnya)</h3>
<form role="form" method="POST" action="{{ url('/updateripek',$data->NIK.'-'.$url) }}">
  {{ csrf_field() }}
    <div class="box-body">
    <div id="alertz4">
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
      <div class="input-group pull-right col-md-12">
      <div class="col-md-9" >
      <?php $no = 0; ?>
      @if($ripek)
        @foreach ($ripek as $ripeks)
        <?php $no++; ?>
        <h4>Perusahaan {{ $no }}</h4>
        <input type="hidden" name="id{{$no}}" value="{{ $ripeks->ID_rk }}" >
      <small>Nama Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="namaperu{{$no}}u"  class="form-control" placeholder="Nama Perusahaan" value="{{ $ripeks->NamaPerusahaan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Jabatan Terakhir</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="jabatan{{$no}}u"  class="form-control" placeholder="Jabatan Terakhir" value="{{ $ripeks->Jabatansblm }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Bagian / Divisi</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <input type="text" class="form-control" name="bagiandiv{{$no}}u" placeholder="Bagian / Divisi" value="{{ $ripeks->Divsblm }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Mulai Bekerja</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input type="date" class="form-control" name="mulaikerja{{$no}}u" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" value="{{ $ripeks->TanggalMasuksblm }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Sampai Bekerja</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input type="date" class="form-control" name="sampaikerja{{$no}}u" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" value="{{ $ripeks->TanggalKeluarsblm }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Alamat Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <textarea class="form-control" rows="3" name="alamatperu{{$no}}u"  placeholder="Alamat Perusahaan" >{{$ripeks->AlamatPerusahaan}}</textarea>
        </div>
        <!-- /.input group -->
      </div>
      <small>Telepon Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="teleponperu{{$no}}u"  class="form-control" placeholder="Telepon Perusahaan" value="{{ $ripeks->TelpPerusahaan }}">
        </div>
        <!-- /.input group -->
      </div>
      <small>Fax. Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="faxperu{{$no}}u"  class="form-control" placeholder="Fax. Perusahaan" value="{{ $ripeks->FaxPerusahaan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Pendapatan per Bulan </small>
      <i><small>(bersih, setelah dipotong pajak)</small></i>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="pendapatan{{$no}}u"  class="form-control" placeholder="Pendapatan per Bulan" value="{{ $ripeks->Pendapatan }}" data-inputmask='"mask": "Rp. 999999999"' data-mask> 
        </div>
        <!-- /.input group -->
      </div>
      <small>Fasilitas yang diperoleh</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <textarea class="form-control" rows="3" name="fasilitas{{$no}}u"  placeholder="Fasilitas yang diperoleh">{{$ripeks->Fasilitas}}</textarea>
        </div>
        <!-- /.input group -->
      </div>
      <hr width="95%" size="4" align="center">
        @endforeach
      @endif
      <?php $b=0 ?>
      @if($cripek==null)
      <?php $cripek = 0; ?>
      @endif
      <?php $b = 3-$cripek; 
      if($no){
        $no = $no;
        }  ?>
       
        @for($a=0;$a<$b;$a++)
        <?php $no++; ?>
        <h4>Perusahaan {{ $no }}</h4>
      <small>Nama Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="namaperu{{$no}}"  class="form-control" placeholder="Nama Perusahaan"  >
        </div>
        <!-- /.input group -->
      </div>
      <small>Jabatan Terakhir</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="jabatan{{$no}}"  class="form-control" placeholder="Jabatan Terakhir"  >
        </div>
        <!-- /.input group -->
      </div>
      <small>Bagian / Divisi</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <input type="text" class="form-control" name="bagiandiv{{$no}}" placeholder="Bagian / Divisi" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Mulai Bekerja</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input type="date"  class="form-control" name="mulaikerja{{$no}}" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Sampai Bekerja</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input type="date"  class="form-control" name="sampaikerja{{$no}}" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Alamat Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <textarea class="form-control" rows="3" name="alamatperu{{$no}}"  placeholder="Alamat Perusahaan" ></textarea>
        </div>
        <!-- /.input group -->
      </div>
      <small>Telepon Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="teleponperu{{$no}}"  class="form-control" placeholder="Telepon Perusahaan" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Fax. Perusahaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="faxperu{{$no}}"  class="form-control" placeholder="Fax. Perusahaan"  >
        </div>
        <!-- /.input group -->
      </div>
      <small>Pendapatan per Bulan </small>
      <i><small>(bersih, setelah dipotong pajak)</small></i>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" name="pendapatan{{$no}}"  class="form-control" placeholder="Pendapatan per Bulan"  data-inputmask='"mask": "Rp. 999999999"' data-mask> 
        </div>
        <!-- /.input group -->
      </div>
      <small>Fasilitas yang diperoleh</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <textarea class="form-control" rows="3" name="fasilitas{{$no}}"  placeholder="Fasilitas yang diperoleh"></textarea>
        </div>
        <!-- /.input group -->
      </div>
      <hr width="95%" size="4" align="center">
        @endfor
      </div>
      </div></div>
    
  </form>