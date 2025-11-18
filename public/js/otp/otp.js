document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.querySelector('input[name="otp"]');
    const form = document.getElementById('otp-form');

    // Handle OTP input - only allow numbers
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 6 digits
            if (this.value.length > 6) {
                this.value = this.value.substring(0, 6);
            }
        });

        // Auto-focus OTP input on page load
        otpInput.focus();
    }

    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const otp = otpInput ? otpInput.value : '';
            const newPassword = form.querySelector('input[name="new_password"]').value;
            const confirmPassword = form.querySelector('input[name="new_password_confirmation"]').value;

            if (otp.length !== 6) {
                e.preventDefault();
                alert('Please enter the complete 6-digit OTP.');
                if (otpInput) otpInput.focus();
                return;
            }

            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                form.querySelector('input[name="new_password"]').focus();
                return;
            }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match.');
                form.querySelector('input[name="new_password_confirmation"]').focus();
                return;
            }
        });
    }
});
