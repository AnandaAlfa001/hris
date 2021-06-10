@include('layouts.function')
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">		
	.page-break {
	    page-break-after: always;
	}
	.page {
		margin-top:0em; 
        margin-left: 2em;
        margin-right: 2em;
    }
    .page2 {
    	margin-left: 0em;
        margin-right: 0em;
    }
    .isi {
    	margin-top: 2em;
    }
	</style>
</head>
<body>
	<?php $image_path = '/image/logo/logo_edii.png'; ?>
	@if($statuskar == '5' or $statuskar == '6')
	<img src="">
	@else	
	<img src="{{ public_path() . $image_path }}" width="230px" height="120px">
	@endif
	<div class="page2">
		<p style="font-size:14px;float:right;margin-left:32em;margin-top:-80px">
		@if($statuskar == '5' or $statuskar == '6')
		Komplek Ruko Mitra Sunter Blok E1 No. 8<br>
		@endif
		Jl. Yos Sudarso Kav. 89 Jakarta 14350<br>
		Telp. &nbsp; (62-21) &nbsp; 6520 &nbsp; 509, 9266&nbsp; 8864<br>
		Fax.&nbsp;&nbsp;&nbsp;&nbsp;(62-21) &nbsp; 6521 &nbsp; 511
		</p>
	</div>
	<br><br>
	<div class="page">
		<h3><center><strong>FORM PERMOHONAN UPAH LEMBUR</strong></center></h3>
		
		<div class="isi">
		<table>
		  <tbody>
		  <tr>
		    <td width="145" style="font-size:18px;vertical-align: top">Nama</td>
		    <td width="5" style="font-size:18px;vertical-align: top">:</td>
		    <td style="font-size:18px;vertical-align: top">{{ Session::get('nama') }}</td>
		  </tr>
		 <tr><td></td></tr>
		  <tr>
		    <td width="145" style="font-size:18px;vertical-align: top">Jabatan</td>
		    <td width="5" style="font-size:18px;vertical-align: top">:</td>
		    <td style="font-size:18px;vertical-align: top">{{ Session::get('jabatan') }}</td>
		  </tr>
		  <tr><td></td></tr>
		  <tr>
		    <td width="145" style="font-size:18px;vertical-align: top">Ditempatkan di</td>
		    <td width="5" style="font-size:18px;vertical-align: top">:</td>
		    <td style="font-size:18px;vertical-align: top">{{ Session::get('divisi') }}</td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>		  
		  </tbody>
		</table>
		<table>
			<tbody>
				<tr>
					<td width="400" style="font-size:18px;vertical-align: top">Mengajukan upah lembur sehubung dengan pelaksanaan tugas yang dilakukan pada tanggal di bawah ini dengan perhitungan jam kerja lembur sbb :</td>
				</tr>
			</tbody>
		</table>
		<br>
		<table style="border: 2px solid black;border-collapse: collapse;">
			<thead>
				<tr>
					<th style="border: 2px solid black;border-collapse: collapse;">Tgl.</th>
					<th style="border: 2px solid black;border-collapse: collapse;">Jam Mulai</th>
					<th style="border: 2px solid black;border-collapse: collapse;">Jam Selesai</th>
					<th style="border: 2px solid black;border-collapse: collapse;">Kegiatan</th>
					<th style="border: 2px solid black;border-collapse: collapse;">Selisih Jam kerja</th>
					<th style="border: 2px solid black;border-collapse: collapse;">Perhitungan Jam Upah Lembur</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $datas)
				<tr>
					<td width="70" style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $datas->tgl_lembur }}</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $datas->JamMulai }}</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $datas->JamSelesaiAktual }}</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $datas->Kegiatan }}</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $datas->SelisihJamLembur }}</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $datas->total_upah }}</td>
				</tr>
				@endforeach
				<tr>
					<td style="border:0;"></td>
					<td style="border:0;"></td>
					<td style="border:0;"></td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">Total jam kerja lembur</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $total }}</td>
					<td style="border: 2px solid black;border-collapse: collapse;text-align:center;vertical-align: top">{{ $ta_upahmen }}</td>
				</tr>
			</tbody>
		</table>
		<br>
		<p style="float:right;margin-left:27em;">Jakarta, <?php $a = date('Y-m-d'); echo indonesiaDate($a);  ?></p>
		<br><br>
		<table>
			<tbody>
			<tr>
				<td width="150" style="text-align: center;font-size:18px;vertical-align: top">PT. EDI INDONESIA</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">Menyetujui</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">{{ Session::get('nama') }}</td>
			</tr>
			<tr>
				<td width="150" style="text-align: center;font-size:18px;vertical-align: top">Divisi {{Session::get('divisi') }}</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">&nbsp;</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">&nbsp;</td>
			</tr>
			<tr>
				<td width="150" style="text-align: center;font-size:18px;vertical-align: top">&nbsp;</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">&nbsp;</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">KOPPEDI,</td>
			</tr>
			<tr><td>&nbsp;</td></tr>	
			<tr><td>&nbsp;</td></tr>	
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td width="150" style="text-align: center;font-size:18px;vertical-align: top">( {{ Session::get('atasan') }} )</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">&nbsp;</td>
			    <td width="150" style="text-align: center;font-size:18px;vertical-align: top">(...............................)</td>
			</tr>	
			</tbody>
		</table>
	</div>
	</div>
</body>
</html>