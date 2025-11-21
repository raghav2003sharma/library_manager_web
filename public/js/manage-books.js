      const booksTable = document.getElementById("booksTable");
if(booksTable){
fetch('../app/controllers/fetch-books.php')
  .then(res => res.json())
  .then(books => {
      booksTable.innerHTML = "";

      books.forEach(book => {
          booksTable.innerHTML += `
          <tr>
              <td>${book.book_id}</td>
              <td>${book.title}</td>
              <td>${book.author}</td>
              <td>${book.category}</td>
              <td>${book.stock}</td>
              <td>${book.created_at}</td>
                <td>
                    <button class="btn-edit" onclick="openEditBook('${book.book_id}','${book.title}','${book.author}','${book.category}','${book.stock}','${book.cover_image}')">Edit</button>
                    <button class="btn-delete" onclick="openDeleteBook('${book.book_id}')">Delete</button>
                </td>
          </tr>`;
      });
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