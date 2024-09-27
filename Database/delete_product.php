<?php
    header("location: http://" . $_SERVER['SERVER_NAME'] . '/coffe_website/' . '?page=ProductAdmin');
    require_once('../Database/database.php');
    if(isset($_GET['id'] )){
        $productId = $_GET['id'];
        $config = [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'coffee-website'
        ];
        $db = new Database($config);
        $db->table('product')->delete($productId);
    }
?>

