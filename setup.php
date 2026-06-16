<?php
session_start();
$step = isset($_POST['step']) ? $_POST['step'] : 'form';
$errors = [];
$success = false;

if ($step === 'install') {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $user = trim($_POST['db_user'] ?? 'root');
    $pass = $_POST['db_pass'] ?? '';
    $name = trim($_POST['db_name'] ?? 'resumebridge');
    $apikey = trim($_POST['api_key'] ?? '');

    // Test connection
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Create DB
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$name`");

        // Create tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(150) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS resumes (
            resume_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            file_name VARCHAR(255),
            raw_text LONGTEXT,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS job_postings (
            job_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            job_title VARCHAR(255),
            company_name VARCHAR(255),
            description LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS match_results (
            match_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            resume_id INT NOT NULL,
            job_id INT NOT NULL,
            match_score INT DEFAULT 0,
            ai_feedback LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
            FOREIGN KEY (resume_id) REFERENCES resumes(resume_id) ON DELETE CASCADE,
            FOREIGN KEY (job_id) REFERENCES job_postings(job_id) ON DELETE CASCADE
        )");

        // Write config file
        $configContent = "<?php
define('DB_HOST', " . var_export($host, true) . ");
define('DB_USER', " . var_export($user, true) . ");
define('DB_PASS', " . var_export($pass, true) . ");
define('DB_NAME', " . var_export($name, true) . ");

// Qwen API Key
define('QWEN_API_KEY', " . var_export($apikey, true) . ");
define('QWEN_MODEL', 'qwen-plus');

function getDB() {
    static \$pdo = null;
    if (\$pdo === null) {
        try {
            \$pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException \$e) {
            die('Database connection failed: ' . \$e->getMessage());
        }
    }
    return \$pdo;
}
";
        file_put_contents(__DIR__ . '/config/config.php', $configContent);
        $success = true;

    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ResumeBridge — Setup</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
    --bg:#0a0d14;--surface:#111520;--card:#161b28;--border:#1e2535;
    --accent:#4f8ef7;--accent2:#7c6af7;--green:#3dd68c;--red:#f06565;
    --text:#e8eaf0;--muted:#6b7494;
}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(79,142,247,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(79,142,247,.03) 1px,transparent 1px);background-size:40px 40px;z-index:0}
.wrap{position:relative;z-index:1;width:100%;max-width:500px}
.logo{display:flex;align-items:center;gap:10px;margin-bottom:32px;justify-content:center}
.logo-icon{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:16px;color:#fff}
.logo-text{font-family:'Syne',sans-serif;font-weight:700;font-size:20px}
.card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:32px}
.setup-title{font-family:'Syne',sans-serif;font-size:22px;font-weight:700;margin-bottom:6px}
.setup-sub{color:var(--muted);font-size:14px;margin-bottom:28px}

.steps{display:flex;gap:0;margin-bottom:28px}
.step{flex:1;text-align:center;padding:10px 0;position:relative;font-size:12px;color:var(--muted)}
.step.done{color:var(--green)}
.step.active{color:var(--accent);font-weight:500}
.step-dot{width:24px;height:24px;border-radius:50%;border:2px solid var(--border);margin:0 auto 6px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;background:var(--surface)}
.step.done .step-dot{border-color:var(--green);background:rgba(61,214,140,.12);color:var(--green)}
.step.active .step-dot{border-color:var(--accent);background:rgba(79,142,247,.12);color:var(--accent)}
.step-line{position:absolute;top:21px;left:calc(50% + 14px);right:calc(-50% + 14px);height:1px;background:var(--border)}
.step:last-child .step-line{display:none}

.field{margin-bottom:16px}
.field label{display:block;font-size:12px;color:var(--muted);margin-bottom:6px;font-weight:500}
.field input{width:100%;padding:11px 14px;background:var(--surface);border:1px solid var(--border);border-radius:10px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none;transition:border-color .2s}
.field input:focus{border-color:var(--accent)}
.field input::placeholder{color:var(--muted)}
.field-hint{font-size:11px;color:var(--muted);margin-top:4px}

.btn-primary{width:100%;padding:13px;background:linear-gradient(135deg,var(--accent),var(--accent2));border:none;border-radius:10px;color:#fff;font-family:'Syne',sans-serif;font-size:16px;font-weight:700;cursor:pointer;margin-top:8px;transition:opacity .2s}
.btn-primary:hover{opacity:.9}

.alert{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px}
.alert-error{background:rgba(240,101,101,.1);border:1px solid rgba(240,101,101,.3);color:var(--red)}

/* Success state */
.success-icon{width:64px;height:64px;border-radius:50%;background:rgba(61,214,140,.12);border:2px solid var(--green);margin:0 auto 20px;display:flex;align-items:center;justify-content:center}
.success-icon svg{width:28px;height:28px;color:var(--green)}
.checklist{list-style:none;margin:20px 0}
.checklist li{padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;display:flex;align-items:center;gap:8px}
.checklist li:last-child{border:none}
.checklist li::before{content:'✓';color:var(--green);font-weight:700;flex-shrink:0}
.go-btn{display:block;text-align:center;padding:13px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;color:#fff;font-family:'Syne',sans-serif;font-size:16px;font-weight:700;text-decoration:none;margin-top:8px;transition:opacity .2s}
.go-btn:hover{opacity:.9}

.section-label{font-size:11px;font-weight:600;color:var(--muted);letter-spacing:.05em;text-transform:uppercase;margin:20px 0 12px;padding-bottom:8px;border-bottom:1px solid var(--border)}
</style>
</head>
<body>
<div class="wrap">
    <div class="logo">
        <div class="logo-icon">RB</div>
        <div class="logo-text">ResumeBridge</div>
    </div>

    <div class="card">
        <?php if ($success): ?>
        <!-- SUCCESS -->
        <div style="text-align:center">
            <div class="success-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="setup-title">Setup Complete!</div>
            <div class="setup-sub">ResumeBridge is ready to use.</div>
            <ul class="checklist">
                <li>Database created successfully</li>
                <li>All tables created (users, resumes, job_postings, match_results)</li>
                <li>config/config.php updated</li>
                <?php if (!empty($_POST['api_key'])): ?>
                <li>Qwen API key saved</li>
                <?php endif; ?>
            </ul>
            <a href="index.php" class="go-btn">Go to Login Page →</a>
            <p style="font-size:11px;color:var(--muted);margin-top:12px;text-align:center">You can delete setup.php after this for security.</p>
        </div>

        <?php else: ?>
        <!-- FORM -->
        <div class="setup-title">Setup Wizard</div>
        <div class="setup-sub">Fill in your database details and we'll set everything up automatically.</div>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="step" value="install">

            <div class="section-label">Database Settings</div>

            <div class="field">
                <label>Database Host</label>
                <input type="text" name="db_host" value="localhost" required>
                <div class="field-hint">Usually "localhost" for XAMPP</div>
            </div>
            <div class="field">
                <label>MySQL Username</label>
                <input type="text" name="db_user" value="root" required>
                <div class="field-hint">Default XAMPP username is "root"</div>
            </div>
            <div class="field">
                <label>MySQL Password</label>
                <input type="password" name="db_pass" placeholder="Leave blank for XAMPP default">
                <div class="field-hint">XAMPP default has no password — leave this empty</div>
            </div>
            <div class="field">
                <label>Database Name</label>
                <input type="text" name="db_name" value="resumebridge" required>
                <div class="field-hint">Will be created automatically if it doesn't exist</div>
            </div>

            <div class="section-label">AI Settings</div>

            <div class="field">
                <label>Qwen API Key</label>
                <input type="text" name="api_key" placeholder="sk-xxxxxxxxxxxxxxxx">
                <div class="field-hint">Get your key from <strong style="color:var(--accent)">dashscope.aliyuncs.com</strong> — you can add this later</div>
            </div>

            <button type="submit" class="btn-primary">Install ResumeBridge</button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
