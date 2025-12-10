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
}
?>
