

<h2>Reservations</h2>
<div class="reserve-filters">
     <button class="filter-btn active" data-filter="pending" onclick="setFilter(this)">Pending</button>
    <button class="filter-btn" data-filter="approved" onclick="setFilter(this)">Approved</button>
    <button class="filter-btn" data-filter="rejected" onclick="setFilter(this)">Rejected</button>
</div>
<div class="search-box">
    <input type="text" name="search" placeholder="Search reservations" id="reserveSearch">
</div>
<div class="table-wrapper">
<table id="reserveTable" class="users-table">
    <thead>
<tr>
    <th>id</th>
    <th>Username</th>
    <th>Email</th>
    <th>Title</th>
    <th>borrow_date</th>
    <th>Cover-image</th>
    <th>Action</th>
</tr>
    </thead>
  <tbody id="reserveTableBody"></tbody>
</table>
</div>
<div class="pagination">
 <button id="resPrevBtn" onclick="resStepChange(-1)">Previous</button>
        <span id="pageNumber">1</span>
        <button id="resNextBtn" onclick="resStepChange(1)">Next</button>
</div>