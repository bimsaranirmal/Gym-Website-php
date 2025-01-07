<?php
session_start(); 

include '../fitness/components/connect.php';

// Check if there is a success message in the session
if (isset($_SESSION['success_msg'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const successMessage = document.getElementById("success-message");
            successMessage.innerHTML = "' . $_SESSION['success_msg'] . '"; // Display the success message
            successMessage.style.display = "block"; // Show the message
            successMessage.style.backgroundColor = "#d4edda"; // Success message background
            successMessage.style.color = "#155724"; // Success message text color
            successMessage.style.padding = "10px"; // Add some padding
            successMessage.style.marginBottom = "20px"; // Add some margin below

             setTimeout(function() {
                successMessage.style.display = "none"; // Hide the message
            }, 5000); // 5000 milliseconds = 5 seconds
        });
    </script>';

    // Clear the message after displaying it
    unset($_SESSION['success_msg']);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"rel="stylesheet"/>
    
    <title>FitnessZone</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../fitness/css/stylesss.css" />

    <style>
      /* Style for the search bar */     
      .search-container {
        margin-top:100px;
        display: flex;
        justify-content: center;

      }
      .search-input {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
        width: 300px;
        margin-right: 10px;
      }
      .search-btn {
        padding: 10px 20px;
        border: none;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border-radius: 5px;
      }
      .search-btn:hover {
        background-color: #0056b3;
      }
    </style>
  </head>
  <body>
    <header>
      <nav>
        <div class="nav__header">
          <div class="nav__logo">
            <a href="#">
              <img src="assets/logo-white.png" alt="logo" class="logo-white" />
              <img src="assets/head1.png" alt="logo" class="logo-dark" />
              
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#service">Services</a></li>
          <li><a href="#session">Sessions</a></li>
          <li><a href="#trainer">Trainers</a></li>
          <li><a href="#class">Classes</a></li>
          <li><a href="#membership">Membership</a></li>
          <li><a href="#blog">Blog</a></li>
          <li><a href="#trainers">Trainer-Login</a></li>
          <li><a href="#contact">contact</a></li>
          <li><a href="#">Login</a></li>
        </ul>
        <div class="nav__btns">
          <button class="btn_admin"><a href="allLogin.html" class="admin_a">Login</a></button>
        </div>
        
      </nav>
      <!-- Search bar to search for section by ID -->
      <div class="search-container">
        <input type="text" id="search-section" class="search-input" placeholder="Enter section ID (e.g., #about)" />
        <button class="search-btn" onclick="searchSection()">Search</button>
      </div>
      
      <div class="section__container header__container" id="home">
      <div id="success-message" style="display: none;"></div>
        <div class="header__content">
        
          <h1>DON'T STOP TILL YOUR SUCCESS!</h1>
          <h2>GET FIT TO HAPPY</h2>
          <div class="para">
          <p>
            Unlock your full potential with our expert training and
            state-of-the-art facilities. Every step you take brings you closer
            to a healthier, happier you. Let's make fitness a lifestyle!
          </p>
         </div>
        
          <div class="header__btn">
            <button class="btn_home"><a href="../fitness/register.php" class="home_a">Sign-Up</a></button>
            <button class="btn_home"><a href="../fitness/login.php" class="home_a">Sign-In</a></button>
          </div>
          
        </div>
      </div>
    </header>

    <div class="about" id="about">
      <div class="section__container about__container">
        <div class="about__image">
          <img src="assets/about.png" alt="about" />
        </div>
        <div class="about__content">
          <h2 class="section__header">Ready To Make A Change?</h2>
          <p>
            Taking the first step towards a healthier, stronger you can be the
            most challenging part of the journey, but it's also the most
            rewarding. Whether you're a beginner or a seasoned athlete, our
            personalized training programs are designed to help you reach your
            goals faster and more efficiently.
          </p>
          <p>
            With our motivating trainers, energizing classes, and
            state-of-the-art equipment, you'll have everything you need to stay
            committed and see real results.
          </p>
          <div class="about__btn">
          <button class="btn_home"><a href="../fitness/login.php" class="home_a">Login</a></button>
          </div>
        </div>
      </div>
    </div>

    <section class="service" id="service">
      <div class="section__container service__container">
        <h2 class="section__header">Services We Provide</h2>
        <div class="service__grid">
          <div class="service__card">
            <span>01</span>
            <h4>Fitness Training</h4>
            <p>
              Our fitness training programs are tailored to help you build
              strength, improve endurance, and achieve your personal fitness
              goals.
            </p>
          </div>
          <div class="service__card">
            <span>02</span>
            <h4>Yoga</h4>
            <p>
              Perfect for all levels, our sessions focus on improving
              flexibility, balance, and mental clarity while helping you manage
              stress.
            </p>
          </div>
          <div class="service__card">
            <span>03</span>
            <h4>Gymnastics</h4>
            <p>
              Our gymnastics classes are designed to boost coordination,
              flexibility, and core strength through a series of fun and
              challenging exercises.
            </p>
          </div>
          <div class="service__card">
            <span>04</span>
            <h4>Karate</h4>
            <p>
              Suitable for all ages and skill levels, our martial arts program
              emphasizes technique, respect, and personal growth while building
              confidence.
            </p>
            
          </div>
          <div class="service__card">
            <span>05</span>
            <h4>Cardio</h4>
            <p>
            Cardio, short for cardiovascular exercise, focuses on increasing heart
            rate and improving endurance. These classes often include high-energy,
            rhythmic movements designed to boost calorie burn, enhance stamina, and
            improve heart health.
            </p>
            <a href="classes.html" class="btn-link has-before">Read More</a>
          </div>
          <div class="service__image">
            <img src="assets/gym-6894893_1920.jpg" alt="service" />
          </div>
          
        </div>
      </div>
    </section>
    
    <!-- session -->
    <section class="session" id="session">
      <div class="container">
        <h2 class="h2 session-title text-center">Our Services</h2>
        <div class="session-list">
          <!-- Personalized Training -->
          <div class="session-card">
            <img src="assets/pt.jpeg" alt="Personalized Training" class="session-img">
            <h3>Personalized Training Sessions</h3>
            <p>Tailored one-on-one fitness programs designed to help you achieve your personal goals, led by certified trainers.</p>
          </div>

          <!-- Group Classes -->
          <div class="session-card">
            <img src="assets/gt.jpeg" alt="Group Classes" class="session-img">
            <h3>Group Classes</h3>
            <p>Join our dynamic group sessions that cater to all fitness levels and build a strong sense of community.</p>
          </div>

          <!-- Nutrition Counseling -->
          <div class="session-card">
            <img src="assets/mt.jpeg" alt="Nutrition Counseling" class="session-img">
            <h3>Nutrition Counseling</h3>
            <p>Receive customized meal plans and nutritional advice from certified experts to complement your training.</p>
          </div>
          
        </div>
        <a href="session.html" class="link-rm">Read More</a>
      </div>
    </section>

    <!-- classes -->
    <section class="popular" id="class">
        <div class="section__container popular__container">
            <h2 class="section__header">What Do You Want To Join Today?</h2>
            <div class="popular__grid">
                <?php
                // Query to fetch non-expired sessions from the database
                $sql = "SELECT session_name, session_type, start_time, trainer_name 
                        FROM sessions 
                        WHERE start_time > NOW()"; // Fetch only sessions in the future

                $result = $conn->query($sql);

                // Display session details dynamically
                if ($result && $result->rowCount() > 0) {
                    // Loop through each row of the results
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<a href='login.php'>";
                        echo "<div class='popular__card'>";
                        echo "<div>";
                        echo "<h3>" . htmlspecialchars($row["session_name"]) . "</h3>"; // Display the session name
                        echo "<h4>" . htmlspecialchars($row["session_type"]) . "</h4>";
                        echo "<p>Start time: " . htmlspecialchars($row["start_time"]) . "</p>";
                        echo "<p>Trainer: " . htmlspecialchars($row["trainer_name"]) . "</p>";
                        echo "</div>";
                        echo "<span><i class='ri-arrow-right-fill'></i></span>";
                        echo "</div>";
                        echo "</a>";  // Close the <a> tag here
                    }
                } else {
                    echo "No sessions available.";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- facilities-->
    <section class="facility__container">
      <div class="facility__image">
        <img src="assets/facility.jpg" alt="facility" />
      </div>
      <div class="facility__content">
        <h2 class="section__header">It's About Who You Can Become</h2>
        <p>
          At our gym, we believe that fitness is more than just physical—it's
          about transforming your mindset, pushing your limits, and realizing
          your full potential. Every workout is a step toward becoming the
          strongest, healthiest, and most confident version of yourself.
        </p>
        <p>
          It's not about quick fixes or temporary results; it's about adopting a
          lifestyle that fuels your passion for self-improvement. With the right
          mindset and the right support, you can overcome obstacles, break
          barriers, and achieve goals you never thought possible.
        </p>
        <p>
          Who you become is entirely up to you, but we believe in your
          potential. With the right training, dedication, and focus, you can
          turn your goals into reality.
        </p>
        <a href="facilities.html" class="btn-link has-before">Read More</a>
      </div>
    </section>

    <!-- trainer -->
    <section class="trainer" id="trainer">
      <div class="section__container mentor__container">
        <h2 class="section__header">Having Your Own Coach And Mentor</h2>
        <div class="mentor__grid">
          <?php
          // Step 1: Query to fetch trainers from the database
          $sql = "SELECT name, image, specializations FROM trainers";
          $result = $conn->query($sql);

          // Step 3: Display trainer details dynamically
          if ($result && $result->rowCount() > 0) { // Use rowCount() instead of num_rows
              // Loop through each row of the results
              while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                  echo "<div class='mentor__card'>";
                  echo "<img src='../uploaded_trainer/" . htmlspecialchars($row["image"]) . "' alt='mentor' class='mentor__image' />";
                  echo "<h4>" . htmlspecialchars($row["name"]) . "</h4>";
                  echo "<p>" . htmlspecialchars($row["specializations"]) . "</p>";
                  echo "</div>";
              }
          } else {
              echo "No trainers available.";
          }
          ?>
        </div>
      </div>
    </section>
    
    <!-- membership -->
    <section class="membership" id="membership">
      <div class="section__container membership__container">
        <h2 class="section__header">MEMBERSHIP</h2>
        <div class="membership__grid">
          <?php
          // Step 2: Query to fetch membership plans from the database
          $sqlMembership = "SELECT plan_name, price, description FROM memberships";
          $resultMembership = $conn->query($sqlMembership);

          // Step 3: Check if the membership query returns results using rowCount()
          if ($resultMembership && $resultMembership->rowCount() > 0) {
              // Loop through each row of the results
              while ($row = $resultMembership->fetch(PDO::FETCH_ASSOC)) {
                  echo "<a href='register.php'>";
                  echo "<div class='membership__card'>";
                  echo "<h4>" . htmlspecialchars($row["plan_name"]) . "</h4>";

                  // Split the description into an array of items using a comma
                  $features = explode(',', $row['description']); // Change delimiter based on how features are stored in DB

                  echo "<ul>";
                  foreach ($features as $feature) {
                      if (!empty(trim($feature))) { // Check if the feature is not empty
                          echo "<li><span><i class='ri-check-line'></i></span> " . htmlspecialchars(trim($feature)) . "</li>";
                      }
                  }
                  echo "</ul>";

                  echo "<h3>Rs:" . htmlspecialchars($row["price"]) . "<span>/month</span></h3>";
                  echo "</div>";
                  echo "</a>"; 
              }
          } else {
              echo "<p>No membership plans available.</p>";
          }
          ?>
        </div>
      </div>
    </section>
  
    <!-- blog -->
    <section class="section blog" id="blog" aria-label="blog">
      <div class="container">

        <p class="section-subtitle">Our News</p>

        <h2 class="h2 section-title text-center">Latest Blog Feed</h2>

        <ul class="blog-list has-scrollbar">

          <li class="scrollbar-item">
            <div class="blog-card">

              <div class="card-banner img-holder" style="--width: 440; --height: 270;">
                <img src="./assets/home.jpg" width="440" height="270" loading="lazy"
                  alt="Going to the gym for the first time" class="img-cover">

                <time class="card-meta" datetime="2022-07-07">7 July 2022</time>
              </div>

              <div class="card-content">

                <h3 class="h3">
                  <a href="blog1.html" class="card-title">Effective Workout Routines for Every Fitness Level</a>
                </h3>

                <p class="card-text">
                  Discover workouts tailored for all levels, from beginner to advanced. Learn how 
                  to create a balanced workout plan that includes strength training, cardio, and flexibility exercises.
                </p>
              </div>

            </div>
          </li>

          <li class="scrollbar-item">
            <div class="blog-card">

              <div class="card-banner img-holder" style="--width: 440; --height: 270;">
                <img src="../fitness/assets/mt.jpeg" width="440" height="270" loading="lazy"
                  alt="Parturient accumsan cacus pulvinar magna" class="img-cover">

                <time class="card-meta" datetime="2022-07-07">27 May 2024</time>
              </div>

              <div class="card-content">

                <h3 class="h3">
                  <a href="blog2.html" class="card-title">5 Delicious and Healthy Recipes for Fitness Enthusiasts</a>
                </h3>

                <p class="card-text">
                   Fuel your workouts with these easy-to-make, nutrient-rich recipes. From high-protein meals
                   to low-carb snacks, we’ve got you covered with meal ideas that keep you energized.
                </p>
              </div>

            </div>
          </li>

          <li class="scrollbar-item">
            <div class="blog-card">

              <div class="card-banner img-holder" style="--width: 440; --height: 270;">
                <img src="./assets/mi.jpeg" width="440" height="270" loading="lazy"
                  alt="Parturient accumsan cacus pulvinar magna" class="img-cover">

                <time class="card-meta" datetime="2022-07-07">17 July 2023</time>
              </div>

              <div class="card-content">

                <h3 class="h3">
                  <a href="blog3.html" class="card-title">7-Day Clean Eating Meal Plan for Weight Loss</a>
                </h3>

                <p class="card-text">
                Follow this 7-day meal plan designed to help you lose weight while keeping
                your energy levels high. Each meal is carefully balanced with macronutrients 
                to fuel your body.
                </p>
              </div>

            </div>
          </li>

          <li class="scrollbar-item">
            <div class="blog-card">

              <div class="card-banner img-holder" style="--width: 440; --height: 270;">
                <img src="./assets/blog-4.jpg" width="440" height="270" loading="lazy"
                  alt="Risus purus namien parturient accumsan cacus" class="img-cover">

                <time class="card-meta" datetime="2022-07-07">05 August 2021</time>
              </div>

              <div class="card-content">

                <h3 class="h3">
                  <a href="blog4.html" class="card-title">How John Lost 30 Pounds in 6 Months: A Success Story</a>
                </h3>

                <p class="card-text">
                Read how John transformed his body and mind through dedication and consistency. 
                Learn the strategies he used to shed pounds and build muscle over six months.
                </p>
              </div>

            </div>
          </li>
          <li class="scrollbar-item">
            <div class="blog-card">

              <div class="card-banner img-holder" style="--width: 440; --height: 270;">
                <img src="./assets/blog-4.jpg" width="440" height="270" loading="lazy"
                  alt="Risus purus namien parturient accumsan cacus" class="img-cover">

                <time class="card-meta" datetime="2022-07-07">05 August 2021</time>
              </div>

              <div class="card-content">

                <h3 class="h3">
                  <a href="blog5.html" class="card-title">5 Self-Care Tips to Support Your Mental Health</a>
                </h3>

                <p class="card-text">
                Fitness is just one part of the equation. Here are five essential self-care habits
                 that complement your physical efforts and support your mental health for a balanced
                  lifestyle.</p>
              </div>

            </div>
          </li>
        </ul>

      </div>
    </section>

    <!-- trainers -->
    <section class="banner" id="trainers">
      <div class="banner__content">
        <h2>THE BEST TRAINERS OUT THERE</h2>
        <p>For Registered Trainers <a href="../fitness/trainerLogin.php">Login Here !</a></p>
      </div>
      <div class="banner__image">
        <img src="assets/session-1.jpg" alt="banner" />
      </div>
    </section>
    
    <!-- contact -->
    <section class="contact" id="contact">
      <div class="section__container contact__container">
        <h2 class="section__header">Contact Us</h2>
        <form id="contactForm" action="submit_contact.php" method="POST" onsubmit="return validateForm()">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required />
          </div>
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required />
          </div>
          <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message" required></textarea>
          </div>
          <button type="submit" class="btn btn-submit">Send Message</button>
          
        </form>
      </div>
    </section>

    <!-- footer -->
    <section class="footer" id="footer">
      <div class="section__container footer__container">
        <div class="footer__col">
          <a href="#" class="footer__logo">
            <img src="assets/head1.png" alt="logo" />
          </a>
          <ul class="footer__links">
            <li>
              <a href="#">
                <span><i class="ri-phone-line"></i></span> +94 0774582459
              </a>
            </li>
            <li>
              <a href="#">
                <span><i class="ri-map-pin-line"></i></span> Kurunegala, Sri Lanka.
              </a>
            </li>
            <li>
              <a href="#">
                <span><i class="ri-mail-line"></i></span> info@fitnesszone
              </a>
            </li>
          </ul>
          <div class="social-links">
            <i class="bx bxl-facebook"></i>
            <i class="bx bxl-instagram"></i>
            <i class="bx bxl-linkedin"></i>
            <i class="bx bxl-twitter"></i>
            <i class="bx bxl-pinterest"></i>
          </div>
        </div>
        <div class="footer__col">
          <h4>Quick Links</h4>
          <ul class="footer__links">
             <li><a href="#home">Home</a></li>
             <li><a href="#about">About</a></li>
             <li><a href="#service">Services</a></li>
             <li><a href="#session">Sessions</a></li>
             <li><a href="#trainer">Trainers</a></li>
             <li><a href="#class">Classes</a></li>
             <li><a href="#membership">Membership</a></li>
             <li><a href="#contact">Blog</a></li>
             <li><a href="#footer">contact</a></li>
             <li><a href="#">Admin Login</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Gym Hours</h4>
          <ul class="footer__links">
            <li>Monday 5am - 10pm</li>
            <li>Tuseday 5am - 10pm</li>
            <li>Wednesday 5am - 10pm</li>
            <li>Thursday 5am - 10pm</li>
            <li>Friday 5am - 10pm</li>
            <li>Saturday 5am - 10pm</li>
            <li>Sunday 5am - 1pm</li>
          </ul>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2024 Web Design Mastery. All rights reserved.
      </div>
    </section>
     <!-- JavaScript for searching and scrolling to the section -->
     <script>
      function searchSection() {
        const sectionId = document.getElementById("search-section").value;
        if (sectionId) {
          const sectionElement = document.querySelector(sectionId);
          if (sectionElement) {
            sectionElement.scrollIntoView({ behavior: "smooth" });
          } else {
            alert("Section not found! Please check the ID.");
          }
        } else {
          alert("Please enter a section ID.");
        }
      }
    </script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/main.js"></script>
    <?php include '../fitness/components/alert.php'; ?>
  </body>
</html>
