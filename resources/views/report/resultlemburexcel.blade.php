<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
</head>
<body> 

<h1 colspan="10" align="center" height="40">Report Lembur Karyawan Bulan {{$bulan}} Tahun {{$tahun}} </h1>

<table >
    <thead>
    <tr>
        <td>&nbsp;</td>  
    </tr>
    </thead>
</table>

<table style="border: 1px solid black;">
    
    <tr>        
        <th width="6" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">No</th>
        <th width="20" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tanggal</th>
        <th width="20" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">NIK</th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Nama</th>
        <th width="25" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jabatan</th>
        <th width="15" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jam Mulai</th>
        <th width="15" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jam Selesai</th>
        <th width="15" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Selisih Jam</th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Kegiatan</th>
    </tr>



    <?php $z=0; ?>
    @foreach($data as $datas)
    <?php
        
        $z++;
    ?>
    <tr>

        <td style="border: 3px solid #000000;">{{ $z }}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> TanggalMulaiLembur}}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> NIK}}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> Nama }}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> jabatan}}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> JamMulai }}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> JamSelesaiAktual}}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> SelisihJamLembur}}</td>
        <td style="border: 3px solid #000000;">{{ $datas-> Kegiatan}}</td>
    </tr>
    @endforeach

</table> 
</body> 
</html>