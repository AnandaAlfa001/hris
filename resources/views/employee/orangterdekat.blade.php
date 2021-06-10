<h3 class="title">Data Orang Terdekat Yang Bisa Dihubungi</h3>
<div class="control-group" >
    <div class="controls5"> 
        <form role="form" autocomplete="off" method="POST" action="{{ url('/updateorangterdekat',$data->NIK.'-'.$url) }}">
          {{ csrf_field() }}
          <div class="box-body">
          <div id="alertz9">
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
        @if($orangterdekat)
            @foreach ($orangterdekat as $orangterdekats)
            <div class="entry input-group col-md-12">
                <input type="hidden" name="id[]" value="{{ $orangterdekats->id }}" >
                <div class="col-md-3">
                    <label class="control-label">Nama</label>
                    <input class="form-control" name="namau[]" type="text" placeholder="Nama" value="{{ $orangterdekats->nama }}" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Status Dalam Keluarga</label>
                    <input class="form-control" name="statusu[]" type="text" placeholder="Status Dalam Keluarga" value="{{ $orangterdekats->status }}" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">No. Telepon Yang bisa dihubungi</label>
                    <input class="form-control" name="telpu[]" type="text" placeholder="No. Telepon Yang bisa dihubungi" value="{{ $orangterdekats->no_telp }}" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">Alamat</label>
                    <input class="form-control" name="alamatu[]" type="text" placeholder="Alamat" value="{{ $orangterdekats->alamat }}" />
                </div>
                
                <div class="col-md-1">
                <label class="control-label">&nbsp; </label>
                <span class="input-group-btn"> 
                <a href="{{ url('/deleteorangterdekat',$orangterdekats->id) }}">
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
                <div class="col-md-3">
                    <label class="control-label">Nama</label>
                    <input class="form-control" name="nama[]" type="text" placeholder="Nama" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Status Dalam Keluarga</label>
                    <input class="form-control" name="status[]" type="text" placeholder="Status Dalam Keluarga" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">No. Telepon Yang bisa dihubungi</label>
                    <input class="form-control" name="telp[]" type="text" placeholder="No. Telepon Yang bisa dihubungi" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">Alamat</label>
                    <input class="form-control" name="alamat[]" type="text" placeholder="Alamat" />
                </div>            
                <div class="col-md-1">
                <label class="control-label">&nbsp; </label>
                <span class="input-group-btn"> 
                    <button class="btn btn-success btn-add-hm5" type="button" >
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
    
    
