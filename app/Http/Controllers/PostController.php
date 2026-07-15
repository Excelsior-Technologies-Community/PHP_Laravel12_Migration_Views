<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserPostView;

class PostController extends Controller
{
    // ─── MAIN INDEX ───────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $start = microtime(true);

        $query = UserPostView::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%$s%")
                  ->orWhere('title', 'like', "%$s%");
        }

        $data = $query->orderBy('user_id', 'asc')->paginate(5);

        $queryTime = round((microtime(true) - $start) * 1000, 2);

        // ── BENCHMARK: store last 10 query times in session ──
        $logs = session('query_logs', []);
        array_unshift($logs, [
            'time'   => $queryTime,
            'search' => $request->search ?? '(none)',
            'rows'   => $data->total(),
            'at'     => now()->format('H:i:s'),
        ]);
        session(['query_logs' => array_slice($logs, 0, 10)]);

        // ── SCHEMA BLUEPRINT ──
        $schema = $this->getSchemaBlueprint();

        // ── STATS ──
        $stats = [
            'total_posts' => DB::table('posts')->count(),
            'total_users' => DB::table('users')->count(),
            'view_rows'   => DB::table('user_posts_view')->count(),
            'query_ms'    => $queryTime,
        ];

        return view('user-posts', compact('data', 'schema', 'stats'));
    }

    // ─── CREATE ───────────────────────────────────────────────────────────────
    public function create()
    {
        $users = DB::table('users')->get();
        return view('create-post', compact('users'));
    }

    // ─── STORE ────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate(['user_id' => 'required', 'title' => 'required|string|max:255']);

        DB::table('posts')->insert([
            'user_id'    => $request->user_id,
            'title'      => $request->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user-posts')->with('success', 'Post added successfully!');
    }

    // ─── EDIT ─────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $post  = DB::table('posts')->where('id', $id)->first();
        $users = DB::table('users')->get();
        return view('edit-post', compact('post', 'users'));
    }

    // ─── UPDATE ───────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate(['user_id' => 'required', 'title' => 'required|string|max:255']);

        DB::table('posts')->where('id', $id)->update([
            'user_id'    => $request->user_id,
            'title'      => $request->title,
            'updated_at' => now(),
        ]);

        return redirect()->route('user-posts')->with('success', 'Post updated successfully!');
    }

    // ─── DELETE ───────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        DB::table('posts')->where('id', $id)->delete();
        return redirect()->route('user-posts')->with('success', 'Post deleted successfully!');
    }

    // ─── FEATURE 1: MATERIALIZED VIEW REFRESH ────────────────────────────────
    public function refreshView()
    {
        $start = microtime(true);

        // Drop and recreate the view (simulate materialized refresh)
        DB::statement('DROP VIEW IF EXISTS user_posts_view');
        DB::statement('
            CREATE VIEW user_posts_view AS
            SELECT posts.id as post_id, users.id as user_id, users.name, posts.title
            FROM users
            INNER JOIN posts ON users.id = posts.user_id
        ');

        $ms = round((microtime(true) - $start) * 1000, 2);

        // Push refresh log to session
        $logs = session('refresh_logs', []);
        array_unshift($logs, ['at' => now()->format('H:i:s'), 'ms' => $ms]);
        session(['refresh_logs' => array_slice($logs, 0, 5)]);

        return redirect()->route('user-posts')->with('success', "View refreshed in {$ms}ms");
    }

    // ─── FEATURE 3: BENCHMARK PROFILER ───────────────────────────────────────
    public function benchmark()
    {
        $results = [];

        $queries = [
            'Full View Scan'     => fn() => DB::table('user_posts_view')->get(),
            'Users Count'        => fn() => DB::table('users')->count(),
            'Posts Count'        => fn() => DB::table('posts')->count(),
            'Join Query'         => fn() => DB::table('users')->join('posts', 'users.id', 'posts.user_id')->select('users.name', 'posts.title')->get(),
            'Search Filter'      => fn() => DB::table('user_posts_view')->where('name', 'like', '%Demo%')->get(),
        ];

        foreach ($queries as $label => $fn) {
            $s = microtime(true);
            $rows = $fn();
            $results[] = [
                'label' => $label,
                'ms'    => round((microtime(true) - $s) * 1000, 2),
                'rows'  => is_countable($rows) ? count($rows) : $rows,
            ];
        }

        return redirect()->route('user-posts')->with('benchmark', $results);
    }

    // ─── FEATURE 4: SYNC DELTA ────────────────────────────────────────────────
    public function syncDelta()
    {
        $logs   = [];
        $issues = 0;

        // Check 1: view exists
        $viewExists = DB::select("SHOW FULL TABLES WHERE Table_type = 'VIEW' AND Tables_in_" . env('DB_DATABASE') . " = 'user_posts_view'");
        $logs[] = ['check' => 'View user_posts_view exists', 'status' => !empty($viewExists) ? 'OK' : 'MISSING'];
        if (empty($viewExists)) $issues++;

        // Check 2: posts table has user_id FK column
        $cols = DB::select("SHOW COLUMNS FROM posts");
        $colNames = array_column($cols, 'Field');
        $hasUserId = in_array('user_id', $colNames);
        $logs[] = ['check' => 'posts.user_id column exists', 'status' => $hasUserId ? 'OK' : 'MISSING'];
        if (!$hasUserId) $issues++;

        // Check 3: orphan posts (posts with no matching user)
        $orphans = DB::table('posts')->whereNotIn('user_id', DB::table('users')->pluck('id'))->count();
        $logs[] = ['check' => "Orphan posts check ($orphans orphans)", 'status' => $orphans === 0 ? 'OK' : 'WARNING'];
        if ($orphans > 0) $issues++;

        // Check 4: view row count matches join
        $viewCount = DB::table('user_posts_view')->count();
        $joinCount = DB::table('users')->join('posts', 'users.id', 'posts.user_id')->count();
        $logs[] = ['check' => "View rows ($viewCount) match join ($joinCount)", 'status' => $viewCount === $joinCount ? 'OK' : 'MISMATCH'];
        if ($viewCount !== $joinCount) $issues++;

        session(['sync_logs' => $logs, 'sync_issues' => $issues]);

        return redirect()->route('user-posts')->with('success', "Sync Delta complete — $issues issue(s) found.");
    }

    // ─── FEATURE 5: REPLICATION STATUS ───────────────────────────────────────
    public function replicationStatus()
    {
        $connections = config('database.connections');
        $report = [];

        foreach (['mysql'] as $conn) {
            if (!isset($connections[$conn])) continue;
            $cfg = $connections[$conn];
            try {
                $pdo = DB::connection($conn)->getPdo();
                $viewExists = DB::connection($conn)
                    ->select("SHOW FULL TABLES WHERE Table_type = 'VIEW' AND Tables_in_{$cfg['database']} = 'user_posts_view'");
                $report[] = [
                    'connection' => $conn,
                    'database'   => $cfg['database'],
                    'host'       => $cfg['host'],
                    'status'     => 'Connected',
                    'view'       => !empty($viewExists) ? 'Synced' : 'Missing',
                ];
            } catch (\Exception $e) {
                $report[] = [
                    'connection' => $conn,
                    'database'   => $cfg['database'] ?? '?',
                    'host'       => $cfg['host'] ?? '?',
                    'status'     => 'Failed: ' . $e->getMessage(),
                    'view'       => 'Unknown',
                ];
            }
        }

        session(['replication_report' => $report]);
        return redirect()->route('user-posts')->with('success', 'Replication status checked.');
    }

    // ─── HELPER: SCHEMA BLUEPRINT ─────────────────────────────────────────────
    private function getSchemaBlueprint(): array
    {
        $tables = ['users', 'posts', 'user_posts_view'];
        $schema = [];

        foreach ($tables as $table) {
            try {
                $cols = DB::select("SHOW COLUMNS FROM `$table`");
                $schema[$table] = array_map(fn($c) => [
                    'field' => $c->Field,
                    'type'  => $c->Type,
                    'null'  => $c->Null,
                    'key'   => $c->Key,
                ], $cols);
            } catch (\Exception $e) {
                $schema[$table] = [];
            }
        }

        return $schema;
    }
}
