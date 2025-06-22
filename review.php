<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ReGenMarket Stylish Navbar with Contact</title>
<style>
  /* Reset and base */
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    color: #1f471c;
    line-height: 1.6;
  }

  nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px;
    background: #fff;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  }

  .logo {
    font-weight: 800;
    font-size: 24px;
    color: #21b24a;
    letter-spacing: 1.1px;
    cursor: pointer;
    user-select: none;
  }

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

  main {
    max-width: 960px;
    margin: 50px auto;
    padding: 0 20px;
  }

  .contact-header {
    background-color: #21b24a;
    border-radius: 8px;
    padding: 20px 30px;
    font-weight: 700;
    font-size: 24px;
    color: #1f471c;
    user-select: none;
    margin-bottom: 30px;
  }

  .contact-info {
    background-color: #a0f2a0;
    border-radius: 8px;
    border: 1px solid #78c974;
    padding: 30px 40px;
    font-weight: 700;
    font-size: 18px;
    color: #27451e;
  }

  .contact-info p {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 15px 0;
  }

  #starRating {
    font-size: 40px;
    color: #ccc;
    cursor: pointer;
  }

  #starRating .star {
    display: inline-block;
    transition: transform 0.2s ease, color 0.3s ease;
  }

  #starRating .star.active {
    color: #ffc107;
  }

  /* Add animation class */
  #starRating .star.animate {
    animation: pop 0.3s;
  }

  @keyframes pop {
    0% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.5);
    }
    100% {
      transform: scale(1);
    }
  }

  textarea {
    width: 100%;
    margin-top: 15px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  button.review-btn {
    margin-top: 20px;
    background: #21b24a;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  button.review-btn:hover {
    background-color: #1a8a36;
  }

  @media (max-width: 768px) {
    nav {
      padding: 15px 25px;
    }
    .nav-links {
      gap: 25px;
    }
    .auth-links {
      gap: 15px;
    }
    main {
      margin: 30px 15px;
    }
    .contact-header {
      font-size: 20px;
      padding: 15px 20px;
    }
    .contact-info {
      font-size: 16px;
      padding: 25px 20px;
    }
    #starRating {
      font-size: 36px;
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

<main>
  <div class="contact-header">Leave a Review</div>
  <div class="contact-info">
    <p>Rate your experience:</p>
    <div id="starRating">
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
    </div>

    <textarea id="reviewText" rows="4" placeholder="Write your review here..."></textarea>
    <button onclick="submitReview()" class="review-btn">Submit Review</button>
  </div>
</main>

<script>
  let stars = 0;
  const starElements = document.querySelectorAll("#starRating .star");

  starElements.forEach((star, index) => {
    star.addEventListener("click", () => {
      stars = index + 1;
      updateStars();
    });
  });

  function updateStars() {
    starElements.forEach((star, i) => {
      if (i < stars) {
        star.classList.add("active");
        star.classList.add("animate");

        // Remove animation class after 300ms so it can be reapplied on next click
        setTimeout(() => {
          star.classList.remove("animate");
        }, 300);

      } else {
        star.classList.remove("active");
      }
    });
  }

  function submitReview() {
  const reviewText = document.getElementById("reviewText").value.trim();

  if (stars === 0) {
    alert("Please select a rating.");
    return;
  }

  if (reviewText === "") {
    alert("Please write a review.");
    return;
  }

  fetch("submit_review.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `star=${encodeURIComponent(stars)}&comment=${encodeURIComponent(reviewText)}`
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
    stars = 0;
    updateStars();
    document.getElementById("reviewText").value = "";

    // Redirect after success
    window.location.href = "page1.php";
  })
  .catch(error => {
    console.error("Error submitting review:", error);
    alert("An error occurred. Please try again.");
  });
}

</script>

</body>
</html>