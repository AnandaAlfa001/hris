<style type="text/css">
    #form_ripen input, #form_ripen select {
        font-size: 12px;
    }
</style>
<h3 class="title">Data Riwayat Pendidikan</h3>
<div class="control-group" >
    <div class="controls"> 
        <form role="form" autocomplete="off" method="POST" action="{{ url('/updateripen',$data->NIK.'-'.$url) }}" enctype="multipart/form-data" id="form_ripen">
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
        @if($ripen)
            @foreach ($ripen as $ripens)
            <div class="entry input-group col-md-12">
                <input type="hidden" name="id[]" value="{{ $ripens->id }}" >
                <div class="col-md-2">
                    <label class="control-label">Jenjang</label>
                      <select id="status" class="form-control" name="jenjangu[]" style="width: 100%;" required>
                       @foreach ($jenjang as $jenjangs)
                        <option value="{{ $jenjangs->id_j }}" @if($jenjangs->id_j == $ripens->Jenjang) selected @endif> {{ $jenjangs->jenjang }}</option>
                      @endforeach
                      </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Nama Sekolah</label>
                    <input class="form-control" name="namau[]" type="text" placeholder="Nama Sekolah" value="{{ $ripens->Sekolah_Institut }}" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Jurusan</label>
                    <input class="form-control" name="jurusanu[]" type="text" placeholder="Jurusan" value="{{ $ripens->Jurusan }}" />
                </div>
                <div class="col-md-1">
                    <label class="control-label">Th. Masuk</label>
                    <input class="form-control" name="masuku[]" type="text" placeholder="Th. Masuk" value="{{ $ripens->PeriodeIn }}" />
                </div>
                <div class="col-md-1">
                    <label class="control-label">Th. Lulus</label>
                    <input class="form-control" name="lulusu[]" type="text" placeholder="Th. Lulus" value="{{ $ripens->PeriodeOut }}" />
                </div>
                <div class="col-md-1">
                    <label class="control-label">IPK/Nilai</label>
                    <input class="form-control" name="ipku[]" type="text" placeholder="IPK/Nilai" value="{{ $ripens->ipk }}" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">File Ijazah & Transkip Nilai</label>
                    @if($ripens->id_dokumen)
                        @if(strpos($ripens->FileDokumen, "Pendidikan/") !== false)
                        <?php
                            $path_file = str_replace("public/", "", $ripens->FileDokumen);
                        ?>
                        <a href="http://192.168.5.110/storage/{{$path_file}}" target="_blank"> - Click to Download</a>
                        @else
                        <a href="{{ asset('image/Dokumen/'.$ripens->FileDokumen) }}" target="_blank"> - Click to Download</a>
                        @endif
                    @endif
                    <input class="form-control" type="file" name="fileijazahu[]" multiple />
                </div>
                <div class="col-md-1">
                <label class="control-label">&nbsp; </label>
                <span class="input-group-btn"> 
                <a href="{{ url('/deleteripen',$ripens->id) }}">
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
                    <label class="control-label">Jenjang</label>
                    <select class="form-control" placeholder="Jenjang" name="jenjang[]"  style="width: 100%;" required>
                      @foreach ($jenjang as $jenjangs)
                        <option value="{{ $jenjangs->id_j }}" > {{ $jenjangs->jenjang }}</option>
                      @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Nama Sekolah</label>
                    <input class="form-control" name="nama[]" type="text" placeholder="Nama Sekolah" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Jurusan</label>
                    <input class="form-control" name="jurusan[]" type="text" placeholder="Jurusan" />
                </div>
                <div class="col-md-1">
                    <label class="control-label">Th. Masuk</label>
                    <input class="form-control" name="masuk[]" type="text" placeholder="Th. Masuk" />
                </div>
                <div class="col-md-1">
                    <label class="control-label">Th. Lulus</label>
                    <input class="form-control" name="lulus[]" type="text" placeholder="Th. Lulus" />
                </div>
                <div class="col-md-1">
                    <label class="control-label">IPK/Nilai</label>
                    <input class="form-control" name="ipk[]" type="text" placeholder="IPK/Nilai" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">File Ijazah & Transkip Nilai</label>
                    <input class="form-control" type="file" name="fileijazah[]" multiple />
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

<script type="text/javascript">
    function downloadfile_portalsales(type,id)
    {
        console.log(type);
        console.log(id);
        window.open("http://192.168.5.110/reference/downloadfile_dokumen/"+type+"/"+id,"Preview Pajak","scrollbars=yes, resizable=yes,width=1100,height=700");
    }
</script>
    
