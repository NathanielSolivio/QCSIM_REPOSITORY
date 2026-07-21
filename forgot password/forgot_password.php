<!-- 
 Credits: 
 Creators: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Programmed/Written by: Nathaniel P. Solivio
 Tested by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Design by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
-->

<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/mailer.php';

if (isLoggedIn()) { header('Location: /qcsim/index.php'); exit; }

$error = $success = '';
$submittedEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedEmail = trim($_POST['email'] ?? '');

    if (!$submittedEmail) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("SELECT id, first_name, last_name, is_verified FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $submittedEmail);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && $user['is_verified']) {
            $invalidate = $conn->prepare("UPDATE password_resets SET used = 1 WHERE email = ? AND used = 0");
            $invalidate->bind_param("s", $submittedEmail);
            $invalidate->execute();

            $token   = generateToken();
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $ins = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $ins->bind_param("sss", $submittedEmail, $token, $expires);

            if ($ins->execute()) {
                $fullName = $user['first_name'] . ' ' . $user['last_name'];
                sendPasswordResetEmail($submittedEmail, $fullName, $token);
            }
        }

        $success = 'If an account exists with that email, a reset link has been sent. Please check your inbox.';
        $submittedEmail = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — QCSim</title>
  <link rel="stylesheet" href="/qcsim/Assets/MainWebsite/style.css">
  <style>
    body { display:flex; min-height:100vh; background:#e8f3fc; }
    .auth-left {
      width:420px; min-width:320px; flex-shrink:0;
      background:#fff;
      display:flex; flex-direction:column; justify-content:center;
      padding:56px 48px;
      position:relative; z-index:2;
      box-shadow:4px 0 32px rgba(26,107,181,.10);
    }
    .auth-logo { display:flex; align-items:center; gap:10px; margin-bottom:36px; }
    .auth-logo img { height:36px; }
    .auth-logo span { font-family:'Raleway',sans-serif; font-weight:800; font-size:22px; color:var(--primary); }
    .auth-left h1 { font-family:'Raleway',sans-serif; font-size:28px; font-weight:900; margin-bottom:6px; }
    .auth-left p  { color:var(--text-muted); font-size:14px; margin-bottom:28px; line-height:1.5; }
    .auth-right {
      flex:1;
      background:url('/qcsim/Assets/MainWebsite/bg_lab.png') center/cover no-repeat;
      position:relative;
    }
    .auth-right::after {
      content:''; position:absolute; inset:0;
      background:linear-gradient(135deg, rgba(232,243,252,.55) 0%, rgba(168,216,255,.25) 100%);
    }
    .back-row { text-align:center; margin-top:20px; font-size:14px; color:var(--text-muted); }
    .back-row a { font-weight:700; }

    @media (max-width: 700px) {
      body { flex-direction:column; }
      .auth-left { width:100%; padding:40px 24px; box-shadow:none; }
      .auth-right { display:none; }
    }
  </style>
</head>
<body>
  <div class="auth-left">
    <div class="auth-logo">
      <img src="/qcsim/Assets/MainWebsite/logo.png" alt="QCSim">
      <span>QCSim</span>
    </div>

    <h1>Forgot password?</h1>
    <p>No worries — enter the email associated with your QCSim account and we'll send you a link to reset your password.</p>

    <?php if ($error): ?>
    <div class="alert alert-error">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="alert alert-success">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" novalidate>
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <div class="input-icon-wrap">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/></svg>
          <input class="form-control" type="email" id="email" name="email" placeholder="Enter your email address" value="<?= htmlspecialchars($submittedEmail) ?>" required autocomplete="email">
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">Send Reset Link</button>
    </form>
    <?php endif; ?>

    <div class="back-row">
      Remember your password? <a href="/qcsim/login.php">Back to Sign In</a>
    </div>
  </div>
  <div class="auth-right"></div>
</body>
</html>
