document.addEventListener("DOMContentLoaded", function () {// executes after the complete page is loaded
    const registerForm = document.querySelector(".register-form");
    const contactForm = document.querySelector(".contact-form");
    const changePassword = document.getElementById("change-pass-form");
    const profileForm = document.getElementById("editProfileForm");
   if(registerForm){
    registerForm.addEventListener("submit", function (e) {
          const username = document.querySelector("input[name='username']");
            const email = document.querySelector("input[name='email']");
            const password = document.querySelector("input[name='password']");
            const confirmPassword = document.querySelector("input[name='confirm_password']");

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
if (username.value.length > 30) {
    alert("Username too long.");
    e.preventDefault();
    return;
}
const usernamePattern = /^[A-Za-z]+$/;

if (!usernamePattern.test(username.value.trim())) {
    alert("Username can contain only letters.");
    e.preventDefault();
    return;
}
if (email.value.length > 50) {
    alert("Email too long.");
    e.preventDefault();
    return;
}
        // 3. Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
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
}
if(contactForm){
    contactForm.addEventListener("submit",function(e){
                 const username = contactForm.querySelector("input[name='username']");
            const email = contactForm.querySelector("input[name='email']");
            const message = contactForm.querySelector("textarea[name='message']");
              if (!username.value.trim() || !email.value.trim() || !message.value.trim()) {
                alert("All fields are required.");
                e.preventDefault();
                return;
            }

            // 2. Name length rule
            if (username.value.trim().length < 3) {
                alert("Name must be at least 3 characters long.");
                e.preventDefault();
                return;
            }
            if(username.value.trim().length>30){
                  alert("Name is too long");
                e.preventDefault();
                return;
            }

            // 3. Name only letters
            const usernamePattern = /^[A-Za-z ]+$/;
            if (!usernamePattern.test(username.value.trim())) {
                alert("Name can contain only letters.");
                e.preventDefault();
                return;
            }

            // 4. Email length
            if (email.value.length > 50) {
                alert("Email too long.");
                e.preventDefault();
                return;
            }

            // 5. Email regex
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email.value.trim())) {
                alert("Please enter a valid email address.");
                e.preventDefault();
                return;
            }

            if (message.value.trim().length < 6) {
                alert("Message must be at least 6 characters long.");
                e.preventDefault();
                return;
            }
        });
    }
    if(changePassword){
        changePassword.addEventListener("submit",function(e){
          const currPass =  document.getElementById("current-password");
           const newPass = document.getElementById("new-password");
            const confirm = document.getElementById("confirm-password");

            if(!currPass || !newPass || ! confirm){
                alert("all fields are required");
                 e.preventDefault();
                return;
            }
              if (currPass.value.length < 6) {
            alert("current Password must be at least 6 characters long.");
            e.preventDefault();
            return;
        }
           if (newPass.value.length < 6) {
            alert("new Password must be at least 6 characters long.");
            e.preventDefault();
            return;
        }
         if (newPass.value !== confirm.value) {
            alert("Passwords do not match.");
            e.preventDefault();
            return;
        }
    })
}
if(profileForm){
    profileForm.addEventListener("submit",function(e){
   const name = document.getElementById("editProfileName");
   const email = document.getElementById("editProfileEmail");

        if (!name.value.trim() || !email.value.trim() ) {
            alert("All fields are required.");
            e.preventDefault();
            return;
        }

        if (name.value.trim().length < 3) {
            alert("Username must be at least 3 characters long.");
            e.preventDefault();
            return;
        }
if (name.value.length > 30) {
    alert("Username too long.");
    e.preventDefault();
    return;
}
const usernamePattern = /^[A-Za-z]+$/;

if (!usernamePattern.test(name.value.trim())) {
    alert("Username can contain only letters.");
    e.preventDefault();
    return;
}
if (email.value.length > 50) {
    alert("Email too long.");
    e.preventDefault();
    return;
}
        // 3. Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }



   
    })
}
})
