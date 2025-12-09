<?php 
require_once __DIR__ . "/../configs/dbconfig.php";

Class Reservation{
     private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
    public function getBorrowRecords($user_id, $book_id, $date){
        $sql_check_record = "SELECT id FROM borrow_records 
                     WHERE user_id = ? 
                       AND book_id = ? 
                       AND borrow_date = ?"; 
            $stmt_check_record = $this->conn->prepare($sql_check_record);
            $stmt_check_record->bind_param("iis", $user_id, $book_id, $date);
            $stmt_check_record->execute();
            return $stmt_check_record->get_result();
    }
    public function updateReservationStatus($result,$user_id,$book_id){
        if ($result->num_rows > 0) {
            $sql6 ="UPDATE reservations SET status='borrowed' WHERE user_id=? AND book_id=? AND status='approved'";// update reservation status
            $stmt6 = $this->conn->prepare($sql6);
            $stmt6->bind_param("ii", $user_id, $book_id);
            $stmt6->execute();
        }
    // change the status to expiry if the reservation date has passed
        $sql_expire = "UPDATE reservations SET status='expired' WHERE status='approved' AND borrow_date < CURDATE() AND user_id=? AND book_id=?";
        $stmt_expire = $this->conn->prepare($sql_expire);
        $stmt_expire->bind_param("ii", $user_id, $book_id);
        $stmt_expire->execute();
    }
    public function getActiveReservations($user_id, $book_id){
            $sql_check = "SELECT * FROM reservations WHERE user_id=? AND book_id=? AND status IN ('pending','approved','borrowed')";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $book_id);
        $stmt_check->execute();
    return $stmt_check->get_result();
    }
    public function addReservation($user_id, $book_id, $date){

        $sql = "INSERT INTO reservations (user_id, book_id, status,borrow_date) VALUES (?, ?, 'pending',?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $book_id, $date);
        return $stmt->execute();
    }

}
?>