<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Recycle Marketplace</title>
<style>
  /* Reset */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0; padding: 0;
    background: #fff;
    color: #000;
  }

  /* Updated Topbar (Navigation Bar) */
  nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px;
    background: #fff;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  }

  /* Logo styling */
  .logo {
    font-weight: 800;
    font-size: 24px;
    color: #21b24a;
    letter-spacing: 1.1px;
    cursor: pointer;
    user-select: none;
  }

  /* Navigation menu */
  .nav-links {
    display: flex;
    gap: 40px;
  }

  .nav-links a {
    font-weight: 600;
    font-size: 15px;
    color: #333;
    text-decoration: none;
    text-transform: uppercase;
    padding: 8px 0;
    position: relative;
    transition: color 0.3s ease;
  }

  /* Underline effect on hover */
  .nav-links a::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0%;
    height: 2px;
    background-color: #21b24a;
    transition: width 0.3s ease;
    border-radius: 2px;
  }

  .nav-links a:hover {
    color: #21b24a;
  }

  .nav-links a:hover::after {
    width: 100%;
  }

  /* Auth links container */
  .auth-links {
    display: flex;
    align-items: center;
    gap: 25px;
  }

  .auth-links a {
    font-weight: 600;
    font-size: 14px;
    color: #21b24a;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .auth-links a:hover {
    background-color: #21b24a;
    color: #fff;
  }

  .sign-up-btn {
    background-color: #21b24a;
    color: white;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 22px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(33,178,74,0.4);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .sign-up-btn:hover {
    background-color: #1a8a36;
    box-shadow: 0 6px 15px rgba(26,138,54,0.6);
  }

  /* Main Image Slider (single image for demo) */
  .main-slider {
    margin: 20px auto;
    max-width: 1000px;
    overflow: hidden;
    border-radius: 10px;
  }
  .main-slider img {
    width: 100%;
    display: block;
  }

  /* Icon grid */
  .icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 30px;
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
    justify-items: center;
  }
  .icon-circle {
    background: #2ecc71;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    filter: drop-shadow(0 0 10px #2ecc71);
    cursor: pointer;
    transition: transform 0.3s ease;
  }
  .icon-circle:hover {
    transform: scale(1.1);
  }
  .icon-circle img {
    width: 50px;
    height: 50px;
  }
  .icon-label {
    margin-top: 12px;
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 1px;
  }

  /* News section */
  .news-section {
    max-width: 1000px;
    margin: 50px auto 70px;
    background: #2ecc71;
    color: white;
    display: flex;
    gap: 20px;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
  }
  .news-text {
    flex: 1;
  }
  .news-text h2 {
    margin-top: 0;
    font-size: 24px;
  }
  .news-text p {
    font-size: 16px;
    line-height: 1.4;
  }
  .news-text button {
    background: white;
    color: #2ecc71;
    border: none;
    padding: 10px 20px;
    font-weight: 700;
    border-radius: 30px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s ease;
  }
  .news-text button:hover {
    background: #27ae60;
    color: white;
  }
  .news-image {
    flex: 1;
  }
  .news-image img {
    width: 100%;
    border-radius: 12px;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .news-section {
      flex-direction: column;
    }
    .news-image {
      order: -1;
    }
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

<section class="main-slider">
  <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1000&q=80" alt="Recycle Image" />
</section>

<section class="icon-grid">
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=tyvWCKuC3W8R&format=png&color=000000" alt="Car Battery" /></div>
    <div class="icon-label">Car Battery</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=97445&format=png&color=000000" alt="Glass" /></div>
    <div class="icon-label">Glass</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://cdn-icons-png.flaticon.com/512/1038/1038544.png" alt="Ink Cartridge" /></div>
    <div class="icon-label">Ink Cartridge</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=bJk0gjKmP8PA&format=png&color=000000" alt="Cooking Oil" /></div>
    <div class="icon-label">Cooking Oil</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=yuSXSbufxxiw&format=png&color=000000" alt="Plastic" /></div>
    <div class="icon-label">Plastic</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=KRXpHoxTdjNQ&format=png&color=000000" alt="Paper" /></div>
    <div class="icon-label">Paper</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=9sTlipzTruZ4&format=png&color=000000" alt="Electronic" /></div>
    <div class="icon-label">Electronic</div>
  </div>
  <div>
    <div class="icon-circle"><img src="https://img.icons8.com/?size=100&id=aTk3yd33wbL7&format=png&color=000000" alt="Newspaper" /></div>
    <div class="icon-label">Newspaper</div>
  </div>
</section>

<section class="news-section">
  <div class="news-text">
    <h2>What News?</h2>
    <p>
      KUALA LUMPUR, April 15 — When you see headlines warning that Malaysia will need to recycle 87,000 electric vehicles (EV) batteries by 2030, it’s easy to feel worried.
      The number cited in recent New Straits Times articles suggests a looming waste crisis from EV adoption. But is that really the case?
    </p>
    <a href="page3.html">
    <button>Contact us</button>
</a>

  </div>
  <div class="news-image">
    <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=600&q=80" alt="Recycle News" />
  </div>
</section>

</body>
</html>