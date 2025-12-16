document.addEventListener("DOMContentLoaded", function () {// executes after the complete page is loaded
    const registerForm = document.querySelector(".register-form");
    const contactForm = document.querySelector(".contact-form");
    const changePassword = document.getElementById("change-pass-form");
    const profileForm = document.getElementById("editProfileForm");
   if(registerForm){
    registerForm.addEventListener("submit", function (e) {
          const username = document.getElementById("reg-name");
            const email = document.getElementById("reg-email");
            const password = document.getElementById("reg-pass");
            const confirmPassword = document.getElementById("reg-confirm");
                    console.log(username.value,email.value,password.value,confirmPassword.value);

        //  Check empty fields
        if (!username.value.trim() || !email.value.trim() || !password.value.trim() || !confirmPassword.value.trim()) {
            showAlert("All fields are required.", "error");
            e.preventDefault();
            return;
        }

        //  Username length < 3
        if (username.value.trim().length < 3) {
             showAlert("Username must be at least 3 characters long.", "error");
             username.autofocus =true;
            e.preventDefault();
            return;
        }
        if (username.value.length > 30) {
            showAlert("Username too long.", "error");
          e.preventDefault();
          return;
        }
        const usernamePattern = /^[A-Za-z]+$/;

         if (!usernamePattern.test(username.value.trim())) {
        showAlert("Username can contain only letters..", "error");
        e.preventDefault();
         return;
        }
        if (email.value.length > 50) {
            showAlert("Email too long.", "error");
          e.preventDefault();
         return;
        }
        //  Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
            showAlert("Please enter a valid email address.", "error");
            e.preventDefault();
            return;
        }

        if (password.value.length < 6) {
            showAlert("Password must be at least 6 characters long.", "error");
            e.preventDefault();
            return;
        }
        //  Confirm password matches
        if (password.value !== confirmPassword.value) {
             showAlert("Confirm Password do not match.", "error");
            // alert("Passwords do not match.");
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
            showAlert("All fields are required.", "error");
                e.preventDefault();
                return;
            }

            // 2. Name length rule
            if (username.value.trim().length < 3) {
             showAlert("Username must be at least 3 characters long.", "error");
                e.preventDefault();
                return;
            }
            if(username.value.trim().length>30){
    showAlert("Username too long.", "error");
                e.preventDefault();
                return;
            }

            // 3. Name only letters
            const usernamePattern = /^[A-Za-z ]+$/;
            if (!usernamePattern.test(username.value.trim())) {
        showAlert("Username can contain only letters..", "error");
                e.preventDefault();
                return;
            }

            // 4. Email length
            if (email.value.length > 50) {
            showAlert("Email too long.", "error");
                e.preventDefault();
                return;
            }

            // 5. Email regex
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email.value.trim())) {
                        showAlert("Please enter a valid email address.", "error");
                e.preventDefault();
                return;
            }

            if (message.value.trim().length < 6) {
                showAlert("Message must be at least 6 characters long.","error");
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
            showAlert("All fields are required.", "error");
                 e.preventDefault();
                return;
            }
              if (currPass.value.length < 6) {
            showAlert("current Password must be at least 6 characters long.","error");
            e.preventDefault();
            return;
        }
           if (newPass.value.length < 6) {
            showAlert("new Password must be at least 6 characters long.","error");
            e.preventDefault();
            return;
        }
         if (newPass.value !== confirm.value) {
            showAlert("Passwords do not match.","error");
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
            showAlert("All fields are required.", "error");
            e.preventDefault();
            return;
        }

        if (name.value.trim().length < 3) {
             showAlert("Username must be at least 3 characters long.", "error");
            e.preventDefault();
            return;
        }
if (name.value.length > 30) {
    showAlert("Username too long.","error");
    e.preventDefault();
    return;
}
const usernamePattern = /^[A-Za-z]+$/;

if (!usernamePattern.test(name.value.trim())) {
        showAlert("Username can contain only letters..", "error");
    e.preventDefault();
    return;
}
if (email.value.length > 50) {
    showAlert("Email too long.","error");
    e.preventDefault();
    return;
}
        //  Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
            showAlert("Please enter a valid email address.","error");
            e.preventDefault();
            return;
        }



   
    })
}
 function showAlert(message, type) {
    const alertBox = document.getElementById("custom-alert");
    alertBox.innerHTML = message;
    alertBox.className = "alert-box alert-" + type;
    alertBox.style.display = "block";

    setTimeout(() => {
        alertBox.style.display = "none";
    }, 3500);
}
})
