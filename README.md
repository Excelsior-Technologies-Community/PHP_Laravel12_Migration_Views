# PHP_Laravel12_Migration_Views


## Project Description

PHP_Laravel12_Migration_Views is a Laravel 12-based application demonstrating the use of database views via the staudenmeir/laravel-migration-views package. This project allows developers to efficiently join multiple tables and display combined data in a user-friendly interface.

Key functionalities include:

- User & Post Management: Create, view, and manage posts linked to users.
- Database Views: Display combined user-post data using SQL views, improving query efficiency.
- Modern UI: Dark-mode styled Blade templates with clean and responsive design.

This project is ideal for learning:

- How to create and use database views in Laravel.
- How to join tables efficiently and fetch combined results.
- Building CRUD interfaces with Laravel 12.
- Designing user-friendly UI using Blade templates with custom CSS.



## Features

- Laravel 12 Framework: Built on the latest stable version for modern features.
- Database Views Support: Uses staudenmeir/laravel-migration-views package to manage SQL views via migrations.
- User-Post Management: Link posts to users with foreign key constraints.
- Demo Data Setup: Includes ready-to-use demo users and posts for testing.
- Dark Mode UI: Clean and modern interface for tables and forms.
- Blade Templates: Fully responsive and customizable front-end views.
- CRUD Operations: Add, view, and manage posts linked to users.
- Simple Routing: Intuitive route setup for list, create, and store operations.



## Technologies Used

- PHP 8+ – Backend scripting language.
- Laravel 12 – PHP framework for web applications.
- MySQL – Relational database for storing users, posts, and views.
- Blade Templates – Laravel’s templating engine for UI.
- staudenmeir/laravel-migration-views – Package for creating SQL views via Laravel migrations.
- HTML5 & CSS3 – Frontend structure and styling (dark mode).




---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Migration_Views "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Migration_Views

```

#### Explanation:

Installs Laravel 12 and moves into the project directory.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_migration_views
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_migration_views


```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel to MySQL and creates default tables (users, password_resets, etc.).





## STEP 3: Install Migration Views Package 

### Run:

```
composer require staudenmeir/laravel-migration-views:^1.11

```

#### Explanation: 

Adds package to create SQL views via migrations.





## STEP 4: Create Posts Table

### Run:

```
php artisan make:migration create_posts_table

```

### database/migrations/xxxx_create_posts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```

#### Explanation: 

Creates posts table with foreign key linking to users.





## STEP 5: Create SQL View

### Run:

```
php artisan make:migration create_user_posts_view

```

### database/migrations/xxxx_create_user_posts_view.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Define the query
        $query = DB::table('users')
            ->join('posts', 'users.id', '=', 'posts.user_id')
            ->select('users.id as user_id', 'users.name', 'posts.title');

        // Create or replace the view (avoids "already exists" error)
        Schema::createOrReplaceView('user_posts_view', $query);
    }

    public function down(): void
    {
        Schema::dropView('user_posts_view');
    }
};

```


### Then Run:

```
php artisan migrate

```

#### Explanation: 

Creates SQL view user_posts_view combining users and their posts.





## STEP 6: Insert Demo Data

### Run:

```
php artisan tinker

```

### // Insert demo users

```
DB::table('users')->insert([
    [
        'name' => 'Demo User 1',
        'email' => 'user1@gmail.com',
        'password' => bcrypt('123456'),
    ],
    [
        'name' => 'Demo User 2',
        'email' => 'user2@gmail.com',
        'password' => bcrypt('123456'),
    ],
]);

// Insert posts
DB::table('posts')->insert([
    ['user_id' => 1, 'title' => 'Laravel 12', 'created_at'=>now(),'updated_at'=>now()],
    ['user_id' => 1, 'title' => 'Migration Views', 'created_at'=>now(),'updated_at'=>now()],
    ['user_id' => 2, 'title' => 'PHP Basics', 'created_at'=>now(),'updated_at'=>now()],
]);

```

#### Explanation: 

Adds demo users and posts for testing.





## STEP 7: Create Model for View

### Run:

```
php artisan make:model UserPostView

```

### app/Models/UserPostView.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPostView extends Model
{
    protected $table = 'user_posts_view';
    public $timestamps = false;
}

```

#### Explanation: 

Eloquent model for the SQL view to fetch combined user-post data.





## STEP 8: Create Controller

### Run:

```
php artisan make:controller PostController

```

### app/Http/Controllers/PostController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserPostView;

class PostController extends Controller
{
    public function index()
    {
        $data = UserPostView::all();
        $users = DB::table('users')->get();  // Add this
        return view('user-posts', compact('data', 'users'));
    }

    public function create()
    {
        $users = DB::table('users')->get();
        return view('create-post', compact('users'));
    }

    public function store(Request $request)
    {
        DB::table('posts')->insert([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user-posts');
    }
}

```

#### Explanation: 

Handles displaying, creating, and storing posts.






## STEP 9: Add Routes

### routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/user-posts', [PostController::class, 'index'])->name('user-posts');
Route::get('/create-post', [PostController::class, 'create']);
Route::post('/store-post', [PostController::class, 'store']);

```

#### Explanation: 

Defines URL endpoints for listing and creating posts.





## STEP 10: Create Blade Files

### resources/views/user-posts.blade.php

```
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

```



### resources/views/create-post.blade.php

```
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

```

#### Explanation: 

Blade templates render the front-end UI in dark mode.





## STEP 11: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000/user-posts

```

#### Explanation:

Starts the Laravel development server to view the app.




## Expected Output:


### User Posts Page:


<img src="screenshots/Screenshot 2026-03-27 130057.png" width="900">


### Create Post Page:


<img src="screenshots/Screenshot 2026-03-27 130221.png" width="900">


### User Post View (After Adding New Post):


<img src="screenshots/Screenshot 2026-03-27 130235.png" width="900">



---

## Project Folder Structure:

```
PHP_Laravel12_Migration_Views/
│
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── PostController.php
│   │   └── Middleware/
│   ├── Models/
│   │   └── UserPostView.php
│   ├── Providers/
│   └── ...
│
├── bootstrap/
│   └── ...
│
├── config/
│   └── ...
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── xxxx_create_posts_table.php
│   │   ├── xxxx_create_user_posts_view.php
│   │   └── (other default Laravel migrations)
│   └── seeders/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── css/                (optional if using external CSS)
│   ├── js/                 (optional if using external JS)
│   └── views/
│       ├── create-post.blade.php
│       └── user-posts.blade.php
│
├── routes/
│   └── web.php
│
├── storage/
│   └── ...
│
├── tests/
│   └── ...
│
├── vendor/
│   └── ...
│
├── .env
├── artisan
├── composer.json
└── composer.lock

```
