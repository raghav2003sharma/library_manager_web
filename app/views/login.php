<?php
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>
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
</div>

     <div class="container">
        <h1 class="frm-heading">Sign in</h1>
        <form class="main-frm login-form"action="../app/controllers/auth/login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Submit</button>
        </form>
        <p class="redirect-txt">Don't have an account? <a href="?page=register">Signup here</a></p>
    </div>
        <div id="custom-alert" class="alert-box"></div>

<script>
  <?php if (!empty($success)) : ?>
            showAlert("<?= $success ?>","success");
        <?php endif; ?>
        
  <?php if (!empty($error)) : ?>
            showAlert("<?= $error ?>","error");
        <?php endif; ?>

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector(".login-form");
        const email = document.querySelector("input[name='email']");
        const password = document.querySelector("input[name='password']");

        form.addEventListener("submit", function (e) {
            console.log("hi")
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
     function showAlert(message, type) {
    const alertBox = document.getElementById("custom-alert");
    alertBox.innerHTML = message;
    alertBox.className = "alert-box alert-" + type;
    alertBox.style.display = "block";

    setTimeout(() => {
        alertBox.style.display = "none";
    }, 3500);
}
   


    </script>
</body>
</html>
