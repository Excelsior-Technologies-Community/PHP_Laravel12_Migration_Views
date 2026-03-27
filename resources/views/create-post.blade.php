<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1e1e2f;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .form-box {
            max-width: 450px;
            margin: 60px auto;
            background-color: #2c2c3e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ffcc00;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #ffcc00;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 6px;
            background-color: #3a3a5a;
            color: #f0f0f0;
            font-size: 14px;
        }

        input::placeholder {
            color: #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Back button style */
        .back-btn {
            display: inline-block;
            text-align: center;
            text-decoration: none;
            background-color: #ff5722;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #e64a19;
        }
    </style>
</head>

<body>

    <div class="form-box">
        <h2>Create Post</h2>

        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="back-btn">← Back</a>

        <form action="/store-post" method="POST">
            @csrf

            <label>Select User</label>
            <select name="user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            <label>Post Title</label>
            <input type="text" name="title" placeholder="Enter title" required>

            <button type="submit">Save</button>
        </form>
    </div>

</body>

</html>