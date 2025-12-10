<div class="section-title">Borrowed Books</div>



    <div class="search-box">
        <input type="text" id="searchBorrow" placeholder="Search user or book...">
    </div>
<div class="table-wrapper">

    <table class="users-table">
        <thead>
            <tr>
                <th  class="sortable" data-column="username"  data-type="borrowed">User Name <i class="fa-solid fa-sort"></i></th>
                <th class="sortable" data-column="title" data-type="borrowed">Book Title <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="status" data-type="borrowed">Status <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="borrow_date" data-type="borrowed">Borrow Date <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="due_date" data-type="borrowed">Due Date <i class="fa-solid fa-sort"></i></th>
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
