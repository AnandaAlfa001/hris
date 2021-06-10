<h3 class="title">Data Orang Tua</h3>
<div class="control-group">
<div class="controls4">
<form role="form" method="POST" action="{{ url('/updatekeluarga',$data->NIK.'-'.$url) }}">
  {{ csrf_field() }}
    <div class="box-body">
    <div id="alertz3">
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
        <h4>Data Ayah</h4>
        <small>Nama Bapak</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="namabapak" name="namabp"  class="form-control" placeholder="Nama Bapak" value="{{ $data->nama_bapak }}">
          </div>
          <!-- /.input group -->
        </div>
        <small>Pekerjaan</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="Pekerjaan" name="pekerjaanbp"  class="form-control" placeholder="Pekerjaan" value="{{ $data->kerja_bapak }}">
          </div>
          <!-- /.input group -->
        </div>
        <small>Tempat Lahir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <input type="text" id="tempatlahir" class="form-control" name="TempatLahirbp" placeholder="Tempat Lahir" value="{{ $data->tmplhr_bapak }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Tanggal Lahir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control" name="tglLahirbp" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" value="{{ $data->tgllhr_bapak }}">
          </div>
          <!-- /.input group -->
        </div>
        <small>Alamat Rumah</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <textarea class="form-control" rows="3" name="alamatbp"  placeholder="Alamat Rumah" >{{$data->alamat_bapak}}</textarea>
          </div>
          <!-- /.input group -->
        </div>
        <small>Pendidikan Terakhir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="pendidikan" name="pendidikanbp"  class="form-control" placeholder="Pendidikan Terakhir" value="{{ $data->pnddk_bapak }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>agamabp</small>
        <div class="form-group">
          <select class="form-control select2" name="agamabp" style="width: 100%;">
             @foreach ($agama as $agamaa)
              <option value="{{ $agamaa->idagama }}" @if($agamaa->idagama == $data->agama_bapak) selected @endif> {{ $agamaa->nama_agama }} </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-md-6">
        <h4>Data Ibu</h4>
        <small>Nama Ibu</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="namaib" name="namaib"  class="form-control" placeholder="Nama Ibu" value="{{ $data->nama_ibu }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Pekerjaan</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="pekerjaanib" name="pekerjaanib"  class="form-control" placeholder="Pekerjaan" value="{{ $data->kerja_ibu }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Tempat Lahir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <input type="text" id="TempatLahirib" class="form-control" name="TempatLahirib" placeholder="Tempat Lahir" value="{{ $data->tmplhr_ibu }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Tanggal Lahir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control" name="tglLahirib" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" value="{{ $data->tgllhr_ibu }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Alamat Rumah</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-home"></i>
            </div>
            <textarea class="form-control" rows="3" name="alamatib"  placeholder="Alamat Rumah" >{{$data->alamat_ibu}}</textarea>
          </div>
          <!-- /.input group -->
        </div>
        <small>Pendidikan Terakhir</small>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-user"></i>
            </div>
            <input type="text" id="pendidikanib" name="pendidikanib"  class="form-control" placeholder="Pendidikan Terakhir" value="{{ $data->pnddk_ibu }}" >
          </div>
          <!-- /.input group -->
        </div>
        <small>Agama</small>
        <div class="form-group">
          <select class="form-control select2" name="agamaib" style="width: 100%;">
             @foreach ($agama as $agamas)
              <option value="{{ $agamas->idagama }}" @if($agamas->idagama == $data->agama_ibu) selected @endif> {{ $agamas->nama_agama }} </option>
              @endforeach
          </select>
        </div>
        </div>
      </div>
      <hr width="95%" size="4" align="center">
      <div class="input-group col-md-12">
      <div class="col-md-12">
      <h4>Data Istri/Suami</h4>
      <small>Nama Istri/Suami</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" id="namais" name="namais"  class="form-control" placeholder="Nama Istri/Suami" value="{{ $data->NamaPasangan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Pekerjaan</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" id="pekerjaanis" name="pekerjaanis"  class="form-control" placeholder="Pekerjaan" value="{{ $data->jobpasangan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Tempat Lahir</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-home"></i>
          </div>
          <input type="text" id="TempatLahiris" class="form-control" name="TempatLahiris" placeholder="Tempat Lahir" value="{{ $data->TmplLahirPasangan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Tanggal Lahir</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input type="date" class="form-control" name="tglLahiris" placeholder="YYYY-MMM-DDD" class="form-control" id="tglLahir" value="{{ $data->TanggalLahirPasangan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Pendidikan Terakhir</small>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-user"></i>
          </div>
          <input type="text" id="pendidikanis" name="pendidikanis"  class="form-control" placeholder="Pendidikan Terakhir" value="{{ $data->pndkpasangan }}" >
        </div>
        <!-- /.input group -->
      </div>
      <small>Agama</small>
      <div class="form-group">
        <select class="form-control select2" name="agamais" style="width: 100%;">
           @foreach ($agama as $agamad)
          <option value="{{ $agamad->idagama }}" @if($agamad->idagama == $data->agamapasangan) selected @endif> {{ $agamad->nama_agama }} </option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
      <hr width="95%" size="4" align="center">
    <h3 class="title">Data Anak</h3>

    @if($anak)
      @foreach ($anak as $anaks)
          <div class="entry input-group col-md-12">
          <input type="hidden" name="id[]" value="{{ $anaks->id }}" >
              <div class="col-md-2">
                  <label class="control-label">Nama Anak</label>
                  <input class="form-control" name="namau[]" type="text" placeholder="Nama Anak" value="{{ $anaks->NamaAnak }}" />
              </div>
              <div class="col-md-2">
                  <label class="control-label">Tanggal Lahir</label><br>
                  <input class="form-control" name="lahiru[]" type="date"  placeholder="YYYY-MMM-DDD" value="{{ $anaks->TanggalAnak }}" />
              </div>
              <div class="col-md-2">
                  <label class="control-label">Jenis kelamin</label>
                  <select class="form-control" name="jku[]">
                    <option value="">--JK--</option>
                    <option value="1" @if($anaks->jk == 1) selected @endif> Laki-Laki</option>
                    <option value="2" @if($anaks->jk == 2) selected @endif>Perempuan</option>
                  </select>
                  <!-- <div class="input-group">
                    <div class="radio">
                    <label>
                      <input type="radio" name="jk" value="1" @if($anaks->jk == 1) checked="true" @endif>
                      Laki Laki
                    </label>
                    </div>
                    <div class="radio">
                    <label>
                      <input type="radio" name="jk" value="2" @if($anaks->jk == 2) checked="true" @endif>
                      Perempuan
                    </label>
                    </div>
                  </div> -->
              </div>
              <div class="col-md-2">
                  <label class="control-label">Pendidikan</label>
                  <input class="form-control" name="pendidikanu[]" type="text" placeholder="Tingkat Terakhir jika Tidak Lulus" value="{{ $anaks->didikan }}" />
              </div>
              <div class="col-md-1">
              <label class="control-label">&nbsp; </label>
              <span class="input-group-btn"> 
              <a href="{{ url('/deleteanak',$anaks->id) }}">
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
              <label class="control-label">Nama Anak</label>
              <input class="form-control" name="nama[]" type="text" placeholder="Nama Anak" />
          </div>
          <div class="col-md-2">
              <label class="control-label">Tanggal Lahir</label><br>
              <input class="form-control" name="lahir[]" type="date" placeholder="YYYY-MMM-DDD" />
          </div>
          <div class="col-md-2">
              <label class="control-label">Jenis kelamin</label>
              <select class="form-control" name="jk[]">
                <option value="">--JK--</option>
                <option value="1">Laki-Laki</option>
                <option value="2">Perempuan</option>
              </select>
              <!-- <div class="input-group">
                    <div class="radio">
                    <label>
                      <input type="radio" name="jk" value="1" >
                      Laki Laki
                    </label>
                    </div>
                    <div class="radio">
                    <label>
                      <input type="radio" name="jk" value="2" >
                      Perempuan
                    </label>
                    </div>
              </div> -->
          </div>
          <div class="col-md-2">
              <label class="control-label">Pendidikan</label>
              <input class="form-control" name="pendidikan[]" type="text" placeholder="Tingkat Terakhir jika Tidak Lulus" />
          </div>
          <div class="col-md-1">
          <label class="control-label">&nbsp; </label>
          <span class="input-group-btn"> 
              <button class="btn btn-success btn-add-hm4" type="button" >
              <!-- onclick="return confirm('Apakah anda yakin mau melanjutkan?')" -->
                  <span class="glyphicon glyphicon-plus"></span>
              </button>
          </span>
          </div>
          <span><br><br><br><br></span>
      </div> 
    </div>
    <!-- /.box-body -->

  </form>
  </div>
  <div class="box-footer">
  </div>
  </div>