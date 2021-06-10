<h3 class="title">Data Klaim Rawat Jalan</h3>
<div class="control-group">
<div class="controls">
<form role="form" method="POST" enctype="multipart/form-data" action="{{ url('/saverawatjalan') }}">
  {{ csrf_field() }}
    <div class="box-body">
      <div id="alertz">
        @if (count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           Mohon data dilengkapi
        </div>
        @endif
      </div>
      <div id="alertz2">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           {{ session('error') }}
        </div>
        @endif
      </div>
      <div class="input-group col-md-12">
          <div class="col-md-6">
            <div class="form-group">
                <label>NIK *</label>
                <input type="text" class="form-control" id="nikjalan" name="nik" placeholder="NIK" readonly required>
            </div>
                <input type="hidden" class="form-control" id="status" name="status">
            <label>Nama Pasien *</label>
            <div class="input-group input-group-sm">
              <select class="form-control select2" id="pasien" name="pasien" style="width:100%;">
                <option value="">---Pilih Karyawan--</option>
                  @foreach($data as $datas)
                  <option value="{{$datas->NIK}}|{{$datas->status}}">{{$datas->pasien}}</option>
                  @endforeach
                </select>
                  <span class="input-group-btn">
                    <button type="button" onclick="getjalan();" class="btn btn-success btn-flat">Cek!</button>
                  </span>
            </div>
            <br>

            <div class="form-group">
                <label>Nama Apotek/RS *</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Apotek" required>
            </div>

            <div class="form-group">
                <label>Diagnosa *</label>
                <input type="text" class="form-control" name="diagnosa" placeholder="Diagnosa" required>
            </div>
            <label>Tgl. Berobat *</label>
            <div class="form-group">
                <input type="date"  class="form-control" name="obat" placeholder="YYYY-MMM-DDD" required> 
            </div>
            <label>Tgl. Klaim *</label>
            <div class="form-group">    
                <input type="date"  class="form-control" name="klaim" placeholder="YYYY-MMM-DDD" required> 
            </div>
            

            <div class="form-group">
                <label>Jumlah Benefit *</label>
                <!-- <input type="hidden" class="form-control" name="benefit" id="benefit_1" placeholder="Jumlah Benefit" readonly required> -->
                <input type="text" class="form-control" id="benefit" name="benefit" placeholder="Jumlah Benefit" readonly required>
            </div>
            <div class="form-group">
                <label>Sisa Benefit *</label>
                <input type="text" class="form-control" id="sisa" name="sisa" placeholder="Sisa Benefit" readonly required>
            </div>
            <div class="form-group">
                <label>Sisa Benefit Keluarga *</label>
                <input type="text" class="form-control" id="sisak" name="sisak" placeholder="Sisa Benefit Keluarga" readonly required>
            </div>
            </div>

            <div class="col-md-6">
             <div class="form-group">
                <label>Jenis Klaim</label>
                <input type="text" class="form-control" name="jenis" value="TOTAL BIAYA KESEHATAN" disabled required>
            </div>
             <div class="form-group">
                <label>Jumlah Klaim *</label>
                <input type="hidden" class="form-control" id="jklaim_1" name="jklaim" placeholder="Jumlah Klaim" required>
                <input type="text" class="form-control" id="jklaim" placeholder="Jumlah Klaim" required>
            </div>
            <div class="form-group">
                <label>Jumlah Disetujui *</label>
                <input type="hidden" class="form-control" id="jsetuju_1" name="jsetuju" placeholder="Jumlah Disetujui" required>
                <input type="text" class="form-control" id="jsetuju" placeholder="Jumlah Disetujui" required>
            </div>
            <br><br>
            <div class="box-footer">
              <button type="submit" onclick="return confirm('Apakah anda yakin?')" class="btn btn-primary">Simpan</button>
              <button type="reset" value="reset" class="btn btn-danger">Reset</button>
            </div>
          </div>
        </div>
        <span><br><br></span>
          <div class="entry input-group col-md-12">
          <div class="col-md-3">
              <img src="{{ asset('image/max200.png') }}" id="showgambar" class="img-bordered-sm">
          </div>
          <div class="col-md-3">
          <label>Bisa memilih lebih dari 1 foto (image only)</label>
            <div class="input-group-addon">
              <i class="fa fa-camera"></i>
            </div>
            <input id="inputgambar" type="file" name="gambar[]" class="form-control" placeholder="gambar" multiple="true">
          </div>
          
      </div> 
  <span><br><br></span>
        </div>
        </form>
      </div>
  </div>
