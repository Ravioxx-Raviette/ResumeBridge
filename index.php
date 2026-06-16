<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            header('Location: pages/dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } elseif ($action === 'register') {
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                $success = 'Account created! You can now log in.';
            } catch (PDOException $e) {
                $error = 'Email already registered.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ResumeBridge — Professional AI Job Matching</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        /* OLED / True Midnight Palette */
        --bg: #000000;           /* Pure black background */
        --surface: #09090b;      /* Zinc 950 */
        --card: #121214;         /* Zinc 900 for inputs/tabs */
        --border: #27272a;       /* Zinc 800 for crisp, subtle borders */
        
        /* Modern Premium Accents */
        --accent: #6366f1;       /* Crisp Indigo */
        --accent-hover: #4f46e5; /* Deep Indigo */
        --accent2: #ec4899;      /* Vibrant Magenta for gradient punch */
        --green: #10b981;        /* Professional success green */
        
        /* High Contrast Text */
        --text: #fafafa;         /* Pure off-white */
        --muted: #a1a1aa;        /* Slate gray */
        
        /* Typography */
        --font-display: 'Syne', sans-serif;
        --font-body: 'DM Sans', sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: var(--font-body);
        background-color: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
        position: relative;
    }

    /* --- Immersive Background Elements --- */
    .bg-elements {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
    }

    /* Grid Overlay */
    .bg-elements::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(var(--border) 1px, transparent 1px),
                          linear-gradient(90deg, var(--border) 1px, transparent 1px);
        background-size: 40px 40px;
        opacity: 0.2; /* Dimmer for true black */
    }

    /* Floating Ambient Glows */
    .glow-1 {
        position: absolute;
        width: 600px; height: 600px;
        background: var(--accent2);
        filter: blur(150px);
        opacity: 0.12;
        top: -20%; left: -10%;
        border-radius: 50%;
        animation: float 15s infinite alternate ease-in-out;
    }

    .glow-2 {
        position: absolute;
        width: 500px; height: 500px;
        background: var(--accent);
        filter: blur(150px);
        opacity: 0.12;
        bottom: -20%; right: -10%;
        border-radius: 50%;
        animation: float 20s infinite alternate-reverse ease-in-out;
    }

    @keyframes float {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    /* --- Centered Layout Wrapper --- */
    .app-wrapper {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 440px;
        padding: 2rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Brand Header */
    .brand-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .logo-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 1.2rem;
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
        margin-bottom: 1.25rem;
    }

    .logo-text {
        font-family: var(--font-display);
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        margin-bottom: 0.5rem;
    }

    .brand-tagline {
        color: var(--muted);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* --- Auth Card --- */
    .auth-card {
        width: 100%;
        background: rgba(18, 18, 20, 0.6); /* Slightly transparent dark charcoal */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 1);
    }

    /* Segmented Control Tabs */
    .tab-container {
        display: flex;
        background: var(--surface);
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        position: relative;
    }

    .tab-btn {
        flex: 1;
        text-align: center;
        padding: 12px 0;
        border: none;
        background: transparent;
        color: var(--muted);
        font-weight: 600;
        font-family: inherit;
        font-size: 0.95rem;
        cursor: pointer;
        border-radius: 10px;
        transition: color 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .tab-btn.active { color: var(--text); }

    .tab-indicator {
        position: absolute;
        top: 6px;
        bottom: 6px;
        left: 6px;
        width: calc(50% - 6px);
        background: var(--border);
        border-radius: 10px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }

    /* Form Styles */
    .form-section { display: none; animation: fadeIn 0.3s ease forwards; }
    .form-section.active { display: block; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-header { margin-bottom: 1.5rem; text-align: center; }
    .form-header h2 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; margin-bottom: 4px; }
    .form-header p { color: var(--muted); font-size: 0.9rem; }

    .input-group { margin-bottom: 1.25rem; position: relative; }
    .input-group label {
        display: block; font-size: 0.85rem; font-weight: 600;
        color: var(--text); margin-bottom: 8px; letter-spacing: 0.02em;
    }

    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-icon {
        position: absolute; left: 16px; color: var(--muted);
        display: flex; align-items: center; pointer-events: none;
    }

    .input-group input {
        width: 100%;
        padding: 14px 16px 14px 44px;
        background: var(--surface); /* Darker input background */
        border: 1px solid var(--border);
        border-radius: 12px;
        font-family: inherit;
        font-size: 0.95rem;
        color: var(--text);
        transition: all 0.2s ease;
    }

    .input-group input::placeholder { color: #52525b; }
    .input-group input:focus {
        outline: none;
        border-color: var(--accent);
        background: rgba(99, 102, 241, 0.05); /* Slight indigo tint on focus */
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }
    .input-group input:focus + .input-icon { color: var(--accent); }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(to right, var(--accent), var(--accent2));
        color: white;
        border: none;
        border-radius: 12px;
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 1rem;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(99, 102, 241, 0.4);
        filter: brightness(1.1);
    }
    .btn-submit:active { transform: translateY(0); }

    /* Alerts */
    .alert { padding: 14px 16px; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500; display: flex; align-items: center; gap: 10px; }
    .alert-error { background-color: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background-color: rgba(16, 185, 129, 0.1); color: var(--green); border: 1px solid rgba(16, 185, 129, 0.2); }

    /* Footer text */
    .auth-footer {
        margin-top: 2rem;
        text-align: center;
        color: var(--muted);
        font-size: 0.85rem;
    }
    .auth-footer a {
        color: var(--accent);
        text-decoration: none;
        transition: color 0.2s;
    }
    .auth-footer a:hover { color: var(--accent2); }
</style>
</head>
<body>

<div class="bg-elements">
    <div class="glow-1"></div>
    <div class="glow-2"></div>
</div>

<div class="app-wrapper">
    
    <div class="brand-header">
        <div class="logo-icon">RB</div>
        <div class="logo-text">ResumeBridge</div>
        <div class="brand-tagline">Optimize your career trajectory with Enterprise-grade AI analysis.</div>
    </div>

    <div class="auth-card">
        
        <div class="tab-container">
            <div class="tab-indicator" id="tab-indicator"></div>
            <button class="tab-btn active" onclick="switchTab('login', 0)" id="btn-login">Sign In</button>
            <button class="tab-btn" onclick="switchTab('register', 1)" id="btn-register">Register</button>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="form-section active" id="sec-login">
            <div class="form-header">
                <h2>Welcome back</h2>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                
                <div class="input-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="name@company.com" required>
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="••••••••" required>
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>
        </div>

        <div class="form-section" id="sec-register">
            <div class="form-header">
                <h2>Create account</h2>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="register">
                
                <div class="input-group">
                    <label>Full Name</label>
                    <div class="input-wrapper">
                        <input type="text" name="full_name" placeholder="Juan dela Cruz" required>
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="name@company.com" required>
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="At least 6 characters" required>
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>
            </form>
        </div>

    </div>
    
    <div class="auth-footer">
        Protected by ResumeBridge. <a href="#">Privacy Policy</a>
    </div>

</div>

<script>
    function switchTab(tab, index) {
        // Handle Button States
        document.getElementById('btn-login').classList.remove('active');
        document.getElementById('btn-register').classList.remove('active');
        document.getElementById('btn-' + tab).classList.add('active');

        // Move the sliding indicator
        const indicator = document.getElementById('tab-indicator');
        indicator.style.transform = `translateX(${index * 100}%)`;

        // Switch Forms
        document.getElementById('sec-login').classList.remove('active');
        document.getElementById('sec-register').classList.remove('active');
        document.getElementById('sec-' + tab).classList.add('active');
    }

    // If successful registration, default to login tab
    <?php if ($success): ?>
    switchTab('login', 0);
    <?php elseif (isset($_POST['action']) && $_POST['action'] === 'register' && $error): ?>
    // If registration failed, keep them on the register tab
    switchTab('register', 1);
    <?php endif; ?>
</script>
</body>
</html>