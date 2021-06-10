@include('layouts.function')
<?php 
$dz=substr($head->photo, -3);
        if($dz=='pdf') {
            $head->photo = null;
        } 
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">		
	.page-break {
	    page-break-after: always;
	}
	.page {
		margin-top:3em; 
        margin-left: 3em;
        margin-right: 3em;
    }
    .page2 {
    	margin-left: 3em;
        margin-right: 3em;
    }
    .isi {
    	margin-top: -8em;
    }
	</style>
</head>
<body>
<!-- 	<div class="page">
		<br>
		<br>
		<br>
		<br>
		<br>
		<h3><strong><center>PERNYATAAN KESEDIAAN UNTUK DITUGASKAN</center></strong></h3>
		<br>
		<br>
		Yang bertanda tangan di bawah ini :
		<br>
		<br>
		<table>
		  <tbody>
		  <tr>
		    <td width="80">Nama</td>
		    <td>:</td>
		    <td>{{ $head->nama}}</td>
		  </tr>
		  <tr>
		    <td width="80">Alamat</td>
		    <td>:</td>
		    <td>{{ $head->alamat}}</td>
		  </tr>
		  </tbody>
		</table>
		<br>
		<br>
		Dengan ini menyatakan bahwa saya bersedia untuk melaksanakan paket pekerjaan {{ $head }}
	</div> -->
	<div class="page">
		<h3><center><strong>DAFTAR RIWAYAT HIDUP</strong></center></h3>
		<br>
		<br>
		<?php if($head->photo == '' or $head->photo == null) { ?>
	      <img src="{{base_path()}}/public/image/notfound.jpg" style="max-width:200px;max-height:200px;float:right;margin-left:30em;margin-top:-5em">            
	     <?php } else { ?>
	      <img src="{{base_path()}}/public/image/Photo/{{$head->photo}}" style="max-width:200px;max-height:200px;float:right;margin-left:30em;margin-top:-5em">
	     <?php } ?>
	     <br><br><br>
		<div class="isi">
		<table>
		  <tbody>
		  <tr>
		  	<td width="5" style="vertical-align: top;">1. </td>
		    <td width="145" style="vertical-align: top">Posisi yang Diusulkan</td>
		    <td width="5" style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{ $head->posisi}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">2. </td>
		    <td style="vertical-align: top">Nama Perusahaan</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">PT. EDI Indonesia</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">3. </td>
		    <td style="vertical-align: top">Nama Personil</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$head->nama}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">4. </td>
		    <td style="vertical-align: top">Tempat/ Tanggal Lahir</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$head->tempatlahir}}, {{ indonesiaDate($head->tgl_lahir) }}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">5. </td>
		    <td style="vertical-align: top">Pendidikan (Lembaga Pendidikan, tempat, dan tahun tamat belajar, dilampirkan rekaman ijazah</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$head->didikan}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">6. </td>
		    <td style="vertical-align: top">Pendidikan Non Formal</td>
		    <td>:</td>
		    <td style="vertical-align: top">{{$head->didikannf}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">7. </td>
		    <td style="vertical-align: top">Penguasaan Bahasa Inggris dan Bahasa Indonesia</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top"><?php if ($head->bhs == 1) { ?>
		    	Aktif
		    	<?php } else { ?>
		    	Pasif	
		    	<?php } ?>
		    	
		    </td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">8. </td>
		    <td style="vertical-align: top">Pengalaman Kerja</td>
		  </tr>
		  </tbody>
		</table>
		<br>
		<br>
		<br>
		<div class="page2">
		<?php $z=0; ?>
		@foreach($projectex as $key => $projectexs)
		<?php $a[$key] = $projectexs->waktu_pelaksanaan;  ?>
		@endforeach
		<?php $a[$z-1] = ''; ?>
		@foreach($projectex as $key => $projectexs)
		@if($a[$key] != $a[$key-1])
		<strong>Tahun {{$projectexs->waktu_pelaksanaan}}</strong>
		@else @endif
		<br>
		<br>
		<table>
		  <tbody>
		  <tr>
		  	<td width="5" style="vertical-align: top;">a. </td>
		    <td width="139" style="vertical-align: top">Nama Proyek</td>
		    <td width="5" style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$projectexs->nama_project}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">b. </td>
		    <td style="vertical-align: top">Lokasi Proyek</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$projectexs->lokasi_project}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">c. </td>
		    <td style="vertical-align: top">Pengguna Jasa</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$projectexs->pengguna}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">d. </td>
		    <td style="vertical-align: top">Nama Perusahaan</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$projectexs->perusahaan}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">e. </td>
		    <td style="vertical-align: top">Uraian Tugas</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$projectexs->uraian_tugas}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">f. </td>
		    <td style="vertical-align: top">Waktu Pelaksanaan</td>
		    <td>:</td>
		    <td style="vertical-align: top">{{$projectexs->waktu_pelaksanaan}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">g. </td>
		    <td style="vertical-align: top">Posisi Penugasan</td>
		    <td style="vertical-align: top">:</td>
		    <td style="vertical-align: top">{{$projectexs->posisipenugasan}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">h. </td>
		    <td style="vertical-align: top">Status Kepegawaian pada Perusahaan</td>
		    <td>:</td>
		    <td style="vertical-align: top">{{$projectexs->statuskep}}</td>
		  </tr>
		  <tr>
		  	<td style="vertical-align: top">i. </td>
		    <td style="vertical-align: top">Surat Referensi dari pengguna Jasa</td>
		    <td>:</td>
		    <td style="vertical-align: top">-</td>
		  </tr>
		  
		  </tbody>
		</table>
		<br>
		<br>	
		@endforeach
		</div>
		<div class="page-break"></div>
		<br>
		<br>
		<div style="text-align: justify;">
		Daftar riwayat hidup ini saya buat dengan sebenar-benarnya dan penuh rasa tanggung jawab. Jika terdapat pengungkapan keterangan yang tidak benar secara sengaja atau sepatutnya diduga maka saya siap untuk digugurkan dari proses seleksi atau dikeluarkan jika sudah dipekerjakan.
		</div>
		</div>
	</div>
</body>
</html>