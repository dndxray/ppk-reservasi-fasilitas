<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: sans-serif; font-size: 12px; color: #1E2A38; }
    h1 { font-size: 16px; margin-bottom: 4px; }
    p.sub { color: #6B7280; margin-top: 0; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #1E2A38; color: #fff; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; }
    td { padding: 6px 8px; border-bottom: 1px solid #D8D3C6; }
</style>
</head>
<body>
    <h1>Rekap Okupansi & Frekuensi Kerusakan Fasilitas</h1>
    <p class="sub">Sarana Kampus — dicetak {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Fasilitas</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Total Reservasi</th>
                <th>Total Laporan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $facility)
                <tr>
                    <td>{{ $facility->nama_fasilitas }}</td>
                    <td>{{ $facility->lokasi }}</td>
                    <td>{{ $facility->statusLabel() }}</td>
                    <td>{{ $facility->total_reservasi }}</td>
                    <td>{{ $facility->total_laporan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
