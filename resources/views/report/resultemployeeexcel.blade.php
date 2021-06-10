<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
</head>
<body> 
<h1 colspan="10" align="center" height="40">Employee Report</h1>  

<!-- <?php 
    // foreach ($divisi as $divisis) {
        // $divisis->nama_div_ext;
    // }

    // var_dump($slep);
?> -->

<?php 
$p=0;
foreach($Result as $ehe => $Resultss) {
    $p++;

    $a[$ehe] = 0;
    $aa[$ehe] = '';
    $tetap[$ehe] = 0;
    $coba[$ehe] = 0;
    $kon[$ehe] = 0;
    $pkl[$ehe] = 0;
    $out[$ehe] = 0;
    $bantu[$ehe] = 0;
    //
    $g[$ehe] = $Result[$ehe]->Divisi;
    
}
$z=0;
$x=0;

$g[$p]='';
$pp = 1;

foreach ($Result as $key => $Results)
{

      if($Results->Divisi!= $g[$pp])
      {

        if ($Results->Status == 'Tetap') {
            $tetap[$x]+=1;
        }
        if ($Results->Status == 'Percobaan') {
            $coba[$x] += 1;
        }
        if ($Results->Status == 'Kontrak') {
            $kon[$x] += 1;
        }
        if ($Results->Status == 'PKL') {
           $pkl[$x] += 1;
        }
        if ($Results->Status == 'Outsource') {
            $out[$x] += 1;
        }
        if ($Results->Status == 'Perbantuan') {
            $bantu[$x] += 1;
        }

        $a[$z] +=1;
        $aa[$x] = $Results->Divisi;
        $pp++;
        $z++;
        $x++;
      } else {

        if ($Results->Status == 'Tetap') {
            $tetap[$x]+=1;
        }
        if ($Results->Status == 'Percobaan') {
            $coba[$x] += 1;
        }
        if ($Results->Status == 'Kontrak') {
            $kon[$x] += 1;
        }
        if ($Results->Status == 'PKL') {
           $pkl[$x] += 1;
        }
        if ($Results->Status == 'Outsource') {
            $out[$x] += 1;
        }
        if ($Results->Status == 'Perbantuan') {
            $bantu[$x] += 1;
        }

        $pp++;
        $a[$z] +=1;
        $aa[$x] = $Results->Divisi;
      }
}

// $l=$pp-1;
// for ($i=0; $i < $l; $i++) { 
//     echo $aa[$i].' = '.$a[$i].'---Tetap = '.$tetap[$i].'---coba = '.$coba[$i].'---Kontrak = '.$kon[$i].'---pkl = '.$pkl[$i].'---out = '.$out[$i].'---bantu = '.$bantu[$i].'<br>'; 
// }

// echo 'variabel A = ' ; var_dump($a); echo '<br>';
// echo 'variabel AA = ' ; var_dump($aa); echo '<br>';
// echo 'variabel TETAP = ' ; var_dump($tetap); echo '<br>';
// echo 'variabel COBA = ' ; var_dump($coba); echo '<br>';
// echo 'variabel KONTRAK = ' ; var_dump($kon); echo '<br>';
// echo 'variabel PKL = ' ; var_dump($pkl); echo '<br>';
// echo 'variabel OUTSOURCE = ' ; var_dump($out); echo '<br>';
// echo 'variabel BANTUAN = ' ; var_dump($bantu); echo '<br>';

?>





<table >
    <thead>
    <tr>
        <td>&nbsp;</td>  
    </tr>
    </thead>
</table>

