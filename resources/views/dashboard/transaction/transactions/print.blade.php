<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi #{{ $transaction->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Detail Transaksi</h2>
    <p><strong>ID:</strong> {{ $transaction->id }}</p>
    <p><strong>Kasir:</strong> {{ $transaction->user->name ?? '-' }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Obat</th>
                <th>Kode Obat</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->details as $item)
            <tr>
                <td>{{ $item->drug->nama_obat }}</td>
                <td>{{ $item->drug->kode_obat }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Transaksi:</strong>
        Rp {{ number_format($transaction->details->sum(fn($d) => $d->qty * $d->price), 2, ',', '.') }}
    </p>
</body>
</html>
