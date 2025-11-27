      const booksTable = document.getElementById("booksTable");
      const searchBooks = document.getElementById("searchBooks");
      const prevBooks = document.getElementById("prevBooks");
    const nextBooks = document.getElementById("nextBooks");
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
                    <button class="btn-edit" onclick="openEditBook('${book.book_id}','${book.title}','${book.author}','${book.category}','${book.stock}','${book.cover_image}')">Edit</button>
                    <button class="btn-delete" onclick="openDeleteBook('${book.book_id}')">Delete</button>
                </td>
          </tr>`;
      });
       const totalPages = Math.ceil(data.totalRows / 5);
        if(page === 1){
        prevBooks.disabled = true;
    } else {
        prevBooks.disabled = false;
    }
    if(page >= totalPages){
        nextBooks.disabled = true;
    } else {
        nextBooks.disabled = false;
    }
  });
}
  function showBookAddForm(){
    document.getElementById('addBookForm').style.display='block';
  }
  function hideBookAddForm(){   
    document.getElementById('addBookForm').style.display='none';
    }
    function openEditBook(id,title, author, category, stock, cover_image) {
        document.getElementById("editTitle").value = title;
        document.getElementById("editAuthor").value = author;
        document.getElementById("editCategory").value = category;
        document.getElementById("editStock").value = stock;
        // document.getElementById("editCover").value = cover_image;
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