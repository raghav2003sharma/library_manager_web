<?php
session_start();
require_once "../../config/db.php";

if (empty($_POST['user_id']) ||
    empty($_POST['book_id']) ||
    empty($_POST['borrow_date'])
) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}

$user_id = $_POST['user_id'];
$book_id = $_POST['book_id'];
$borrow_date = $_POST['borrow_date'];

// --- Date validation ---
$today = date('Y-m-d');
if ($borrow_date < $today) {
    $_SESSION['error'] = "Borrow date cannot be in the past.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}

// --- Check book availability ---
$sqlBook = "SELECT stock FROM books WHERE book_id = ?";
$stmtBook = $conn->prepare($sqlBook);
$stmtBook->bind_param("i", $book_id);
$stmtBook->execute();
$resultBook = $stmtBook->get_result();

if ($resultBook->num_rows === 0) {
    $_SESSION['error'] = "Book not found.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}

$book = $resultBook->fetch_assoc();

if ($book['stock'] <= 0) {
    $_SESSION['error'] = "Book is out of stock.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}

// --- Check previous borrow records ---
$sqlBorrow = "SELECT * FROM borrow_records WHERE user_id = ? AND book_id = ?";
$stmtBorrow = $conn->prepare($sqlBorrow);
$stmtBorrow->bind_param("ii", $user_id, $book_id);
$stmtBorrow->execute();
$resultBorrow = $stmtBorrow->get_result();

$borrowLimit = 0;
while ($row = $resultBorrow->fetch_assoc()) {
    $borrowLimit++;

    if ($row['return_date'] === null) {
        $_SESSION['error'] = "User already borrowed this book and has not returned it.";
        header("Location: /public/index.php?page=admin-home&main-page=dashboard");
        exit;
    }

    if ($row['fine_status'] === 'unpaid') {
        $_SESSION['error'] = "User has unpaid fines.";
        header("Location: /public/index.php?page=admin-home&main-page=dashboard");
        exit;
    }
}

if ($borrowLimit > 5) {
    $_SESSION['error'] = "User reached the borrow limit of 5 books.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}

// --- Due date (14 days) ---
$borrowDateObj = new DateTime($borrow_date);
$dueDateObj = clone $borrowDateObj;
$dueDateObj->modify('+14 days');

$due_date = $dueDateObj->format('Y-m-d');
$status = "borrowed";

// --- Transaction: insert + decrease stock ---
$conn->begin_transaction();

try {
    // Insert borrow record
    $sqlInsert = "INSERT INTO borrow_records 
                  (user_id, book_id, borrow_date, due_date, status)
                  VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iisss", $user_id, $book_id, $borrow_date, $due_date, $status);
    $stmtInsert->execute();

    // Reduce stock
    $sqlStock = "UPDATE books SET stock = stock - 1 WHERE book_id = ?";
    $stmtStock = $conn->prepare($sqlStock);
    $stmtStock->bind_param("i", $book_id);
    $stmtStock->execute();
     $updateReservation = $conn->prepare("
        UPDATE reservations 
        SET status = 'borrowed'
        WHERE user_id = ? AND book_id = ? AND status = 'approved'
    ");
    $updateReservation->bind_param("ii", $user_id, $book_id);
    $updateReservation->execute();
    $conn->commit();
    $_SESSION['success'] = "Book borrowed successfully!";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to borrow book.";
}

header("Location: /public/index.php?page=admin-home&main-page=dashboard");
exit;
?>
