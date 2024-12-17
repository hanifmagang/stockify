<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stock</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Stock Product</h1>
    <p>Tanggal: {{ $date }}</p>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Category</th>
                <th>Name Product</th>
                <th>Quantity Change</th>
                <th>Stock</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->product->sku }}</td>
                <td>{{ $transaction->product->category->name }}</td>
                <td>{{ $transaction->product->name }}</td>
                <td>
                    @if($transaction['type'] === 'Masuk')
                        +{{ $transaction['quantity'] }}
                    @else
                        -{{ $transaction['quantity'] }}
                    @endif
                </td>
                <td>{{ $transaction->stockSementara }}</td>
                <td>{{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 