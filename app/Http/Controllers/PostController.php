<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserPostView;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = UserPostView::query();

        //  SEARCH FUNCTIONALITY
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%');
        }

        //  PAGINATION
        $data = $query->orderBy('user_id', 'asc')->paginate(3);

        return view('user-posts', compact('data'));
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