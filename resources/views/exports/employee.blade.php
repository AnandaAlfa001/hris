<table>
    <thead>
        <tr>
            <th>NIK</th>
            <th>Nama</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employee as $v)
        <tr>
            <td>{{ $v->NIK }}</td>
            <td>{{ $v->Nama }}</td>
        </tr>
        @endforeach
    </tbody>
</table>