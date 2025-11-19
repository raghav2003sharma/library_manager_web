<?php
session_start();

// Protect admin access
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$page = $_GET['main-page'] ?? 'dashboard';
?>
<?php include "layouts/sidebar.php"; ?>

<!-- Main Section -->
<div class="main">

<?php
    $file = "layouts/$page.php";
    include $file;
    
    ?>
<!-- Additional Implementable Features -->
<!-- <div class="section-title" style="margin-top:40px;">Extra Features You Can Implement</div>
<div class="cards">
<div class="card">
<h3>Generate Reports</h3>
<p>Daily, Monthly, Yearly Reports for Library Usage</p>
</div>
<div class="card">
<h3>Notifications</h3>
<p>Send email alerts for due dates or new books</p>
</div>
<div class="card">
<h3>Activity Logs</h3>
<p>Track admin and user activities</p>
</div>
<div class="card">
<h3>Inventory Audit</h3>
<p>Check missing or damaged books</p>
</div>
</div> -->

</div>
<!-- End of Main Section -->
</div>
</body>
</html>