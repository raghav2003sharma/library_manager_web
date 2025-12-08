<?php
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../public/styles/auth.css">
</head>
<body>
     <div class="home-btn">
    <a href="?page=user-home">
      <div class="home-icon"> <i class="fa-solid fa-house"></i></div> <p>Home</p>
    </a>
</div>
    <div class="container">
        
        <h1 class="frm-heading">Become a Member</h1>
        <form class="main-frm register-form"action="../app/controllers/register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="text" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p class="redirect-txt">Already have an account? <a href="?page=login">Login here</a></p>
    </div>
    <script src="../../public/js/validation.js"></script>
    <script>
  <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php endif; ?>
</script>
</body>
</html>