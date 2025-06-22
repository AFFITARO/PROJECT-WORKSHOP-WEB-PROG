<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: mainpage.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$categories = [
    "Car Battery" => ["Lead-acid battery", "Old terminals", "Battery cables"],
    "Glass" => ["Glass bottles", "Broken glassware", "Jars"],
    "Ink Cartridge" => ["HP cartridges", "Canon ink tanks", "Empty toner"],
    "Cooking Oil" => ["Used vegetable oil", "Grease", "Palm oil"],
    "Paper" => ["A4 paper", "Receipts", "Boxes"],
    "Plastic" => ["Bottles", "Food containers", "Plastic bags"],
    "Electronic" => ["Chargers", "Old phones", "Wires"],
    "Newspaper" => ["Daily paper", "Old magazines", "Flyers"]
];

$icons = [
    "Car Battery" => "https://img.icons8.com/?size=100&id=tyvWCKuC3W8R&format=png",
    "Glass" => "https://img.icons8.com/?size=100&id=97445&format=png",
    "Ink Cartridge" => "https://cdn-icons-png.flaticon.com/512/1038/1038544.png",
    "Cooking Oil" => "https://img.icons8.com/?size=100&id=bJk0gjKmP8PA&format=png",
    "Paper" => "https://img.icons8.com/?size=100&id=yuSXSbufxxiw&format=png",
    "Plastic" => "https://img.icons8.com/?size=100&id=KRXpHoxTdjNQ&format=png",
    "Electronic" => "https://img.icons8.com/?size=100&id=9sTlipzTruZ4&format=png",
    "Newspaper" => "https://img.icons8.com/?size=100&id=aTk3yd33wbL7&format=png"
];

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_name'])) {
    $item_name = $conn->real_escape_string(trim($_POST['item_name']));
    if ($item_name !== '') {
        $check = $conn->query("SELECT id FROM donation_cart WHERE user_id = $user_id AND item_name = '$item_name'");
        if ($check && $check->num_rows > 0) {
            $conn->query("UPDATE donation_cart SET quantity = quantity + 1 WHERE user_id = $user_id AND item_name = '$item_name'");
        } else {
            $conn->query("INSERT INTO donation_cart (user_id, item_name, quantity) VALUES ($user_id, '$item_name', 1)");
        }
    }
    header("Location: censeidonation.php");
    exit();
}

// Fetch Cart Items
$cart_items = [];
$result = $conn->query("SELECT * FROM donation_cart WHERE user_id = $user_id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ReGenMarket - Donation</title>
  <link rel="stylesheet" href="location.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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

<div class="container censei-page">
  <header class="page-header">
    <h1>FTMK DONATION CENTER</h1>
    <p class="operating-hours"><strong>Operating hours:</strong> 9 a.m - 5 p.m</p>
  </header>

  <section class="recycle-categories-section">
    <h2>Donation Categories</h2>
    <div class="categories-grid">
      <?php foreach ($categories as $category => $items): ?>
        <div class="category-card" data-category-id="<?= htmlspecialchars($category) ?>">
          <img src="<?= htmlspecialchars($icons[$category]) ?>" alt="<?= htmlspecialchars($category) ?> icon" class="category-icon" />
          <span class="category-name"><?= strtoupper(htmlspecialchars($category)) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php foreach ($categories as $category => $items): ?>
    <div class="item-list-section" id="<?= htmlspecialchars($category) ?>" style="display:none;">
      <h3>Items in <?= htmlspecialchars($category) ?></h3>
      <ul class="item-list">
        <?php foreach ($items as $item): ?>
          <li class="item-card">
            <span class="item-name"><?= htmlspecialchars($item) ?></span>
            <form method="post" class="add-to-cart-form">
              <input type="hidden" name="item_name" value="<?= htmlspecialchars($item) ?>">
              <button type="submit" class="btn btn-add">Add to Cart</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>

  <section class="cart-section" id="cartBox">
    <h2>🛒 Your Donation Cart</h2>
    <ul class="cart-items">
      <?php if (empty($cart_items)): ?>
        <li class="empty-cart-message">Your donation cart is empty.</li>
      <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
          <li class="cart-item">
            <span class="cart-item-name"><?= htmlspecialchars($item['item_name']) ?></span>
            <span class="cart-item-quantity">(x<?= intval($item['quantity']) ?>)</span>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>

    <?php if (!empty($cart_items)): ?>
      <div class="cart-footer">
        <form action="donation_cart.php" method="post">
          <button type="submit" class="btn btn-proceed">Proceed to Checkout</button>
        </form>
      </div>
    <?php endif; ?>
  </section>
</div>

<script>
document.querySelectorAll('.category-card').forEach(card => {
  card.addEventListener('click', () => {
    const categoryId = card.dataset.categoryId;
    const itemList = document.getElementById(categoryId);
    document.querySelectorAll('.item-list-section').forEach(section => {
      section.style.display = 'none';
    });
    if (itemList) {
      itemList.style.display = 'block';
    }
  });
});
</script>

</body>
</html>