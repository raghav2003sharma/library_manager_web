

<div class="section-title">Borrow History</div>

    <div class="search-box">
        <input type="text" id="searchHistory" placeholder="Search user or book...">
    </div>
<div class="table-wrapper">

    <table id="historyTable"class="users-table">
        <thead>
            <tr>
                <th class="sortable" data-column="username" data-type="history">User Name <i class="fa-solid fa-sort"></i></th>
                <th class="sortable" data-column="title"data-type="history">Book Title <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="status"data-type="history">Status <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="borrow_date"data-type="history">Borrow Date <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="due_date"data-type="history">Due Date <i class="fa-solid fa-sort"></i></th>
                <th class="sortable" data-column="return_date"data-type="history">Return Date <i class="fa-solid fa-sort"></i></th>
            </tr>
        </thead>

        <tbody id="borrowHistory">

            
        </tbody>
    </table>

</div>
<div class="pagination">
    <button id="prevPage" onclick="historyPage(-1)">Previous</button>
    <span id="historypageNumber">1</span>
    <button id="nextPage" onclick="historyPage(1)">Next</button>
</div>
