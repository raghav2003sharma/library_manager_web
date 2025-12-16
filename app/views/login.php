<!-- 
// $error = $_SESSION['error'] ?? '';
// unset($_SESSION['error']);
// $success = $_SESSION['success'] ?? '';
// unset($_SESSION['success']);
// 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Mania</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="/public/styles/auth.css">
</head>
<body> 
     <div class="home-btn">
    <a href="?page=user-home">
      <div class="home-icon"> <i class="fa-solid fa-house"></i></div> <p>Home</p>
    </a>
</div> -->
<?php include "layouts/header.php"; ?>

    <div class="auth-container">
     <div class="auth-inside-container">
        <h1 class="auth-frm-heading">Sign in</h1>
        <form class="main-frm login-form"action="../app/controllers/auth/login.php" method="POST">
            <input type="email" id="login-email" name="email" placeholder="Email" required><br>
            <div class="auth-pass"> 

            <input type="password" id="login-pass" name="password" placeholder="Password" required>
            <span class="eye" style="background-color:none;" onclick="togglePassword(this)"><i class="fa-solid fa-eye-slash"></i></span>
        </div>
            <button type="submit" >Submit</button>
        </form>
        <p class="redirect-txt">Don't have an account? <a href="?page=register">Signup here</a></p>
    </div>
        <div id="custom-alert" class="alert-box"></div>
    </div>
<script>

   function togglePassword(eye) {
    const input = eye.previousElementSibling;
    const icon = eye.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector(".login-form");
        const email = document.getElementById("login-email");
        const password = document.getElementById("login-pass");

        form.addEventListener("submit", function (e) {
              if ( !email.value.trim() || !password.value.trim()) {
            showAlert("All fields are required.", "error");
            e.preventDefault();
            return;
        }
           if (email.value.length > 50) {
                        showAlert("Email too long.", "error");
    // alert("Email too long.");
    e.preventDefault();
    return;
    }
        //  Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
             showAlert("Please enter a valid email address.", "error");

            // alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

            // Password validation
            if (password.value.length < 6) {
              showAlert("Password must be at least 6 characters long.", "error");

                // alert("Password must be at least 6 characters long.");
                 e.preventDefault();
                 return;
            }
        });
        
    });
    
   


    </script>
    <?php include "layouts/footer.php"; ?>

<!-- </body>
</html> -->
