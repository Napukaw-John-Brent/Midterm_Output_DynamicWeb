<?php
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// Handle Save Budget
if (isset($_POST['budget']) && $_POST['budget'] !== '') {
    $total_budget = (float) $_POST['budget'];
    $month = date('Y-m');
    $stmt = $conn->prepare("INSERT INTO budgets (user_id, month, total_budget) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $month, $total_budget);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// Handle Add Expense
if (isset($_POST['amount']) && $_POST['amount'] !== '') {
    $amount = (float) $_POST['amount'];
    $category = isset($_POST['category']) ? trim($_POST['category']) : 'Other';
    $date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, category, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $user_id, $amount, $category, $date);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT total_budget FROM budgets WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
$total = $row ? (float) $row['total_budget'] : 0;

$stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
$spent = $row ? (float) $row['total'] : 0;

$remaining = $total - $spent;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Budget Tracker</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="app-page">
  <div class="container">
    <header class="page-header">
      <h1>Monthly Budget Tracker</h1>
      <nav>
        <a href="index.php">Dashboard</a> |
        <a href="../deals/deals.php">Deals</a> |
        <a href="../deals/qr-saver.php">QR Saver</a> |
        <a href="../auth/login.php">Logout</a>
      </nav>
    </header>

    <div class="card summary">
      <p><span class="summary-label">Total Budget</span><strong>₱<?= number_format($total, 2) ?></strong></p>
      <p><span class="summary-label">Spent</span><strong>₱<?= number_format($spent, 2) ?></strong></p>
      <p><span class="summary-label">Remaining</span><strong>₱<?= number_format($remaining, 2) ?></strong></p>
    </div>

    <div class="card">
      <h3>Set budget</h3>
      <form method="POST">
        <input name="budget" type="number" step="0.01" min="0" placeholder="Enter total budget" required>
        <button type="submit">Save Budget</button>
      </form>
    </div>

    <div class="card">
      <h3>Add expense</h3>
      <form method="POST">
        <div class="form-row">
          <input name="amount" type="number" step="0.01" min="0" placeholder="Amount" required>
          <input name="category" placeholder="Category (optional)">
          <button type="submit">Add Expense</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
