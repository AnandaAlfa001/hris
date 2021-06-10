<h3 class="title">Data Riwayat Pendidikan Non Formal</h3>
<div class="control-group" >
    <div class="controls2"> 
        <form role="form" autocomplete="off" method="POST" action="{{ url('/updateripennon',$data->NIK.'-'.$url) }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
          <div id="alertz6">
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
        @if($ripennon)
            @foreach ($ripennon as $ripennons)
                <div class="entry input-group col-md-12">
                <input type="hidden" name="id[]" value="{{ $ripennons->id_pnf }}" >
                    <div class="col-md-2">
                        <label class="control-label">Nama Kursus/Seminar</label>
                        <input class="form-control" name="namau[]" type="text" placeholder="Nama Kursus/Seminar" value="{{ $ripennons->nama_kursus }}"  />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Bidang Keahlian</label>
                        <input class="form-control" name="bidangu[]" type="text" placeholder="Bidang Keahlian" value="{{ $ripennons->keahlian }}"  />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Th.Kepesertaan</label>
                        <input class="form-control" name="tahunu[]" type="text" placeholder="Th.Kepesertaan" value="{{ $ripennons->thikut }}" data-inputmask='"mask": "9999"' data-mask />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Sertifikat</label>
                        <select class="form-control" placeholder="Sertifikat" name="sertifikatu[]" required>
                            <option value="0" @if($ripennons->Sertifikat == 0) selected @endif>Tidak Ada</option>
                            <option value="1" @if($ripennons->Sertifikat == 1) selected @endif>Ada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">File</label>
                        @if($ripennons->FileSertifikat)
                        <a href="{{ asset('image/Sertifikat/'.$ripennons->FileSertifikat) }}" target="_blank"> - Click to Download</a>
                        @endif
                        <input class="form-control" type="file" name="filesertifikatu[]" multiple />
                    </div>
                    <div class="col-md-1">
                    <label class="control-label">&nbsp; </label>
                    <span class="input-group-btn">
                    <a href="{{ url('/deleteripennon',$ripennons->id_pnf) }}">
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
                        <label class="control-label">Nama Kursus/Seminar</label>
                        <input class="form-control" name="nama[]" type="text" placeholder="Nama Kursus/Seminar" />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Bidang Keahlian</label>
                        <input class="form-control" name="bidang[]" type="text" placeholder="Bidang Keahlian" />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Th.Kepesertaan</label>
                        <input class="form-control" name="tahun[]" type="text" placeholder="Th.Kepesertaan" />
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Sertifikat</label>
                        <select class="form-control" placeholder="Sertifikat" name="sertifikat[]" required>
                            <option value="0" >Tidak Ada</option>
                            <option value="1" >Ada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">File Sertifikat</label>
                        <input class="form-control" type="file" name="filesertifikat[]" multiple />
                    </div>
                    <div class="col-md-1">
                    <label class="control-label">&nbsp; </label>
                    <span class="input-group-btn"> 
                        <button class="btn btn-success btn-add-hm2" type="button" >
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