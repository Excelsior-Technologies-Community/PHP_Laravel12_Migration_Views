<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Posts View</title>

    <!-- ✅ BOOTSTRAP CDN (IMPORTANT FIX) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #1f1f3a, #0f0f1a);
            color: #fff;
        }

        h1 {
            text-align: center;
            margin: 30px 0;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(90deg, #ffcc00, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .container {
            width: 92%;
            max-width: 1100px;
            margin: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-box {
            display: flex;
            gap: 10px;
            flex: 1;
        }

        input {
            flex: 1;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            outline: none;
        }

        input:focus {
            border-color: #ffcc00;
            box-shadow: 0 0 10px rgba(255, 204, 0, 0.3);
        }

        button {
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #ffcc00, #ff6b6b);
            color: #000;
            font-weight: 600;
            cursor: pointer;
        }

        .add-btn {
            padding: 12px 18px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            padding: 15px;
            background: rgba(255, 255, 255, 0.08);
            color: #ffcc00;
        }

        td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.07);
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #aaa;
        }

        /* ✅ PAGINATION FIX */
        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            gap: 5px;
        }

        .pagination .page-item .page-link {
            background: rgba(255, 255, 255, 0.07);
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 8px 14px;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #ffcc00, #ff6b6b);
            color: #000;
            font-weight: bold;
        }

        .pagination .page-item .page-link:hover {
            background: rgba(255, 204, 0, 0.2);
            color: #fff;
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>

    <h1>User Posts Dashboard</h1>

    <div class="container">

        <!-- TOP BAR -->
        <div class="top-bar">

            <form class="search-box" method="GET" action="{{ route('user-posts') }}">
                <input type="text" name="search" placeholder="Search users or posts..."
                    value="{{ request('search') }}">
                <button type="submit">Search</button>
            </form>

            <a href="/create-post" class="add-btn">+ Add Post</a>
        </div>

        <!-- TABLE -->
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Post Title</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $row)
                <tr>
                    <td>{{ $row->user_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->title }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="empty">No Data Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION (FIXED) -->
        <div class="pagination-wrapper">
            {{ $data->appends(request()->query())->links() }}
        </div>

    </div>

</body>

</html>