<?php include "layouts/header.php"; ?>

<div id="contact">
    <h1>Contact Us</h1>
        <div id="contact-message"></div>

    <form id="contactForm" class="contact-frm contact-form"  method="POST" action="/app/controllers/user/contact-form.php">
        <input name="username"type="text" placeholder="Enter your name" required>

        <input name="email"type="email" placeholder="Enter your email" required>

        <textarea name="message" placeholder="Write your message..." required></textarea>

        <button type="submit">Send Message</button>
    </form>
</div>



<?php include "layouts/footer.php"; ?>
