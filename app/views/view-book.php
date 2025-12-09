<?php include "layouts/header.php"; ?>
<?php
require_once "../config/db.php";
$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$img = $book['cover_image'] ?? '';
$title = $book['title'] ?? '';
$author = $book['author'] ?? '';
$category = $book['category'] ?? '';
$desc = $book['description'] ?? '';
$stock = $book['stock'] ?? '';
$preview = $book['preview_link'] ?? '';

?>
<div id="view-book"> 
    <header>
         <div class="header-content">
             <a href="index.php?page='user-home'">
                <span ><i style="color:black;" class="fa-solid fa-arrow-left"></i>
            </span></a>
             <h1 style="font-size: 18px; margin: 0; font-weight: 600;">Book Details</h1>
             </div>
             </header>
              <main class="container">
                 <div class="book-image-wrapper">
                     <img src="<?=$img ?>" alt="Book Image">
                </div> 
                <div class="book-info">
                     <div class="title-block">
                         <h2><?= $title ?></h2>
                          <p><?= $author ?></p> 
                    </div>
                     <div class="details-row"> 
                        <div class="item">Reading Time: 14 days</div> 
                        <div class="item preview-btn">
                                <a href="<?= $preview?$preview:"#" ?>"><?php echo $preview?"Preview":"no preview available" ?></a>
                            </div>
                         </div>
                          <div class="description-section">
                                 <h3>Description</h3>
                                  <p> <?=$desc ?> </p>
                            </div>
                            <div class="info-grid">
                            <div> 
                                        <span>Genre</span>
                                         <p><?= $category ?></p>
                            </div>
                        <div> <span>Stock</span> <p class="status">Available</p> 
                    </div> 
                     </div>
                      <div class="reserve-section">
                                             <button class="reserve-btn" onclick="openReserveForm()">Reserve</button>
                                             </div>
                </div>
                 </main> 
                                          <!-- POP-UP RESERVE FORM --> 
                                             <div class="reserve-modal" id="reserveModal">
                                                 <div class="reserve-box">
                                                     <h2>Reserve Book</h2> 
                                                     <form action="../app/controllers/reserve.php" method="POST" onsubmit="return confirmReserve()"> 
                                                        <input type="hidden" name="book_id" value="<?= $id ?>">
                                                         <label>Borrow Date</label>
                                                          <input type="date" name="borrow_date" id="borrowDate" min="<?= date('Y-m-d') ?>"required>
                                                           <div class="res-actions"> 
                                                            <button type="button" class="cancel" onclick="closeReserveForm()">Cancel</button>
                                                             <button type="submit" class="confirm">Reserve</button> </div> </form> </div> </div> <!-- <div class="reserve-form" > 
                                                                <form method="Post" action="../app/controllers/reserve.php" class="footer-content"> <input type="hidden" name="book_id" value="<?= $id ?>"> 
                                                                <button type="submit" class="rent-btn">Reserve Book</button> </form> </div> --> </div> 

 <?php include "layouts/footer.php"; ?>
