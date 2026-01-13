document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgot-password-form');
    const submitBtn = document.getElementById('send-otp-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const loadingSpinner = submitBtn.querySelector('.loading-spinner');

    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
        
        // Optional: You can add form validation here
        const email = form.querySelector('input[name="email"]').value;
        if (!email || !isValidEmail(email)) {
            e.preventDefault();
            resetButton();
            alert('Please enter a valid email address.');
            return;
        }
    });

    function resetButton() {
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Reset button state if there are errors (page reload)
    window.addEventListener('load', function() {
        resetButton();
    });
});
