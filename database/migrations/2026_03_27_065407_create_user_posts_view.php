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
            ->select('posts.id as post_id', 'users.id as user_id', 'users.name', 'posts.title');

        // Create or replace the view (avoids "already exists" error)
        Schema::createOrReplaceView('user_posts_view', $query);
    }

    public function down(): void
    {
        Schema::dropView('user_posts_view');
    }
};