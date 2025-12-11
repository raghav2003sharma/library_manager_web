<?php
session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/User.php";
$auth = new User();
if (
    empty($_POST['email']) ||
    empty($_POST['password'])
) {
            redirectBack( "error", "Email and Password are required.");

}
$email = trim($_POST['email']);
$password = $_POST['password'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectBack("error","Invalid email format.");

}

if (strlen($password) < 6) {
            redirectBack("error","Password must be at least 6 characters.");
}
;
    $result = $auth->getUserByEmail($email);

    if ($result->num_rows === 0) {
                    redirectBack("error","No user found with this email. Register first to login");
    }

    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        if ($user['role'] === 'admin') {
        // redirect("/public/index.php?page=admin-home","success","login success");

        header("Location: /public/index.php?page=admin-home");
        } else {
        header("Location: /public/index.php?page=user-home");
        }
        exit;
      
    } else {
         redirectBack("error","Password is incorrect");
    }
?>