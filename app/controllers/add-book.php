<?php
session_start();
require_once "../../config/db.php";
if(
    empty($_POST['title']) ||
    empty($_POST['author']) ||
    empty($_POST['category']) ){
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
if (strlen($title) < 2 || strlen($title) > 100) {
    $_SESSION['error'] = "Title must be between 2 and 100 characters.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}

// Author validation
if (!preg_match("/^[A-Za-z\s]+$/", $author)) {
    $_SESSION['error'] = "Author name can contain only letters & spaces.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}
if (strlen($author) < 2 || strlen($author) > 50) {
    $_SESSION['error'] = "Author must be between 2 and 50 characters.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}

// Allowed categories
// $validCategories = ['fiction','sci-fi','history','self-help','education'];
// if (!in_array($category, $validCategories)) {
//     $_SESSION['error'] = "Invalid category.";
//     header("Location: /public/index.php?page=admin-home&main-page=manage-books");
//     exit;
// }

// Description limit
if (strlen($desc) > 1000) {
    $_SESSION['error'] = "Description cannot exceed 1000 characters.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}

// Stock validation
if (!is_numeric($_POST['stock']) || $stock < 0) {
    $_SESSION['error'] = "Stock must be a valid non-negative number.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
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