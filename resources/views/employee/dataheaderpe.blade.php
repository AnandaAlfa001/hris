<h3 class="title">Project Experience Karyawan</h3>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{ url('saveupdateheaderpe') }}">
                {{ csrf_field() }}
                <div class="input-group col-md-12">
                  <div class="col-md-12">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Posisi yang diusulkan</label> 
                          <input type="hidden" class="form-control" name="idhead" placeholder="" value="{{$headerpe->idhead}}" required>
                          <input type="hidden" class="form-control" name="nik" placeholder="" value="{{$headerpe->nikhead}}" required>                     
                          <input type="text" class="form-control" name="posisi" placeholder="Posisi yang diusulkan" value="{{$headerpe->posisihead}}" required>
                        </div><br>
                        <div class="form-group">
                          <label>Nama Perusahaan</label>
                          <input type="text" class="form-control" name="akhir_kontrak" placeholder="Nama Perusahaan" value="PT. EDI Indonesia" readonly required>
                        </div><br>
                        <div class="form-group">
                          <label>Nama Personil</label>
                          <input type="text"  class="form-control" name="akhir_kontrak" placeholder="Nama Personil" value="{{$headerpe->namahead}}" readonly required>
                        </div><br>
                        <div class="form-group">
                          <label>Tempat Lahir</label>
                          <input type="text"  class="form-control" name="akhir_kontrak" placeholder="Tempat Lahir" value="{{$headerpe->TempatLahir}}" required>
                        </div><br>
                        <div class="form-group">
                          <label>Tanggal Lahir</label>
                          <input type="date" data-date-split-input="true" class="form-control" name="akhir_kontrak" placeholder="YYYY-MMM-DDD" value="{{$headerpe->TanggalLahir}}" required>
                        </div><br>
                        <div class="form-group">
                          <label>Pendidikan (Lembaga Pendidikan, tempat, dan tahun tamat belajar, dilampirkan rekaman ijazah)</label>
                          <textarea type="text" class="form-control" name="pendidikan_formal" placeholder="Pendidikan Formal" required>{{$headerpe->didikan}}</textarea>
                        </div><br>
                        <div class="form-group">
                          <label>Pendidikan Non Formal</label>
                          <textarea type="text" class="form-control" name="pendidikan_nonformal" placeholder="Pendidikan Non Formal" required>{{$headerpe->didikannf}}</textarea>
                        </div>    
                        <div class="form-group">
                          <label>Penguasaan Bahasa Inggris dan Bahasa Indonesia  </label>
                          <select name="penguasaanbhs" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                          </select>
                        </div>
                        <!-- <div class="form-group">
                            <label>Bulan Tahun AWAL</label><br>
                            <div class="col-xs-5">
                              <select class="form-control" name="bulan_awal">
                                <option value="" @if($headerpe->bulan_awal == '') selected @endif>--Pilih Bulan Awal--</option>
                                <option value="Januari" @if($headerpe->bulan_awal == 'Januari') selected @endif>Januari</option>
                                <option value="Februari" @if($headerpe->bulan_awal == 'Februari') selected @endif>Februari</option>
                                <option value="Maret" @if($headerpe->bulan_awal == 'Maret') selected @endif>Maret</option>
                                <option value="April" @if($headerpe->bulan_awal == 'April') selected @endif>April</option>
                                <option value="Mei" @if($headerpe->bulan_awal == 'Mei') selected @endif>Mei</option>
                                <option value="Juni" @if($headerpe->bulan_awal == 'Juni') selected @endif>Juni</option>
                                <option value="Juli" @if($headerpe->bulan_awal == 'Juli') selected @endif>Juli</option>
                                <option value="Agustus" @if($headerpe->bulan_awal == 'Agustus') selected @endif>Agustus</option>
                                <option value="September" @if($headerpe->bulan_awal == 'September') selected @endif>September</option>
                                <option value="Oktober" @if($headerpe->bulan_awal == 'Oktober') selected @endif>Oktober</option>
                                <option value="November" @if($headerpe->bulan_awal == 'November') selected @endif>November</option>                                
                                <option value="Desember" @if($headerpe->bulan_awal == 'Desember') selected @endif>Desember</option>
                              </select>
                            </div>
                            <div class="col-xs-2">
                                <label>s/d</label>
                            </div>
                            <div class="col-xs-5">
                               <div class="form-group">
                                <select class="form-control" name="tahun_awal">
                                    <option value="">--Pilih Tahun Awal--</option>
                                    @foreach($tahundropdowns as $tahun)
                                    <option value="{{$tahun->tahun}}" @if($tahun->tahun == $headerpe->tahun_awal) selected @endif>{{$tahun->tahun}}</option>
                                    @endforeach
                                </select>                                
                                </div> 
                            </div><br>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Bulan Tahun AKHIR</label><br>
                            <div class="col-xs-5">
                            <select class="form-control" name="bulan_akhir">                                
                                  <option value="" @if($headerpe->bulan_akhir == '') selected @endif>--Pilih Bulan Awal--</option>
                                  <option value="Januari" @if($headerpe->bulan_akhir == 'Januari') selected @endif>Januari</option>
                                  <option value="Februari" @if($headerpe->bulan_akhir == 'Februari') selected @endif>Februari</option>
                                  <option value="Maret" @if($headerpe->bulan_akhir == 'Maret') selected @endif>Maret</option>
                                  <option value="April" @if($headerpe->bulan_akhir == 'April') selected @endif>April</option>
                                  <option value="Mei" @if($headerpe->bulan_akhir == 'Mei') selected @endif>Mei</option>
                                  <option value="Juni" @if($headerpe->bulan_akhir == 'Juni') selected @endif>Juni</option>
                                  <option value="Juli" @if($headerpe->bulan_akhir == 'Juli') selected @endif>Juli</option>
                                  <option value="Agustus" @if($headerpe->bulan_akhir == 'Agustus') selected @endif>Agustus</option>
                                  <option value="September" @if($headerpe->bulan_akhir == 'September') selected @endif>September</option>
                                  <option value="Oktober" @if($headerpe->bulan_akhir == 'Oktober') selected @endif>Oktober</option>
                                  <option value="November" @if($headerpe->bulan_akhir == 'November') selected @endif>November</option>                                
                                  <option value="Desember" @if($headerpe->bulan_akhir == 'Desember') selected @endif>Desember</option>
                              </select>
                            </div>
                            <div class="col-xs-2">
                                <label>s/d</label>
                            </div>
                            <div class="col-xs-5">
                               <div class="form-group">
                               <select class="form-control" name="tahun_akhir">
                                  <option value="">--Pilih Tahun Akhir--</option>
                                  @foreach($tahundropdowns as $tahun)
                                  <option value="{{$tahun->tahun}}" @if($tahun->tahun == $headerpe->tahun_akhir) selected @endif>{{$tahun->tahun}}</option>
                                  @endforeach
                                </select>
                                </div>  
                            </div><br>
                        </div>
                        <br>            -->  
                    </div>
                    <!-- /.box-body -->
                  </div>                  
                </div>
                  <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <button type="reset" class="btn btn-danger">Reset</button>
                  <a href="{{ url('headerpe',$headerpe->nikhead) }}"><span type="button" class="btn btn-warning">Kembali</span></a>
                </div>
                </form>
