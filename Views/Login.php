<?php
session_start();
require_once('./Database/database.php');
if(isset($_SESSION['user_id'])){
    header('location: ?page=HomePage');
}
$config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'coffee-website'
];
$db = new Database($config);
$email = '';
$password = '';
$err_acc = '';
$err_pass = '';

if(!empty($_POST['submit']) && $_POST['submit'] == 'login'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = $db->table('user')->get(['email' => $email]);
    if (!empty($response)) {
        if ($password == $response['password']) {
            $_SESSION['user_id'] = $response['id'];
            $_SESSION['role'] = $response['role'];
            header("location: http://" . $_SERVER['SERVER_NAME'] . '/coffe_website/' . '?page=HomePage');
            $response = $db->table('cart')->get(['user_id' => $_SESSION['user_id']]);
            if(empty($response)){
                $db->table('cart')->insert(['user_id' => $_SESSION['user_id'],]);
            }
        } else $err_pass = 'Mật khẩu không đúng';
    } else {
        $err_acc = 'Tài khoản không tồn tại';
        $user = '';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="./styles/Login.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="title">Login</h1>
    <form action="" method="post">
        <div>
            <input type="text" class="input" placeholder="Email" id="email" name="email">
            <input type="password" class="input" placeholder="Password" id="password" name="password">
        </div>
        <div>
            <button class="button button-login" type="submit" name="submit" value="login">
                Login
            </button>
        </div>
    </form>
    <div>
        <p>Don’t have an account?</p>
        <a href="?page=Register">SIGN UP NOW</a>
    </div>
</div>

</body>
</html>


