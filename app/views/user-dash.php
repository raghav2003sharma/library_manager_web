<?php include "layouts/header.php"; ?>


<!-- SEARCH BAR -->
 <div id="home-section">
<div class="search-bar">
<div class="inp-box">
    <input type="text" class="search-input" id="search-inp"  oninput="autoSearch(this.value)" placeholder="Search books by title or author..." />
    <button class="clear-btn" onclick="clearSearch()"><i class="fa-solid fa-xmark"></i></button>
</div>
<button class="search-btn" onclick="searchBooks()" >Search</button>
<div id="suggestions" class="suggest-box"></div>

</div>

<div class="avail-books">

<h2 class="section-title">Discover Available Books</h2>
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
<div class="avail-books" id="reservation-section">
    <h2 class="section-title">My Reservations</h2>
    
    <div class="reservation-controls">
    <select id="reservation-filter" class="filter-dropdown">
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>
</div>
<div id="reserves" class="book-grid"></div>

</div>
   <div class="edit-date-modal" id="editDateModal">
                                                    <div class="reserve-box">

                                                     <h2>Reserve Book</h2> 
                                                     <form action="../app/controllers/update-reservation.php" method="POST" onsubmit="return confirmReserve()"> 
                                                        <input type="hidden" name="book_id" id="hidden-bookid" >
                                                         <label>Borrow Date</label>
                                                          <input type="date" name="borrow_date" required>
                                                           <div class="res-actions"> 
                                                            <button type="button" class="cancel" onclick="closeDateForm()">Cancel</button>
                                                             <button type="submit" class="confirm">Reserve</button> </div>
                                                             </form> </div> </div>
                                                             <div id="deleteReserveModal" class="modal">
    <form method="post" action="../app/controllers/delete-reservation.php" class="modal-content">
        <h3>Delete Reservation?</h3>
        <p>This action cannot be undone.</p>

        <input type="hidden" name="id" id="reserve_delete_id">
            <input type="hidden" name="status" id="reserve_status">


        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeDeleteBook()">Cancel</button>
            <button type="submit"class="btn-confirm" >Delete</button>
        </div>
</form>
</div>
<?php include "layouts/aboutus.php"; ?>
<?php include "layouts/contact.php"; ?>
<?php include "layouts/footer.php"; ?>
