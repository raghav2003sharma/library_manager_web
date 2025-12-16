<?php 
require_once __DIR__ . "/../configs/dbconfig.php";

Class Borrow{
  private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
    public function getUserBorrowed($user_id){
        try{
            $sql = "SELECT b.book_id, b.title, b.author,b.category,b.cover_image, br.borrow_date,
            br.due_date FROM borrow_records br INNER JOIN books b on br.book_id = b.book_id
            WHERE br.user_id = ? AND br.return_date IS NULL  ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $records = [];
        while ($row = $result->fetch_assoc()) {
        $records[] = $row;
        }
        return $records;
        } catch (Exception $e) {
            error_log("Error in getting user Borrowed: " . $e->getMessage());
            return [];
        }
    }
    public function recordsToBorrow($limit,$offset){
        try{
            $stmt = $this->conn->prepare("
                SELECT r.*, u.name, u.email, b.title,b.author
                FROM reservations r
                 JOIN users u ON r.user_id = u.user_id
                 JOIN books b ON r.book_id = b.book_id
                WHERE r.status = 'approved'
                AND r.borrow_date = CURDATE() LIMIT ? OFFSET ?");
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();

            $data = [];
            $res = $stmt->get_result();

            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;

        }catch (Exception $e) {
            error_log("error in fetching records to borrow today".$e->getMessage());
            return false;
        }
    }
    public function countRecordsToBorrow(){
        try{
            $sql = "SELECT COUNT(*) as total
                FROM reservations r
                 JOIN users u ON r.user_id = u.user_id
                 JOIN books b ON r.book_id = b.book_id
                WHERE r.status = 'approved'
                AND r.borrow_date = CURDATE()";
                $result = $this->conn->query($sql);
                return $result->fetch_assoc()['total'];
        }
        catch (Exception $e) {
            error_log("error in counting records to borrow".$e->getMessage());
            return 0;
        }
    }
     public function getBorrowHistoryCount($search) {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM borrow_records br
                    INNER JOIN users u ON br.user_id = u.user_id
                    INNER JOIN books b ON br.book_id = b.book_id
                    WHERE u.name LIKE ? OR b.title LIKE ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $search, $search);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc()['total'];

        } catch (Exception $e) {
            error_log("borrow history Count Error: " . $e->getMessage());
            return 0; 
        }
    }
 public function getBorrowHistory($search, $sort, $order, $limit, $offset) {
        try {
            $sql = "SELECT u.name, b.title, br.borrow_date, br.status, 
                           br.return_date, br.due_date
                    FROM borrow_records br
                    INNER JOIN users u ON br.user_id = u.user_id
                    INNER JOIN books b ON br.book_id = b.book_id
                    WHERE u.name LIKE ? OR b.title LIKE ?
                    ORDER BY $sort $order
                    LIMIT ? OFFSET ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssii", $search, $search, $limit, $offset);
            $stmt->execute();

            $data = [];
            $res = $stmt->get_result();

            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;

        } catch (Exception $e) {
            error_log("Error in getting borrowing history: " . $e->getMessage());
            return [];
        }
    }
      public function getActiveBorrowCount($search) {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM borrow_records br
                    INNER JOIN users u ON br.user_id = u.user_id
                    INNER JOIN books b ON br.book_id = b.book_id
                    WHERE br.return_date IS NULL
                    AND (u.name LIKE ? OR b.title LIKE ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $search, $search);

            $stmt->execute();
            return $stmt->get_result()->fetch_assoc()['total'];

        } catch (Exception $e) {
            error_log("Active Borrow Count Error: " . $e->getMessage());
            return 0;
        }
    }
 public function getActiveBorrowRecords($search, $sort, $order, $limit, $offset) {
        try {
            $sql = "SELECT 
                        u.user_id, u.name,
                        b.book_id, b.title,
                        br.borrow_date, br.due_date
                    FROM borrow_records br
                    INNER JOIN users u ON br.user_id = u.user_id
                    INNER JOIN books b ON br.book_id = b.book_id
                    WHERE br.return_date IS NULL
                    AND (u.name LIKE ? OR b.title LIKE ?)
                    ORDER BY $sort $order
                    LIMIT ? OFFSET ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssii", $search, $search, $limit, $offset);
            $stmt->execute();

            $records = [];
            $res = $stmt->get_result();
            $today = new DateTime();

            while ($row = $res->fetch_assoc()) {

                $dueDate = new DateTime($row['due_date']);

                if ($dueDate == $today) {
                    $row["status"] = "due today";
                } elseif ($today > $dueDate) {
                    $row["status"] = "over due";
                } else {
                    $row["status"] = "borrowed";
                }

                $records[] = $row;
            }

            return $records;

        } catch (Exception $e) {
            error_log("Active Borrow Fetch Error: " . $e->getMessage());
            return [];
        }
    }
