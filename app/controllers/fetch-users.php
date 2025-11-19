<?php
require_once "../../config/db.php";

$result = $conn->query("SELECT user_id, name, email, role,created_at FROM users");
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
echo json_encode($users);

?>