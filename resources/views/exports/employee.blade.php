<table style="border: 1px solid black;">
    <thead>
        <tr>
            <th>NIK</th>
            <th>SEQUENCE NIK</th>
            <th>NAMA</th>
            <th>JENIS KELAMIN</th>
            <th>ALAMAT</th>
            <th>NOMOR HP</th>
            <th>EMAIL</th>
            <th>UNIT KERJA</th>
            <th>JABATAN</th>
            <th>ATASAN 1</th>
            <th>ATASAN 2</th>
            <th>AWAL KONTRAK</th>
            <th>AKHIR KONTRAK</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employee as $v)
        <tr>
            <td>{{ $v->NIK }}</td>
            <td>{{ $v->SEQUENCE_NIK }}</td>
            <td>{{ $v->NAMA }}</td>
            <td>{{ $v->JENIS_KELAMIN }}</td>
            <td>{{ $v->ALAMAT }}</td>
            <td>{{ $v->NOMOR_HP }}</td>
            <td>{{ $v->EMAIL }}</td>
            <td>{{ $v->UNIT_KERJA }}</td>
            <td>{{ $v->JABATAN }}</td>
            <td>{{ $v->ATASAN_1 }}</td>
            <td>{{ $v->ATASAN_2 }}</td>
            <td>{{ $v->AWAL_KONTRAK }}</td>
            <td>{{ $v->AKHIR_KONTRAK }}</td>
        </tr>
        @endforeach
    </tbody>
</table>