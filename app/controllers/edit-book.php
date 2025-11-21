<?php
session_start();
require_once "../../config/db.php";
if(
    empty($_POST['title']) ||
    empty($_POST['author']) ||
    empty($_POST['category']) ||
        empty($_POST['stock'])
){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}
$title = trim($_POST['title']);
$author = trim($_POST['author']);
$category = trim($_POST['category']);
$stock = intval($_POST['stock']);
$id = intval($_POST['book_id']);
$cover = $_FILES['cover'];
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
        $_SESSION['error'] = "Failed to upload cover image.";
        header("Location: /public/index.php?page=admin-home&main-page=manage-books");
        exit;
    }
}
if($coverImagePath === null){
    $stmt = $conn->prepare("UPDATE books SET title = ?,author=?,category=?,stock=? where book_id=?");
    $stmt->bind_param("sssii",$title, $author, $category, $stock,$id);
    
}
else{
$stmt = $conn->prepare("UPDATE books SET title=?,author=?,category=?,stock=?,cover_image=? where book_id=?");
$stmt->bind_param("sssisi",$title, $author, $category, $stock, $coverImagePath,$id);
}
if($stmt->execute()){
    $_SESSION['success'] = "Book updated successfully.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
} else {
    $_SESSION['error'] = "Error updating book";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}
?>