<?php
session_start();
include 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
  header("Location: mainpage.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($query);

if ($result && $result->num_rows === 1) {
  $user = $result->fetch_assoc();
} else {
  $user = [
    'fullname' => 'Unknown',
    'gmail' => 'Not found',
    'id' => '00000000'
  ];
}

$total_recycled = 0;
$result_recycle = $conn->query("SELECT SUM(quantity) AS total FROM history WHERE user_id = $user_id");
if ($result_recycle && $row = $result_recycle->fetch_assoc()) {
    $total_recycled = (int)$row['total'];
}

$total_donated = 0;
$result_donate = $conn->query("SELECT SUM(quantity) AS total FROM donations WHERE user_id = $user_id");
if ($result_donate && $row = $result_donate->fetch_assoc()) {
    $total_donated = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recycle Marketplace</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background-color: #f9f9f9; }
    nav {
      display: flex; justify-content: space-between; align-items: center;
      padding: 15px 50px; background: #fff;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }
    .logo { font-weight: 800; font-size: 24px; color: #21b24a; cursor: pointer; }
    .nav-links { display: flex; gap: 40px; }
    .nav-links a {
      font-weight: 600; font-size: 15px; color: #333;
      text-decoration: none; text-transform: uppercase; position: relative;
    }
    .nav-links a::after {
      content: ""; position: absolute; left: 0; bottom: -6px; width: 0%; height: 2px;
      background-color: #21b24a; transition: width 0.3s ease; border-radius: 2px;
    }
    .nav-links a:hover { color: #21b24a; }
    .nav-links a:hover::after { width: 100%; }
    .auth-links { display: flex; gap: 25px; }
    .auth-links a {
      font-weight: 600; font-size: 14px; color: #21b24a;
      text-decoration: none; padding: 6px 12px; border-radius: 6px;
    }
    .auth-links a:hover { background-color: #21b24a; color: #fff; }
    .profile-section, .history {
      max-width: 900px; margin: 40px auto; padding: 20px;
      background-color: #a0f2a0; border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .profile-header { display: flex; align-items: center; margin-bottom: 20px; }
    .profile-image {
      width: 80px; height: 80px; border-radius: 50%; overflow: hidden; margin-right: 20px;
    }
    .profile-image img { width: 100%; height: 100%; object-fit: cover; }
    .profile-details h2 { font-size: 20px; font-weight: 600; }
    .profile-details p { color: #333; font-size: 14px; }
    .account-menu ul { list-style: none; margin-top: 20px; }
    .account-menu ul li { margin: 10px 0; cursor: pointer; }
    .account-menu ul li a { text-decoration: none; color: #21b24a; font-size: 16px; }
    .account-menu ul li a:hover { text-decoration: underline; }
    table {
      width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff;
      border-radius: 10px; overflow: hidden;
    }
    th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
    th { background: #21b24a; color: white; }
    .history h2 { margin-bottom: 10px; }
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

  <main>
    <section class="profile-section">
      <div class="profile-header">
        <div class="profile-image">
          <img src="https://img.icons8.com/ios/452/user-male-circle.png" alt="User Icon" />
        </div>
        <div class="profile-details">
          <h2><?= htmlspecialchars($user['fullname']) ?></h2>
          <p>ID: <?= htmlspecialchars($user['id']) ?></p>
          <p>Email: <?= htmlspecialchars($user['gmail']) ?></p>
          <p>Total Item Recycled: <?= $total_recycled ?></p>
          <p>Total Item Donated: <?= $total_donated ?></p>
        </div>
      </div>
      <div class="account-menu">
        <ul>
          <li onclick="popup()">Edit Profile</li>
        </ul>
      </div>
    </section>

    <section class="history">
      <h2>Your Recycle History</h2>
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $hist = $conn->query("SELECT * FROM history WHERE user_id = $user_id AND accepted = 0 ORDER BY recycled_at DESC");
        if ($hist && $hist->num_rows > 0) {
          while ($row = $hist->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($row['item_name'])."</td><td>".$row['quantity']."</td><td>".$row['recycled_at']."</td></tr>";
          }
        } else {
          echo "<tr><td colspan='3' style='text-align:center;'>No recycled history yet.</td></tr>";
        }

        $accepted = $conn->query("SELECT * FROM history WHERE user_id = $user_id AND accepted = 1 ORDER BY recycled_at DESC");
        if ($accepted && $accepted->num_rows > 0) {
          echo "<tr><td colspan='3' style='text-align:center; font-weight:bold;'>Accepted Recycle Items</td></tr>";
          while ($row = $accepted->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($row['item_name'])."</td><td>".$row['quantity']."</td><td>".$row['recycled_at']."</td></tr>";
          }
        }
        ?>
        </tbody>
      </table>
    </section>

    <section class="history">
      <h2>Your Donation History</h2>
      <table>
        <thead>
          <tr style="background:#3a63c9; color:white;">
            <th>Item</th>
            <th>Quantity</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $donations = $conn->query("SELECT * FROM donations WHERE user_id = $user_id AND accepted = 0 ORDER BY donated_at DESC");
        if ($donations && $donations->num_rows > 0) {
          while ($row = $donations->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($row['item_name'])."</td><td>".$row['quantity']."</td><td>".$row['donated_at']."</td></tr>";
          }
        } else {
          echo "<tr><td colspan='3' style='text-align:center;'>No donation history yet.</td></tr>";
        }

        $accepted_donations = $conn->query("SELECT * FROM donations WHERE user_id = $user_id AND accepted = 1 ORDER BY donated_at DESC");
        if ($accepted_donations && $accepted_donations->num_rows > 0) {
          echo "<tr><td colspan='3' style='text-align:center; font-weight:bold;'>Accepted Donation Items</td></tr>";
          while ($row = $accepted_donations->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($row['item_name'])."</td><td>".$row['quantity']."</td><td>".$row['donated_at']."</td></tr>";
          }
        }
        ?>
        </tbody>
      </table>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function popup() {
      Swal.fire({
        title: "<h3>Edit Your Profile</h3>",
        html: `
          <input id="swal-input-name" class="swal2-input" placeholder="Name">
          <input id="swal-input-phone" class="swal2-input" placeholder="Phone No">
          <input id="swal-input-password" type="password" class="swal2-input" placeholder="Password">
        `,
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: `Edit`,
        cancelButtonText: `Cancel`,
        preConfirm: () => {
          const name = document.getElementById("swal-input-name").value;
          const phone = document.getElementById("swal-input-phone").value;
          const password = document.getElementById("swal-input-password").value;

          if (!name || !phone || !password) {
            Swal.showValidationMessage("Please fill in all fields");
            return false;
          }
          return { name, phone, password };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("Name:", result.value.name);
          console.log("Phone:", result.value.phone);
          console.log("Password:", result.value.password);
        }
      });
    }
  </script>
</body>
</html>
