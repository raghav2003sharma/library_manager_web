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
function loadCategory(button){
      document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.remove('active');
    });
    button.classList.add('active');
}
function fetchAvailableBooks(category="all",query=""){
    fetch(`/app/controllers/fetch-available.php?category=${category}&q=${query}`)
    .then(response => response.json())
    .then(data => {
        const booksContainer = document.getElementById('avail');
        booksContainer.innerHTML = '';
        data.length === 0 && (booksContainer.innerHTML = '<p>No books available in this category.</p>');
        data.forEach(book => {
            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-card');
            bookDiv.innerHTML = `
                <img src="${book.cover_image}" />
                <div class="title">${book.title}</div>
            <div class="author">${book.author}</div>
            <div class="category-tag">${book.category}</div>
            <div class="availability available">Available(${book.stock})</div>
            <div  class="preview-link">
                <a href="${book.preview_link}" target="_blank">Preview
                </a>
            </div>
            `;
            booksContainer.appendChild(bookDiv);
        })
    })
}
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
    fetchAvailableBooks("all",query);

}