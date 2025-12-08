      const booksTable = document.getElementById("booksTable");
      const searchBooks = document.getElementById("searchBooks");
      const prevBooks = document.getElementById("prevBooks");
    const nextBooks = document.getElementById("nextBooks");
    const addBookForm = document.getElementById("addBookForm");
    const editBookForm = document.getElementById("editBookForm");

    if(addBookForm){
           addBookForm.addEventListener("submit", function (e) {
            const title = document.querySelector("input[name='title']");
            const author = document.querySelector("input[name='author']");
            const description = document.querySelector("input[name='description']");
            const category = document.querySelector("select[name='category']");
            const stock = document.querySelector("input[name='stock']");
            const cover = document.querySelector("input[name='cover']");

            if (!title.value.trim() || !author.value.trim() || !category.value.trim() || !stock.value.trim()) {
                alert("Title, author, category, and stock are required.");
                e.preventDefault();
                return;
            }

            if (title.value.length < 2 || title.value.length > 100) {
                alert("Title must be between 2 and 100 characters.");
                e.preventDefault();
                return;
            }

            if (author.value.length < 2 || author.value.length > 50) {
                alert("Author name must be between 2 and 50 characters.");
                e.preventDefault();
                return;
            }

            if (description.value && description.value.length > 1000) {
                alert("Description cannot exceed 1000 characters.");
                e.preventDefault();
                return;
            }

            //  Stock validation
            const stockValue = parseInt(stock.value);
            if (isNaN(stockValue) || stockValue < 0) {
                alert("Stock must be a valid number.");
                e.preventDefault();
                return;
            }
        })
    }
    if(editBookForm){
          editBookForm.addEventListener("submit", function (e) {
                    console.log("hi");
            const title =   document.getElementById("editTitle");
        const author = document.getElementById("editAuthor");
        const category = document.getElementById("editCategory");
        const stock = document.getElementById("editStock");
            const description = document.getElementById("editDescription");


            if (!title.value.trim() || !author.value.trim() || !category.value.trim() || !stock.value.trim()) {
                alert("Title, author, category, and stock are required.");
                e.preventDefault();
                return;
            }

            if (title.value.length < 2 || title.value.length > 100) {
                alert("Title must be between 2 and 100 characters.");
                e.preventDefault();
                return;
            }

            if (author.value.length < 2 || author.value.length > 50) {
                alert("Author name must be between 2 and 50 characters.");
                e.preventDefault();
                return;
            }

            if (description.value && description.value.length > 1000) {
                alert("Description cannot exceed 1000 characters.");
                e.preventDefault();
                return;
            }

            //  Stock validation
            const stockValue = parseInt(stock.value);
            if (isNaN(stockValue) || stockValue < 0) {
                alert("Stock must be a valid number.");
                e.preventDefault();
                return;
            }
        })
    }
      if(searchBooks){
      searchBooks.addEventListener("keyup", () => {
          let query = searchBooks.value.trim();
          loadBooks(query);
      });
    }
    function bookStep(step){
         const pageNumberSpan = document.getElementById("pageNumber");
    const currentPage = parseInt(pageNumberSpan.textContent);
    const newPage = currentPage + step;
    if(newPage < 1) return; 
    pageNumberSpan.textContent = newPage;
    

    loadBooks(searchBooks.value.trim(), newPage);
    
    }
if(booksTable){
    loadBooks();
}
    function loadBooks(query="",page=1){
fetch(`../app/controllers/fetch-books.php?q=${query}&page=${page}`)
  .then(res => res.json())
  .then(data => {
      booksTable.innerHTML = "";
     if (data.books.length === 0) {
                booksTable.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:15px;">No books found.</td>
                    </tr>
                `;
                return;
            }

      data.books.forEach(book => {
          booksTable.innerHTML += `
          <tr>
              <td>${book.book_id}</td>
              <td>${book.title}</td>
              <td>${book.author}</td>
              <td>${book.category}</td>
              <td>${book.stock}</td>
              <td>${book.created_at}</td>
              <td><img src="${book.cover_image}"/></td>
                <td>
                    <button class="btn-edit"  class="btn-edit edit-book-btn"
   data-id="${book.book_id}"
    data-title="${book.title.replace(/"/g, '&quot;')}"
    data-author="${book.author.replace(/"/g, '&quot;')}"
    data-category="${book.category}"
    data-stock="${book.stock}"
    data-description="${book.description.replace(/"/g, '&quot;')}"
    data-description="${book.description.replace(/"/g, '&quot;')}" onclick="openEditBook(this)"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-delete" onclick="openDeleteBook('${book.book_id}')"><i class="fa-solid fa-trash"></i></button>
                </td>
          </tr>`;
      });
      console.log("total rows",data.totalRows);
       const totalPages = Math.ceil(data.totalRows / 5);
        if(page === 1){
        prevBooks.disabled = true;
                prevBooks.classList.add("disable");


    } else {
        prevBooks.disabled = false;
        prevBooks.classList.remove("disable");

    }
    if(page >= totalPages){
        nextBooks.disabled = true;
                nextBooks.classList.add("disable");

    } else {
        nextBooks.disabled = false;
                nextBooks.classList.remove("disable");

    }
  });
}
  function showBookAddForm(){
    document.getElementById('addBookForm').style.display='block';
  }
  function hideBookAddForm(){   
    document.getElementById('addBookForm').style.display='none';
    }
    function openEditBook(btn) {
       const id = btn.dataset.id;
    const title = btn.dataset.title;
    const author = btn.dataset.author;
    const category = btn.dataset.category;
    const stock = btn.dataset.stock;
    const description = btn.dataset.description;

        document.getElementById("editTitle").value = title;
        document.getElementById("editAuthor").value = author;
        document.getElementById("editCategory").value = category;
        document.getElementById("editStock").value = stock;
        document.getElementById("editDescription").value = description;
        document.getElementById("editBook_id").value = id;

        document.getElementById("editBookForm").style.display = "block";
    }
    
    function closeEditBook() {
        document.getElementById("editBookForm").style.display = "none";
    }
    function openDeleteBook(id) {
        document.getElementById("delete_id").value = id;
        document.getElementById("deleteBookModal").style.display = "block";
    }
    
    function closeDeleteBook() {
        document.getElementById("deleteBookModal").style.display = "none";
    }