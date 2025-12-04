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
<i class="fa-solid fa-users"></i>  </div>
<p><?= $total_users ?></p>
</div>
<div class="card">
  <div class="card-icon">
<h3>Total Books</h3>
<i class="fa-solid fa-layer-group"></i>  </div>
<p><?= $total_books ?></p>
</div>
<div class="card">
    <div class="card-icon">

<h3>Borrowed Books</h3>
<i class="fa-solid fa-address-book"></i>
    </div>

<p><?= $borrowed_books ?></p>
</div>
<div class="card">
    <div class="card-icon">
<h3>Over due books</h3>
<i class="fa-solid fa-calendar-days"></i>
    </div>

<p><?=$overdue_books?></p>
    
</div>
</div>

<div class="form-container">
<!-- Borrow Record Form -->
<div class="form-box">
<h2>Enter Borrow Record</h2>
<form id="borrow-form" method="post" action="../app/controllers/borrow-book.php">
<input id="borrow-email"  name="b-email" type="text" placeholder="Enter User email" />

<input name="b-title" list="book-suggestions" placeholder=" Book title" />
<datalist id="book-suggestions" class="suggestions-box"></datalist>

<label>Borrow Date</label>
<input name="b-date" type="date" />


<button type="submit">Submit Borrow Record</button>
</form>
</div>


<!-- Return Record Form -->
<div class="form-box" >
<h2>Enter Return Record</h2>
<form id=return-form method="post" action="../app/controllers/return-book.php">
<input type="text" id="return-email"name="r-email" placeholder="Enter User email" />
<input list="return-suggestions" name="r-title" placeholder=" Book title" />
<datalist id="return-suggestions" class="suggestions-box"></datalist>
<label>Return Date</label>
<input name="r-date" type="date" />
<button type="submit">Submit Return Record</button>
</form>
</div>
<!-- pay fine  -->
<div class="form-box" >
<h2>Pay fine </h2>
<form id="fineForm" method="POST" action="../app/controllers/pay-fine.php">
    <input type="email"id="fine-email" name="email" placeholder="User Email" required>
    <input list="fine-suggestions" id="fine-book"name="title" placeholder="Book Title"required>
    <datalist id="fine-suggestions" class="suggestions-box"></datalist>

    <input type="number" id="fine-amount" step="0.01" placeholder="fine amount paid" name="amount" required>


    <button type="submit">Submit</button>
</form>
</div>
</div>
