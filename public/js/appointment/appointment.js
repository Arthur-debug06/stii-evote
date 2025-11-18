document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appointment-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = submitBtn.querySelector('.submit-text');
    const loadingSpinner = submitBtn.querySelector('.loading-spinner');

    // Form validation
    const fullnameInput = document.getElementById('fullname');
    const emailInput = document.getElementById('email');
    const contactInput = document.getElementById('contact_number');
    const datetimeInput = document.getElementById('appointment_datetime');
    const reasonInput = document.getElementById('reason');

    // Set minimum datetime to tomorrow 9 AM
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(9, 0, 0, 0);
    datetimeInput.min = tomorrow.toISOString().slice(0, 16);

    // Set maximum time to 4 PM for today's appointments
    const maxTime = new Date();
    maxTime.setFullYear(maxTime.getFullYear() + 1);
    maxTime.setHours(16, 0, 0, 0);
    datetimeInput.max = maxTime.toISOString().slice(0, 16);

    // Contact number validation (Philippine format)
    contactInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Remove non-digits
        
        // Limit to 11 digits for Philippine numbers
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        // Format as Philippine number
        if (value.length >= 4) {
            if (value.startsWith('09')) {
                // Mobile format: 09XX-XXX-XXXX
                value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1-$2-$3');
            } else if (value.startsWith('02') || value.startsWith('03') || value.startsWith('04')) {
                // Landline format: 0X-XXXX-XXXX
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '$1-$2-$3');
            }
        }
        
        this.value = value;
    });

    // Email validation
    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.setCustomValidity('Please enter a valid email address');
        } else {
            this.setCustomValidity('');
        }
    });

    // Full name validation (letters and spaces only)
    fullnameInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
    });

    // Reason character limit
    reasonInput.addEventListener('input', function() {
        const maxLength = 500;
        if (this.value.length > maxLength) {
            this.value = this.value.substring(0, maxLength);
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
        
        // Basic client-side validation
        if (!validateForm()) {
            e.preventDefault();
            // Reset button state
            resetButtonState();
            return;
        }
        
        // Let form submit naturally
        // Don't prevent default if validation passes
    });

    // Datetime validation for clinic hours and weekdays
    datetimeInput.addEventListener('change', function() {
        const selectedDateTime = new Date(this.value);
        const hour = selectedDateTime.getHours();
        const dayOfWeek = selectedDateTime.getDay();
        
        // Check clinic hours (9 AM to 4 PM)
        if (hour < 9 || hour >= 16) {
            alert('Please select a time between 9:00 AM and 4:00 PM');
            this.focus();
            return;
        }
        
        // Check weekdays only (Sunday = 0)
        if (dayOfWeek === 0) {
            alert('Please select a weekday (Monday to Saturday)');
            this.focus();
            return;
        }
    });

    function validateForm() {
        let isValid = true;
        
        // Check all required fields
        const requiredFields = [fullnameInput, emailInput, contactInput, datetimeInput, reasonInput];
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.focus();
                isValid = false;
                return false;
            }
        });
        
        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            emailInput.focus();
            isValid = false;
        }
        
        // Validate contact number (should be 11 digits for Philippine numbers)
        const contactDigits = contactInput.value.replace(/\D/g, '');
        if (contactDigits.length < 10 || contactDigits.length > 11) {
            contactInput.focus();
            isValid = false;
        }
        
        // Validate appointment datetime
        const selectedDateTime = new Date(datetimeInput.value);
        const now = new Date();
        
        if (selectedDateTime <= now) {
            datetimeInput.focus();
            isValid = false;
        }
        
        // Check clinic hours
        const hour = selectedDateTime.getHours();
        if (hour < 9 || hour >= 16) {
            datetimeInput.focus();
            isValid = false;
        }
        
        // Check weekdays only
        const dayOfWeek = selectedDateTime.getDay();
        if (dayOfWeek === 0) {
            datetimeInput.focus();
            isValid = false;
        }
        
        return isValid;
    }

    function resetButtonState() {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    }

    // Auto-hide success/error messages after 5 seconds
    const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});
