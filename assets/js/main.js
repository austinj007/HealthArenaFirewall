function validateRegistration() {


    var phoneNumber = document.getElementById("phone").value;
    var password = document.getElementById("pwd").value;
    var confirmPassword = document.getElementById("confirmPwd").value;
    var medicalHistory = document.getElementById("med").value;

    if (phoneNumber.length !== 10) {
        alert("Phone number must be 10 digits.");
        return false;
    }

    var passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()])[A-Za-z\d!@#$%^&*()]{8,}$/;
    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters and include at least one uppercase letter, one digit, and one special character.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    if (!validateForm()) {
        return false;
    }

    return true;
}

function generateRandomCode(length) {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    var code = '';

    for (var i = 0; i < length; i++) {
        var index = Math.floor(Math.random() * characters.length);
        code += characters.charAt(index);
    }

    return code;
}

function generateCaptcha() {
    var canvas = document.getElementById('captchaCanvas');
    var context = canvas.getContext('2d');

    canvas.width = 150;
    canvas.height = 50;

    var backgroundColor = getRandomColor();
    context.fillStyle = backgroundColor;

    context.fillRect(0, 0, canvas.width, canvas.height);

    var captchaCode = generateRandomCode(5);
    context.font = 'italic 30px cursive';

    var fontColor = getSimilarColor(backgroundColor);
    context.fillStyle = fontColor;

    var charWidth = 14;
    var x = charWidth / 2;
    var y = canvas.height / 2 + 10;

    for (var i = 0; i < captchaCode.length; i++) {
        context.fillText(captchaCode[i], x, y);
        x += charWidth;
    }

    canvas.setAttribute('data-captcha-code', captchaCode);
}

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';

    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }

    return color;
}

function getSimilarColor(color) {
    var rgbValues = color.match(/\w\w/g).map(function(hex) {
        return parseInt(hex, 16);
    });

    var similarValues = rgbValues.map(function(value) {
        return Math.max(0, Math.min(255, value + 50));
    });

    return 'rgb(' + similarValues.join(',') + ')';
}


function validateForm() {
    var userInput = document.getElementById('incaptcha').value.trim();

    var captchaCode = document.getElementById('captchaCanvas').getAttribute('data-captcha-code');

    if (userInput === captchaCode) {
        return true;
    } else {
        alert('Captcha validation failed. Please try again.');
    }
    return false;
}



function validatePassword() {
    var password = document.getElementById("newPassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    var passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()])[A-Za-z\d!@#$%^&*()]{8,}$/;
    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters and include at least one uppercase letter, one digit, and one special character.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}