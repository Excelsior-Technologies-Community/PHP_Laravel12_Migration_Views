<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #1a1a2e, #0d0d1a);
            color: #e0e0e0;
            min-height: 100vh;
        }
        .form-box {
            max-width: 480px;
            margin: 70px auto;
            padding: 32px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 30px rgba(0,0,0,0.6);
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 22px;
            padding: 9px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            color: #fff;
            background: rgba(255,255,255,0.08);
            transition: 0.2s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.15); }
        h2 {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 6px;
        }
        .subtitle { text-align: center; font-size: 12px; color: #666; margin-bottom: 24px; }
        label { display: block; margin: 12px 0 6px; font-weight: 600; color: #00c6ff; font-size: 13px; }
        input, select {
            width: 100%;
            height: 46px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(20,20,30,0.9);
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }
        input:focus, select:focus {
            border-color: #00c6ff;
            box-shadow: 0 0 10px rgba(0,198,255,0.3);
        }
        option { background: #1a1a2e; }
        button {
            width: 100%;
            padding: 13px;
            margin-top: 18px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            transition: 0.2s;
        }
        button:hover { transform: scale(1.02); }
    </style>
</head>
<body>
    <div class="form-box">
        <a href="{{ route('user-posts') }}" class="back-btn">← Back to Dashboard</a>
        <h2>Edit Post</h2>
        <div class="subtitle">Update post details below</div>

        <form action="{{ route('update-post', $post->id) }}" method="POST">
            @csrf
            <label>Select User</label>
            <select name="user_id" required>
                <option value="">-- Choose User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $post->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <label>Post Title</label>
            <input type="text" name="title" value="{{ $post->title }}" required>

            <button type="submit">💾 Update Post</button>
        </form>
    </div>
</body>
</html>
