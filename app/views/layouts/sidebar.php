
    <div class="container">
        <!-- Sidebar -->
<div class="sidebar">
<h2>Admin Panel</h2>
<a href="index.php?page=admin-home&main-page=dashboard">Dashboard</a>
<a href="index.php?page=admin-home&main-page=manage-users">Manage Users</a>
<a href="index.php?page=admin-home&main-page=manage-books">Manage Books</a>
<a href="index.php?page=admin-home&main-page=borrowed-books">Borrowed Books</a>
<a href="index.php?page=admin-home&main-page=borrow-history" >Borrowed History</a>
<a href="#" onclick="toggleSettings()">Settings</a>
<div class="settings" >
    <!-- <a href="#">edit profile</a> -->
    <a href="#" onclick="showForm()">Change Password</a>
</div>
<a href="index.php?page=logout">Logout</a>
</div>
<div class="pass-modal">
            <div class="form-modal">
                <h1>Change Password</h1>
        <form action="../app/controllers/change-password.php" method="post" id="change-pass-form">
            <input type="password" name="cur-pass"id="current-password" placeholder="Current Password" required />
            <input type="password" name="new-pass"id="new-password" placeholder="New Password" required />
            <input type="password" name="conf-pass"id="confirm-password" placeholder="Confirm New Password" required />
            <div class="modal-actions">
                <button type="button" class="cancel-btn" onclick="closeChangePass()">Cancel</button>
                <button type="submit" class="save-btn">Update Password</button>
            </div>     
           </form>
            </div>
        </div>