<!DOCTYPE html>
<html>
<head>
    <title>Laporan Aktivitas Pengguna</title>
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
    <h1>Laporan Aktivitas Pengguna</h1>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Activity</th>
                <th>Time</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td>{{ $activity->user->name }}</td>
                <td>{{ $activity->user->role }}</td>
                <td>{{ $activity->activity }}</td>
                <td>{{ $activity->created_at->setTimeZone('Asia/Jakarta')->format('H-i-s') }}</td>
                <td>{{ $activity->created_at->setTimeZone('Asia/Jakarta')->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 