<div class="section-title">Manage Users</div>
<div class="users-container">

    <div class="users-header">
        <button class="btn-add" id="btn-add" type="button" onclick="showAddUserForm()">+ Add User</button>
        <form action="../app/controllers/add-user.php" method="post" class="add-user-form add-user" id="addUserForm" style="display:none;">
            <h3>Add New User</h3>
            <input type="text" name="username" placeholder="username" required>
            <input type="email" name="email"placeholder="email" required>
            <input type="password" name="password"placeholder="password" required>
            <label>Role</label>
            <select  name="role">
                <option>User</option>
                <option>Admin</option>
            </select>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
                <button type="button" class="btn-close" onclick="hideAddUserForm()">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Search Bar -->
    <div class="search-box">
        <input type="text" placeholder="Search users by name,email and role" id="userSearch">
    </div>

    <!-- Users Table -->
    <div class="table-wrapper">
        <table class="users-table" id="usersTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="userTableBody">
               
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button id="prevBtn" onclick="stepChange(-1)">Previous</button>
        <span id="pageNumber">1</span>
        <button id="nextBtn" onclick="stepChange(1)">Next</button>
    </div>

</div>


<!-- EDIT USER MODAL -->
    <form id="editUserForm" class="editForm edit-user-form" action="../app/controllers/edit-user.php" method="POST">
        <h3>Edit User</h3>
        <input type="hidden" id="edit_id" name="id">
        <input name="name"type="text" placeholder="username" id="editUsername">
        <input name="email" type="email" placeholder="email" id="editEmail">
        <label>Role</label>
        <select name="role" id="editRole">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <div class="form-actions">
            <button class="btn-save">Save</button>
            <button type="button" class="btn-close" onclick="closeEditModal()">Cancel</button>
        </div>
    </form>
    
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <form class="modal-content" method="post" action="../app/controllers/delete-user.php">
        <h3>Are you sure?</h3>
        <p>You are about to delete this user. This action cannot be undone.</p>

        <input name="id"type="hidden" id="delete_id">

        <div class="modal-buttons">
            <button type="button"class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="submit"class="btn-delete" >Delete</button>
        </div>
</form>
</div>
