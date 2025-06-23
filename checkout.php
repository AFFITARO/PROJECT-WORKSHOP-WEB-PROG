<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: mainpage.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$result = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");

// Handle confirmation and move to history
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($result && $result->num_rows > 0) && (isset($_POST['confirm_checkout']) || isset($_POST['review_checkout']))) {
        while ($row = $result->fetch_assoc()) {
            $item = $conn->real_escape_string($row['item_name']);
            $qty = (int)$row['quantity'];
            $conn->query("INSERT INTO history (user_id, item_name, quantity) VALUES ($user_id, '$item', $qty)");
        }
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");

        if (isset($_POST['confirm_checkout'])) {
            header("Location: page2.php");
        } elseif (isset($_POST['review_checkout'])) {
            header("Location: review.php");
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ReGenMarket</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f5f7fa;
      color: #333;
    }
    h2 {
      color: #21b24a;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      max-width: 800px;
      border-collapse: collapse;
      margin-bottom: 20px;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #e0e0e0;
    }
    th {
      background-color: #21b24a;
      color: white;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .btn-container {
      display: flex;
      gap: 15px;
    }

    /* Styles for both buttons */
    button.back-link,
    button.confirm-btn {
      background-color: #21b24a;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      border: none;
      cursor: pointer;
      display: inline-block;
      font-family: Arial, sans-serif;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }
    button.back-link:hover,
    button.confirm-btn:hover {
      background-color: #1a8a36;
    }
  </style>
</head>
<body>

  <h2>🛒 Confirm Your Cart Items</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <form method="post">
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody>
          <?php $result->data_seek(0); while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['item_name']) ?></td>
              <td><?= intval($row['quantity']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <div class="btn-container">
        <button type="submit" name="review_checkout" class="back-link">📝 Review</button>
        <button type="submit" name="confirm_checkout" class="confirm-btn">✅ Confirm</button>
      </div>
    </form>
  <?php else: ?>
    <p>Your cart is empty.</p>
    <a href="censei.php" class="back-link">🔙 Back to Shop</a>
  <?php endif; ?>

</body>
</html>
