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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/styles/user.css">
</head>
<body>
<div class="page-wrapper">
<!-- NAVBAR -->
<div class="navbar">
<div class="logo">📘 Books Mania</div>
<div id="navLinks" class="nav-links">
<a href="#" onclick="showPage('home')">Home</a>
 <?php if(isset($_SESSION['user_id'])): ?>
        <!-- User is logged in -->
<a href="#" onclick="showPage('borrow')">Borrowed</a>
<a href="#" onclick="toggleSettings()">Settings</a>
   <?php else: ?>
        <!-- User is NOT logged in -->
        <a href="index.php?page=login">Login</a>
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

<!-- SEARCH BAR -->
 <div id="home-section">
<div class="search-bar">
<input type="text" class="search-input" id="availBooks"  oninput="autoSearch(this.value)" placeholder="Search books by title or author..." />
<button class="search-btn" onclick="searchBooks()" >Search</button>
<div id="suggestions" class="suggest-box"></div>

</div>

<div class="avail-books">

<h2 class="section-title">Available Books</h2>
<div class="filters">
<button class="filter-btn active" onclick="loadCategory(this,'all');fetchAvailableBooks('all')">All Books</button>
<button class="filter-btn" onclick="loadCategory(this,'fiction');fetchAvailableBooks('fiction')">Fiction</button>
<button class="filter-btn" onclick="loadCategory(this,'sci-fi');fetchAvailableBooks('sci-fi')">Sci-Fi</button>
<button class="filter-btn"onclick="loadCategory(this,'history');fetchAvailableBooks('history')">History</button>
<button class="filter-btn" onclick="loadCategory(this,'self-help');fetchAvailableBooks('self-help')">Self-Help</button>
<button class="filter-btn" onclick="loadCategory(this,'education');fetchAvailableBooks('education')">Education</button>

</div>


<div id="avail"class="book-grid">

</div>
</div>
</div>
<div class="avail-books" id="borrowed-section" style="display:none;">

<!-- BORROWED SECTION -->
<h2 class="section-title">My Borrowed Books</h2>
<div id="borrow" class="book-grid">

</div>
</div>
<div class="footer">
    <p>&copy; <?php echo date('Y')?> Books Mania. All rights reserved.</p>
</div>
</div>
<script src="../../public/js/user-dash.js"></script>
<script> 
     <?php if (!empty($success)) : ?>
            alert("<?= $success ?>");
        <?php endif; ?>
        
  <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php endif; ?>
</script>
</body>
</html>
