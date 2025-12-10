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
            error_log("Fetch Error: " . $e->getMessage());
            return [];
        }
    }
    public function recordsToBorrow(){
        try{
          return  $this->conn->query("
                SELECT r.*, u.name, u.email, b.title,b.author
                FROM reservations r
                 JOIN users u ON r.user_id = u.user_id
                 JOIN books b ON r.book_id = b.book_id
                WHERE r.status = 'approved'
                AND r.borrow_date = CURDATE()");
        }catch (Exception $e) {
            error_log($e->getMessage());
            return false;
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
            error_log("Count Error: " . $e->getMessage());
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
            error_log("Fetch Error: " . $e->getMessage());
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
}
?>