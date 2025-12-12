<?php 
require_once __DIR__ . "/../configs/dbconfig.php";

Class Book{
  private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
  
   public function getBook($title,$author){

       try {
            $stmt = $this->conn->prepare(
                "SELECT book_id FROM books WHERE title = ? AND author = ?"
            );
            if (!$stmt) {
                throw new Exception($this->conn->error);
            }

            $stmt->bind_param("ss", $title, $author);
            $stmt->execute();
            return $stmt->get_result();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
   }
   public function getBookByTitle($title){
    try{
        $sql2 = "SELECT book_id FROM books WHERE title = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("s", $title);
        $stmt2->execute();
        return $stmt2->get_result();
    }catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
   }
   public function addBook($title, $author,$desc, $category, $stock, $coverImagePath){
         try {
            $stmt = $this->conn->prepare(
                "INSERT INTO books (title, author, description, category, stock, cover_image)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            if (!$stmt) {
                throw new Exception($this->conn->error);
            }

            $stmt->bind_param("ssssis", $title, $author, $desc, $category, $stock, $coverImagePath);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
   }
   public function updateBook($title, $author, $category, $stock, $coverImagePath,$desc,$id){
        try {

            if ($coverImagePath === null) {
                $stmt = $this->conn->prepare(
                    "UPDATE books 
                     SET title = ?, author = ?, category = ?, stock = ?, description = ?
                     WHERE book_id = ?"
                );
                if (!$stmt) {
                    throw new Exception($this->conn->error);
                }

                $stmt->bind_param("sssisi", $title, $author, $category, $stock, $desc, $id);

            } else {
                $stmt = $this->conn->prepare(
                    "UPDATE books 
                     SET title = ?, author = ?, category = ?, stock = ?, cover_image = ?, description = ?
                     WHERE book_id = ?"
                );
                if (!$stmt) {
                    throw new Exception($this->conn->error);
                }

                $stmt->bind_param("sssissi", $title, $author, $category, $stock, $coverImagePath, $desc, $id);
            }

            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function deleteBook($book_id){
        try {
            $stmt = $this->conn->prepare("DELETE FROM books WHERE book_id = ?");
            if (!$stmt) {
                throw new Exception($this->conn->error);
            }

            $stmt->bind_param("i", $book_id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    
    }
    public function getOtherBooks($title,$author,$book_id){
    try{
        $check = $this->conn->prepare("SELECT book_id FROM books WHERE title = ? AND author=? AND book_id != ?");
          if (!$check) {
                throw new Exception($this->conn->error);
            }
        $check->bind_param("ssi", $title,$author,$book_id);
        $check->execute();
        return $check->get_result();
    } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

public function fetchAllBooks( $search,$limit,$offset,$sort, $order){
    try{
        $sql = "SELECT book_id, title, author,description, category, stock,cover_image,created_at 
        FROM books
        WHERE title LIKE ?
           OR author LIKE ?
           OR category LIKE ? ORDER BY $sort $order limit ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $search, $search, $search,$limit,$offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    return $books;
    }catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
}
public function getAllBooksCount($search){
    try{
         $total = $this->conn->prepare("Select count(*) as total from books WHERE title LIKE ?
           OR author LIKE ? OR category LIKE ?");
         $total->bind_param("sss", $search, $search,$search);
        $total->execute();
        $res = $total->get_result();
         return $res->fetch_assoc()['total'];
    }catch (Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
}
function getBookPreview($bookId,$title,$preview_link_db) {
    try{
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
    $stmt = $this->conn->prepare("UPDATE books SET preview_link=? WHERE book_id=?");
    $stmt->bind_param("si", $previewLink, $bookId);
    $stmt->execute();
    return [
        "preview_link" => $previewLink ?? null
    ];
}catch(Exception $e) {
            error_log("error in fetching preview",$e->getMessage());
            return [];
        }
    }
public function fetchAvailableBooks($category,$search, $limit, $offset){
    try{
        if($category === "all" || $category === null){
    $sql = "SELECT book_id, title, author, category,description, stock, cover_image,preview_link FROM books WHERE stock > 0 AND (title LIKE ? OR author LIKE ?) LIMIT ? OFFSET ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssii", $search, $search, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT book_id, title, author, category,description, stock, cover_image,preview_link FROM books WHERE stock > 0 AND category = ? AND (title LIKE ? OR author LIKE ?) LIMIT ? OFFSET ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssii", $category, $search, $search, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

$books = [];    
    while ($row = $result->fetch_assoc()) {
           $previewData =$this->getBookPreview($row['book_id'],$row['title'],$row['preview_link']);

        $row['preview_link'] = $previewData['preview_link'];
        $books[] = $row;
    
    }
    return $books;
}catch (Exception $e) {
            error_log( "error in fetching available books",$e->getMessage());
            return [];
        }
}
public function getBookSuggestions($q,$c){
    try{
        if($c==="all"){
             $sql = "SELECT title FROM books WHERE title LIKE ? LIMIT 10";
    $stmt = $this->conn->prepare($sql);
    $search = "%$q%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
        }else{
    $sql = "SELECT title FROM books WHERE title LIKE ? AND category =? LIMIT 10";
    $stmt = $this->conn->prepare($sql);
    $search = "%$q%";
    $stmt->bind_param("ss", $search,$c);
    $stmt->execute();
        }

    $result = $stmt->get_result();
    $suggestions = [];

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
    return $suggestions;
    }catch (Exception $e) {
            error_log( "error in fetching suggestion books",$e->getMessage());
            return [];
        }
}
}
?>
