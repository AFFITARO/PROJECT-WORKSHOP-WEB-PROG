<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $conn->real_escape_string(trim($_POST['gmail']));
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE gmail='$gmail'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // Clear any previous session keys to avoid conflicts
            unset($_SESSION['user_id']);
            unset($_SESSION['admin_id']);

            // Set session key based on role (id 10000000 = admin)
            if ($row['id'] == 10000000) {
                $_SESSION['admin_id'] = $row['id'];
                echo "<script>alert('Admin login successful!'); location.href='admin_dashboard.php';</script>";
                exit();
            } else {
                $_SESSION['user_id'] = $row['id'];
                echo "<script>alert('Login successful!'); location.href='page1.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Incorrect password.'); location.href='{$_SERVER['PHP_SELF']}';</script>";
            exit();
        }
    } else {
        echo "<script>alert('No account found with that email.'); location.href='{$_SERVER['PHP_SELF']}';</script>";
        exit();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="main.css" />
    <title>Login - ReGenMarket</title>
  </head>
  <body>
    <div class="tajuk">
      <img
        src="WhatsApp Image 2025-04-13 at 22.04.44_5cc4ef84-Photoroom.png"
        class="logo"
        width="13%"
      />
      <div class="header-tengah">
        <h1>ReGenMarket</h1>
        <h3>Sustainable Marketplace For SecondHand & Upcycle Goods</h3>
      </div>
      <div class="spacer"></div>
    </div>

    <div class="login-container">
      <form method="POST" action="">
        <div class="user-icon"><ion-icon name="person-outline"></ion-icon></div>

        <input type="text" name="gmail" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />

        <div class="btn-container">
          <button type="submit" class="btn login">Login</button>
          <button
            type="button"
            class="btn sign-in"
            onclick="location.href='signup.php'"
          >
            Sign Up
          </button>
        </div>
      </form>
    </div>

    <!-- Icons Plugin -->
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
  </body>
</html>
