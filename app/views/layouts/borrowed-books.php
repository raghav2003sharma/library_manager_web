
<div class="borrow-books">

<div class="section-title">Borrowed Books</div>

    <div class="search-box">
        <input type="text" id="searchBorrow" placeholder="Search user or book...">
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
                <th>Return Book</th>
            </tr>
        </thead>

        <tbody id="borrowTable">

            
        </tbody>
    </table>

</div>
<div class="pagination">
    <button id="prevBorrow" onclick="borrowPage(-1)">Previous</button>
    <span id="pageNumber">1</span>
    <button id="nextBorrow" onclick="borrowPage(1)">Next</button>
</div>
</div>
