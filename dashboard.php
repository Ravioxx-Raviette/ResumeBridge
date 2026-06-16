<?php
// This file is included in all dashboard pages
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ResumeBridge — <?= $pageTitle ?? 'Dashboard' ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --bg: #0a0d14;
    --surface: #111520;
    --card: #161b28;
    --card-hover: #1c2235;
    --border: #1e2535;
    --accent: #4f8ef7;
    --accent2: #7c6af7;
    --green: #3dd68c;
    --yellow: #f5c542;
    --red: #f06565;
    --text: #e8eaf0;
    --muted: #6b7494;
    --sidebar-w: 240px;
    --font-display: 'Syne', sans-serif;
    --font-body: 'DM Sans', sans-serif;
}
body {
    background: var(--bg); color: var(--text);
    font-family: var(--font-body);
    min-height: 100vh; display: flex;
}
/* Sidebar */
.sidebar {
    width: var(--sidebar-w); min-height: 100vh;
    background: var(--surface); border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; z-index: 100;
}
.sidebar-logo {
    padding: 24px 20px 20px;
    display: flex; align-items: center; gap: 10px;
    border-bottom: 1px solid var(--border);
}
.logo-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display); font-weight: 800; font-size: 14px; color: white;
}
.logo-name { font-family: var(--font-display); font-weight: 700; font-size: 16px; }

.nav { padding: 16px 12px; flex: 1; }
.nav-label { font-size: 10px; font-weight: 600; color: var(--muted); letter-spacing: 0.1em; text-transform: uppercase; padding: 8px 8px 4px; }
.nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px; text-decoration: none;
    color: var(--muted); font-size: 14px; font-weight: 500;
    transition: all 0.15s; margin-bottom: 2px;
}
.nav-link:hover { background: var(--card); color: var(--text); }
.nav-link.active { background: rgba(79,142,247,0.12); color: var(--accent); }
.nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }

.sidebar-user {
    padding: 16px; border-top: 1px solid var(--border);
}
.user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.user-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display); font-weight: 700; font-size: 14px; color: white;
    flex-shrink: 0;
}
.user-name { font-size: 13px; font-weight: 600; }
.user-email { font-size: 11px; color: var(--muted); }
.logout-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 12px; border-radius: 8px; text-decoration: none;
    color: var(--muted); font-size: 13px; transition: all 0.15s;
}
.logout-btn:hover { background: rgba(240,101,101,0.1); color: var(--red); }

/* Main content */
.main {
    margin-left: var(--sidebar-w);
    flex: 1; min-height: 100vh;
    padding: 0;
}
.topbar {
    padding: 20px 36px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.page-title { font-family: var(--font-display); font-size: 22px; font-weight: 700; }
.page-subtitle { font-size: 13px; color: var(--muted); margin-top: 2px; }
.content { padding: 32px 36px; }

/* Cards */
.card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: 16px; padding: 24px;
}
.stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: 14px; padding: 20px;
}
.stat-card-label { font-size: 12px; color: var(--muted); margin-bottom: 8px; font-weight: 500; }
.stat-card-value { font-family: var(--font-display); font-size: 32px; font-weight: 800; }
.stat-card-value.accent { color: var(--accent); }
.stat-card-value.green { color: var(--green); }

/* Buttons */
.btn {
    padding: 10px 18px; border-radius: 10px; font-family: var(--font-body);
    font-size: 14px; font-weight: 500; cursor: pointer;
    border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.15s;
}
.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: white;
}
.btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }
.btn-ghost {
    background: var(--card); border: 1px solid var(--border); color: var(--text);
}
.btn-ghost:hover { background: var(--card-hover); }

/* Alerts */
.alert { padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 16px; }
.alert-error { background: rgba(240,101,101,0.1); border: 1px solid rgba(240,101,101,0.3); color: var(--red); }
.alert-success { background: rgba(61,214,140,0.1); border: 1px solid rgba(61,214,140,0.3); color: var(--green); }

/* Score ring */
.score-ring-wrap { display: flex; flex-direction: column; align-items: center; }
.score-circle {
    width: 120px; height: 120px; border-radius: 50%;
    border: 6px solid var(--border);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    position: relative;
}
.score-num { font-family: var(--font-display); font-size: 28px; font-weight: 800; }
.score-label { font-size: 11px; color: var(--muted); }

/* Table */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
th { padding: 12px 14px; text-align: left; font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
td { padding: 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--card-hover); }

.badge {
    display: inline-flex; align-items: center; padding: 3px 10px;
    border-radius: 100px; font-size: 12px; font-weight: 600;
}
.badge-green { background: rgba(61,214,140,0.12); color: var(--green); }
.badge-yellow { background: rgba(245,197,66,0.12); color: var(--yellow); }
.badge-red { background: rgba(240,101,101,0.12); color: var(--red); }
</style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">RB</div>
        <div class="logo-name">ResumeBridge</div>
    </div>
    <nav class="nav">
        <div class="nav-label">Menu</div>
        <a href="dashboard.php" class="nav-link <?= $currentPage==='dashboard.php'?'active':'' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="analyzer.php" class="nav-link <?= $currentPage==='analyzer.php'?'active':'' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            AI Analyzer
        </a>
        <a href="history.php" class="nav-link <?= $currentPage==='history.php'?'active':'' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            History
        </a>
    </nav>
    <div class="sidebar-user">
        <div class="user-info">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['full_name'] ?? '') ?></div>
                <div class="user-email"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
            </div>
        </div>
        <a href="../logout.php" class="logout-btn">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
    </div>
</aside>

<main class="main">
