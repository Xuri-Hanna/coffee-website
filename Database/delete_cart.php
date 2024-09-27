<?php
header("location: http://" . $_SERVER['SERVER_NAME'] . '/coffe_website/' . '?page=Cart');
require_once('../Database/database.php');
if(isset($_GET['id'] )){
    $cartId = $_GET['id'];
    $config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'coffee-website'
    ];
    $db = new Database($config);
    $db->table('cart_item')->delete($cartId);
}
?>

