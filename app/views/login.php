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
    <title>Document</title>
    <link rel="stylesheet" href="../../public/styles/auth.css">
</head>
<body>
     <div class="container">
        <h1 class="frm-heading">Sign in</h1>
        <form class="main-frm"action="../app/controllers/login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Submit</button>
        </form>
        <p class="redirect-txt">Don't have an account? <a href="?page=register">Signup here</a></p>
    </div>
<script>
  <?php if (!empty($success)) : ?>
            alert("<?= $success ?>");
        <?php endif; ?>
        
  <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php endif; ?>
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const email = document.querySelector("input[name='email']");
        const password = document.querySelector("input[name='password']");

        form.addEventListener("submit", function (e) {
              if ( !email.value.trim() || !password.value.trim()) {
            alert("All fields are required.");
            e.preventDefault();
            return;
        }
            // Email validation
            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (!email.value.match(emailPattern)) {
                 alert("Please enter a valid email address.");
                 e.preventDefault();
                 return;
            }

            // Password validation
            if (password.value.length < 6) {
                alert("Password must be at least 6 characters long.");
                 e.preventDefault();
                 return;
            }
        });
    });
    </script>

</body>
</html>