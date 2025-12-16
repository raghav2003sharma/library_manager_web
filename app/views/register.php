<!-- 
// $error = $_SESSION['error'] ?? '';
// unset($_SESSION['error']);

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Mania</title>
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
        
        <h1 class="auth-frm-heading">Become a Member</h1>
        <form class="main-frm register-form"action="../app/controllers/auth/register.php" method="POST">
            <input type="text" id="reg-name"name="username" placeholder="Username" required><br>
            <input type="text" id="reg-email" name="email" placeholder="Email" required><br>
           <div class="auth-pass"> 
            <input type="password" id="reg-pass" name="password" placeholder="Password" required>
            <span class="eye" style="background-color:none;" onclick="togglePassword(this)"><i class="fa-solid fa-eye-slash"></i></span></div>
            <div class="auth-pass"> 
                <input type="password" id="reg-confirm" name="confirm_password" placeholder="Confirm Password" required>
              <span class="eye" style="background-color:none;" onclick="togglePassword(this)"><i class="fa-solid fa-eye-slash"></i></span></div>
            <button type="submit">Register</button>
        </form>
        <p class="redirect-txt">Already have an account? <a href="?page=login">Login here</a></p>
    </div>
    </div>
    <div id="custom-alert" class="alert-box"></div>
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
    </script>
    <!-- <script>
        function showAlert(message, type) {
    const alertBox = document.getElementById("custom-alert");
    alertBox.innerHTML = message;
    alertBox.className = "alert-box alert-" + type;
    alertBox.style.display = "block";

    setTimeout(() => {
        alertBox.style.display = "none";
    }, 3500);
}
 
       

</script> -->
    <?php include "layouts/footer.php"; ?>

<!-- 
</body>
</html> -->