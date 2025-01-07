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
    
    // Function to display success message
    function displaySuccessMessage(message) {
        const messageContainer = document.createElement("div");
        messageContainer.classList.add("success-message"); // You can add a class for styling
        messageContainer.textContent = message;

        // Append message container to the body or a specific section
        document.body.appendChild(messageContainer);
        
        // Optionally, set a timeout to remove the message after a few seconds
        setTimeout(() => {
            messageContainer.remove();
        }, 5000); // Adjust duration as needed (e.g., 5000 ms = 5 seconds)
    }

    // Check for success message from PHP
    const successMsg = "<?php echo addslashes(htmlspecialchars($success_msg)); ?>"; // Use htmlspecialchars for safety
    if (successMsg) {
        displaySuccessMessage(successMsg);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const editBtns = document.querySelectorAll('.edit-btn');
    const modal = document.getElementById('editMemberModal');
    const closeModalBtn = document.querySelector('.close');
    
    // Function to show the modal with member data
    editBtns.forEach(button => {
        button.addEventListener('click', function () {
            const memberId = this.getAttribute('data-id'); // Get the member ID

            // Use AJAX to fetch member details
            fetch(`fetch_member_details.php?member_id=${memberId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the form fields with the member data
                    document.getElementById('edit_member_id').value = data.id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_email').value = data.email;
                    document.getElementById('edit_dob').value = data.dob;
                    document.getElementById('edit_gender').value = data.gender;

                    // Populate membership plan options
                    const planSelect = document.getElementById('edit_plan');
                    planSelect.innerHTML = ''; // Clear existing options
                    data.plans.forEach(plan => {
                        const option = document.createElement('option');
                        option.value = plan.id;
                        option.textContent = `${plan.plan_name} - Rs.${plan.price}`;
                        if (data.membership_plan === plan.id) option.selected = true;
                        planSelect.appendChild(option);
                    });

                    modal.style.display = 'block'; // Show the modal
                })
                .catch(error => {
                    console.error('Error fetching member details:', error);
                });
        });
    });

    // Close the modal when the close button is clicked
    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close the modal if the user clicks outside the modal content
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const deleteBtns = document.querySelectorAll('.delete-btn');

    deleteBtns.forEach(button => {
        button.addEventListener('click', function () {
            const memberId = this.getAttribute('data-id'); // Get the member ID from the data attribute

            if (confirm('Are you sure you want to delete this member?')) {
                // Make an AJAX request to delete the member
                fetch('delete_member.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        id: memberId // Send the member ID
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the member row from the table
                        document.getElementById(`member-${memberId}`).remove();
                        alert('Member deleted successfully');
                    } else {
                        alert('Error deleting member');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });
});



