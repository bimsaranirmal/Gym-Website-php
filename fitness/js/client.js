ScrollReveal().reveal(".mentor__card", {
    ...scrollRevealOption,
    interval: 500,
  });

document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
  
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
  
    if (name && email && message) {
      alert('Your message has been sent successfully!');
    } else {
      alert('Please fill in all fields.');
    }
  });
  
  document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
  
    const fullName = document.getElementById('full_name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const membershipPlan = document.getElementById('membership_plan').value;
  
    if (fullName && email && phone && membershipPlan) {
      alert('You have successfully registered!');
    } else {
      alert('Please fill in all fields.');
    }
  });
  
