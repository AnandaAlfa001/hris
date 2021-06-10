<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
</head>
<body> 
<tr></tr>
<td colspan="10">LAMPIRAN SK Nomor : <?php echo $datanow->no_sk;  ?></td><br><br>
<table style="border: 1px solid black;">
  <tr>
    <td style="font-weight:normal;border: 3px solid #000000;" rowspan="2" align="center">NO</td>
    <td style="font-weight:normal;border: 3px solid #000000;" rowspan="2" align="center">NAMA</td>
    <td style="font-weight:normal;border: 3px solid #000000;" rowspan="2" align="center">NIK</td>
    <td style="font-weight:bold;border: 3px solid #000000;" colspan="7" align="center">Sampai dengan tanggal : {{$datanow->tgl_yes_tmt}}</td>
    <td align="center" style="font-weight:bold;border: 3px solid #000000;"></td>
    <td style="font-weight:bold;border: 3px solid #000000;" colspan="7" align="center">Terhitung mulai tanggal : {{$datanow->tgl_now_tmt}}</td>
  </tr>
  <tr>
    <td align="center" style="border: 3px solid #000000;">&nbsp;</td>
    <td align="center" style="border: 3px solid #000000;">&nbsp;</td>
    <td align="center" style="border: 3px solid #000000;">&nbsp;</td>
    <td align="center" style="border: 3px solid #000000;">JABATAN</td>
    <td align="center" style="border: 3px solid #000000;">Sub Divisi</td>
    <td align="center" style="border: 3px solid #000000;">DIVISI / KANTOR</td>
    <td align="center" style="border: 3px solid #000000;">GOL</td>
    <td align="center" style="border: 3px solid #000000;">GAJI POKOK</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN POKOK TMR</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN JABATAN</td>
    <td align="center" style="border: 3px solid #000000;">UTILITAS</td>
    <td align="center" style="border: 3px solid #000000;">JABATAN</td>
    <td align="center" style="border: 3px solid #000000;">Sub Divisi</td>
    <td align="center" style="border: 3px solid #000000;">DIVISI / KANTOR</td>
    <td align="center" style="border: 3px solid #000000;">GOL</td>
    <td align="center" style="border: 3px solid #000000;">GAJI POKOK</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN TMR</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN JABATAN</td>
  </tr>
  <tr>
    <td align="center" style="border: 3px solid #000000;">(1)</td>
    <td align="center" style="border: 3px solid #000000;">(2)</td>
    <td align="center" style="border: 3px solid #000000;">(3)</td>
    <td align="center" style="border: 3px solid #000000;">(4)</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">(5)</td>
    <td align="center" style="border: 3px solid #000000;">(6)</td>
    <td align="center" style="border: 3px solid #000000;">(7)</td>
    <td align="center" style="border: 3px solid #000000;">(8)</td>
    <td align="center" style="border: 3px solid #000000;">(9)</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">(10)</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">(11)</td>
    <td align="center" style="border: 3px solid #000000;">(12)</td>
    <td align="center" style="border: 3px solid #000000;">(13)</td>
    <td align="center" style="border: 3px solid #000000;">(14)</td>
    <td align="center" style="border: 3px solid #000000;">(15)</td>  
  </tr>

  <tr>
    <td align="center" style="border: 3px solid #000000;">1</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_karyawan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nik}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_jabatan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_subdivisi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_divisi}} / {{$dataprev->lokasi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_golongan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$gajifixprev}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_tmrfixprev}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_jabfixprev}}</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_jabatan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_subdivisi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_divisi}} / {{$datanow->lokasi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_golongan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$gajifixnow}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_tmrfixnow}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_jabfixnow}}</td>
  </tr>

</table>
<tr>
  <td colspan="10">Catatan:</td>
</tr>
<tr>
  <td colspan="10">1. Karyawan yang mendapatkan Tunjangan Jabatan tidak berhak atas uang lembur</td>  
</tr>
<tr></tr>

<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">Jakarta, <?php echo $datanow->tgl_now_sk ?></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">DIREKSI PT EDI INDONESIA</td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">DIREKTUR UTAMA</td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center"><u>E.HELMI WANTONO</u></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">'6614273</td>
</tr>

