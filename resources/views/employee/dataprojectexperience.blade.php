                <h3 class="title">Project Experience Karyawan</h3>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{ url('saveprojectex') }}">
                {{ csrf_field() }}
                <div class="input-group col-md-12">
                  <div class="col-md-12">
                    <div class="box-body">
                    <div id="alertz2">
                      @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="icon fa fa-check"></i> {{ session('success') }}
                            </div>
                      @endif
                    </div>
                        <div class="form-group">
                          <label>Nama Project</label> 
                          <input type="hidden" class="form-control" name="nikprojectex" placeholder="" value="{{$headerpe->nikhead}}" required>
                          <input type="hidden" class="form-control" name="idprojectex" placeholder="" value="{{$headerpe->idhead}}" required>                     
                          <input type="text" class="form-control" name="nama_project" placeholder="Nama Project" value="" required>
                        </div><br>
                        <div class="form-group">
                          <label>Lokasi Project</label>
                          <input type="text" class="form-control" name="lokasi_project" placeholder="Lokasi project" value="" required>
                        </div><br>
                        <div class="form-group">
                          <label>Pengguna Jasa</label>
                          <input type="text"  class="form-control" name="pengguna_jasa" placeholder="Pengguna Jasa" value="" required>
                        </div><br>
                        <div class="form-group">
                          <label>Nama Perusahaan</label>
                          <input type="text"  class="form-control" name="nama_perusahaan" placeholder="Nama Perusahaan" value="PT. EDI Indonesia" readonly required>
                        </div><br>
                        <div class="form-group">
                          <label>Uraian Tugas</label>
                          <textarea type="text" class="form-control" name="uraian_tugas" placeholder="Uraian Tugas" required></textarea>
                        </div><br>
                        <div class="form-group">
                          <label>Waktu Pelaksanaan</label>
                          <select class="form-control" name="waktupel">
                            <option value="">--Pilih Waktu Pelaksanaan--</option>
                            @foreach($tahundropdowns as $tahunmen)
                            <option value="{{$tahunmen->tahun}}">{{$tahunmen->tahun}}</option>
                            @endforeach
                          </select>
                        </div><br>
                        <div class="form-group">
                          <label>Posisi Penugasan</label>
                          <input type="text"  class="form-control" name="posisi_pen" placeholder="Tempat Lahir" value="" required>
                        </div><br>
                        <div class="form-group">
                          <label>Status Kepegawaian</label>
                          <select class="form-control" name="statuskar">
                            <option value="">--Pilih Status Karyawan--</option>
                            @foreach($statuskaryawan as $statuskar)
                            <option value="{{$statuskar->id}}">{{$statuskar->status_kar}}</option>
                            @endforeach
                          </select>
                        </div><br>
                                   
                    </div>
                    <!-- /.box-body -->
                  </div>                  
                </div>
                  <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <button type="reset" class="btn btn-danger">Reset</button>
                </div>
                </form>

      <div class="box-body">
        <div class="table-responsive">
          <div class="col-md-12">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>NO</th>
                <th>Nama Project</th>
                <th>Lokasi Project</th>
                <th>Pengguna Jasa</th>
                <th>Nama Perusahaan</th>
                <th>Uraian Tugas</th>
                <th>Waktu Pelaksanaan</th>
                <th>Posisi Penugasan</th>
                <th>Status Penugasan</th>
              </tr>
              </thead>
              <tbody>
              <?php $a=0; ?>
              @foreach($tablemen as $tablemens)
              <?php $a++; ?>
              <tr>
                <td>{{ $a }}</td>
                <td>{{ $tablemens->nama}}</td>
                <td>{{ $tablemens->lokasi }}</td>
                <td>{{ $tablemens->pengguna}}</td>
                <td>{{ $tablemens->perusahaan }}</td>
                <td>{{ $tablemens->uraian_tugas}}</td>
                <td>{{ $tablemens->waktu_pelaksanaan}}</td>
                <td>{{ $tablemens->posisi}}</td>
                <td>{{ $tablemens->statusbro}}</td>
              </tr>
              @endforeach
              
              </tbody>
              
            </table>
          </div>
        </div>
      </div>

