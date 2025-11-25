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
$desc = $_POST['description'] ?? null;
$stock = intval($_POST['stock']);
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


$stmt = $conn->prepare("INSERT INTO books (title,author,description,category,stock,cover_image) VALUES (?, ?, ?,?, ?,?)");
$stmt->bind_param("ssssis",$title, $author,$desc, $category, $stock, $coverImagePath);
if($stmt->execute()){
    $_SESSION['success'] = "Book added successfully.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
} else {
    $_SESSION['error'] = "Error adding user. Email may already exist.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}
?>