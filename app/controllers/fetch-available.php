<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit =10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
function getBookPreview($conn,$bookId,$title,$preview_link_db) {
      if (!empty($preview_link_db)) {
        return [
            "preview_link" => $preview_link_db
        ];
    }

    $titleEncoded = urlencode($title);
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=intitle:$titleEncoded";

    $response = @file_get_contents($apiUrl);
    if (!$response) return [ "preview_link" => null, "thumbnail" => null ];

    $data = json_decode($response, true);

    if (empty($data['items'][0]['volumeInfo'])) {
        return [ "preview_link" => null, "thumbnail" => null ];
    }

    $info = $data['items'][0]['volumeInfo'];
    $previewLink = $info['previewLink'] ?? null;
    $stmt = $conn->prepare("UPDATE books SET preview_link=? WHERE book_id=?");
    $stmt->bind_param("si", $previewLink, $bookId);
    $stmt->execute();
    return [
        "preview_link" => $previewLink ?? null
    ];
}

$category = isset($_GET['category']) ? $_GET['category'] : null;
if($category === "all" || $category === null){
    $sql = "SELECT book_id, title, author, category, stock, cover_image,preview_link FROM books WHERE stock > 0 AND (title LIKE ? OR author LIKE ?) LIMIT ? OFFSET ?";
} else {
    $sql = "SELECT book_id, title, author, category, stock, cover_image,preview_link FROM books WHERE stock > 0 AND category = ? AND (title LIKE ? OR author LIKE ?) LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $category, $search, $search, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
              $previewData = getBookPreview($conn,$row['book_id'],$row['title'],$row['preview_link']);
        $row['preview_link'] = $previewData['preview_link'];
            $books[] = $row;
        }
    }
    echo json_encode($books);
    exit;
}
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $search, $search, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$books = [];    
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
           $previewData =getBookPreview($conn,$row['book_id'],$row['title'],$row['preview_link']);

        $row['preview_link'] = $previewData['preview_link'];
        $books[] = $row;
    }
}
echo json_encode($books);