<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi - {{ ucfirst($type) }}</title>
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
    <h1>Laporan Transaksi - {{ ucfirst($type) }}</h1>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>User</th>
                <th>Quantity</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->product->name }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ $transaction->date }}</td>
                <td>{{ $transaction->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 