<?php
session_name("USERSESS");
session_start();
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
</head>
<body>
<div class="page-wrapper">
<!-- NAVBAR -->
<div class="navbar">
<div class="logo" onclick="showPage('home')"> <img src="/public/uploads/book_logo.png" alt="Logo" class="logo-img"><span> Books Mania </span></div>
<div id="navLinks" class="nav-links">
<!-- <a href="#" >Home</a> -->
 <?php if(isset($_SESSION['user_id'])): ?>
        <!-- User is logged in -->
<a href="#" onclick="showPage('borrow')">Borrowed</a>
<a href="#" onclick="showPage('reservation')">Reservations</a>
<a href="#" onclick="toggleSettings()">Settings</a>
   <?php else: ?>
        <!-- User is NOT logged in -->
        <a href="index.php?page=login">Login</a>
        <a href="index.php?page=register">Register</a>

    <?php endif; ?>
</div>
<div class="menu-icon" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
    </div>
</div>
        <div class="dropdown">
            <a href="#" onclick="showForm()">Change Password</a>
            <a href="public/index.php?page=logout">Logout</a>
        </div>
        <div class="pass-modal">
            <div class="form-modal">
                <h1>Change Password</h1>
        <form action="../app/controllers/change-password.php" method="post" id="change-pass-form">
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