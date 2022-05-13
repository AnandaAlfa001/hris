<table style="border: 1px solid black;">
    <thead>
        <tr>
            <th>NIK</th>
            <th>NAMA</th>
            <th>AWAL CUTI</th>
            <th>AKHIR CUTI</th>
            <th>JUMLAH HARI</th>
            <th>ALAMAT CUTI</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $v)
        <tr>
            <td>{{ $v->NIK }}</td>
            <td>{{ $v->NAMA }}</td>
            <td>{{ $v->AWAL_CUTI }}</td>
            <td>{{ $v->AKHIR_CUTI }}</td>
            <td>{{ $v->JUMLAH_CUTI }}</td>
            <td>{{ $v->ALAMAT_CUTI }}</td>
            <td>{{ $v->STATUS }}</td>
        </tr>
        @endforeach
    </tbody>
</table>