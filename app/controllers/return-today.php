<?php
session_start();
require_once "../../config/db.php";

if (empty($_POST['user_id']) ||
    empty($_POST['book_id']) )
{
    $_SESSION['error'] = "Missing required return data.";
    header("Location: /public/index.php?page=admin-home&main-page=borrowed-books");
    exit;
}
$today = date('Y-m-d');

$user_id = intval($_POST['user_id']);
$book_id = intval($_POST['book_id']);
$return_date = $today;

$returnDateObj = new DateTime($return_date);


//  Fetch active borrow record
$sql = $conn->prepare("
    SELECT borrow_date, due_date, return_date, fine_status 
    FROM borrow_records
    WHERE user_id = ? 
      AND book_id = ? 
      AND return_date IS NULL
");
$sql->bind_param("ii", $user_id, $book_id);
$sql->execute();
$record = $sql->get_result()->fetch_assoc();

if (!$record) {
    $_SESSION['error'] = "No active borrow record found.";
    header("Location: /public/index.php?page=admin-home&main-page=borrowed-books");
    exit;
}

$dueDate = new DateTime($record['due_date']);
$dayFine = 10.00;
$fine_amount = 0.00;
$fine_status = "no_fine";


//  Late return-> Calculate fine
if ($returnDateObj > $dueDate) {

    $daysLate = $returnDateObj->diff($dueDate)->days;
    $fine_amount = $daysLate * $dayFine;
    $fine_status = "unpaid";
}

$return_date_final = $returnDateObj->format("Y-m-d");

$conn->begin_transaction();

try {

    // Update borrow record
    $update = $conn->prepare("
        UPDATE borrow_records
        SET return_date = ?, 
            status = 'returned',
            fine_amount = ?, 
            fine_status = ?
        WHERE user_id = ? AND book_id = ? AND return_date IS NULL
    ");
    $update->bind_param(
        "sdsii",
        $return_date_final,
        $fine_amount,
        $fine_status,
        $user_id,
        $book_id
    );
    $update->execute();

    // Increase stock
    $stock = $conn->prepare("UPDATE books SET stock = stock + 1 WHERE book_id = ?");
    $stock->bind_param("i", $book_id);
    $stock->execute();

    $conn->commit();
    $_SESSION['success'] = "Return processed successfully.";

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to process return.";
}

header("Location: /public/index.php?page=admin-home&main-page=borrowed-books");
exit;
?>
