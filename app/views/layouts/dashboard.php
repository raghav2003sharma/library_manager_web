

<div class="section-title">Dashboard</div>
<div class="cards">
<div class="card">
<h3>Total Users</h3>
<p>124</p>
</div>
<div class="card">
<h3>Total Books</h3>
<p>540</p>
</div>
<div class="card">
<h3>Borrowed Books</h3>
<p>89</p>
</div>
<div class="card">
<h3>Overdue Books</h3>
<p>12</p>
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