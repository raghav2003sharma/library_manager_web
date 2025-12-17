<?php
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
if(isset($_SESSION['user_id'])){
$name = $_SESSION['name'];
$email = $_SESSION['email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>Books Mania</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/styles/user.css">
    <link rel="stylesheet" href="../../../public/styles/book.css">
        <link rel="stylesheet" href="/public/styles/auth.css">



</head>
<body>
<div class="page-wrapper">
<!-- NAVBAR -->
<div class="navbar">
<div class="logo" > <a href="/user-home"><img src="/public/uploads/book_logo.png" alt="Logo" class="logo-img"><span> Books Mania </span></a></div>
<div id="navLinks" class="nav-links">
<a href="/user-home">Dashboard</a>
 <?php if(isset($_SESSION['user_id']) && $_SESSION['role']=== "user"): ?>
        <!-- User is logged in -->
<a href="/borrowed" >Borrowed</a>
<a href="/reserves">Reservations</a>
<a href="#" onclick="toggleSettings(event)">Settings</a>
   <?php else: ?>
        <!-- User is NOT logged in -->
        <a href="/login">Login</a>
        <a href="/register">Register</a>

    <?php endif; ?>
</div>
<div class="menu-icon" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
    </div>
</div>
        <div  id="settingsDropdown" class="dropdown">
            <a href="#" onclick="showUser('<?= $email ?>','<?= $name ?>')">Edit Profile</a>
            <a href="#" onclick="showForm()">Change Password</a>
            <a href="logout">Logout</a>
        </div>
        <div class="pass-modal">
            <div class="form-modal">
                <h1>Change Password</h1>
        <form action="/app/controllers/auth/change-password.php" method="post" id="change-pass-form">
            <input type="password" name="cur-pass"id="current-password" placeholder="Current Password" required />
            <input type="password" name="new-pass"id="new-password" placeholder="New Password" required />
            <input type="password" name="conf-pass"id="confirm-password" placeholder="Confirm New Password" required />
            <div class="modal-actions">
                <button type="button" class="cancel-btn" onclick="closeChangePass()">Cancel</button>
                <button type="submit" class="save-btn">Update Password</button>
            </div>     
           </form>
            </div>
        </div>
        <!-- EDIT PROFILE MODAL -->
<div class="edit-user-modal" id="editUserModal">
    <div class="edit-user-box">
        <h1>Edit Profile</h1>

        <form action="/app/controllers/user/update-profile.php" method="POST" id="editProfileForm">
            <input type="text" name="username" id="editProfileName" placeholder="Full Name" required>
            <input type="email" name="email" id="editProfileEmail" placeholder="Email Address" required>

            <div class="edit-profile-actions">
                <button type="button" class="profile-cancel-btn" onclick="closeEditUser()">Cancel</button>
                <button type="submit" class="profile-save-btn">Save Changes</button>
            </div>

            <button type="button" class="profile-delete-btn" onclick="confirmDeleteUser()">
                <i class="fa-solid fa-trash"></i> Delete Account
            </button>

        </form>
    </div>
</div>
