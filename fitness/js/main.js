const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

// Toggle the navigation menu and change the icon
menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");
  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

// Close the navigation menu when a link is clicked
navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

// ScrollReveal options
const scrollRevealOption = {
  origin: "bottom",
  distance: "50px",
  duration: 1000,
};

// Apply ScrollReveal animations
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
ScrollReveal().reveal(".membership__card", {
  origin: "bottom",
  distance: "50px",
  duration: 1000,
  interval: 200,
});
ScrollReveal().reveal(".service-card", {
  origin: "bottom",
  distance: "50px",
  duration: 1000,
  interval: 200,
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
if (membershipSection) { // Ensure the element exists
  observer.observe(membershipSection);
}
