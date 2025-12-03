<?php session_name("ADMINSESS"); 
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../../../public/styles/admin.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
<div class="sidebar">
    <div class="upper-side">
<h2>Admin Panel</h2>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=dashboard" ><div class="side-icon"><i class="fa-solid fa-chart-line"></i></div>Dashboard</a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=manage-users"><div class="side-icon"><i class="fa-solid fa-users"></i></div>Manage Users</a>
<a onclick="addActive(this)" href="index.php?page=admin-home&main-page=manage-books"><div class="side-icon"><i class="fa-solid fa-layer-group"></i></div>Manage Books</a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=reservations"><div class="side-icon"><i class="fa-solid fa-book-bookmark"></i></div>Reservations</a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=borrowed-books"><div class="side-icon"><i class="fa-solid fa-address-book"></i></div>Borrowed Books</a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=borrow-history" ><div class="side-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>Borrow History</a>
    </div>
<div class="bottom-side">
<a onclick="addActive(this);toggleSettings()"href="#" ><div class="side-icon"><i class="fa-solid fa-gear"></i> </div> Settings</a>
<div class="settings" >
    <!-- <a href="#">edit profile</a> -->
    <a href="#" onclick="showForm()">Change Password</a>
</div>
<a href="index.php?page=logout"><div class="side-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div> Logout</a>
</div>
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