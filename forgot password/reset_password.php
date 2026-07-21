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

if (isLoggedIn()) { header('Location: /qcsim/index.php'); exit; }

$error = $success = '';
$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$validToken = false;
$tokenEmail = '';

// Validate token
if ($token) {
    $stmt = $conn->prepare("SELECT email, expires_at, used FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        $error = 'This reset link is invalid. Please request a new one.';
    } elseif ($row['used']) {
        $error = 'This reset link has already been used. Please request a new one.';
    } elseif (strtotime($row['expires_at']) < time()) {
        $error = 'This reset link has expired. Please request a new one.';
    } else {
        $validToken = true;
        $tokenEmail = $row['email'];
    }
} else {
    $error = 'No reset token provided.';
}

// password update ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password  = $_POST['password']         ?? '';
    $confirmPw = $_POST['confirm_password'] ?? '';

    if (!$password || !$confirmPw) {
        $error = 'Please fill in both password fields.';
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password must be at least 8 characters with uppercase, lowercase, and a number.';
    } elseif ($password !== $confirmPw) {
        $error = 'Passwords do not match.';
    } else {
        // Hash + update user password
        $hash = hashPassword($password);
        $upd  = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $upd->bind_param("ss", $hash, $tokenEmail);

        if ($upd->execute()) {
            // Mark token as used
            $mark = $conn->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $mark->bind_param("s", $token);
            $mark->execute();

            // invalidate any other unused tokens for this email
            $other = $conn->prepare("UPDATE password_resets SET used = 1 WHERE email = ? AND used = 0");
            $other->bind_param("s", $tokenEmail);
            $other->execute();

            $success    = 'Your password has been reset successfully. You can now sign in with your new password.';
            $validToken = false;     
        } else {
            $error = 'Failed to update password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — QCSim</title>
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

    <h1>Reset password</h1>
    <p>Enter a new password for your QCSim account. Make sure it's strong and easy for you to remember.</p>

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

    <?php if ($validToken): ?>
    <form method="POST" novalidate>
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <div class="form-group">
        <label class="form-label" for="password">New password <span style="color:var(--danger)">*</span></label>
        <div class="input-icon-wrap">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input class="form-control" type="password" id="password" name="password" placeholder="Enter a new password" required autocomplete="new-password">
          <button type="button" class="eye-toggle" onclick="togglePw('password',this)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <div class="form-hint">Minimum of 8 characters, with upper and lowercase letter and a number</div>
      </div>

      <div class="form-group">
        <label class="form-label" for="confirm_password">Confirm new password <span style="color:var(--danger)">*</span></label>
        <div class="input-icon-wrap">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Re-type new password" required autocomplete="new-password">
          <button type="button" class="eye-toggle" onclick="togglePw('confirm_password',this)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">Reset Password</button>
    </form>
    <?php endif; ?>

    <div class="back-row">
      <?php if ($success): ?>
        <a href="/qcsim/login.php">Go to Sign In →</a>
      <?php else: ?>
        Need a new link? <a href="/qcsim/forgot_password.php">Request another</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="auth-right"></div>

  <script>
    function togglePw(id, btn) {
      const f = document.getElementById(id);
      f.type = f.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
