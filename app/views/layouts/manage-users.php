<div class="section-title">Manage Users</div>
<div class="users-container">

    <div class="users-header">
        <button class="btn-add" id="btn-add" type="button" onclick="showAddUserForm()">+ Add User</button>
        <form action="/app/controllers/admin/add-user.php" method="post" class="add-user-form add-user" id="addUserForm" style="display:none;">
            <h3>Add New User</h3>
            <input type="text" id="add-name" name="username" placeholder="username" required>
            <input type="email" id="add-email" name="email"placeholder="email" required>
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
                    <th class="sortable" data-column="user_id"  data-type="user">User Id <i class="fa-solid fa-sort"></i></th>
                    <th class="sortable" data-column="name"  data-type="user">Username <i class="fa-solid fa-sort"></i></th>
                    <th class="sortable" data-column="email"  data-type="user">Email <i class="fa-solid fa-sort"></i></th>
                    <th class="sortable" data-column="role"  data-type="user">Role <i class="fa-solid fa-sort"></i></th>
                    <th class="sortable" data-column="created_at"  data-type="user">Created At <i class="fa-solid fa-sort"></i></th>
                    <th >Actions</th>
                </tr>
            </thead>

            <tbody id="userTableBody">
               
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <p>Total Pages: <span id="userpage-count"></span></p>
        <div class="page-buttons">
        <button id="prevBtn" onclick="stepChange(-1)"><i class="fa-solid fa-chevron-left"></i></button>
        <span id="userpageNumber">1</span>
        <button id="nextBtn" onclick="stepChange(1)"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>


<!-- EDIT USER MODAL -->
    <form id="editUserForm" class="editForm edit-user-form" action="/app/controllers/admin/edit-user.php" method="POST">
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
    <form class="modal-content" method="post" action="/app/controllers/admin/delete-user.php">
        <h3>Are you sure?</h3>
        <p>You are about to delete this user. This action cannot be undone.</p>

        <input name="id"type="hidden" id="delete_id">

        <div class="modal-buttons">
            <button type="button"class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="submit"class="btn-delete" >Delete</button>
        </div>
</form>
</div>
