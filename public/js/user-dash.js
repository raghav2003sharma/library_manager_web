let currentPage = 1;
let isLoading = false;
let hasMore = true;
let currentCategory = "all";
let currentQuery = "";
window.onload = function () {
    const savedPage = localStorage.getItem("activePage") || "home";

    if (savedPage === "home") showAvailable();
    if (savedPage === "borrow") showBorrowed();
    if (savedPage === "settings") toggleSettings();
};
function toggleSettings(){
    const dropdown = document.querySelector('.dropdown');
    if(dropdown.style.display === "none" || dropdown.style.display === ""){
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
    }
}
fetchAvailableBooks();
fetchBorrowedBooks();
function loadCategory(button,category){
    currentCategory = category;  
    currentPage = 1;             // reset to page 1
    hasMore = true;             
    isLoading = false;
    document.querySelectorAll('.filter-btn').forEach(btn => {
                                                    btn.classList.remove('active');
    });
    button.classList.add('active');
}


function fetchAvailableBooks(category="all",query="",page=1,append=false){
           if (isLoading || !hasMore) return;
            isLoading = true;
    fetch(`/app/controllers/fetch-available.php?category=${category}&q=${query}&page=${page}`)
    .then(response => response.json())
    .then(data => {
          const booksContainer = document.getElementById('avail');

            // Clear container only on the first page
            if (!append) {
                booksContainer.innerHTML = '';
                currentPage = 1;
                hasMore = true;
            }

            if (data.length === 0) {
                hasMore = false; // No more data
                return;
            }
        // data.length === 0 && (booksContainer.innerHTML = '<p>No books available in this category.</p>');
        data.forEach(book => {
            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-card');
            bookDiv.innerHTML = `
                <a  href="/public/index.php?page=view-book&id=${book.id}">
                <img src="${book.cover_image}" />
                <div class="title">${book.title}</div>
            <div class="author">${book.author}</div>
            <div class="category-tag">${book.category}</div>
            <div class="availability available">Available(${book.stock})</div>
            <div  class="preview-link">
                <a href="${book.preview_link?book.preview_link:'#'}" target="_blank"> ${book.preview_link?"Preview":"no preview"}
                </a>
            </div>
                </a>
            `;
            booksContainer.appendChild(bookDiv);
        })
                    isLoading = false;

    })
}

window.addEventListener('scroll', () => {
    const bottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 500;

    if (bottom && !isLoading && hasMore) {
        currentPage++;
        fetchAvailableBooks(currentCategory,currentQuery, currentPage, true);
    }
});
function fetchBorrowedBooks(){
    fetch('/app/controllers/fetch-borrowed.php',{
         
        method: "GET",
        credentials: "include"  
    })
    .then(response => response.json())
    .then(data => {
        const borrowedContainer = document.getElementById('borrow');
        borrowedContainer.innerHTML = '';
        data.forEach(book => {
            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-card');
            bookDiv.innerHTML = `
                <img src="${book.cover_image}" />
                <div class="title">${book.title}</div>
            <div class="author">${book.author}</div>
            <div class="category-tag">${book.category}</div>
            <div class="due-date">Borrow-date: ${book.borrow_date}</div>
              <div class="due-date">Due: ${book.due_date}</div>
            `;
            borrowedContainer.appendChild(bookDiv);
        })
    })
}
function showForm(){
    const form = document.querySelector(".pass-modal");
    if(form.style.display === "none" || form.style.display === ""){
        form.style.display = "flex";
    } else {
        form.style.display = "none";
    }
}
function closeChangePass(){
    document.querySelector(".pass-modal").style.display = "none";
}
function searchBooks(){
    const query = document.getElementById("availBooks").value.trim();
      currentPage = 1;
    hasMore = true;
    isLoading = false;
        fetchAvailableBooks(currentCategory,query,currentPage,false);


}
function showAvailable() {
  document.getElementById("home-section").style.display = "block";
    document.getElementById("borrowed-section").style.display = "none";
}

function showBorrowed() {
    document.getElementById("home-section").style.display = "none";
    document.getElementById("borrowed-section").style.display = "block";
}
function showPage(page) {
    localStorage.setItem("activePage", page);

    if (page === "home") showAvailable();
    if (page === "borrow") showBorrowed();
    if (page === "settings") toggleSettings();
}
function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("active");
}
function autoSearch(query) {
    if (query.length < 1) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    }

    fetch("/app/controllers/search-suggestions.php?q=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            let html = "";

            data.forEach(book => {
                html += `<div class="suggest-item" onclick="selectSuggestion('${book.title}')">
                            ${book.title}
                         </div>`;
            });

            document.getElementById("suggestions").innerHTML = html;
        });
}
function selectSuggestion(title) {
    document.getElementById("search").value = title;
    document.getElementById("suggestions").innerHTML = "";
}