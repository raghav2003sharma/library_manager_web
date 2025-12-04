<?php
require_once "../../config/db.php";
error_log($_GET['type']);
if($_GET['type'] === "borrow"){
$email = $_GET['email'] ?? "";

if ($email == "") {
    echo json_encode([]);
    exit;
}

//  Get user_id from email
$sql = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$sql->bind_param("s", $email);
$sql->execute();
$user = $sql->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode([]);
    exit;
}

$user_id = $user["user_id"];

// Fetch books user has currently borrowed (not returned yet)
$borrowedSql = "
    SELECT book_id 
    FROM borrow_records 
    WHERE user_id = ? AND return_date IS NULL
";

$stmtBorrowed = $conn->prepare($borrowedSql);
$stmtBorrowed->bind_param("i", $user_id);
$stmtBorrowed->execute();

$borrowedBooks = [];
$res = $stmtBorrowed->get_result();
while ($row = $res->fetch_assoc()) {
    $borrowedBooks[] = $row["book_id"];
}

$excludeList = implode(",", $borrowedBooks);

// 3. Fetch available books NOT in borrowed list
if (!empty($excludeList)) {
    $booksSql = "
        SELECT book_id, title
        FROM books
        WHERE stock > 0
        AND book_id NOT IN ($excludeList)
    ";
} else {
    $booksSql = "
        SELECT book_id, title
        FROM books
        WHERE stock > 0
    ";
}

$books = $conn->query($booksSql);

$data = [];
while ($b = $books->fetch_assoc()) {
    $data[] = [
        "book_id" => $b["book_id"],
        "title"   => $b["title"]
    ];
}

echo json_encode($data);
exit;
}
if($_GET['type']==="return"){
    $email = $_GET['email'] ?? '';

if (strlen($email) < 3) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT  b.title
        FROM borrow_records br
        JOIN users u ON br.user_id = u.user_id
        JOIN books b ON br.book_id = b.book_id
        WHERE u.email LIKE ? AND br.return_date IS NULL;";

$stmt = $conn->prepare($sql);
$search = "%$email%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
}
if ($_GET['type'] === "fine") {
    $email = $_GET['email'] ?? "";

    if ($email == "") {
        echo json_encode([]);
        exit;
    }

    // Get user_id
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        echo json_encode([]);
        exit;
    }

    $user_id = $user["user_id"];

    // Fetch books with unpaid fines
    $sql = "
        SELECT 
            b.book_id,
            b.title,
            f.fine_amount
        FROM borrow_records f
        JOIN books b ON f.book_id = b.book_id
        WHERE f.user_id = ? AND f.fine_status = 'unpaid'
    ";

    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result = $stmt2->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "book_id" => $row["book_id"],
            "title" => $row["title"],
            "fine_amount" => $row["fine_amount"]
        ];
    }

    echo json_encode($data);
    exit;
}

?>