// Password strength checker
document.addEventListener('DOMContentLoaded', function() {
    var passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordStrength);
    }
    
    var confirmInput = document.getElementById('confirm_password');
    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }
});

function checkPasswordStrength() {
    var password = this.value;
    var meter = document.querySelector('.password-strength-meter');
    
    if (!meter) return;
    
    var strength = 0;
    
    if (password.length >= 6) strength += 25;
    if (password.match(/[a-z]+/)) strength += 25;
    if (password.match(/[A-Z]+/)) strength += 25;
    if (password.match(/[0-9]+/)) strength += 25;
    
    meter.style.width = strength + '%';
    
    if (strength < 50) {
        meter.className = 'password-strength-meter weak';
    } else if (strength < 75) {
        meter.className = 'password-strength-meter medium';
    } else {
        meter.className = 'password-strength-meter strong';
    }
}

function checkPasswordMatch() {
    var password = document.getElementById('password').value;
    var confirm = this.value;
    
    if (password !== confirm) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
}