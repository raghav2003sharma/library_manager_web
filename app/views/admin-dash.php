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
</div>
<!-- End of Main Section -->
</div>
<script src="../../public/js/manage-users.js"></script>
</body>
</html>