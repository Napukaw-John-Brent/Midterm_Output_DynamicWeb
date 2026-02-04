<?php
include "../config/db.php";

$registerError = '';
if (!empty($_POST['name']) && !empty($_POST['email']) && isset($_POST['password'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: login.php");
        exit;
    }
    $registerError = $conn->errno === 1062 ? "Email already registered." : "Registration failed.";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
  <div class="container">
    <div class="hero-brand">
      <div class="logo">
        <img src="../images/wallet-icon.svg" alt="" width="28" height="28">
      </div>
      <h1>Budget App</h1>
      <p>Create your account</p>
    </div>
    <div class="card">
      <form method="POST">
        <h2>Register</h2>
        <?php if ($registerError !== ''): ?><p class="error"><?= htmlspecialchars($registerError) ?></p><?php endif; ?>
        <input name="name" type="text" placeholder="Name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
        <input name="email" type="email" placeholder="Email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
      </form>
      <p class="footer-links"><a href="login.php">Login</a></p>
    </div>
  </div>
</body>
</html>
