<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
require_once("./Database/email.php");
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
$payload = [];
$role = $_SESSION['role'];
if(isset($role) && $role == '0'){
    $payload = array_merge($payload,[
        'user_id' => $_SESSION['user_id'],
    ]);
}
$tieude ="email Từ coffeeshop";
$noidung ="<h4>Đơn đặt hàng bao gồm : </h4>";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    foreach($db->table('orders')->gets($payload) as $order){
        $orderId = $order['id'];
        if(isset($_POST[$orderId])){
            $order = $db->table('orders')->get(['id'=>$orderId]);
            $db->table('orders')->update($orderId,['status' => $_POST[$orderId]]);
            $response = $db->table('orders')->gets($payload);
            if($order['status'] !== $_POST[$orderId]){
                $response = $db->table('order_item')->gets(['order_id' => $orderId]);
                foreach ($response as $item){
                    $product = $db->table('product')->get(['id' => $item['product_id']]);
                    if($_POST[$orderId] == "1"){
                        $noidung.="<ul>
                    <li>" .$product['name']."</li>
                    <li> Số lượng: ".$item['quantity']."</li>
                    <li> Tổng tiền: ".$item['total']."</li>
                    </ul>";
                    }
                    else{
                        $noidung.="<p>Đơn hàng của bạn đã bị từ chối</p>";
                    }
                }
                $guest = $db->table('user')->get(['id' => $order['user_id']]);
                $mailer = new Mailer();
                $mailer->sendEmail($guest['email'],$tieude,$noidung);
            }
        }
    }
}
$response = $db->table('orders')->gets($payload);

$user = $db->table('user')->get(['id' => $_SESSION['user_id']]);



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
            <h1 class="main-title"><?php echo $role === 0 ?'Kiểm tra đơn hàng' : 'Quản lí đơn hàng'?></h1>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Email</th>
                <th style="<?php echo $role === 1 ? 'display:none;' : ''?>">Status</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th style="<?php echo $role === 0 ? 'display:none;' : ''?>">Actions</th>
            </tr>
            <?php
            if($response != null){
                foreach ($response as $order){
                    $guest = $db->table('user')->get(['id' => $order['user_id']]);
                    ?>
                    <tr>
                        <td><?php echo $order['id'] ?></td>
                        <td><?php echo $order['total'] ?></td>

                        <td><?php echo $guest['email']?></td>
                        <td style="<?php echo $role === 1 ? 'display:none;' : ''?>"><?php if($order['status'] === 0) {
                                echo 'Đang chờ';
                            }
                            else if($order['status'] === 1){
                                echo 'Đã xác nhận';
                            }
                            else if($order['status'] === 2){
                                echo 'Từ chối';
                            }
                            ?></td>
                        <td><?php echo $order['address'] ?></td>
                        <td><?php echo $order['phoneNumber'] ?></td>
                        <td class="buttons" style="<?php echo $role === 0 ? 'display:none;' : ''?>">
                            <form class="radio-container" method="post">

                                <div>
                                    <input type="radio" name="<?php echo $order['id']?>" id="accept-<?php echo $order['id'] ?>" <?php echo ($order['status'] === 1 ? 'checked' : '')?> value="1" onchange="this.form.submit()">
                                    <label for="accept-<?php echo $order['id'] ?>">Accept</label>
                                </div>
                                <div>
                                    <input type="radio" name="<?php echo $order['id']?>" id="reject-<?php echo $order['id'] ?>" <?php echo ($order['status'] === 2 ? 'checked' : '')?> value="2" onchange="this.form.submit()">
                                    <label for="reject-<?php echo $order['id'] ?>">Reject</label>
                                </div>
                            </form>
                        </td>
                        <td><a href="?page=OrderDetail&orderId=<?php echo $order['id']?>" class="primary-button">Chi tiết</a></td>
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
<style>
    .table-container .radio-container{
        display: flex;
        flex-wrap: wrap;
        gap: 30px 15px;

    }
    .radio-container input{
        display: none;
    }
    .radio-container input:checked + label{
        color: #ffffff;
        background: rgb(188, 110, 50);
    }

    .radio-container label{
        border: 1px solid rgb(80, 44, 20);
        padding: 0.5rem;
        border-radius: 10px;
        color: rgb(188, 110, 50);
        cursor: pointer;
        white-space: nowrap;
        margin: 10px 0;
    }
    .buttons form{
        margin: 0 auto;
    }
</style>
<script>


</script>