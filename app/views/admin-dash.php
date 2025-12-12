<?php
session_start();
// Protect admin access
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login");
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
<script src="../../public/js/dashboard.js"></script>
<script src="../../public/js/fines.js"></script>



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
    
        const changePassword = document.getElementById("change-pass-form");
        const profileForm = document.getElementById("editProfileForm");
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
    const form = document.getElementById("pass-modal");
    if(form.style.display === "none" || form.style.display === ""){
        form.style.display = "flex";
    } else {
        form.style.display = "none";
    }
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
 const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }
   
    })
}
    function showEditForm(name,email){
          document.getElementById("editProfileName").value = name;
        document.getElementById("editProfileEmail").value = email;

    const form = document.getElementById("editUserModal");
    if(form.style.display === "none" || form.style.display === ""){
        form.style.display = "flex";
    } else {
        form.style.display = "none";
    }
}

function closeChangePass(){
    document.getElementById("pass-modal").style.display = "none";
}
function closeEditUser(){
        document.getElementById("editUserModal").style.display = "none";

}
function confirmDeleteUser() {
    if (confirm("Are you sure you want to delete your account? This cannot be undone.")) {
        window.location.href = "../app/controllers/delete-profile.php";
    }
}
    </script>
</body>
</html>