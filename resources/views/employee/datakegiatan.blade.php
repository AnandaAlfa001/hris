<h3 class="title">Data Kegiatan Organisasi</h3>
<div class="control-group" id="fields">
    <div class="controls3"> 
        <form role="form" autocomplete="off" method="POST" action="{{ url('/updatekegiatan',$data->NIK.'-'.$url) }}">
          {{ csrf_field() }}
          <div class="box-body">
          <div id="alertz7">
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
        @if($kegiatan)
            @foreach ($kegiatan as $kegiatans)
                <div class="entry input-group col-md-12">
                <input type="hidden" name="id[]" value="{{ $kegiatans->id_org }}" >
                    <div class="col-md-2">
                        <label class="control-label">Nama Organisasi</label>
                        <input class="form-control" name="namau[]" type="text" placeholder="Nama Organisasi" value="{{ $kegiatans->nama_organisasi }}"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Kedudukan</label>
                        <input class="form-control" name="kedudukanu[]" type="text" placeholder="Kedudukan" value="{{ $kegiatans->kedudukan }}"/>
                    </div>
                    <div class="col-md-1">
                        <label class="control-label">Th.Bergabung</label>
                        <input class="form-control" name="masuku[]" type="text" placeholder="Tgl.Bergabung" value="{{ $kegiatans->th_gabung }}"/>
                    </div>
                    <div class="col-md-1">
                        <label class="control-label">Th.Berhenti</label>
                        <input class="form-control" name="keluaru[]" type="text" placeholder="Tgl.Berhenti" value="{{ $kegiatans->th_berhenti }}"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Alasan Berhenti</label>
                        <input class="form-control" name="alasanu[]" type="text" placeholder="Alasan Berhenti" value="{{ $kegiatans->alshenti }}"/>
                    </div>
                    <div class="col-md-1">
                    <label class="control-label">&nbsp; </label>
                    <span class="input-group-btn"> 
                    <a href="{{ url('/deletekegiatan',$kegiatans->id_org) }}">
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
                        <label class="control-label">Nama Organisasi</label>
                        <input class="form-control" name="nama[]" type="text" placeholder="Nama Organisasi" />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Kedudukan</label>
                        <input class="form-control" name="kedudukan[]" type="text" placeholder="Kedudukan" />
                    </div>
                    <div class="col-md-1">
                        <label class="control-label">Th.Bergabung</label>
                        <input class="form-control" name="masuk[]" type="text" placeholder="Tgl.Bergabung" />
                    </div>
                    <div class="col-md-1">
                        <label class="control-label">Th.Berhenti</label>
                        <input class="form-control" name="keluar[]" type="text" placeholder="Tgl.Berhenti" />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Alasan Berhenti</label>
                        <input class="form-control" name="alasan[]" type="text" placeholder="Alasan Berhenti" />
                    </div>
                    <div class="col-md-1">
                    <label class="control-label">&nbsp; </label>
                    <span class="input-group-btn "> 
                        <button class="btn btn btn-success btn-add-hm3" type="button" >
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
    