<?php 
require_once __DIR__ . "/../configs/dbconfig.php";

Class Reservation{
     private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
    public function getBorrowRecords($user_id, $book_id, $date) {
        try {
            $sql = "SELECT id FROM borrow_records 
                    WHERE user_id = ? 
                      AND book_id = ? 
                      AND borrow_date = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("iis", $user_id, $book_id, $date);
            $stmt->execute();

            return $stmt->get_result();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function updateReservationStatus($result, $user_id, $book_id) {
        try {
            // If matching borrow record exists
            if ($result && $result->num_rows > 0) {
                $sql = "UPDATE reservations 
                        SET status = 'borrowed' 
                        WHERE user_id = ? 
                          AND book_id = ? 
                          AND status = 'approved'";

                $stmt = $this->conn->prepare($sql);
                if (!$stmt) throw new Exception($this->conn->error);

                $stmt->bind_param("ii", $user_id, $book_id);
                $stmt->execute();
            }
            // Expire outdated reservations
            $sql_expire = "UPDATE reservations 
                           SET status = 'expired' 
                           WHERE status = 'approved' 
                             AND borrow_date < CURDATE() 
                             AND user_id = ? 
                             AND book_id = ?";

            $stmt_expire = $this->conn->prepare($sql_expire);
            if (!$stmt_expire) throw new Exception($this->conn->error);

            $stmt_expire->bind_param("ii", $user_id, $book_id);
            return $stmt_expire->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function getActiveReservations($user_id, $book_id) {
        try {
            $sql = "SELECT * FROM reservations 
                    WHERE user_id = ? 
                      AND book_id = ? 
                      AND status IN ('pending','approved','borrowed')";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("ii", $user_id, $book_id);
            $stmt->execute();

            return $stmt->get_result();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function addReservation($user_id, $book_id, $date) {
        try {
            $sql = "INSERT INTO reservations (user_id, book_id, status, borrow_date)
                    VALUES (?, ?, 'pending', ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("iis", $user_id, $book_id, $date);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function deleteReservation($user_id, $book_id, $status) {
        try {
            $sql = "DELETE FROM reservations 
                    WHERE user_id = ? 
                      AND book_id = ? 
                      AND status = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("iis", $user_id, $book_id, $status);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function approveReservation($id) {
        try {
            $sql = "UPDATE reservations 
                    SET status = 'approved' 
                    WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("i", $id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function rejectReservation($id) {
        try {
            $sql = "UPDATE reservations 
                    SET status = 'rejected' 
                    WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("i", $id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function updateReservation($date, $book_id, $user_id) {
        try {
            $sql = "UPDATE reservations 
                    SET borrow_date = ? 
                    WHERE book_id = ? 
                      AND user_id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("sii", $date, $book_id, $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>