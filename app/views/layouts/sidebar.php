<?php
session_start();
$name = $_SESSION['name'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../../../public/styles/admin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
<div class="sidebar">
    <div class="upper-side">
<h2>Admin Panel</h2>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=dashboard" ><div class="side-icon"><i class="fa-solid fa-chart-line"></i></div><span>Dashboard</span></a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=manage-users"><div class="side-icon"><i class="fa-solid fa-users"></i></div><span>Manage Users</span></a>
<a onclick="addActive(this)" href="index.php?page=admin-home&main-page=manage-books"><div class="side-icon"><i class="fa-solid fa-layer-group"></i></div><span>Manage Books</span></a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=reservations"><div class="side-icon"><i class="fa-solid fa-book-bookmark"></i></div><span>Reservations</span></a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=fines"><div class="side-icon"><i class="fa-solid fa-money-bill"></i></div><span>Fines</span></a>
<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=borrowed-books"><div class="side-icon"><i class="fa-solid fa-address-book"></i></div><span>Borrowed Books</span></a>

<a onclick="addActive(this)"href="index.php?page=admin-home&main-page=borrow-history" ><div class="side-icon"><i class="fa-solid fa-clock-rotate-left"></i></div><span>Borrow History</span></a>
    </div>
<div class="bottom-side">
<a onclick="addActive(this);toggleSettings()"href="#" ><div class="side-icon"><i class="fa-solid fa-gear"></i> </div> <span>Settings</span></a>
<div class="settings" >
    <a href="#"onclick="showEditForm('<?= $name?>','<?= $email?>')">Edit Profile</a>
    <a href="#" onclick="showForm()">Change Password</a>
</div>
<a href="index.php?page=logout"><div class="side-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div> <span>Logout</span></a>
</div>
</div>
<div id="pass-modal" >
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
             <!-- EDIT PROFILE MODAL -->
<div class="edit-user-modal" id="editUserModal">
    <div class="edit-user-box">
        <h1>Edit Profile</h1>

        <form action="../app/controllers/update-profile.php" method="POST" id="editProfileForm">
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
