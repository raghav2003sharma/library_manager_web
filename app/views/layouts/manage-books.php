<div class="section-title">Manage Books</div>

<div class="books-section">

<button type="button" onclick="showBookAddForm()" class="btn-add">+ Add Book</button>
<form action="/app/controllers/add-book.php" enctype="multipart/form-data" method="post" class="add-user-form " id="addBookForm" style="display:none;">
            <h3>Add New Book</h3>
            <input type="text" name="title" placeholder="book title" required>
            <input type="text" name="author"  placeholder="author" required>
            <input type="text" name="description"  placeholder="description" >
            <!-- <input type="text" name="category" placeholder="category" required> -->
               <label for="category">Category</label>
             <select name="category" id="addCategory" required>
                <option value="fiction">Fiction</option>
                <option value="sci-fi">Sci-Fi</option>
                <option value="history">History</option>
                <option value="helf-help">Self-Help</option>
                <option value="education">Education</option>
            </select>
           <input type="number" name="stock"  placeholder="stock" required>
           <label>Cover Image</label>
            <input id ="imageInput"type="file" name="cover"  placeholder="cover image" accept="image/*" onchange="previewImage(event)">
            <img id="preview" src="" alt="Image Preview" style="display:none; width:150px; margin-top:10px;">

            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
                <button type="button" class="btn-close" onclick="hideBookAddForm()">Cancel</button>
            </div>
        </form>
<div class="search-box">
    <input type="text" id="searchBooks" placeholder="Search books...">
</div>
<div class="table-wrapper">
<table class="users-table">
    <thead>
        <tr>
            <th class="sortable" data-column="book_id" data-type="book">Book Id <i class="fa-solid fa-sort"></i></th>
            <th class="sortable" data-column="title" data-type="book">Book Title <i class="fa-solid fa-sort"></i></th>
            <th class="sortable" data-column="author" data-type="book">Author <i class="fa-solid fa-sort"></i></th>
            <th class="sortable" data-column="category" data-type="book">Category <i class="fa-solid fa-sort"></i></th>
            <th class="sortable" data-column="stock" data-type="book">Stock <i class="fa-solid fa-sort"></i></th>
            <th class="sortable" data-column="created_at" data-type="book">Created At <i class="fa-solid fa-sort"></i></th>
            <th>Cover-image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="booksTable">
        <!-- Filled dynamically -->
    </tbody>
</table>

<div class="pagination">
            <p>Total Pages: <span id="bookpage-count"></span></p>
            <div class="page-buttons">
    <button id="prevBooks" onclick="bookStep(-1)"><i class="fa-solid fa-chevron-left"></i></button>
    <span id="bookpageNumber">1</span>
    <button id="nextBooks" onclick="bookStep(1)"><i class="fa-solid fa-angle-right"></i></button>
            </div>
</div>
 <form style="display:none;" id="editBookForm" enctype="multipart/form-data" class="editForm" action="/app/controllers/edit-book.php" method="POST">
        <h3>Edit Book</h3>
        <input type="hidden" id="editBook_id" name="book_id">
        <input type="text" name="title" placeholder="book title" id="editTitle"required>
            <input type="text" name="author"  placeholder="author" id="editAuthor"required>
            <input id="editDescription" type="text" name="description"  placeholder="description" >

            <!-- <input type="text" name="category" placeholder="category"id="editCategory" required> -->
             <label for="category"> Edit Category</label>
             <select name="category" id="editCategory" required>
                <option value="fiction">Fiction</option>
                <option value="sci-fi">Sci-Fi</option>
                <option value="history">History</option>
                <option value="self-help">Self-Help</option>
                <option value="education">Education</option>
            </select>
           <input type="number" name="stock"  placeholder="stock"id="editStock" required>
           <label> Edit Cover Image</label>
            <input type="file" name="cover" id="editCover"  placeholder="cover image" accept="image/*" onchange="editPreview(event)">
                <img id="edit-preview" src="" alt="Image Preview" style="display:none; width:150px; margin-top:10px;">
        <div class="form-actions">
            <button type="submit"class="btn-save">Save</button>
            <button type="button" class="btn-close" onclick="closeEditBook()">Cancel</button>
        </div>
    </form>
    
<!-- Delete Modal -->
<div id="deleteBookModal" class="modal">
    <form method="post" action="/app/controllers/delete-book.php" class="modal-content">
        <h3>Delete Book?</h3>
        <p>This action cannot be undone.</p>

        <input type="hidden" name="id" id="delete_id">

        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeDeleteBook()">Cancel</button>
            <button type="submit"class="btn-confirm" >Delete</button>
        </div>
</form>
</div>
</div>
