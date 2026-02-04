<?php
include "../config/db.php";

if (!empty($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        header("Location: ../dashboard/index.php");
        exit;
    }
    $loginError = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
  <div class="container">
    <div class="hero-brand">
      <div class="logo">
        <img src="../images/wallet-icon.svg" alt="" width="28" height="28">
      </div>
      <h1>Budget App</h1>
      <p>Sign in to manage your budget</p>
    </div>
    <div class="card">
      <form method="POST">
        <h2>Login</h2>
        <?php if (!empty($loginError)): ?><p class="error"><?= htmlspecialchars($loginError) ?></p><?php endif; ?>
        <input name="email" type="email" placeholder="Email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
      <p class="footer-links"><a href="register.php">Register</a></p>
    </div>
  </div>
</body>
</html>
