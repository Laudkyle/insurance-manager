
    function validateEmail() {
        const emailInput = document.getElementById("email");
        const emailValue = emailInput.value;

        // Regular expression for a valid email format
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

        if (!emailRegex.test(emailValue)) {
            alert("Invalid email format. Please enter a valid email address.");
            emailInput.focus();
            return false;
        }

        return true;
    }
    function validateForm() {
        // Get the values of the expiry date, effective date, and policy date inputs
        const expiryDateInput = document.getElementById("expiry_date");
        const effectiveDateInput = document.getElementById("effective_date");
        const renewalDateInput = document.getElementById("renewal_date");

    
        const expiryDate = new Date(expiryDateInput.value);
        const effectiveDate = new Date(effectiveDateInput.value);
        const renewalDate = new Date(renewalDateInput.value);

    
        // Compare the dates to ensure they meet your conditions
        if (effectiveDate >= expiryDate) {
            alert("Effective date must be less than expiry date!!");
            return false;
        }
        // Compare the dates to ensure renewal date is greater than expiry date
        if (renewalDate <= expiryDate) {
            alert("Renewal date must be greater than expiry date.");
            return false;
        }
          
        // Call the validateEmail function and consider its result
        if (!validateEmail()) {
            return false;
        }
    
        // If both email validation and date validation pass, return true
        return true;
    }
    function validateUpdateForm() {
        
        // Get the values of the expiry date, effective date, and policy date inputs
        const updateExpiryDateInput = document.getElementById("update_expiry_date");
        const updateEffectiveDateInput = document.getElementById("update_effective_date");
    
        const updateExpiryDate = new Date(updateExpiryDateInput.value);
        const updateEffectiveDate = new Date(updateEffectiveDateInput.value);
    
        // Compare the dates to ensure they meet your conditions
        if (updateEffectiveDate >= updateExpiryDate) {
            alert("Effective date must be less than expiry date!!");
            return false;
        }
    
        // Get the email input value
        const updateEmailInput = document.getElementById("update_email");
        const updateEmailValue = updateEmailInput.value;
    
        // Validate the email using the validateEmail function
        if (!validateEmail(updateEmailValue)) {
            alert("Invalid email format. Please enter a valid email address.");
            updateEmailInput.focus();
            return false;
        }
    
        // If all email validation and date validation pass, return true
        return true;
        
    }


    