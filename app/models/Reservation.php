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
      public function countReservations($filter, $searchLike)
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM reservations r
                    JOIN users u ON r.user_id = u.user_id
                    JOIN books b ON r.book_id = b.book_id
                    WHERE r.status = ?
                    AND (u.name LIKE ? OR u.email LIKE ? OR b.title LIKE ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $filter, $searchLike, $searchLike, $searchLike);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc()['total'];

        }  catch (Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
      public function getReservations($filter, $searchLike, $sort, $order, $offset, $limit)
    {
        try {
            $sql = "SELECT r.id, r.status, r.borrow_date, 
                           u.name AS username, u.email, 
                           b.title, b.cover_image
                    FROM reservations r
                    JOIN users u ON r.user_id = u.user_id
                    JOIN books b ON r.book_id = b.book_id
                    WHERE r.status = ?
                    AND (u.name LIKE ? OR u.email LIKE ? OR b.title LIKE ?)
                    ORDER BY $sort $order
                    LIMIT ?, ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssii", $filter, $searchLike, $searchLike, $searchLike, $offset, $limit);
            $stmt->execute();

            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    "id"          => $row["id"],
                    "status"      => $row["status"],
                    "username"    => $row["username"],
                    "email"       => $row["email"],
                    "title"       => $row["title"],
                    "cover_image" => $row["cover_image"],
                    "borrow_date" => $row["borrow_date"],
                ];
            }

            return $data;
        }catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    public function countUserReservations($user_id, $filter, $searchLike)
    {
        try {
            $sql = "SELECT COUNT(*) AS total 
                    FROM reservations r
                    JOIN books b ON r.book_id = b.book_id
                    WHERE r.user_id = ? 
                      AND r.status = ? 
                      AND b.title LIKE ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $user_id, $filter, $searchLike);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc()['total'];

        } catch (Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
      public function getUserReservations($user_id, $filter, $searchLike, $offset, $limit)
    {
        try {
            $sql = "SELECT r.id, r.status, r.borrow_date, r.reserved_at,
                           b.book_id, b.title, b.cover_image
                    FROM reservations r
                    JOIN books b ON r.book_id = b.book_id
                    WHERE r.user_id = ? 
                      AND r.status = ? 
                      AND b.title LIKE ?
                    LIMIT ?, ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issii", $user_id, $filter, $searchLike, $offset, $limit);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    "id"          => $row["id"],
                    "book_id"     => $row["book_id"],
                    "status"      => $row["status"],
                    "title"       => $row["title"],
                    "cover_image" => $row["cover_image"],
                    "borrow_date" => $row["borrow_date"] ?? '',
                    "due_date"    => $row["due_date"] ?? '',
                    "reserved_at" => $row["reserved_at"] ?? ''
                ];
            }

            return $data;

        } catch (Exception $e) {
            error_log("error in fetching user reservations",$e->getMessage());
            return [];
        }
    }
}
?>