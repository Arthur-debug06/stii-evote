document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    const registerButton = document.querySelector('#register-button');
    
    // Auto-calculate age from date of birth
    const dateOfBirthInput = document.querySelector('input[name="date_of_birth"]');
    const ageInput = document.querySelector('input[name="age"]');
    
    if (dateOfBirthInput && ageInput) {
        dateOfBirthInput.addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age >= 0 && age <= 150) {
                ageInput.value = age;
                // Immediately validate age and mark border
                if (age >= 18) {
                    ageInput.classList.remove('border-red-500');
                    ageInput.classList.add('border-green-500');
                    const ah = document.getElementById('age-hint'); if (ah) { ah.textContent = 'Age OK'; ah.style.color = '#16a34a'; }
                } else {
                    ageInput.classList.remove('border-green-500');
                    ageInput.classList.add('border-red-500');
                    const ah = document.getElementById('age-hint'); if (ah) { ah.textContent = 'Must be 18 or older'; ah.style.color = '#ef4444'; }
                }
            }
        });
    }
    
    // Password confirmation validation
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
    
    function validatePasswordMatch() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }
    
    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    }

    // Password strength validation: min 8 chars, at least one uppercase and one lowercase
    function validatePasswordStrength() {
        if (!passwordInput) return true;
        const value = passwordInput.value || '';
        const minLen = value.length >= 8;
        const hasUpper = /[A-Z]/.test(value);
        const hasLower = /[a-z]/.test(value);

        const isValid = minLen && hasUpper && hasLower;

    // Toggle styles: green when valid, red when invalid
        if (isValid) {
            passwordInput.classList.remove('border-red-500');
            passwordInput.classList.add('border-green-500');
            // inline fallback in case Tailwind classes are not present in compiled CSS
            try { passwordInput.style.border = '1px solid #16a34a'; passwordInput.style.outline = '1px solid #16a34a'; } catch (e) {}
    } else {
            passwordInput.classList.remove('border-green-500');
            if (value.length > 0) passwordInput.classList.add('border-red-500');
            else passwordInput.classList.remove('border-red-500');
            try { passwordInput.style.border = value.length > 0 ? '1px solid #ef4444' : ''; passwordInput.style.outline = value.length > 0 ? '1px solid #ef4444' : ''; } catch (e) {}
        }

        return isValid;
    }

    // update password hint text
    function updatePasswordHint() {
        const hint = document.getElementById('password-hint');
        if (!hint) return;
        if (!passwordInput) { hint.textContent = ''; return; }
        const v = passwordInput.value || '';
        const ok = validatePasswordStrength();
        if (ok) {
            hint.textContent = 'Password looks good';
            hint.style.color = '#16a34a';
        } else if (v.length === 0) {
            hint.textContent = '';
            hint.style.color = '';
        } else {
            hint.textContent = 'Password must be >=8 chars, include upper & lower case';
            hint.style.color = '#ef4444';
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePasswordStrength();
            validatePasswordMatch();
            // Also update confirm visual when password changes
            updateConfirmPasswordVisual();
            updatePasswordHint();
        });
    }

    // Also update confirm password green state when they match
    function updateConfirmPasswordVisual() {
        if (!confirmPasswordInput) return;
        if (passwordInput && confirmPasswordInput.value.length > 0 && passwordInput.value === confirmPasswordInput.value) {
            confirmPasswordInput.classList.remove('border-red-500');
            confirmPasswordInput.classList.add('border-green-500');
            try { confirmPasswordInput.style.border = '2px solid #16a34a'; confirmPasswordInput.style.outline = '3px solid #16a34a'; } catch (e) {}
            const ch = document.getElementById('confirm-password-hint'); if (ch) { ch.textContent = 'Passwords match'; ch.style.color = '#16a34a'; }
        } else if (confirmPasswordInput.value.length > 0) {
            confirmPasswordInput.classList.remove('border-green-500');
            confirmPasswordInput.classList.add('border-red-500');
            try { confirmPasswordInput.style.border = '2px solid #ef4444'; confirmPasswordInput.style.outline = '3px solid #ef4444'; } catch (e) {}
            const ch = document.getElementById('confirm-password-hint'); if (ch) { ch.textContent = 'Passwords do not match'; ch.style.color = '#ef4444'; }
        } else {
            confirmPasswordInput.classList.remove('border-green-500');
            confirmPasswordInput.classList.remove('border-red-500');
            try { confirmPasswordInput.style.border = ''; confirmPasswordInput.style.outline = ''; } catch (e) {}
            const ch = document.getElementById('confirm-password-hint'); if (ch) { ch.textContent = ''; ch.style.color = ''; }
        }
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordMatch();
            updateConfirmPasswordVisual();
        });
    }

    // Run initial checks in case browser autofills or page loads with values
    try {
        validatePasswordStrength();
        updateConfirmPasswordVisual();

        if (dateOfBirthInput && dateOfBirthInput.value) {
            // trigger the change handler logic to compute age and mark color
            const ev = new Event('change');
            dateOfBirthInput.dispatchEvent(ev);
        } else if (ageInput && ageInput.value) {
            const ageVal = parseInt(ageInput.value, 10);
            if (!isNaN(ageVal) && ageVal >= 18) {
                ageInput.classList.add('border-green-500');
                try { ageInput.style.border = '2px solid #16a34a'; } catch (e) {}
            }
        }
    } catch (e) {
        // defensive - do nothing on error
    }
    
    // File upload validation
    const profileImageInput = document.querySelector('input[name="profile_image"]');
    const studentIdImageInput = document.querySelector('input[name="student_id_image"]');
    
    function validateFileSize(input, maxSizeMB = 2) {
        if (input.files && input.files[0]) {
            const fileSize = input.files[0].size / 1024 / 1024; // Convert to MB
            if (fileSize > maxSizeMB) {
                input.setCustomValidity(`File size must be less than ${maxSizeMB}MB`);
                return false;
            } else {
                input.setCustomValidity('');
                return true;
            }
        }
        return true;
    }
    
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            validateFileSize(this);
        });
    }
    
    if (studentIdImageInput) {
        studentIdImageInput.addEventListener('change', function() {
            validateFileSize(this);
        });
    }
    
    // Form submission with loading state
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validate all file uploads
            let allFilesValid = true;
            if (profileImageInput && !validateFileSize(profileImageInput)) {
                allFilesValid = false;
            }
            if (studentIdImageInput && !validateFileSize(studentIdImageInput)) {
                allFilesValid = false;
            }
            
            if (!allFilesValid) {
                e.preventDefault();
                return false;
            }
            // Validate password strength and match
            const passwordOk = validatePasswordStrength();
            const passwordsMatch = (passwordInput && confirmPasswordInput) ? (passwordInput.value === confirmPasswordInput.value) : true;

            if (!passwordsMatch) {
                if (confirmPasswordInput) {
                    confirmPasswordInput.classList.add('border-red-500');
                    confirmPasswordInput.focus();
                }
                e.preventDefault();
                return false;
            }

            if (!passwordOk) {
                if (passwordInput) {
                    passwordInput.focus();
                }
                e.preventDefault();
                return false;
            }

            // Age validation: ensure >= 18
            const ageEl = document.getElementById('age');
            if (ageEl) {
                const ageVal = parseInt(ageEl.value, 10);
                if (isNaN(ageVal) || ageVal < 18) {
                    ageEl.classList.add('border-red-500');
                    try { ageEl.style.border = '2px solid #ef4444'; ageEl.style.outline = '3px solid #ef4444'; } catch (e) {}
                    ageEl.focus();
                    e.preventDefault();
                    return false;
                } else {
                    ageEl.classList.remove('border-red-500');
                    ageEl.classList.add('border-green-500');
                    try { ageEl.style.border = '2px solid #16a34a'; ageEl.style.outline = '3px solid #16a34a'; } catch (e) {}
                    const ah = document.getElementById('age-hint'); if (ah) { ah.textContent = 'Age OK'; ah.style.color = '#16a34a'; }
                }
            }

            // Show loading state
            if (registerButton) {
                registerButton.disabled = true;
                registerButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Registering...';
            }
        });
    }
    
    // Real-time validation feedback
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.checkValidity()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('border-red-500');
            }
        });
    });
    
    // Student ID format validation (optional - customize based on your requirements)
    const studentIdInput = document.querySelector('input[name="student_id"]');
    if (studentIdInput) {
        studentIdInput.addEventListener('input', function() {
            // Remove any non-alphanumeric characters except hyphens
            this.value = this.value.replace(/[^a-zA-Z0-9-]/g, '');
        });
    }
    
    // Email format validation
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.setCustomValidity('Please enter a valid email address');
                this.classList.add('border-red-500');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-500');
            }
        });
    }
});
