<?php include "layouts/header.php"; ?>


<!-- SEARCH BAR -->
 <div id="home-section">
<div class="search-bar">
<div class="inp-box">
    <input type="text" class="search-input" id="search-inp"  oninput="autoSearch(this.value)" placeholder="Search books..." />
    <button class="clear-btn" onclick="clearSearch()"><i class="fa-solid fa-xmark"></i></button>
</div>
<button class="search-btn" onclick="searchBooks()" >Search</button>
<div id="suggestions" class="suggest-box"></div>

</div>

<div class="avail-books">

<h2 class="section-title">Discover & Reserve Available Books</h2>
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

<?php include "layouts/footer.php"; ?>
