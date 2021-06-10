<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
</head>
<body> 

<h1 colspan="10" align="center" height="40">Report Cuti Bulan {{$bulaninput}} Tahun {{$tahuninput}} </h1>

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
        <th width="20" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">NIK</th>
        <th width="30" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Nama</th>
        <th width="15" align="center"  style="text-align:center;text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tanggal Mulai</th>
        <th width="18" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jumlah Hari</th>
        <th width="15" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tanggal Selesai</th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Keterangan (Alamat Cuti)</th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Status</th>
    </tr>



    <?php $z=0; ?>
    @foreach($Result as $Results)
    <?php
        
        $z++;
    ?>
    <tr>
        <td style="border: 3px solid #000000;">{{ $z }}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> nik}}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> nama}}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> tgl_mulai }}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> hari}} hari</td>
        <td style="border: 3px solid #000000;">{{ $Results-> tgl_selesai }}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> alamatcuti}}</td>
        <td style="border-right: 3px solid #000000;border-bottom: 3px solid #000000;">{{ $Results-> status}}</td>
    </tr>
    @endforeach

</table> 
</body> 
</html>