<div class="section-title">Manage Users</div>
<div class="users-container">

    <div class="users-header">
        <button class="btn-add" id="btn-add">+ Add User</button>
        <form action="../app/controllers/add-user.php" method="post" class="add-user-form" id="addUserForm" style="display:none;">
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
        <input type="text" placeholder="Search users..." id="userSearch">
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

            <tbody id="tableBody">
               
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button id="prevBtn">Previous</button>
        <span id="pageNumber">1</span>
        <button id="nextBtn">Next</button>
    </div>

</div>


<!-- EDIT USER MODAL -->
    <form id="editUserForm" >
        <h3>Edit User</h3>
        <input type="text" placeholder="username" id="editUsername">
        <input type="email" placeholder="email" id="editEmail">
        <label>Role</label>
        <select id="editRole">
            <option>User</option>
            <option>Admin</option>
        </select>
        <div class="form-actions">
            <button class="btn-save">Save</button>
            <button type="button" class="btn-close" onclick="closeEditModal()">Cancel</button>
        </div>
    </form>
    
</div>
