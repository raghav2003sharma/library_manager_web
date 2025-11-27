
<div class="borrow-books">

<div class="section-title">Borrow History</div>

    <div class="search-box">
        <input type="text" id="searchHistory" placeholder="Search user or book...">
    </div>
<div class="table-wrapper">

    <table class="users-table">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Book Title</th>
                <th>Status</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
            </tr>
        </thead>

        <tbody id="borrowHistory">

            
        </tbody>
    </table>

</div>
<div class="pagination">
    <button id="prevPage" onclick="historyPage(-1)">Previous</button>
    <span id="pageNumber">1</span>
    <button id="nextPage" onclick="historyPage(1)">Next</button>
</div>
</div>
