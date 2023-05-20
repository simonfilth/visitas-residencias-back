<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($visitas as $invoice)
        <tr>
            <td>{{ $invoice->visitante_nombre }}</td>
            <td>{{ $invoice->area_id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
