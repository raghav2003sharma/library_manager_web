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
<?php include "layouts/sidebar.php"; ?>
<!-- Main Section -->


<div class="main">
<?php

    $file = "layouts/$page.php";
    include $file;
    
    ?>

<!-- End of Main Section -->
</div>
</div>

<script src="../../public/js/manage-users.js"></script>
<script src="../../public/js/manage-books.js"></script>
<script src="../../public/js/borrow-record.js"></script>
<script src="../../public/js/reservation.js"></script>


<script> 
     <?php if (!empty($success)) : ?>
        alert(<?= json_encode($success) ?>)
        <?php endif; ?>
        
  <?php if (!empty($error)) : ?>
                alert(<?= json_encode($error) ?>)
        <?php endif; ?>
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let links = document.querySelectorAll(".sidebar a");
    let current = window.location.href;

    links.forEach(link => {
        if (current.includes(link.getAttribute("href"))) {
            link.classList.add("select");
        }
    });
});
    function addActive(element) {
        var links = document.querySelectorAll('.sidebar a');
        links.forEach(link => link.classList.remove('select'));
        element.classList.add('select');
    }
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
        const changePassword = document.getElementById("change-pass-form");
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