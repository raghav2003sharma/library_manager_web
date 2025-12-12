<div class="footer">
      <div class="footer-links">
        <a href="/public/index.php?page=about" >About Us</a>
        <span class="divider"></span>
        <a href="/public/index.php?page=contact">Contact Us</a>
                <span class="divider"></span>
                        <a href="#">Privacy Policy</a>
                        <span class="divider"></span>
                        <a href="#">Terms of Service</a>


    </div>
    <p class="copyright">&copy; <?php echo date('Y')?> Books Mania. All rights reserved.</p>
</div>
</div>
    <div id="custom-alert" class="alert-box"></div>

<script src="../../../public/js/user-dash.js"></script>
    <script src="../../../public/js/validation.js"></script>

<script> 
function showAlert(message, type) {
    const alertBox = document.getElementById("custom-alert");
    alertBox.innerHTML = message;
    alertBox.className = "alert-box alert-" + type;
    alertBox.style.display = "block";

    setTimeout(() => {
        alertBox.style.display = "none";
    }, 3500);
}
   <?php if (!empty($success)) : ?>
    //alert("");
    showAlert("<?= $success ?>","success");
<?php endif; ?>

<?php if (!empty($error)) : ?>
    //alert("");
        showAlert("<?= $error ?>","error");

<?php endif; ?>
</script>
</body>
</html>