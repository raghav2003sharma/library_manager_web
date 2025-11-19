document.addEventListener("DOMContentLoaded", function () {// executes after the complete page is loaded
    const form = document.querySelector("form");
    const username = document.querySelector("input[name='username']");
    const email = document.querySelector("input[name='email']");
    const password = document.querySelector("input[name='password']");
    const confirmPassword = document.querySelector("input[name='confirm_password']");

    form.addEventListener("submit", function (e) {

        // 1. Check empty fields
        if (!username.value.trim() || !email.value.trim() || !password.value.trim() || !confirmPassword.value.trim()) {
            alert("All fields are required.");
            e.preventDefault();
            return;
        }

        // 2. Username length < 3
        if (username.value.trim().length < 3) {
            alert("Username must be at least 3 characters long.");
            e.preventDefault();
            return;
        }

        // 3. Valid email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

        // 4. Password length >= 6
        if (password.value.length < 6) {
            alert("Password must be at least 6 characters long.");
            e.preventDefault();
            return;
        }

        // 5. Confirm password matches
        if (password.value !== confirmPassword.value) {
            alert("Passwords do not match.");
            e.preventDefault();
            return;
        }
    });
});
