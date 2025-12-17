 <?php 
 session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role']!== "user"){
$_SESSION['error'] = "Unauthorized access !";
header("Location: /user-home");
exit;
 } ?>
<?php include "layouts/header.php"; ?>
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
                         <form action="/app/controllers/user/update-reservation.php" method="POST" onsubmit="return confirmReserve()"> 
                                 <input type="hidden" name="book_id" id="hidden-bookid" >
                                 <label>Borrow Date</label>
                                 <input type="date" name="borrow_date" min="<?= date('Y-m-d') ?>"required>
                                <div class="res-actions"> 
                                     <button type="button" class="cancel" onclick="closeDateForm()">Cancel</button>
                                     <button type="submit" class="confirm">Reserve</button> </div>
                             </form> 
                            </div> 
                        </div>
     <div id="deleteReserveModal" class="modal">
    <form method="post" action="/app/controllers/user/delete-reservation.php" class="modal-content">
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

<?php include "layouts/footer.php"; ?>
