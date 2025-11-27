<?php
session_start();
// Protect admin access
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$success = "";
$error = "";
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
$page = $_GET['main-page'] ?? 'dashboard';
?>
<?php include "layouts/navbar.php"; ?>
<?php include "layouts/sidebar.php"; ?>
<!-- Main Section -->
<div class="main">

<?php

    $file = "layouts/$page.php";
    include $file;
    
    ?>
</div>

<!-- End of Main Section -->
</div>
<script src="../../public/js/manage-users.js"></script>
<script src="../../public/js/manage-books.js"></script>
<script src="../../public/js/borrow-record.js"></script>

<script> 
     <?php if (!empty($success)) : ?>
            alert("<?= $success ?>");
        <?php endif; ?>
        
  <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php endif; ?>
</script>
<script>
    function toggleSettings() {
        var settingsDiv = document.querySelector('.settings');
        if (settingsDiv.style.display === 'block') {
            settingsDiv.style.display = 'none';
        } else {
            settingsDiv.style.display = 'block';
        }
    }
    </script>
    <script>
        function showForm(){
    const form = document.querySelector(".pass-modal");
    if(form.style.display === "none" || form.style.display === ""){
        form.style.display = "flex";
    } else {
        form.style.display = "none";
    }
}
function closeChangePass(){
    document.querySelector(".pass-modal").style.display = "none";
}
    </script>
</body>
</html>