<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Posts View</title>
    <style>
        /* General */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1e1e2f;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 30px 0;
            color: #ffcc00;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            padding-bottom: 50px;
        }

        a.button {
            display: inline-block;
            padding: 12px 25px;
            margin-bottom: 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        a.button:hover {
            background-color: #45a049;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2c3e;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        th,
        td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #3f3f5a;
            color: #ffcc00;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #2a2a3c;
        }

        tr:hover {
            background-color: #3a3a5a;
        }
    </style>
</head>

<body>

    <h1>User Posts</h1>
    <div class="container">
        <a href="/create-post" class="button">+ Add Post</a>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Post Title</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->user_id }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->title }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>