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