<table style="border: 1px solid black;">
    
    <tr>        
        <th width="5" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">No</th>
        <th width="15" align="center" rowspan="2" style="text-align:center;text-align:center;background-color:#ECF542; border: 3px solid #000000;">NIK</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Nama</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jenis Kelamin</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Email</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Email Regular</th>
        <th width="70" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Divisi</th>
        <th width="45" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jabatan</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tgl Kontrak</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tgl Kontrak End</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Atasan 1</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Atasan 2</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Lokasi Kerja</th>
        <th width="70" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Alamat</th>
        <th width="70" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Alamat KTP</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">No Telepon</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">No Hp</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tempat Lahir</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Tanggal Lahir</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Agama</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Pendidikan Terakhir</th>
        <th width="15" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Golongan Darah</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">No KTP</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">NPWP</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jamsostek</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">BPJS</th>
        <th width="35" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">No Rekening</th>
        <th width="20" align="center" rowspan="2" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Status</th>
        <th width="20" align="center" colspan="6" style="text-align:center;background-color:#ECF542; border: 3px solid #000000;">Jumlah Per Divisi</th>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>

        <th align="center" width="18" style="text-align:center;background-color:#ECF542; border: 3px solid black;">Tetap</th>
        <th align="center" width="18" style="text-align:center;background-color:#ECF542; border: 3px solid black;">Percobaan</th>
        <th align="center" width="18" style="text-align:center;background-color:#ECF542; border: 3px solid black;">Kontrak</th>
        <th align="center" width="18" style="text-align:center;background-color:#ECF542; border: 3px solid black;">PKL</th>
        <th align="center" width="18" style="text-align:center;background-color:#ECF542; border: 3px solid black;">Outsource</th>
        <th align="center" width="18" style="text-align:center;background-color:#ECF542; border: 3px solid black;">Perbantuan</th>
    </tr>
    <?php $z=0;       
    $count = count($aa);

    for ($h=0; $h<$count-2;$h++){
        $asd[$h] = $a[$h]; 
         for ($m=0; $m<$a[$h];$m++){
             
            if ($a[$h]>1 && $asd[$h]>1) {
               
    $z++; ?>
    <tr style="border: 3px solid #000000;">
        <td style="border: 3px solid #000000;">{{ $z }}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NIK}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Nama}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> jk}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> email}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> emailreg}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Divisi}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Jabatan}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TglKontrak}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TglKontrakEnd}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Atasan1}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Atasan2}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> LokasiKerja}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Alamat}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Alamat_KTP}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NoTelepon}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NoHp}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TempatLahir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TanggalLahir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> nama_agama}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> pendidikan_terakhir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> gol_darah}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> ktp_sim}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> npwp}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> jamsostek}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> bpjs}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> norek}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Status}}</td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="{{ $asd[$h] }}">
            <?php if ($tetap[$h]) {
                echo $tetap[$h]; 
            } ?>            
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="{{ $asd[$h] }}">
            <?php if ($coba[$h]) {
            echo $coba[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="{{ $asd[$h] }}">
            <?php if ($kon[$h]) {
            echo $kon[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="{{ $asd[$h] }}">
            <?php if ($pkl[$h]) {
            echo $pkl[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="{{ $asd[$h] }}">
            <?php if ($out[$h]) {
            echo $out[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border-right: 3px solid #000000;border-bottom: 3px solid #000000;" rowspan="{{ $asd[$h] }}">
            <?php if ($bantu[$h]) {
            echo $bantu[$h]; 
            } ?>
        </td>
        <td>&nbsp;</td>
        <?php $asd[$h]=0; ?>

    </tr>

<?php } elseif($asd[$h]==0) {

    $z++; ?>
    <tr style="border: 3px solid #000000;">
        <td style="border: 3px solid #000000;">{{ $z }}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NIK}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Nama}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> jk}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> email}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> emailreg}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Divisi}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Jabatan}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TglKontrak}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TglKontrakEnd}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Atasan1}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Atasan2}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> LokasiKerja}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Alamat}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Alamat_KTP}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NoTelepon}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NoHp}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TempatLahir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TanggalLahir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> nama_agama}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> pendidikan_terakhir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> gol_darah}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> ktp_sim}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> npwp}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> jamsostek}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> bpjs}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> norek}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Status}}</td>
        


    </tr>

<?php

    } else {

    $z++; ?>
    <tr style="border: 3px solid #000000;">
        <td style="border: 3px solid #000000;">{{ $z }}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NIK}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Nama}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> jk}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> email}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> emailreg}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Divisi}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Jabatan}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TglKontrak}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TglKontrakEnd}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Atasan1}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Atasan2}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> LokasiKerja}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Alamat}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Alamat_KTP}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NoTelepon}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> NoHp}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TempatLahir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> TanggalLahir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> nama_agama}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> pendidikan_terakhir}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> gol_darah}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> ktp_sim}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> npwp}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> jamsostek}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> bpjs}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> norek}}</td>
        <td style="border: 3px solid #000000;">{{ $Result[$z-1]-> Status}}</td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="1">
            <?php if ($tetap[$h]) {
                echo $tetap[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="1">
            <?php if ($coba[$h]) {
                echo $coba[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="1">
            <?php if ($kon[$h]) {
                echo $kon[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="1">
            <?php if ($pkl[$h]) {
                echo $pkl[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border: 3px solid #000000;" rowspan="1">
            <?php if ($out[$h]) {
                echo $out[$h]; 
            } ?>
        </td>
        <td style="text-align:center;border-right: 3px solid #000000;border-bottom: 3px solid #000000;" rowspan="1">
            <?php if ($bantu[$h]) {
                echo $bantu[$h]; 
            } ?>
        </td>


    </tr>

<?php

        }} } ?> 

</table> 
</body> 
</html>