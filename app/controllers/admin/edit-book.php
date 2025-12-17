<?php
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/Book.php";
$book = new Book();
if(
    empty($_POST['title']) ||
    empty($_POST['author']) ||
    empty($_POST['category'])||
        empty($_POST['book_id'])

){
    redirectBack( "error", "All fields are required.");
}
$title = trim($_POST['title']);
$author = trim($_POST['author']);
$category = trim($_POST['category']);
$stock = intval($_POST['stock']);
$id = intval($_POST['book_id']);
$desc = $_POST['description'] ?? null;

$cover = $_FILES['cover'];
if (strlen($title) < 2 || strlen($title) > 100) {
                redirectBack( "error", "Title must be between 2 and 100 characters.");

}

// Author validation
if (!preg_match("/^[A-Za-z\s]+$/", $author)) {
        redirectBack( "error", "Author name can contain only letters & spaces.");

}
if (strlen($author) < 2 || strlen($author) > 50) {
               redirectBack( "error", "Author must be between 2 and 50 characters.");

}



// Description limit
if (strlen($desc) > 1000) {
        redirectBack( "error", "Description cannot exceed 1000 characters.");

}

// Stock validation
if (!is_numeric($_POST['stock']) || $stock < 0) {
            redirectBack( "error", "Stock must be a valid non-negative number.");

}
if($stock > 100){
            redirectBack( "error", "Stock must not be greater than 100.");

      
}

$coverImagePath = null;
if ($cover && $cover['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../../public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileName = uniqid() . '_' . basename($cover['name']);
    $targetFilePath = $uploadDir . $fileName;
    if (move_uploaded_file($cover['tmp_name'], $targetFilePath)) {// move from temp folder to specified folder
        $coverImagePath = '/public/uploads/' . $fileName;
    } else {
         redirectBack( "error", "Failed to upload cover image.");

    }
}
// CHECK IF Book ALREADY EXISTS (BUT NOT FOR CURRENT book)
$checkResult = $book->getOtherBooks($title,$author,$id);
if ($checkResult->num_rows > 0) {
        redirectBack( "error", "Same book already exists.");

}
$result = $book->updateBook($title, $author, $category, $stock, $coverImagePath,$desc,$id);
if($result){
            redirectBack(  "success", "Book updated successfully.");

} else {
        redirectBack( "error", "Error updating book.");

}
?>