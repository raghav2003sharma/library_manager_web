<?php 
require_once __DIR__ . "/../configs/dbconfig.php";


class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

   public function getUserByEmail($email) {//used in login and register
       try {
            $stmt = $this->conn->prepare(
                "SELECT user_id, name, email, password, role FROM users WHERE email = ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function register($username,$email,$hashedPassword){
          try {
            $stmt = $this->conn->prepare(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }

    }
    public function addUser($username, $email, $hashedPassword, $role){
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function updateUser($name, $email, $role, $user_id){
           try {
            $stmt = $this->conn->prepare(
                "UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("sssi", $name, $email, $role, $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function deleteUser($user_id){
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM users WHERE user_id = ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("i", $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
             error_log($e->getMessage());
            return false;
        }
    }
    public function getOtherUsersByEmail($email, $user_id){
        try {
            $stmt = $this->conn->prepare(
                "SELECT user_id FROM users WHERE email = ? AND user_id != ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("si", $email, $user_id);
            $stmt->execute();
            return $stmt->get_result();

        } catch (Exception $e) {
                         error_log($e->getMessage());

            return false;
        }
    }
    public function deleteReservationByUserid($user_id){
            try {
            $stmt = $this->conn->prepare(
                "DELETE FROM reservations WHERE user_id = ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("i", $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
             error_log($e->getMessage());

            return false;
        }
    }
    public function deleteBorrowRecordByUserid($user_id){
            try {
            $stmt = $this->conn->prepare(
                "DELETE FROM borrow_records WHERE user_id = ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("i", $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
                error_log($e->getMessage());

            return false;
        }

    }
    public function deleteContactsByUserid($user_id){
           try {
            $stmt = $this->conn->prepare(
                "DELETE FROM contact_messages WHERE user_id = ?"
            );
            if (!$stmt) throw new Exception($this->conn->error);

            $stmt->bind_param("i", $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
                            error_log($e->getMessage());

            return false;
        }
    }
    public function deleteUserCompletely($user_id) {
    $this->conn->begin_transaction();

        try {
        $this->deleteReservationByUserid($user_id);
        $this->deleteBorrowRecordByUserid($user_id);
        $this->deleteContactsByUserid($user_id);

        $isDeleted = $this->deleteUser($user_id);

        if (!$isDeleted) {
            throw new Exception("User delete failed");
        }

        $this->conn->commit();
        return true;

        } catch (Exception $e) {
        $this->conn->rollback();
        return false;
        }
    }

 

   
}
?>