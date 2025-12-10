

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
    <th class="sortable" data-column="id"  data-type="reserves"> id <i class="fa-solid fa-sort"></i></th>
    <th  class="sortable" data-column="username"  data-type="reserves">User Name <i class="fa-solid fa-sort"></i></th>  
     <th class="sortable" data-column="email"  data-type="reserves">Email <i class="fa-solid fa-sort"></i></th>
        <th class="sortable" data-column="title" data-type="reserves">Book Title <i class="fa-solid fa-sort"></i></th>
            <th class="sortable" data-column="borrow_date" data-type="reserves">Borrow Date <i class="fa-solid fa-sort"></i></th>
    <th >Cover-image </th>
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