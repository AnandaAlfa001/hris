
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h2>Perubahan Data Karyawan HRIS</h2>
    <br>
    <table>
        <tr>
            <td>Karyawan yang dirubah</td>
            <td>:</td>
            <td>{{ '('.$nik_data_dirubah.') '.$nama_data_dirubah }}</td>
        </tr>
        <tr>
            <td>User yang merubah</td>
            <td>:</td>
            <td>{{ '('.$change_by.') '.$nama_change_by }}</td>
        </tr>
        <tr>
            <td>Tanggal Update</td>
            <td>:</td>
            <td>{{ $tanggal_update }}</td>
        </tr>
        <?php
        $array_datachange = explode("<li>", $datachange);
        ?>
        <tr>
            <td>Perubahan</td>
            <td>:</td>
            <td>
                <ol>
                    @foreach($array_datachange as $array)
                        <li>{{ $array }}</li>
                    @endforeach
                </ol>
            </td>
        </tr>
    </table>
</body>
</html>