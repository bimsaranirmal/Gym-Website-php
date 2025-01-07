const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

const scrollRevealOption = {
  origin: "bottom",
  distance: "50px",
  duration: 1000,
};

ScrollReveal().reveal(".header__image img", {
  ...scrollRevealOption,
  origin: "right",
});
ScrollReveal().reveal(".header__content h1", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".header__content h2", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".header__content p", {
  ...scrollRevealOption,
  delay: 1500,
});
ScrollReveal().reveal(".header__btn", {
  ...scrollRevealOption,
  delay: 2000,
});

ScrollReveal().reveal(".about__image img", {
  ...scrollRevealOption,
  origin: "left",
});
ScrollReveal().reveal(".about__content .section__header", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".about__content p", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".about__btn", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".service__card", {
  duration: 1000,
  interval: 500,
});

ScrollReveal().reveal(".facility__content .section__header", {
  ...scrollRevealOption,
});
ScrollReveal().reveal(".facility__content p", {
  ...scrollRevealOption,
  delay: 500,
});

ScrollReveal().reveal(".mentor__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".banner__content h2", {
  ...scrollRevealOption,
});
ScrollReveal().reveal(".banner__content p", {
  ...scrollRevealOption,
  delay: 500,
});

// Function to reveal membership cards one by one
function revealMembershipCards(entries, observer) {
  entries.forEach((entry) => {
      if (entry.isIntersecting) {
          const cards = document.querySelectorAll('.membership__card');
          cards.forEach((card, index) => {
              setTimeout(() => {
                  card.style.opacity = '1'; // Reveal card
                  card.style.transform = 'translateY(0)'; // Move to original position
              }, index * 300); // Adjust timing (300ms) for each card
          });
          observer.unobserve(entry.target); // Stop observing after revealing
      }
  });
}

// Create an Intersection Observer
const observer = new IntersectionObserver(revealMembershipCards, {
  threshold: 0.01 // Trigger when 10% of the section is visible
});

// Target the membership section
const membershipSection = document.getElementById('membership');
observer.observe(membershipSection);

document.getElementById('menu-btn').addEventListener('click', function() {
  var navLinks = document.getElementById('nav-links');
  navLinks.classList.toggle('active');

  const links = navLinks.querySelectorAll('ul li'); // Select all links

  if (navLinks.classList.contains('active')) {
      links.forEach((link, index) => {
          // Set a timeout for each link to create a staggered effect
          setTimeout(() => {
              link.style.opacity = 1; // Make the link visible
          }, index * 100); // Delay based on index
      });
  } else {
      // Reset opacity for links when the menu is closed
      links.forEach(link => {
          link.style.opacity = 0; // Hide the link
      });
  }
});

// Ensure links are hidden initially
const links = document.querySelectorAll('.nav__links ul li');
links.forEach(link => {
  link.style.opacity = 0; // Ensure links are hidden initially
});

document.addEventListener("DOMContentLoaded", function () {
  const logoutForm = document.querySelector("form[action='trainerLogout.php']");

  if (logoutForm) {
      const logoutButton = logoutForm.querySelector(".btn");
      
      logoutButton.addEventListener("click", function (event) {
          // Prevent the form from submitting immediately
          event.preventDefault();

          // Display confirmation dialog
          const confirmation = confirm("Are you sure you want to logout?");
          if (confirmation) {
              // If user confirms, submit the form
              logoutForm.submit();
          }
      });
  }
});
