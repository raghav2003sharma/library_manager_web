<?php
session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);
session_start();
require_once "../../config/db.php";
if (
    empty($_POST['email']) ||
    empty($_POST['password'])
) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=login");
    exit;
}
$email = trim($_POST['email']);
$password = $_POST['password'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /public/index.php?page=register");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['error'] = "Password must be at least 6 characters.";
    header("Location: /public/index.php?page=register");
    exit;
}
 $stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "No user found with this email.";
        header("Location: /public/index.php?page=login");
        exit;
    }

    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
        header("Location: /public/index.php?page=admin-home");
        } else {
        header("Location: /public/index.php?page=user-home");
        }
        exit;
      
    } else {
        $_SESSION['error'] = "Incorrect password.";
        header("Location: /public/index.php?page=login");
        exit;
    }
?>