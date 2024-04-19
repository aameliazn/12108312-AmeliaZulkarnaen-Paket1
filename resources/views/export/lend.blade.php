<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Lend Report | Library</title>

    <style>
        h2 {
            margin-bottom: 30px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: center;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #438af3;
            color: white;
        }

        .text-danger {
            color: red;
        }
    </style>

</head>

<body>

    <div>
        <h2>Lend Data</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Lend Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lends as $lend)
                    @php
                        $isOverdue = $lend->status === 'lend' && \Carbon\Carbon::parse($lend->due_date)->isPast();
                    @endphp
                    <tr>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $loop->iteration }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $lend->users->name }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $lend->users->email }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $lend->books->title }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $lend->books->author }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">
                            {{ \Carbon\Carbon::parse($lend->lend_date)->format('M d, Y') }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">
                            {{ \Carbon\Carbon::parse($lend->due_date)->format('M d, Y') }}</td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">
                            @if ($lend->status === 'returned')
                                {{ \Carbon\Carbon::parse($lend->updated_at)->format('M d, Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $lend->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
