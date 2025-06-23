<?php
session_start(); // Starts the session
include 'config.php'; // Includes database configuration

if (!isset($_SESSION['user_id'])) { // Checks if user is logged in
    header("Location: mainpage.php"); // Redirects to login page if not logged in
    exit(); // Exits script
}

$user_id = $conn->real_escape_string($_SESSION['user_id']); // Gets user ID from session and sanitizes it
$result = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id'"); // Queries cart items for the user
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ReGenMarket - Cart</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .cart-container {
      padding: 30px;
      max-width: 1000px;
      margin: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 2px 4px 8px rgba(0,0,0,0.1);
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

    .actions form {
      display: inline;
    }

    .actions button {
      padding: 5px 10px;
      margin-left: 5px;
      background-color: #21b24a;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .actions button:hover {
      background-color: #1a8a36;
    }

    .empty-msg {
      text-align: center;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 2px 4px 8px rgba(0,0,0,0.1);
    }

    .proceed-btn {
      margin-top: 20px;
      background-color: #21b24a;
      color: white;
      font-size: 16px;
      font-weight: bold;
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(33,178,74,0.4);
    }

    .proceed-btn:hover {
      background-color: #1a8a36;
    }
  </style>
</head>
<body>

<nav>
  <div class="logo">ReGenMarket</div>
  <div class="nav-links">
    <a href="page1.php">HOME</a>
    <a href="page2.php">RECYCLE</a>
    <a href="page4.html">LOCATION</a>
    <a href="profile.php">ACCOUNT</a>
    <a href="cart.php">🛒 Cart</a>
  </div>
  <div class="auth-links">
    <a href="mainpage.php">Log Out</a>
  </div>
</nav>

<div class="cart-container">
  <h2>🛒 Your Cart</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['item_name']) ?></td>
          <td><?= intval($row['quantity']) ?></td>
          <td class="actions">
            <form method="post" action="update_cart.php" style="display:inline;">
              <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
              <button name="action" value="increase">+</button>
              <button name="action" value="decrease">-</button>
              <button name="action" value="remove">Remove</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <form action="checkout.php" method="post">
      <button type="submit" class="proceed-btn">Proceed</button>
    </form>
  <?php else: ?>
    <div class="empty-msg">
      <p>Your cart is currently empty.</p>
    </div>
  <?php endif; ?>
</div>

</body>
</html>