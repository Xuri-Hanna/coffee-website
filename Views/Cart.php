<?php
require_once('./Database/database.php');
include('./Database/user_role.php');
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'dbname' => 'coffee-website'
];
$db = new Database($config);
$cartId = $db->table('cart')->get(['user_id' => $_SESSION['user_id']])['id'];
$response = $db->table('cart_item')->gets(['cart_id' => $cartId]);
$user = $db->table('user')->get(['id' => $_SESSION['user_id']]);
$phoneNumber = $user['phoneNumber'];
$address = $user['address'];
$totalPrice = 0;
foreach($response as $item){
    $totalPrice += $item['total'];
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!empty($_POST['submit']) && $_POST['submit'] == 'save'){
        if(!empty($_POST['phone-number']) && !empty($_POST['address'])){
            $db->table('user')->update($user['id'],['phoneNumber' => $_POST['phone-number'], 'address' => $_POST['address']]);
            $db->table('orders')->insert(['user_id' => $_SESSION['user_id'],'status' => 0,'total' => $totalPrice,'address' => $_POST['address'],'phoneNumber' => $_POST['phone-number']]);
            $orders = $db->table('orders')->gets(['user_id' => $_SESSION['user_id']]);
            $orderId = end($orders)['id'];
            foreach($response as $cart){
                echo $cart['id'];
                $db->table('cart_item')->delete($cart['id']);
            }
            foreach ($response as $item){
                $db->table('order_item')->insert(['order_id' => $orderId, 'product_id' => $item['product_id'], 'quantity' => $item['quantity'], 'total' => $item['total']]);
            }
        }
        header('location: ?page=Order');
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
    <link rel="stylesheet" href="./styles/Table.css">

</head>
<body>
<div id="myModal" class="modal">

    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Confirm</h2>
        </div>
        <div class="modal-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-wrapper">
                    <label for="your-phone-number">Your Phone Number:</label>
                    <input type="number" class="input" id="your-phone-number" placeholder="Your Phone Number" value="<?php echo $phoneNumber?>" name="phone-number">
                </div>
                <div class="input-wrapper">
                    <label for="your-address">Your Address:</label>
                    <input type="text" class="input" id="your-address" placeholder="Your Address" value="<?php echo $address?>" name="address">
                </div>
                <button class="primary-button" type="submit" name="submit" value="save">Confirm</button>
            </form>
        </div>
    </div>

</div>
<div class="table-container container">
    <div>
        <div class="title">
            <h1 class="main-title">Giỏ hàng</h1>
        </div>
        <table>
            <tr>
                <th>ID</th>

                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            if($response != null){
                foreach ($response as $cart){
                    $product = $db->table('product')->get(['id' => $cart['product_id']]);
                    ?>
                    <tr>
                        <th><?php echo $cart['id'] ?></th>
                        <td><?php echo $product['name'] ?></td>
                        <td><?php echo $cart['size'] ?></td>
                        <td><?php echo $cart['quantity'] ?></td>
                        <td><?php echo $cart['total'] ?></td>
                        <td class="buttons">
                            <form action="">
                                <a class="primary-button" href="./Database/delete_cart.php?id=<?php echo $cart['id']?>" target="_self">Xoá</a>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

    </div>
    <div class="actions">
        <button class="primary-button" style="margin:20px 0 !important;" id="myBtn" type="submit" name="submit">Thanh toán</button>
        <h1>Tổng tiền: <?php echo $totalPrice ?></h1>
    </div>
</div>
</body>
</html>
<script src="./Scripts/Modal.js"></script>
<style>
    .input-wrapper{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 20px;
        width: 100%;
        margin: 20px 0;
    }
    .input-wrapper label{
        color: rgb(80, 44, 20);
        font-weight: bold;
        font-size: large;
    }
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    /* Modal Header */
    .modal-header {
        padding: 2px 16px;
        background: rgba(80, 44, 20, 1);
        color: white;
    }

    /* Modal Body */
    .modal-body {padding: 2px 16px;}

    /* Modal Footer */
    .modal-footer {
        padding: 2px 16px;
        background: rgba(80, 44, 20, 1);
        color: white;
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
        animation-name: animatetop;
        animation-duration: 0.4s;
        top: 300px;
        border-radius: 20px;
        overflow: hidden;
    }

    /* Add Animation */
    @keyframes animatetop {
        from {top: -300px; opacity: 0}
        to {top: 300px; opacity: 1}
    }


    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
