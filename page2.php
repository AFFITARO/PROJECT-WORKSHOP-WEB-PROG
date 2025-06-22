<?php
session_start();
include 'config.php';

$recycle_accepted = mysqli_query($conn, "SELECT COUNT(*) AS total FROM history WHERE accepted = 1");
$donation_accepted = mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations WHERE accepted = 1");

$recycle_count = mysqli_fetch_assoc($recycle_accepted)['total'] ?? 0;
$donation_count = mysqli_fetch_assoc($donation_accepted)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ReGenMarket Stylish Navbar & Recycle Request</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9f9;
      color: #1f471c;
      line-height: 1.6;
    }
    nav {
      display: flex; justify-content: space-between; align-items: center;
      padding: 18px 60px; background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      position: sticky; top: 0; z-index: 1000;
    }
    .logo { font-weight: 900; font-size: 28px; color: #21b24a; text-transform: uppercase; }
    .nav-links { display: flex; gap: 44px; }
    .nav-links a {
      font-weight: 600; font-size: 16px; color: #333;
      text-decoration: none; text-transform: uppercase; padding: 10px 0;
      position: relative; transition: color 0.35s ease;
    }
    .nav-links a::after {
      content: ''; position: absolute; left: 0; bottom: -8px; width: 0%;
      height: 3px; background-color: #21b24a; border-radius: 3px;
      transition: width 0.35s ease;
    }
    .nav-links a:hover, .nav-links a:focus-visible { color: #21b24a; }
    .nav-links a:hover::after, .nav-links a:focus-visible::after { width: 100%; }
    .auth-links { display: flex; align-items: center; gap: 32px; }
    .auth-links a {
      font-weight: 600; font-size: 15px; color: #21b24a;
      text-decoration: none; padding: 8px 18px; border-radius: 6px;
    }
    .auth-links a:hover, .auth-links a:focus-visible {
      background-color: #21b24a; color: #fff;
    }
    .sign-up-btn {
      background-color: #21b24a; color: white; font-weight: 700;
      font-size: 15px; padding: 12px 26px; border: none;
      border-radius: 10px; cursor: pointer;
      box-shadow: 0 5px 14px rgba(33,178,74,0.45);
    }
    .sign-up-btn:hover, .sign-up-btn:focus-visible {
      background-color: #1a8a36; box-shadow: 0 7px 20px rgba(26,138,54,0.65);
    }
    main { max-width: 960px; margin: 48px auto 80px auto; padding: 0 24px; }
    .recycle-request-header {
      background-color: #21b24a; border-radius: 12px; padding: 22px 32px;
      display: flex; justify-content: space-between; align-items: center;
      font-weight: 800; font-size: 24px; color: #e9f5e9;
      margin-bottom: 20px;
    }
    .add-btn {
      background: rgba(255, 255, 255, 0.25); border: none; font-size: 32px;
      font-weight: 900; color: #e9f5e9; cursor: pointer;
      width: 44px; height: 44px; border-radius: 50%;
      display: flex; justify-content: center; align-items: center;
    }
    .add-btn:hover { background: rgba(255, 255, 255, 0.4); transform: scale(1.1); }
    .request-container {
      background: linear-gradient(135deg, #baf3b8, #9ae98e);
      border-radius: 14px; margin-top: 32px;
      min-height: 400px; padding: 36px 40px;
      display: flex; flex-direction: column; align-items: center;
    }
    .see-more {
      margin: 10px 6px 0 6px;
      background-color: #3a63c9; border: none; color: white;
      padding: 14px 28px; font-weight: 700; font-size: 16px;
      border-radius: 10px; cursor: pointer;
      box-shadow: 0 6px 20px rgba(58, 99, 201, 0.75);
      transition: background-color 0.35s, transform 0.2s;
    }
    .see-more:hover { background-color: #2a44a0; transform: translateY(-2px); }
    .button-container { text-align: center; margin-top: 15px; display: none; }

    .accepted-text {
      font-weight: bold;
      color: #155724;
      margin-top: 12px;
      background-color: #d4edda;
      padding: 10px 20px;
      border-radius: 6px;
      text-align: center;
    }
  </style>
</head>
<body>

<nav>
  <div class="logo" tabindex="0">ReGenMarket</div>
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

  <!-- Recycle Section -->
  <section class="recycle-request-header">
    <h1>Recycle Request</h1>
    <button class="add-btn" onclick="toggleButtons('recycle')">+</button>
  </section>
  <div class="button-container" id="recycle-buttons">
    <button class="see-more" onclick="selectLocation('recycle', 'Censei')">Censei</button>
    <button class="see-more" onclick="selectLocation('recycle', 'FTMK')">FTMK</button>
    <button class="see-more" onclick="selectLocation('recycle', 'Jebat')">Jebat</button>
  </div>

  <!-- Donation Section -->
  <section class="recycle-request-header">
    <h1>Donation</h1>
    <button class="add-btn" onclick="toggleButtons('donation')">+</button>
  </section>
  <div class="button-container" id="donation-buttons">
    <button class="see-more" onclick="selectLocation('donation', 'Censei')">Censei</button>
    <button class="see-more" onclick="selectLocation('donation', 'FTMK')">FTMK</button>
    <button class="see-more" onclick="selectLocation('donation', 'Jebat')">Jebat</button>
  </div>

  <!-- Placeholder Request Section -->
  <section class="request-container">
    
    
    <?php if ($recycle_count > 0): ?>
      <div class="accepted-text">✅ Accepted (Recycle): <?= $recycle_count ?> request(s)</div>
    <?php endif; ?>

    <?php if ($donation_count > 0): ?>
      <div class="accepted-text">✅ Accepted (Donation): <?= $donation_count ?> request(s)</div>
    <?php endif; ?>
  </section>

</main>

<script>
  function toggleButtons(type) {
    const container = document.getElementById(`${type}-buttons`);
    container.style.display = container.style.display === 'block' ? 'none' : 'block';
  }

  function selectLocation(type, location) {
    const pageMapRecycle = {
      Censei: "censei.php",
      FTMK: "ftmk.php",
      Jebat: "jebat.php"
    };

    const pageMapDonation = {
      Censei: "censeidonation.php",
      FTMK: "ftmkdonation.php",
      Jebat: "jebatdonation.php"
    };

    const selectedMap = type === "recycle" ? pageMapRecycle : pageMapDonation;
    const page = selectedMap[location];

    if (page) {
      window.location.href = page;
    }
  }
</script>

</body>
</html>
 