<!-- KEDUA MENN -->
<tr></tr>
<td colspan="10">KUTIPAN LAMPIRAN SK Nomor : <?php echo $datanow->no_sk; ?></td><br><br>
<table style="border: 1px solid black;">
  <tr>
    <td style="font-weight:normal;border: 3px solid #000000;" rowspan="2" align="center">NO</td>
    <td style="font-weight:normal;border: 3px solid #000000;" rowspan="2" align="center">NAMA</td>
    <td style="font-weight:normal;border: 3px solid #000000;" rowspan="2" align="center">NIK</td>
    <td style="font-weight:bold;border: 3px solid #000000;" colspan="7" align="center">Sampai dengan tanggal : {{$datanow->tgl_yes_tmt}}</td>
    <td align="center" style="font-weight:bold;border: 3px solid #000000;"></td>
    <td style="font-weight:bold;border: 3px solid #000000;" colspan="7" align="center">Terhitung mulai tanggal : {{$datanow->tgl_now_tmt}}</td>
  </tr>
  <tr>
    <td align="center" style="border: 3px solid #000000;">&nbsp;</td>
    <td align="center" style="border: 3px solid #000000;">&nbsp;</td>
    <td align="center" style="border: 3px solid #000000;">&nbsp;</td>
    <td align="center" style="border: 3px solid #000000;">JABATAN</td>
    <td align="center" style="border: 3px solid #000000;">Sub Divisi</td>
    <td align="center" style="border: 3px solid #000000;">DIVISI / KANTOR</td>
    <td align="center" style="border: 3px solid #000000;">GOL</td>
    <td align="center" style="border: 3px solid #000000;">GAJI POKOK</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN POKOK TMR</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN JABATAN</td>
    <td align="center" style="border: 3px solid #000000;">UTILITAS</td>
    <td align="center" style="border: 3px solid #000000;">JABATAN</td>
    <td align="center" style="border: 3px solid #000000;">Sub Divisi</td>
    <td align="center" style="border: 3px solid #000000;">DIVISI / KANTOR</td>
    <td align="center" style="border: 3px solid #000000;">GOL</td>
    <td align="center" style="border: 3px solid #000000;">GAJI POKOK</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN TMR</td>
    <td align="center" style="border: 3px solid #000000;">TUNJANGAN JABATAN</td>
  </tr>
  <tr>
    <td align="center" style="border: 3px solid #000000;">(1)</td>
    <td align="center" style="border: 3px solid #000000;">(2)</td>
    <td align="center" style="border: 3px solid #000000;">(3)</td>
    <td align="center" style="border: 3px solid #000000;">(4)</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">(5)</td>
    <td align="center" style="border: 3px solid #000000;">(6)</td>
    <td align="center" style="border: 3px solid #000000;">(7)</td>
    <td align="center" style="border: 3px solid #000000;">(8)</td>
    <td align="center" style="border: 3px solid #000000;">(9)</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">(10)</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">(11)</td>
    <td align="center" style="border: 3px solid #000000;">(12)</td>
    <td align="center" style="border: 3px solid #000000;">(13)</td>
    <td align="center" style="border: 3px solid #000000;">(14)</td>
    <td align="center" style="border: 3px solid #000000;">(15)</td>  
  </tr>

  <tr>
    <td align="center" style="border: 3px solid #000000;">1</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_karyawan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nik}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_jabatan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_subdivisi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_divisi}} / {{$dataprev->lokasi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$dataprev->nama_golongan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$gajifixprev}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_tmrfixprev}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_jabfixprev}}</td>
    <td align="center" style="border: 3px solid #000000;"></td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_jabatan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_subdivisi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_divisi}} / {{$datanow->lokasi}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$datanow->nama_golongan}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$gajifixnow}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_tmrfixnow}}</td>
    <td align="center" style="border: 3px solid #000000;">{{$tunj_jabfixnow}}</td>
  </tr>

</table>
<tr>
  <td colspan="10">Catatan:</td>
</tr>
<tr>
  <td colspan="10">1. Karyawan yang mendapatkan Tunjangan Jabatan tidak berhak atas uang lembur</td>  
</tr>
<tr></tr>

<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">Jakarta, <?php echo $datanow->tgl_now_sk; ?></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">Sesuai dengan aslinya,</td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">DIREKSI PT EDI INDONESIA</td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">DIREKTUR KEUANGAN & PENDUKUNG</td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">DIREKTUR UTAMA</td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">ELON MANURUNG</td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center"><u>E.HELMI WANTONO</u></td>
</tr>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">'6114275</td>
  <td align="center"></td>
  <td align="center"></td>
  <td colspan="4" align="center">'6614273</td>
</tr>


</body> 
</html>