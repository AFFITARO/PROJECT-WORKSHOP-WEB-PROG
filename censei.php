<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: mainpage.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_name'])) {
    $item_name = $conn->real_escape_string(trim($_POST['item_name']));
    if ($item_name === '') {
        die("Invalid item name");
    }

    $check = $conn->query("SELECT id FROM cart WHERE user_id = $user_id AND item_name = '$item_name'");
    if ($check === false) {
        die("MySQL error (check): " . $conn->error);
    }

    if ($check->num_rows > 0) {
        $update = $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND item_name = '$item_name'");
        if ($update === false) {
            die("MySQL error (update): " . $conn->error);
        }
    } else {
        $insert = $conn->query("INSERT INTO cart (user_id, item_name, quantity) VALUES ($user_id, '$item_name', 1)");
        if ($insert === false) {
            die("MySQL error (insert): " . $conn->error);
        }
    }
    header("Location: censei.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ReGenMarket - CENSEI UTEM</title>
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
    <h1>CENSEI UTEM</h1>
    <p class="operating-hours"><strong>Operating hours:</strong> 9 a.m - 5 p.m</p>
  </header>

  <section class="recycle-categories-section">
    <h2>Recycle Category</h2>
    <div class="categories-grid">
      <?php
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

      foreach ($categories as $category => $items) {
        echo "<div class='category-card' data-category-id='".htmlspecialchars($category)."'>
                <img src='".htmlspecialchars($icons[$category])."' alt='".htmlspecialchars($category)." icon' class='category-icon' />
                <span class='category-name'>" . strtoupper(htmlspecialchars($category)) . "</span>
              </div>";
      }
      ?>
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
    <h2>🛒 Your Cart</h2>
    <ul class="cart-items">
      <?php
      $result = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
      if ($result) {
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()):
      ?>
          <li class="cart-item">
            <span class="cart-item-name"><?= htmlspecialchars($row['item_name']) ?></span>
            <span class="cart-item-quantity">(x<?= intval($row['quantity']) ?>)</span>
            <!-- Buttons removed here -->
          </li>
      <?php
              endwhile;
          } else {
              echo "<li class='empty-cart-message'>Your cart is empty.</li>";
          }
      } else {
          echo "<li class='error-message'>Failed to fetch cart items: " . $conn->error . "</li>";
      }
      ?>
    </ul>
    <div class="cart-footer">
      <a href="cart.php" class="btn btn-proceed">Proceed to Checkout</a>
    </div>
  </section>
</div>

<script>
document.querySelectorAll('.category-card').forEach(card => {
  card.addEventListener('click', () => {
    const categoryId = card.dataset.categoryId;
    const itemList = document.getElementById(categoryId);

    // Close all other open item lists
    document.querySelectorAll('.item-list-section').forEach(section => {
      if (section.id !== categoryId) {
        section.style.display = 'none';
      }
    });

    // Toggle the clicked item list
    if (itemList) {
      itemList.style.display = itemList.style.display === 'block' ? 'none' : 'block';
    }
  });
});
</script>

</body>
</html>
