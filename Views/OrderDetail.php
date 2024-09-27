<?php
require_once('./Database/database.php');
include('./Database/user_role.php');
header('Cache-Control: no cache');
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'dbname' => 'coffee-website'
];
$db = new Database($config);
$orderId = $_GET['orderId'];
$response = $db->table('order_item')->gets(['order_id' => $orderId]);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/Table.css">
</head>
<body>

<div class="table-container container">
    <div>
        <div class="title">
            <h1 class="main-title">Chi tiết đơn hàng</h1>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            if($response != null){
                foreach ($response as $order){
                    $product = $db->table('product')->get(['id' => $order['product_id']]);
                    ?>
                    <tr>
                        <td><?php echo $order['id'] ?></td>
                        <td><?php echo $product['name'] ?></td>
                        <td><?php echo $order['quantity'] ?></td>
                        <td><?php echo $order['total'] ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>


