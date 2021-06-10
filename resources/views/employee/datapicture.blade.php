<h3 class="title">Picture</h3>
<form role="form" method="POST" enctype="multipart/form-data" action="{{ url('updatepicture',$data->NIK.'-'.$url) }}">
  {{ csrf_field() }}
    <div class="box-body">
    <div id="alertz2">
    @if (session('success'))
          <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <i class="icon fa fa-check"></i> {{ session('success') }}
          </div>
      @endif
      </div>
      <div class="box-footer"></div>
      <div class="form-group">
      	<label>Foto Karyawan</label>
      	<div class="col-md-3">
      		@if($data->photo)
      		<img src="{{ asset('image/Photo/'.$data->photo) }}" id="showgambar" class="img-circle img-bordered-sm" style="border-radius: 50%;max-width:200px;max-height:200px;float:left;">
      		@else
      		<img src="{{ asset('image/max200.png') }}" id="showgambar" class="img-circle img-bordered-sm">
      		@endif
      	</div>
      </div>
      @if(Session::get('admin') == 1)
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-camera"></i>
          </div>
          <input type="file" id="inputgambar" name="gambar" class="form-control" placeholder="gambar" >

        </div>  
        <!-- /.input group -->
      </div>
      @endif
   </div>
	<!-- /.box-body -->

	<div class="box-footer">

    @if(Session::get('admin') == 1)
	  <button type="submit" class="btn btn-primary">Simpan</button>
    @endif
	  <a href="{{ url($urlback) }}"><span type="button" class="btn btn-warning">Kembali</span></a>
	</div>
</form>