<?php
session_start();
include 'config.php';

// Access control for admin only
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 10000000) {
    header("Location: mainpage.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete user except admin
    if (isset($_POST['delete_user_id'])) {
        $del_user_id = (int)$_POST['delete_user_id'];
        if ($del_user_id != 10000000) {
            $conn->query("DELETE FROM users WHERE id = $del_user_id");
        }
    }

    // Delete cart history item
    if (isset($_POST['delete_history_id'])) {
        $del_history_id = (int)$_POST['delete_history_id'];
        $conn->query("DELETE FROM history WHERE id = $del_history_id");
    }

    // Delete donation item
    if (isset($_POST['delete_donation_id'])) {
        $del_donation_id = (int)$_POST['delete_donation_id'];
        $conn->query("DELETE FROM donations WHERE id = $del_donation_id");
    }

    // Accept cart history item
    if (isset($_POST['accept_history_id'])) {
        $accept_history_id = (int)$_POST['accept_history_id'];
        $conn->query("UPDATE history SET accepted = 1 WHERE id = $accept_history_id");
    }

    // Accept donation item
    if (isset($_POST['accept_donation_id'])) {
        $accept_donation_id = (int)$_POST['accept_donation_id'];
        $conn->query("UPDATE donations SET accepted = 1 WHERE id = $accept_donation_id");
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
    .header { background: #4CAF50; color: white; padding: 20px; text-align: center; position: relative; }
    .header a { position: absolute; right: 20px; top: 20px; color: white; text-decoration: none; }
    .container { max-width: 1200px; margin: 40px auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h2 { margin-top: 40px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background: #4CAF50; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    form.inline { display: inline; margin: 0; padding: 0; }
    button { cursor: pointer; padding: 5px 10px; margin: 2px; border: none; border-radius: 4px; }
    button.delete { background-color: #f44336; color: white; }
    button.accept { background-color: #4CAF50; color: white; }
  </style>
</head>
<body>

<div class="header">
  <h1>Admin Dashboard</h1>
  <a href="mainpage.php">Logout</a>
</div>

<div class="container">

  <!-- Login History -->
  <h2>Login History</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>Username</th><th>Login Time</th><th>IP Address</th></tr>
    </thead>
    <tbody>
      <?php
      $res = mysqli_query($conn, "SELECT * FROM login_history ORDER BY login_time DESC");
      if (!$res || mysqli_num_rows($res) === 0) {
          echo "<tr><td colspan='4'>No login history found.</td></tr>";
      } else {
          while ($row = mysqli_fetch_assoc($res)) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['id']) . "</td>
                      <td>" . htmlspecialchars($row['username']) . "</td>
                      <td>" . htmlspecialchars($row['login_time']) . "</td>
                      <td>" . htmlspecialchars($row['ip_address']) . "</td>
                    </tr>";
          }
      }
      ?>
    </tbody>
  </table>

  <!-- User Details -->
  <h2>User Details (Non-Admins)</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>Full Name</th><th>Email</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php
      $res = mysqli_query($conn, "SELECT id, fullname, gmail FROM users WHERE id != 10000000 ORDER BY id DESC");
      if (!$res || mysqli_num_rows($res) === 0) {
          echo "<tr><td colspan='4'>No users found.</td></tr>";
      } else {
          while ($row = mysqli_fetch_assoc($res)) {
              ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['gmail']) ?></td>
                <td>
                  <form class="inline" method="POST" action="" onsubmit="return confirm('Delete this user?');">
                    <input type="hidden" name="delete_user_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete" title="Delete User">Delete</button>
                  </form>
                </td>
              </tr>
              <?php
          }
      }
      ?>
    </tbody>
  </table>

  <!-- Cart History -->
  <h2>Cart History</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>User Name</th><th>Item Name</th><th>Quantity</th><th>Created At</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT history.id, history.item_name, history.quantity, history.recycled_at AS created_at, history.accepted, users.fullname
              FROM history
              LEFT JOIN users ON history.user_id = users.id
              ORDER BY history.recycled_at DESC";
      $res = mysqli_query($conn, $sql);
      if (!$res || mysqli_num_rows($res) === 0) {
          echo "<tr><td colspan='7'>No cart history found.</td></tr>";
      } else {
          while ($row = mysqli_fetch_assoc($res)) {
              $username = $row['fullname'] ?? 'Unknown User';
              $accepted = (int)$row['accepted'];
              ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($username) ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= $accepted ? 'Accepted' : 'Pending' ?></td>
                <td>
                  <?php if (!$accepted): ?>
                    <form class="inline" method="POST" action="" onsubmit="return confirm('Accept this cart item?');" style="display:inline-block;">
                      <input type="hidden" name="accept_history_id" value="<?= $row['id'] ?>">
                      <button type="submit" class="accept" title="Accept Cart Item">Accept</button>
                    </form>
                  <?php endif; ?>
                  <form class="inline" method="POST" action="" onsubmit="return confirm('Delete this cart item?');" style="display:inline-block;">
                    <input type="hidden" name="delete_history_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete" title="Delete Cart Item">Delete</button>
                  </form>
                </td>
              </tr>
              <?php
          }
      }
      ?>
    </tbody>
  </table>

  <!-- Donation History -->
  <h2>Donation History</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>User Name</th><th>Item Name</th><th>Quantity</th><th>Donated At</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT donations.id, donations.item_name, donations.quantity, donations.donated_at, donations.accepted, users.fullname
              FROM donations
              LEFT JOIN users ON donations.user_id = users.id
              ORDER BY donations.donated_at DESC";
      $res = mysqli_query($conn, $sql);
      if (!$res || mysqli_num_rows($res) === 0) {
          echo "<tr><td colspan='7'>No donation history found.</td></tr>";
      } else {
          while ($row = mysqli_fetch_assoc($res)) {
              $username = $row['fullname'] ?? 'Unknown User';
              $accepted = (int)$row['accepted'];
              ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($username) ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['donated_at']) ?></td>
                <td><?= $accepted ? 'Accepted' : 'Pending' ?></td>
                <td>
                  <?php if (!$accepted): ?>
                    <form class="inline" method="POST" action="" onsubmit="return confirm('Accept this donation item?');" style="display:inline-block;">
                      <input type="hidden" name="accept_donation_id" value="<?= $row['id'] ?>">
                      <button type="submit" class="accept" title="Accept Donation Item">Accept</button>
                    </form>
                  <?php endif; ?>
                  <form class="inline" method="POST" action="" onsubmit="return confirm('Delete this donation item?');" style="display:inline-block;">
                    <input type="hidden" name="delete_donation_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete" title="Delete Donation Item">Delete</button>
                  </form>
                </td>
              </tr>
              <?php
          }
      }
      ?>
    </tbody>
  </table>

</div>

</body>
</html>
