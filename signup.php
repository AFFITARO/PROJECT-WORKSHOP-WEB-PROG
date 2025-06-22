<?php
include 'config.php'; // Includes database configuration

// Function to generate unique 8-digit ID
function generateUniqueId($conn) {
  do {
    $id = rand(10000000, 99999999); // Generates an 8-digit random number
    $check = $conn->query("SELECT id FROM users WHERE id = $id"); // Checks if ID already exists in the database
  } while ($check->num_rows > 0); // Continues looping until a unique ID is found
  return $id; // Returns the unique ID
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Checks if the request method is POST
  $fullname = $_POST['fullname']; // Gets full name from POST data
  $gmail = $_POST['email']; // Gets email from POST data
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashes the password

  // Check if user is admin
  $isAdmin = isset($_POST['is_admin']) && $_POST['is_admin'] === 'on';

  if ($isAdmin) {
    $id = 10000000; // fixed ID for admin
    // Optional: Check if admin ID already exists (to avoid duplicate)
    $checkAdmin = $conn->query("SELECT id FROM users WHERE id = $id");
    if ($checkAdmin->num_rows > 0) {
      echo "<script>alert('Admin account already exists.'); location.href='{$_SERVER['PHP_SELF']}';</script>";
      exit;
    }
  } else {
    $id = generateUniqueId($conn); // Generates a unique user ID
  }

  // Check if email already exists
  $checkSql = "SELECT * FROM users WHERE gmail='$gmail'"; // SQL query to check for existing email
  $checkResult = $conn->query($checkSql); // Executes the query

  if ($checkResult->num_rows > 0) { // If email already exists
    echo "<script>alert('Email already registered. Please use another email.'); location.href='{$_SERVER['PHP_SELF']}';</script>"; // Alerts user and redirects
  } else { // If email is unique
    $sql = "INSERT INTO users (id, fullname, gmail, password) VALUES ('$id', '$fullname', '$gmail', '$password')"; // SQL query to insert new user

    if ($conn->query($sql) === TRUE) { // Executes query and checks for success
      session_start(); // Starts the session
      $_SESSION['user_id'] = $id; // Sets the user ID in the session
      echo "<script>alert('Registration successful!'); location.href='mainpage.php';</script>"; // Alerts user and redirects to mainpage
    } else {
      echo "Error: " . $conn->error; // Outputs error message if query fails
    }
  }

  $conn->close(); // Closes database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>RegenMarket - Create Account</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="signup.css" />
</head>
<body>
  <div class="container">
    <div class="form-section">
      <img src="WhatsApp Image 2025-04-13 at 22.04.43_b172caee.jpg" alt="Logo" class="logo" />
      <h2>Create an account</h2>
      <form method="POST" action="">
        <input type="text" name="fullname" placeholder="Full name" required />
        <input type="email" name="email" placeholder="example.email@gmail.com" required />
        <input type="password" name="password" placeholder="Enter at least 8+ characters" required minlength="8" />

        <!-- Admin checkbox -->
        <label style="display: flex; align-items: center; margin: 10px 0;">
          <input type="checkbox" name="is_admin" />
          <span style="margin-left: 8px;">Register as Admin</span>
        </label>

        <button type="submit">Sign up</button>
      </form>

      <p>Already have an account? <a href="login.php">Log in</a></p>

      <p>Or sign in with</p>
      <div class="social-icons">
        <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" alt="Google" />
        <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" />
        <img src="https://cdn-icons-png.flaticon.com/512/831/831276.png" alt="Apple" />
      </div>
    </div>

    <div class="side-banner">
      <div class="recycle-icon">♻</div>
    </div>
  </div>
</body>
</html>
