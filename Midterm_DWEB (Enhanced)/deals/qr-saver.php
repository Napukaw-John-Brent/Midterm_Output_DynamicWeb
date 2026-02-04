<?php
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

if (!empty($_POST['qr'])) {
    $qr_data = trim($_POST['qr']);
    $stmt = $conn->prepare("INSERT INTO qr_codes (user_id, qr_data) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $qr_data);
    $stmt->execute();
    $stmt->close();
    header("Location: qr-saver.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, qr_data FROM qr_codes WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>QR Saver</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="app-page">
  <div class="container">
    <header class="page-header">
      <h1>QR Saver</h1>
      <nav>
        <a href="../dashboard/index.php">Dashboard</a> |
        <a href="deals.php">Deals</a> |
        <a href="../auth/login.php">Logout</a>
      </nav>
    </header>

    <div class="card">
      <form method="POST">
        <input name="qr" type="text" placeholder="Paste QR data">
        <button type="submit">Save QR</button>
      </form>
    </div>

    <div class="card">
      <h3>Saved QRs</h3>
      <?php
      $hasQr = false;
      while ($qr = $result->fetch_assoc()):
        $hasQr = true;
      ?>
        <div class="qr-item"><?= htmlspecialchars($qr['qr_data']) ?></div>
      <?php endwhile; ?>
      <?php if (!$hasQr): ?>
        <p class="empty-state">No saved QR codes yet. Paste data above to save.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
