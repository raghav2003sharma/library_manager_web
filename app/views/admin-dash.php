<?php
session_start();
// Protect admin access
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] ="unauthorized access";
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
    <div id="custom-alert" class="alert-box"></div>

<script> 
    // if (!empty($success)) : ?>
        //alert(// json_encode($success) )
        //endif; ?>
      function showAlert(message, type) {
    const alertBox = document.getElementById("custom-alert");
    alertBox.innerHTML = message;
    alertBox.className = "alert-box alert-" + type;
    alertBox.style.display = "block";

    setTimeout(() => {
        alertBox.style.display = "none";
    }, 3500);
}   
  <?php if (!empty($error)) : ?>
                //alert()

        Toastify({
    text: <?= json_encode($error) ?>,
    gravity: "top",
    position: "center",
    backgroundColor: "#a75028ff",
    duration: 2500
}).showToast();
        <?php endif; ?>
             <?php if (!empty($success)) : ?>

        Toastify({
    text: <?= json_encode($success) ?>,
    gravity: "top",
    position: "center",
    backgroundColor: "#28a745",
    duration: 2500
}).showToast();
        <?php endif; ?>
</script>
<script src="../../public/js/manage-users.js"></script>
<script src="../../public/js/manage-books.js"></script>
<script src="../../public/js/borrow-record.js"></script>
<script src="../../public/js/reservation.js"></script>
<script src="../../public/js/dashboard.js"></script>
<script src="../../public/js/fines.js"></script>



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
            showAlert("All fields are required.", "error");
                 e.preventDefault();
                return;
            }
              if (currPass.value.length < 6) {
                showAlert("current Password must be at least 6 characters long.", "error");
            e.preventDefault();
            return;
        }
           if (newPass.value.length < 6) {
                showAlert("new Password must be at least 6 characters long.", "error");
            e.preventDefault();
            return;
        }
         if (newPass.value !== confirm.value) {
             showAlert("Confirm Password do not match.", "error");
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
    showAlert("Username too long.", "error");
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
            showAlert("Email too long.", "error");
    e.preventDefault();
    return;
}
 const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
                        showAlert("Please enter a valid email address.", "error");
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