<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
</head>
<body> 

<h1 colspan="10" align="center" height="40">Report Kesehatan Bulan {{$bulaninput}} Tahun {{$tahuninput}} </h1>

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
        <th width="20" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Nama Pasien</th>
        <th width="30" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jenis</th>
        <th width="18" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tanggal Berobat</th>
        <th width="15" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tanggal Klaim</th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Apotek / RS </th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Diagnosa </th>
        <th width="35" align="center"  style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Total Klaim</th>
    </tr>



    <?php $z=0; ?>
    @foreach($Result as $Results)
    <?php
        
        $z++;
    ?>
    <tr>

        <td style="border: 3px solid #000000;">{{ $z }}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> nama}}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> remb}}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> tglberobat }}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> tglklaim}}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> nama_apotek }}</td>
        <td style="border: 3px solid #000000;">{{ $Results-> diagnosa}}</td>
        <td style="border-right: 3px solid #000000;border-bottom: 3px solid #000000;">{{ number_format ($Results->total_klaim,0,",",".")}}</td>
    </tr>
    @endforeach

</table> 
</body> 
</html>