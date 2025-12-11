<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/Book.php";
$book = new Book();
if(
    empty($_POST['title']) ||
    empty($_POST['author']) ||
    empty($_POST['category']) ){
    redirectBack( "error", "All fields are required.");

}
$title = trim($_POST['title']);
$author = trim($_POST['author']);
$category = trim($_POST['category']);
$desc = $_POST['description'] ?? null;
$stock = intval($_POST['stock']);
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
    $uploadDir = '../../public/uploads/';
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
// check if book already exists
$exists = $book->getBook($title,$author);
if($exists->num_rows > 0){
                redirectBack( "error", "Same book already exists.");

}
//insert new book 
$result = $book->addBook($title, $author,$desc, $category, $stock, $coverImagePath);
if($result){
                    redirectBack(  "success", "Book added successfully.");

} else {
             redirectBack( "error", "Error adding book.");


    
}
?>