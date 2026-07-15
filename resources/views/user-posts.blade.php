<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Migration Views — Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* ── HEADER ── */
        .page-header {
            text-align: center;
            padding: 36px 20px 10px;
        }
        .page-header h1 {
            font-size: 30px;
            font-weight: 700;
            background: linear-gradient(90deg, #ffcc00, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 6px;
        }
        .page-header p { color: #888; font-size: 13px; margin: 0; }

        /* ── LAYOUT ── */
        .wrap { width: 96%; max-width: 1300px; margin: 0 auto; padding-bottom: 60px; }

        /* ── STAT CARDS ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin: 28px 0 20px;
        }
        .stat-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 18px 16px;
            text-align: center;
        }
        .stat-card .val {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #ffcc00, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-card .lbl { font-size: 12px; color: #888; margin-top: 4px; }

        /* ── SECTION TITLE ── */
        .sec-title {
            font-size: 13px;
            font-weight: 600;
            color: #ffcc00;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 28px 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sec-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.07);
        }

        /* ── ACTION BUTTONS ROW ── */
        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn-action {
            padding: 9px 18px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            color: #fff;
        }
        .btn-action:hover { transform: translateY(-1px); opacity: 0.9; }
        .btn-refresh  { background: linear-gradient(135deg, #11998e, #38ef7d); color: #000; }
        .btn-bench    { background: linear-gradient(135deg, #f7971e, #ffd200); color: #000; }
        .btn-sync     { background: linear-gradient(135deg, #8e2de2, #4a00e0); }
        .btn-replic   { background: linear-gradient(135deg, #0072ff, #00c6ff); }
        .btn-add      { background: linear-gradient(135deg, #00c6ff, #0072ff); text-decoration: none; display: inline-flex; align-items: center; padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #fff; }

        /* ── SEARCH BAR ── */
        .search-row {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }
        .search-row input {
            flex: 1;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            color: #fff;
            font-size: 14px;
            outline: none;
        }
        .search-row input:focus { border-color: #ffcc00; }
        .search-row button {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #ffcc00, #ff6b6b);
            color: #000;
            font-weight: 600;
            cursor: pointer;
        }

        /* ── FLASH ── */
        .flash {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            background: rgba(0,200,100,0.12);
            border: 1px solid rgba(0,200,100,0.35);
            color: #00e676;
        }

        /* ── TABLE ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            padding: 13px 14px;
            background: rgba(255,255,255,0.06);
            color: #ffcc00;
            font-size: 13px;
            text-align: center;
        }
        .data-table td {
            padding: 12px 14px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 14px;
        }
        .data-table tr:hover td { background: rgba(255,255,255,0.04); }
        .table-wrap {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            overflow: hidden;
        }

        /* ── ACTION BTNS IN TABLE ── */
        .btn-edit {
            padding: 5px 13px;
            border-radius: 8px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            margin-right: 6px;
        }
        .btn-del {
            padding: 5px 13px;
            border-radius: 8px;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: #fff;
            border: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ── PAGINATION ── */
        .pag-wrap { margin-top: 20px; display: flex; justify-content: center; }
        .pagination .page-link {
            background: rgba(255,255,255,0.06);
            border: none;
            color: #fff;
            border-radius: 8px;
            margin: 0 2px;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #ffcc00, #ff6b6b);
            color: #000;
            font-weight: 700;
        }

        /* ── PANEL CARD ── */
        .panel {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .panel-title {
            font-size: 13px;
            font-weight: 700;
            color: #ffcc00;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* ── SCHEMA BLUEPRINT ── */
        .schema-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
        }
        .schema-table-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .schema-table-box .tbl-head {
            background: linear-gradient(135deg, #1a1a3e, #2a2a5e);
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            color: #00c6ff;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .schema-table-box .tbl-head span {
            font-size: 10px;
            color: #888;
            font-weight: 400;
            margin-left: 6px;
        }
        .schema-col-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 12px;
        }
        .schema-col-row:last-child { border-bottom: none; }
        .col-name { color: #e0e0e0; font-weight: 600; }
        .col-type { color: #888; font-size: 11px; }
        .badge-pk  { background: rgba(255,204,0,0.2);  color: #ffcc00; padding: 2px 7px; border-radius: 5px; font-size: 10px; }
        .badge-fk  { background: rgba(0,198,255,0.2);  color: #00c6ff; padding: 2px 7px; border-radius: 5px; font-size: 10px; }
        .badge-mul { background: rgba(138,43,226,0.2); color: #bf7fff; padding: 2px 7px; border-radius: 5px; font-size: 10px; }

        /* ── BENCHMARK TABLE ── */
        .bench-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 13px;
        }
        .bench-row:last-child { border-bottom: none; }
        .bench-bar-wrap { flex: 1; margin: 0 14px; height: 6px; background: rgba(255,255,255,0.07); border-radius: 4px; overflow: hidden; }
        .bench-bar { height: 100%; border-radius: 4px; background: linear-gradient(90deg, #11998e, #38ef7d); }
        .bench-ms { font-size: 12px; color: #ffcc00; font-weight: 700; min-width: 55px; text-align: right; }

        /* ── SYNC DELTA ── */
        .sync-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 13px;
        }
        .sync-row:last-child { border-bottom: none; }
        .badge-ok      { background: rgba(0,200,100,0.2);  color: #00e676; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .badge-warn    { background: rgba(255,193,7,0.2);   color: #ffc107; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .badge-missing { background: rgba(255,65,108,0.2);  color: #ff416c; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }

        /* ── REPLICATION ── */
        .replic-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
            padding: 10px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 12px;
            align-items: center;
        }
        .replic-row:last-child { border-bottom: none; }
        .badge-conn    { background: rgba(0,198,255,0.2); color: #00c6ff; padding: 3px 10px; border-radius: 6px; font-size: 11px; }
        .badge-synced  { background: rgba(0,200,100,0.2); color: #00e676; padding: 3px 10px; border-radius: 6px; font-size: 11px; }
        .badge-missing2{ background: rgba(255,65,108,0.2); color: #ff416c; padding: 3px 10px; border-radius: 6px; font-size: 11px; }

        /* ── QUERY LOG ── */
        .log-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 12px;
            color: #aaa;
        }
        .log-row:last-child { border-bottom: none; }
        .log-ms { color: #ffcc00; font-weight: 700; }

        @media (max-width: 768px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .replic-row { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<div class="page-header">
    <h1>⚡ Laravel Migration Views Dashboard</h1>
    <p>Database View Engine · Schema Visualizer · Benchmark Profiler · Sync Delta · Replication Monitor</p>
</div>

<div class="wrap">

    {{-- ── STAT CARDS ── --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="val">{{ $stats['total_users'] }}</div>
            <div class="lbl">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="val">{{ $stats['total_posts'] }}</div>
            <div class="lbl">Total Posts</div>
        </div>
        <div class="stat-card">
            <div class="val">{{ $stats['view_rows'] }}</div>
            <div class="lbl">View Rows</div>
        </div>
        <div class="stat-card">
            <div class="val">{{ $stats['query_ms'] }}<span style="font-size:14px;color:#888;">ms</span></div>
            <div class="lbl">Last Query Time</div>
        </div>
    </div>

    {{-- ── FLASH ── --}}
    @if(session('success'))
    <div class="flash">✅ {{ session('success') }}</div>
    @endif

    {{-- ── ACTION BUTTONS ── --}}
    <div class="sec-title">🎛️ Control Panel</div>
    <div class="action-row">
        <a href="{{ route('create-post') }}" class="btn-add">＋ Add Post</a>

        <form method="POST" action="{{ route('refresh-view') }}" style="margin:0">
            @csrf
            <button class="btn-action btn-refresh" type="submit">🔄 Refresh Materialized View</button>
        </form>

        <form method="POST" action="{{ route('benchmark') }}" style="margin:0">
            @csrf
            <button class="btn-action btn-bench" type="submit">📊 Run Benchmark Profiler</button>
        </form>

        <form method="POST" action="{{ route('sync-delta') }}" style="margin:0">
            @csrf
            <button class="btn-action btn-sync" type="submit">🔍 Sync Delta Check</button>
        </form>

        <form method="POST" action="{{ route('replication') }}" style="margin:0">
            @csrf
            <button class="btn-action btn-replic" type="submit">🌐 Replication Status</button>
        </form>
    </div>

    {{-- ── SEARCH + TABLE ── --}}
    <div class="sec-title">📋 Posts Data (via SQL View)</div>

    <form class="search-row" method="GET" action="{{ route('user-posts') }}">
        <input type="text" name="search" placeholder="🔎 Search by user name or post title..." value="{{ request('search') }}">
        <button type="submit">Search</button>
        @if(request('search'))
            <a href="{{ route('user-posts') }}" style="padding:10px 16px;border-radius:10px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;font-size:13px;">✕ Clear</a>
        @endif
    </form>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Post ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Post Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr>
                    <td>{{ $row->post_id }}</td>
                    <td>{{ $row->user_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->title }}</td>
                    <td>
                        <a href="{{ route('edit-post', $row->post_id) }}" class="btn-edit">✏️ Edit</a>
                        <form action="{{ route('delete-post', $row->post_id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this post?')">
                            @csrf
                            <button type="submit" class="btn-del">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding:24px;color:#666;text-align:center;">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pag-wrap">
        {{ $data->appends(request()->query())->links() }}
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 2: SCHEMA BLUEPRINT VISUALIZER                               --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="sec-title">🗂️ Feature 2 — Live Schema Blueprint Visualizer</div>
    <div class="panel">
        <div class="panel-title">Table · Column · Key Dependency Map</div>
        <div class="schema-grid">
            @foreach($schema as $tableName => $columns)
            <div class="schema-table-box">
                <div class="tbl-head">
                    {{ $tableName }}
                    <span>{{ count($columns) }} cols</span>
                </div>
                @foreach($columns as $col)
                <div class="schema-col-row">
                    <span class="col-name">{{ $col['field'] }}</span>
                    <span class="col-type">{{ $col['type'] }}</span>
                    @if($col['key'] === 'PRI')
                        <span class="badge-pk">PK</span>
                    @elseif($col['key'] === 'MUL')
                        <span class="badge-fk">FK</span>
                    @elseif($col['key'] === 'UNI')
                        <span class="badge-mul">UNI</span>
                    @else
                        <span style="width:30px;"></span>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        {{-- Dependency Flow Lines --}}
        <div style="margin-top:18px;padding:14px;background:rgba(0,198,255,0.05);border:1px solid rgba(0,198,255,0.15);border-radius:10px;font-size:12px;color:#aaa;">
            <span style="color:#00c6ff;font-weight:700;">Dependency Flow:</span>
            &nbsp;
            <span style="color:#ffcc00;">users</span>
            <span style="color:#555;"> ──[ id ]──▶ </span>
            <span style="color:#ff6b6b;">posts</span>
            <span style="color:#555;"> ──[ user_id FK ]──▶ </span>
            <span style="color:#38ef7d;">user_posts_view</span>
            <span style="color:#555;"> (JOIN result)</span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 3: BENCHMARK PROFILER                                        --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="sec-title">📊 Feature 3 — Query Benchmark Profiler</div>
    <div class="panel">
        <div class="panel-title">Query Performance Audit Results</div>

        @if(session('benchmark'))
            @php $maxMs = max(array_column(session('benchmark'), 'ms')) ?: 1; @endphp
            @foreach(session('benchmark') as $b)
            <div class="bench-row">
                <span style="min-width:160px;font-size:12px;">{{ $b['label'] }}</span>
                <div class="bench-bar-wrap">
                    <div class="bench-bar" style="width:{{ min(100, ($b['ms']/$maxMs)*100) }}%"></div>
                </div>
                <span style="color:#aaa;font-size:11px;min-width:60px;">{{ $b['rows'] }} rows</span>
                <span class="bench-ms">{{ $b['ms'] }}ms</span>
            </div>
            @endforeach
        @else
            <div style="color:#555;font-size:13px;padding:10px 0;">Click <strong style="color:#ffd200;">Run Benchmark Profiler</strong> to see query performance results.</div>
        @endif

        {{-- Query Log --}}
        @if(session('query_logs'))
        <div style="margin-top:16px;">
            <div style="font-size:11px;color:#888;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.8px;">Recent Query Log</div>
            @foreach(session('query_logs') as $log)
                @if(isset($log['at'], $log['search'], $log['rows'], $log['ms']))
                <div class="log-row">
                    <span>{{ $log['at'] }}</span>
                    <span>Search: <em>{{ $log['search'] }}</em></span>
                    <span>{{ $log['rows'] }} rows</span>
                    <span class="log-ms">{{ $log['ms'] }}ms</span>
                </div>
                @endif
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 4: SYNC DELTA                                                --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="sec-title">🔍 Feature 4 — Raw SQL Sync Delta Validator</div>
    <div class="panel">
        <div class="panel-title">Structure Integrity Check Results</div>

        @if(session('sync_logs'))
            @foreach(session('sync_logs') as $log)
            <div class="sync-row">
                <span>{{ $log['check'] }}</span>
                @if($log['status'] === 'OK')
                    <span class="badge-ok">✓ OK</span>
                @elseif($log['status'] === 'WARNING')
                    <span class="badge-warn">⚠ WARNING</span>
                @else
                    <span class="badge-missing">✗ {{ $log['status'] }}</span>
                @endif
            </div>
            @endforeach
            <div style="margin-top:12px;font-size:12px;color:#888;">
                Total Issues Found: <strong style="color:{{ session('sync_issues') > 0 ? '#ff416c' : '#00e676' }}">{{ session('sync_issues') }}</strong>
            </div>
        @else
            <div style="color:#555;font-size:13px;padding:10px 0;">Click <strong style="color:#bf7fff;">Sync Delta Check</strong> to validate database structure integrity.</div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 5: REPLICATION STATUS                                        --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="sec-title">🌐 Feature 5 — Multi-DB Replication Control</div>
    <div class="panel">
        <div class="panel-title">Connection · Database · View Sync Status</div>

        @if(session('replication_report'))
            <div class="replic-row" style="color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.6px;">
                <span>Connection</span><span>Database</span><span>Host</span><span>View Status</span>
            </div>
            @foreach(session('replication_report') as $r)
            <div class="replic-row">
                <span><span class="badge-conn">{{ $r['connection'] }}</span></span>
                <span style="color:#e0e0e0;">{{ $r['database'] }}</span>
                <span style="color:#888;">{{ $r['host'] }}</span>
                <span>
                    @if($r['view'] === 'Synced')
                        <span class="badge-synced">✓ Synced</span>
                    @else
                        <span class="badge-missing2">✗ {{ $r['view'] }}</span>
                    @endif
                </span>
            </div>
            @endforeach
        @else
            <div style="color:#555;font-size:13px;padding:10px 0;">Click <strong style="color:#00c6ff;">Replication Status</strong> to check cross-database view sync.</div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 1: MATERIALIZED VIEW REFRESH LOG                             --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="sec-title">🔄 Feature 1 — Materialized View Refresh Log</div>
    <div class="panel">
        <div class="panel-title">View Refresh History</div>
        @if(session('refresh_logs'))
            @foreach(session('refresh_logs') as $r)
            <div class="log-row">
                <span>{{ $r['at'] }}</span>
                <span>View refreshed</span>
                <span class="log-ms">{{ $r['ms'] }}ms</span>
            </div>
            @endforeach
        @else
            <div style="color:#555;font-size:13px;padding:10px 0;">Click <strong style="color:#38ef7d;">Refresh Materialized View</strong> to rebuild the SQL view and log refresh time.</div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
