<h3 class="title">Data Riwayat Penyakit</h3>
<div class="control-group" >
    <div class="controls"> 
        <form role="form" autocomplete="off" method="POST" action="{{ url('/updateriwayatpenyakit',$data->NIK.'-'.$url) }}">
          {{ csrf_field() }}
          <div class="box-body">
          <div id="alertz5">
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
        @if($riwayatpenyakit)
            @foreach ($riwayatpenyakit as $riwayatpenyakits)
            <div class="entry input-group col-md-12">
                <input type="hidden" name="id[]" value="{{ $riwayatpenyakits->id }}" >
                <div class="col-md-2">
                    <label class="control-label">Tahun</label>
                    <input class="form-control" name="tahunu[]" type="text" placeholder="Tahun" value="{{ $riwayatpenyakits->tahun }}" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">Nama Penyakit</label>
                    <input class="form-control" name="nama_penyakitu[]" type="text" placeholder="Nama Penyakit" value="{{ $riwayatpenyakits->nama_penyakit }}" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Dirawat</label>
                    <select id="status" class="form-control" name="dirawatu[]" style="width: 100%;" required>
                        <option value="">-- Pilih Dirawat --</option>
                        <option value="0" @if($riwayatpenyakits->dirawat == '0') selected @endif>Tidak Dirawat</option>
                        <option value="1" @if($riwayatpenyakits->dirawat == '1') selected @endif>Dirawat</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="control-label">Lama Rawat</label>
                    <input class="form-control" name="lama_rawatu[]" type="text" placeholder="Lama Rawat" value="{{ $riwayatpenyakits->lama_rawat }}" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">Cacat</label>
                    <input class="form-control" name="cacatu[]" type="text" placeholder="Cacat" value="{{ $riwayatpenyakits->cacat }}" />
                </div>
                <div class="col-md-1">
                <label class="control-label">&nbsp; </label>
                <span class="input-group-btn"> 
                <a href="{{ url('/deleteriwayatpenyakit',$riwayatpenyakits->id) }}">
                    <button class="btn btn-danger" type="button" onclick="return confirm('Apakah anda yakin mau menghapus ini?')">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </a>
                </span>
                </div>
                <span><br><br><br><br></span>
            </div>
            @endforeach
        @endif
            <div class="entry input-group col-md-12">
                <div class="col-md-2">
                    <label class="control-label">Tahun</label>
                    <input class="form-control" name="tahun[]" type="text" placeholder="Tahun" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">Nama Penyakit</label>
                    <input class="form-control" name="nama_penyakit[]" type="text" placeholder="Nama Penyakit" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Dirawat</label>
                        <select id="status" class="form-control" name="dirawat[]" style="width: 100%;" required>
                            <option value="">-- Pilih Dirawat --</option>
                            <option value="0">Tidak Dirawat</option>
                            <option value="1">Dirawat</option>
                        </select>
                </div>
                <div class="col-md-1">
                    <label class="control-label">Lama Rawat</label>
                    <input class="form-control" name="lama_rawat[]" type="text" placeholder="Lama Rawat" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">Cacat</label>
                    <input class="form-control" name="cacat[]" type="text" placeholder="Cacat" />
                </div>
                <div class="col-md-1">
                <label class="control-label">&nbsp; </label>
                <span class="input-group-btn"> 
                    <button class="btn btn-success btn-add-hm" type="button" >
                    <!-- onclick="return confirm('Apakah anda yakin mau melanjutkan?')" -->
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
                </div>
                <span><br><br><br><br></span>
            </div>  
        </div>
        </form>
    </div>
</div>     
    
    
