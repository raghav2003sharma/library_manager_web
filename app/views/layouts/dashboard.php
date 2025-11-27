<?php
require_once "../config/db.php";

      $users =  $conn->query("SELECT COUNT(*) AS total_users FROM users");
      $books = $conn->query("SELECT COUNT(*) AS total_books FROM books");
      $borrowed = $conn->query("SELECT COUNT(*) AS borrowed_books FROM borrow_records WHERE return_date IS NULL");
      $overdue = $conn->query("SELECT COUNT(*) AS overdue_books FROM borrow_records WHERE return_date IS NULL AND due_date < CURDATE()");
      $total_users = $users->fetch_assoc()['total_users'];
        $total_books = $books->fetch_assoc()['total_books'];
        $borrowed_books = $borrowed->fetch_assoc()['borrowed_books'];
        $overdue_books = $overdue->fetch_assoc()['overdue_books'];
?>

<div class="section-title">Dashboard</div>
<div class="cards">
<div class="card">
  <div class="card-icon">
<h3>Total Users</h3>
<i class="fa-regular fa-user"></i>
  </div>
<p><?= $total_users ?></p>
</div>
<div class="card">
  <div class="card-icon">
<h3>Total Books</h3>
<i class="fa-solid fa-book"></i>
  </div>
<p><?= $total_books ?></p>
</div>
<div class="card">
<h3>Borrowed Books</h3>
<p><?= $borrowed_books ?></p>
</div>
<div class="card">
<h3>Over due books</h3>
<p><?=$overdue_books?></p>
</div>
</div>


<!-- Borrow Record Form -->
<div class="form-box">
<h2>Enter Borrow Record</h2>
<form id="borrow-form" method="post" action="../app/controllers/borrow-book.php">
<input name="b-email" type="text" placeholder="Enter User email" />

<input name="b-title" type="text" placeholder="Enter Book title" />
<label>Borrow Date</label>
<input name="b-date" type="date" />


<button type="submit">Submit Borrow Record</button>
</form>
</div>


<!-- Return Record Form -->
<div class="form-box" >
<h2>Enter Return Record</h2>
<form id=return-form method="post" action="../app/controllers/return-book.php">
<input type="text" name="r-email" placeholder="Enter User email" />

<input type="text" name="r-title" placeholder="Enter Book title" />

<label>Return Date</label>
<input name="r-date" type="date" />

<button type="submit">Submit Return Record</button>
</form>
</div>
<div class="form-box" >
<h2>Pay fine </h2>
<form id="fineForm" method="POST" action="../app/controllers/pay-fine.php">
    <input type="email" name="email" placeholder="User Email" required>
    <input type="text" name="title" placeholder="Book Title"required>
    <input type="number" step="0.01" placeholder="fine amount paid" name="amount" required>

    <button type="submit">Submit</button>
</form>
</div>