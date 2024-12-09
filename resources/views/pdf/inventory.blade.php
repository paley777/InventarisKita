<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Daftar Inventaris</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Ruangan</th>
                <th>Nama Inventaris</th>
                <th>Tipe</th>
                <th>Kepemilikan</th>
                <th>Spesifikasi</th>
                <th>Tahun Pengadaan</th>
                <th>Jumlah</th>
                <th>Layak</th>
                <th>Tidak Layak</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $index => $inventory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $inventory->room->name ?? 'N/A' }}</td>
                    <td>{{ $inventory->name }}</td>
                    <td>{{ $inventory->type }}</td>
                    <td>{{ $inventory->ownership }}</td>
                    <td>{{ $inventory->specification }}</td>
                    <td>{{ $inventory->acquisition_year }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    <td>{{ $inventory->layak_count }}</td>
                    <td>{{ $inventory->tidak_layak_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
