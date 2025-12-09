<div class="section-title">Pay fines</div>
<div class="fines-container">

    <!-- Search Bar -->
    <div class="search-box">
        <input type="text" placeholder="Search by username, email, or title" id="fineSearch">
    </div>

    <!-- Fines Table -->
    <div class="table-wrapper">
        <table class="users-table" id="finesTable">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Fine Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="fineTableBody">
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button id="prevFineBtn" onclick="changeFinePage(-1)">Previous</button>
        <span id="finePageNumber">1</span>
        <button id="nextFineBtn" onclick="changeFinePage(1)">Next</button>
    </div>
</div>

<!-- PAY FINE MODAL -->
<form id="payFineForm" class="editForm pay-fine-form" action="../app/controllers/pay-fine.php" method="POST">
    <h3>Pay Fine</h3>

    <input type="hidden" id="fine_email" name="fine_email">
        <input type="hidden" id="fine_title" name="fine_title">
            <input type="hidden" id="fine_amount" name="fine_amount">
    <p><strong>User:</strong> <span id="fineUsername"></span></p>
    <p><strong>Email:</strong> <span id="fineEmail"></span></p>
    <p><strong>Book:</strong> <span id="fineBook"></span></p>
    <p><strong>Fine Amount:</strong> ₹<span id="fineAmount"></span></p>

    <div class="form-actions">
        <button  type="submit" class="btn-save">Pay Now</button>
        <button type="button" class="btn-close" onclick="closePayFineModal()">Cancel</button>
    </div>
</form>

