<div class="footer">
      <div class="footer-links">
        <a href="#" onclick="showPage('about')">About Us</a>
        <span class="divider"></span>
        <a href="#"onclick="showPage('contact')">Contact Us</a>
                <span class="divider"></span>
                        <a href="#">Privacy Policy</a>
                        <span class="divider"></span>
                        <a href="#">Terms of Service</a>


    </div>
    <p class="copyright">&copy; <?php echo date('Y')?> Books Mania. All rights reserved.</p>
</div>
</div>
<script src="../../../public/js/user-dash.js"></script>
<script> 
     <?php if (!empty($success)) : ?>
            alert("<?= $success ?>");
        <?php endif; ?>
        
  <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php endif; ?>
</script>
</body>
</html>