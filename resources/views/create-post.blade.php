<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Post</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #1f1f3a, #0f0f1a);
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 28px;
            background: linear-gradient(90deg, #ffcc00, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #aaa;
            margin-bottom: 20px;
        }

        .form-box {
            max-width: 480px;
            margin: 70px auto;
            padding: 30px;
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 25px rgba(0,0,0,0.6);
            border: 1px solid rgba(255,255,255,0.08);
        }

        /* BACK BUTTON */
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            color: white;
            background: rgba(255,255,255,0.1);
            transition: 0.3s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        label {
            display: block;
            margin: 10px 0 6px;
            font-weight: 600;
            color: #ffcc00;
            font-size: 14px;
        }

        /* ✅ FINAL FIX: INPUT + SELECT SAME SIZE */
        input,
        select {
            width: 100%;
            box-sizing: border-box; /* IMPORTANT */

            height: 46px; /* SAME HEIGHT FOR BOTH */

            padding: 12px 14px;

            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.12);

            background: rgba(20, 20, 30, 0.85);
            color: #ffffff;

            font-size: 14px;
            outline: none;
            transition: 0.3s;

            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        /* SELECT TEXT ALIGN FIX */
        select {
            line-height: 1.2;
            cursor: pointer;
        }

        input::placeholder {
            color: rgba(255,255,255,0.5);
        }

        input:focus,
        select:focus {
            border-color: #ffcc00;
            box-shadow: 0 0 12px rgba(255,204,0,0.35);
            background: rgba(30, 30, 45, 0.95);
        }

        option {
            background-color: #1e1e2f;
            color: #ffffff;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            background: linear-gradient(135deg, #ffcc00, #ff6b6b);
            color: #000;
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.03);
        }

        input:hover,
        select:hover {
            border-color: rgba(255, 204, 0, 0.5);
        }

        @media (max-width: 600px) {
            .form-box {
                margin: 40px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="form-box">

        <a href="{{ url()->previous() }}" class="back-btn">← Back</a>

        <h2>Create New Post</h2>
        <div class="subtitle">Add user post using Laravel Migration View system</div>

        <form action="/store-post" method="POST">
            @csrf

            <label>Select User</label>
            <select name="user_id" required>
                <option value="">-- Choose User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            <label>Post Title</label>
            <input type="text" name="title" placeholder="Enter post title..." required>

            <button type="submit">Save Post</button>
        </form>

    </div>

</body>
</html>