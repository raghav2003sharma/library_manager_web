<?php
require_once "app/configs/dbconfig.php";
 $db = new Database();
      $users =  $db->conn->query("SELECT COUNT(*) AS total_users FROM users");
      $books = $db->conn->query("SELECT COUNT(*) AS total_books FROM books");
      $borrowed = $db->conn->query("SELECT COUNT(*) AS borrowed_books FROM borrow_records WHERE return_date IS NULL");
      // $overdue = $conn->query("SELECT COUNT(*) AS overdue_books FROM borrow_records WHERE return_date IS NULL AND due_date < CURDATE()");
      $reservations = $db->conn->query("SELECT COUNT(*) AS reservations FROM reservations WHERE status = 'pending'");
      $total_users = $users->fetch_assoc()['total_users'];
        $total_books = $books->fetch_assoc()['total_books'];
        $borrowed_books = $borrowed->fetch_assoc()['borrowed_books'];
        // $overdue_books = $overdue->fetch_assoc()['overdue_books'];
        $pending_reserves = $reservations->fetch_assoc()['reservations'];
?>

<div class="section-title">Dashboard</div>
<div class="cards">
<a href="admin-home?main-page=manage-users"  class="card">
  <div class="card-icon">
<h3>Total Users</h3>
<i class="fa-solid fa-users"></i>  </div>
<p><?= $total_users ?></p>
</a>
<a href="admin-home?main-page=manage-books" class="card">
  <div class="card-icon">
<h3>Total Books</h3>
<i class="fa-solid fa-layer-group"></i>  </div>
<p><?= $total_books ?></p>
</a>
<a href="admin-home?main-page=borrowed-books"  class="card">
    <div class="card-icon">

<h3>Borrowed Books</h3>
<i class="fa-solid fa-address-book"></i>
    </div>

<p><?= $borrowed_books ?></p>
</a>
<a href="admin-home?main-page=reservations"  class="card">
    <div class="card-icon">
<h3>Pending Reservations</h3>
<i class="fa-solid fa-calendar-days"></i>
    </div>

<p><?=$pending_reserves?></p>
    
</a>
</div>



<div class="section-title" style="padding:5px;margin-top:15px;margin-bottom:10px;"> Ready to Borrow Today</div>
<div class="approved-list" id="approved-list">

</div>
<div class="pagination" id="dash-pagination">
      <p>Total Pages: <span id="dashpage-count"></span></p>
            <div class="page-buttons">
    <button id="prevToday" onclick="borrowTodayPage(-1)"><i class="fa-solid fa-chevron-left"></i></button>
    <span id="todayPage">1</span>
    <button id="nextToday" onclick="borrowTodayPage(1)"><i class="fa-solid fa-angle-right"></i></button>
</div>
</div>