public function countFines($search,$types,$params){
    try{
        $countQuery = "
    SELECT COUNT(*) AS total
    FROM borrow_records br
    JOIN users u ON u.user_id = br.user_id
    JOIN books b ON b.book_id = br.book_id
    WHERE status='returned' AND fine_status ='unpaid' $search
";

        $stmt = $this->conn->prepare($countQuery);
        if (!empty($q)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }catch (Exception $e) {
            error_log("fine count Fetch Error: " . $e->getMessage());
            return 0;
        }
    }
    public function getAllFines($searchSQL,$types,$params,$limit,$offset,$sort,$order){
        try{
            $sql = "
    SELECT 
        f.fine_amount,
        f.fine_status,
        f.created_at,
        b.title,
        b.author,
        f.borrow_date,
        f.return_date,
        u.name,
        u.email
    FROM borrow_records f
    JOIN users u ON u.user_id = f.user_id
    JOIN books b ON b.book_id = f.book_id
    WHERE status='returned' AND fine_status ='unpaid' $searchSQL
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
";

$stmt2 = $this->conn->prepare($sql);
if (!empty($q)) $stmt2->bind_param($types, ...$params);
$stmt2->execute();
$result = $stmt2->get_result();

$fines = [];

while ($row = $result->fetch_assoc()) {
    $fines[] = [
        "username"    => $row['name'],
        "email"       => $row['email'],
        "title"       => $row['title'],
        "author"      => $row['author'],
        "borrow_date" => $row['borrow_date'],
        "return_date" => $row['return_date'],
        "amount"      => $row['fine_amount'],
        "status"      => $row['fine_status'],
        "created_at"  => $row['created_at']
    ];
        }
        return $fines;
    }catch (Exception $e) {
            error_log("fine Fetch Error: " . $e->getMessage());
            return [];
        }
    }
    public function getBookStock($book_id){
        try{
            $sqlBook = "SELECT stock FROM books WHERE book_id = ?";
            $stmtBook = $this->conn->prepare($sqlBook);
            $stmtBook->bind_param("i", $book_id);
            $stmtBook->execute();
            return $stmtBook->get_result();
        }catch (Exception $e) {
            error_log("book stock Fetch Error: " . $e->getMessage());
            return 0;
        }
    }
    public function checkDuplicateBorrow($user_id, $book_id){
        try{
            $sqlBorrowSame = "SELECT * FROM borrow_records 
                  WHERE user_id = ? AND book_id = ? AND return_date IS NULL";
            $stmtSame = $this->conn->prepare($sqlBorrowSame);
            $stmtSame->bind_param("ii", $user_id, $book_id);
            $stmtSame->execute();
            return $stmtSame->get_result();
        }catch (Exception $e) {
            error_log("book stock Fetch Error: " . $e->getMessage());
            return 0;
        }
    }
    public function checkUserFine($user_id){
        try{
                $sqlFine = "SELECT * FROM borrow_records 
            WHERE user_id = ? AND fine_status = 'unpaid'";
            $stmtFine = $this->conn->prepare($sqlFine);
            $stmtFine->bind_param("i", $user_id);
            $stmtFine->execute();
            return $stmtFine->get_result();
        }catch (Exception $e) {
            error_log("fine check  Error: " . $e->getMessage());
            return 0;
        }
    }
    public function borrowCount($user_id){
        try{
            $sqlBorrowCount = "SELECT COUNT(*) AS total 
                   FROM borrow_records 
                   WHERE user_id = ? AND return_date IS NULL";
            $stmtCount = $this->conn->prepare($sqlBorrowCount);
            $stmtCount->bind_param("i", $user_id);
            $stmtCount->execute();
            return $stmtCount->get_result()->fetch_assoc();
        }catch (Exception $e) {
            error_log("book count Fetch Error: " . $e->getMessage());
            return 0;
        }
    }
    public function addBorrowRecord($user_id, $book_id, $borrow_date, $due_date, $status){
            $this->conn->begin_transaction();

try {
    // Insert borrow record
    $sqlInsert = "INSERT INTO borrow_records 
                  (user_id, book_id, borrow_date, due_date, status)
                  VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $this->conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iisss", $user_id, $book_id, $borrow_date, $due_date, $status);
    $stmtInsert->execute();

    // Reduce stock
    $sqlStock = "UPDATE books SET stock = stock - 1 WHERE book_id = ?";
    $stmtStock = $this->conn->prepare($sqlStock);
    $stmtStock->bind_param("i", $book_id);
    $stmtStock->execute();
     $updateReservation = $this->conn->prepare("
        UPDATE reservations 
        SET status = 'borrowed'
        WHERE user_id = ? AND book_id = ? AND status = 'approved'
    ");
    $updateReservation->bind_param("ii", $user_id, $book_id);
    $updateReservation->execute();
    $this->conn->commit();
    $_SESSION['success'] = "Book borrowed successfully!";
} catch (Exception $e) {
    $this->conn->rollback();
     error_log("error in borrow transaction: " . $e->getMessage());

    $_SESSION['error'] = "Failed to borrow book.";
}
    }
    public function getActiveReturn($user_id, $book_id){
        try{
            $sql = $this->conn->prepare("
            SELECT borrow_date, due_date, return_date, fine_status 
            FROM borrow_records
            WHERE user_id = ? 
            AND book_id = ? 
            AND return_date IS NULL
            ");
        $sql->bind_param("ii", $user_id, $book_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
        }catch (Exception $e) {
            error_log("error in get Active records to return: " . $e->getMessage());
            return [];
        }
    }
    public function returnBook( $return_date_final, $fine_amount, $fine_status,$user_id,$book_id){
            $this->conn->begin_transaction();

        try {
             // Update borrow record
                $update = $this->conn->prepare("
                 UPDATE borrow_records
                 SET return_date = ?, 
                     status = 'returned',
                     fine_amount = ?, 
                     fine_status = ?
                    WHERE user_id = ? AND book_id = ? AND return_date IS NULL
            ");
            $update->bind_param( "sdsii",
                $return_date_final,
                $fine_amount,
                $fine_status,
                $user_id,
                $book_id
    );
        $update->execute();

    // Increase stock
    $stock = $this->conn->prepare("UPDATE books SET stock = stock + 1 WHERE book_id = ?");
    $stock->bind_param("i", $book_id);
    $stock->execute();

    $this->conn->commit();
    $_SESSION['success'] = "Return processed successfully.";

} catch (Exception $e) {
    $this->conn->rollback();
     error_log("error in retuning transaction: " . $e->getMessage());
    $_SESSION['error'] = "Failed to process return.";
}
        
    }
    public function payFine($user_id, $book_id, $amount){
        try{
            $sql3  = "UPDATE borrow_records 
          SET fine_status = 'paid'
          WHERE user_id = ? AND book_id = ? AND fine_amount = ? AND fine_status = 'unpaid'";
        $stmt3 = $this->conn->prepare($sql3);
        $stmt3->bind_param("iid", $user_id, $book_id, $amount);
         $stmt3->execute();
          return $stmt3->affected_rows;
        }catch (Exception $e) {
            error_log("error in pay fine: " . $e->getMessage());
            return 0;
        }
    }
}
?>