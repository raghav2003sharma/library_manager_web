<?php 
 session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role']!== "user"){
$_SESSION['error'] = "Unauthorized access !";
header("Location: /user-home");
exit;
 } ?>
<?php include "layouts/header.php"; ?>



<div class="avail-books" id="borrowed-section">

<!-- BORROWED SECTION -->
<h2 class="section-title">My Borrowed Books</h2>
<div id="borrow" class="book-grid">

</div>
</div>













<?php include "layouts/footer.php"; ?>
