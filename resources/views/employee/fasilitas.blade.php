<h3 class="title">Fasilitas Karyawan</h3>
  <!-- /.box-header -->
  <!-- form start -->
  <form role="form" method="POST" action="{{ url('/',$data->NIK) }}">
  
  {{ csrf_field() }}
    <div class="box-body">
    <div id="alertz8">
    @if (session('success'))
          <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <i class="icon fa fa-check"></i> {{ session('success') }}
          </div>
      @endif
      </div>
      <div class="box-footer"></div>
      <div class="input-group col-md-7">
        <div class="col-md-5">
          <small>Jatah Cuti Taunan</small>
          
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" id="nik" name="nik" class="form-control" placeholder="Jatah Cuti Taunan" required >
            </div>  
            <!-- /.input group -->
          </div>
        </div>
        <div class="col-md-2">
          <span><br></span>
          <label>Hari</label>
        </div>
      </div>
      <div class="input-group col-md-7">
        <div class="col-md-5">
          <small>Benefit Kesehatan</small>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" id="nik" name="nik" class="form-control" placeholder="Benefit Kesehatan" required >
            </div>  
            <!-- /.input group -->
          </div>
        </div>
        <div class="col-md-2">
          <span><br></span>
          <label>Beserta Keluarga</label>
        </div>
      </div>
      <div class="input-group col-md-7">
        <div class="col-md-7">
          <small>Benefit Kesehatan Rawat Gigi</small>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" id="nik" name="nik" class="form-control" placeholder="Benefit Kesehatan Rawat Gigi" required >
            </div>  
            <!-- /.input group -->
          </div>
        </div>
      </div>
      <div class="input-group col-md-7">
        <div class="col-md-7">
          <small>Benefit Kesehatan Rawat Kacamata</small>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" id="nik" name="nik" class="form-control" placeholder="Benefit Kesehatan Rawat Kacamata" required >
            </div>  
            <!-- /.input group -->
          </div>
        </div>
      </div>
      <div class="input-group col-md-7">
        <div class="col-md-7">
          <small>Benefit Kesehatan Melahirkan</small>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" id="nik" name="nik" class="form-control" placeholder="Benefit Kesehatan Melahirkan" required >
            </div>  
            <!-- /.input group -->
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
  
  