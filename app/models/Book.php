<?php 
require_once __DIR__ . "/../configs/dbconfig.php";

Class Book{
  private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
  
   public function getBook($title,$author){

        $stmt = $this->conn->prepare("SELECT book_id FROM books WHERE title =? AND author =?");
         $stmt->bind_param("ss", $title,$author);
        $stmt->execute();
        return $stmt->get_result();
   }
   public function addBook($title, $author,$desc, $category, $stock, $coverImagePath){
        $stmt = $this->conn->prepare("INSERT INTO books (title,author,description,category,stock,cover_image) VALUES (?, ?, ?,?, ?,?)");
        $stmt->bind_param("ssssis",$title, $author,$desc, $category, $stock, $coverImagePath);
        return $stmt->execute();
   }
   public function updateBook($title, $author, $category, $stock, $coverImagePath,$desc,$id){
        if($coverImagePath === null){
        $stmt = $this->conn->prepare("UPDATE books SET title = ?,author=?,category=?,stock=?,description=? where book_id=?");
        $stmt->bind_param("sssisi",$title, $author, $category, $stock,$desc,$id);
        }else{
        $stmt = $this->conn->prepare("UPDATE books SET title=?,author=?,category=?,stock=?,cover_image=?,description=? where book_id=?");
        $stmt->bind_param("sssissi",$title, $author, $category, $stock, $coverImagePath,$desc,$id);
        }
        return $stmt->execute();
    }
    public function deleteBook($book_id){
        $sql = "DELETE FROM books WHERE book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        return $stmt->execute();
    }
}
?>
