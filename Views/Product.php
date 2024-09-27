<?php
require_once('./Database/database.php');
header('Cache-Control: no cache');
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'dbname' => 'coffee-website'
];
$db = new Database($config);
$quantity = 0;
$size = 'small';
$id = null;
$price = 0;
$cartId = $db->table('cart')->get(['user_id' => $_SESSION['user_id']])['id'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $response = $db->table('product')->get(['id' => $id]);
    $price = (float) $response['price'];
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!empty($_POST['submit']) && $_POST['submit'] == 'add') {
        if(!empty($_POST['quantity']) && $_POST['quantity'] > 0) {
            $cartResponse = $db->table('cart_item')->get(['cart_id' => $cartId,
                'product_id' => $id,'size' => $_POST['size']]);
            if($_POST['size'] == 'small'){
                $price = $price * (int)$_POST['quantity'];
            }
            else if($_POST['size'] == 'medium'){
                $price = ($price + 6000) * (int)$_POST['quantity'] ;
            }
            else if($_POST['size'] == 'large'){
                $price = ($price + 16000) * (int)$_POST['quantity'];
            }
            if(isset($cartResponse)) {
                $quantity = (int) $cartResponse['quantity'] + (int) $_POST['quantity'];
                $db->table('cart_item')->update($cartResponse['id'], ['quantity' => $quantity,'total' => (float) $cartResponse['total'] + $price]);
            }
            else{
                $db->table('cart_item')->insert([
                'cart_id' => $cartId,
                'product_id' => $id,
                'quantity' => $_POST['quantity'],
                'size' => $_POST['size'],
                 'total' => $price
        ]);
            }
            header('location: ?page=Cart');
        }
//
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
    <link rel="stylesheet" href="./Styles/Product.css">
</head>
<body>
   <div class="product-container container">
       <div>
           <div class="image-wrapper">
               <img src="<?php echo isset($response['image']) ? $response['image'] : './Images/no-image.jpg'?>" alt="">
           </div>
           <div>
               <h2 class="main-title"><?php echo $response['name'] ?></h2>
               <h1 class="price" id="price"><?php if($size == 'small'){
                                        echo $response['price'];
                                        }
                                        else if($size == 'medium'){
                                            echo (float) $response['price'] + 6000;
                                        }
                                        else if($size == 'large'){
                                            echo (float) $response['price'] + 16000;
                                        }

                    ?></h1>
               <form method="post">
                   <h3>Chọn size (bắt buộc)</h3>
                   <div class="radio-container">
                       <div>
                           <input type="radio" value="small" name="size" id="small" <?php echo $size == 'small' ? 'checked' : ''?> onchange="changeMoney('small')">
                           <label for="small">
                               <img src="./Images/coffee-icon.png" alt="">
                               Nhỏ + 0 đ
                           </label>
                       </div>
                       <div>
                           <input type="radio" value="medium" name="size" id="medium" <?php echo $size == 'medium' ? 'checked' : ''?> onchange="changeMoney('medium')">
                           <label for="medium">
                               <img src="./Images/coffee-icon.png" alt="">
                               Vừa + 6.000 đ
                           </label>
                       </div>
                       <div>
                           <input type="radio" value="large" name="size" id="large" <?php echo $size == 'large' ? 'checked' : ''?> onchange="changeMoney('large')">
                           <label for="large">
                               <img src="./Images/coffee-icon.png" alt="">
                               Lớn + 16.000 đ
                           </label>
                       </div>
                   </div>
                   <div class="input-wrapper">
                       <label for="quantity">So luong:</label>
                       <input type="number" class="input" id="quantity" placeholder="Quantity" name="quantity" value="0">
                   </div>
                   <button class="primary-button" name="submit" value="add">Cho vào giỏ hàng</button>
               </form>


           </div>
       </div>
       <hr>
       <div class="description">
           <h3>Mô tả sản phẩm</h3>
           <p><?php echo $response['description'] ?></p>
       </div>
       <hr>
       <div class="products-list">
           <h3>Sản phẩm liên quan</h3>
           <div>
               <?php for ($i=0;$i< 6;$i++){
                   echo '<div>
                    <div class="image-wrapper">
                        <img src="./Images/stimulate.png">
                    </div>
                    <h2 class="sub-title">Stimulate</h2>
                    <p class="sub-content">
                        $29
                    </p>
                    <a class="primary-button" href="?page=Product&id=1">Order Now</a>
                </div>';
               } ?>
           </div>
       </div>
   </div>
</body>
</html>
<script>
    const price = parseFloat(document.getElementById('price').innerText) ;
    function changeMoney(size){
        if(size === 'small'){
            document.getElementById('price').innerText = price.toString();
        }
        else if(size === 'medium'){
            document.getElementById('price').innerText = (price + 6000).toString();
        }
        else if(size === 'large'){
            document.getElementById('price').innerText = (price + 16000).toString();
        }
    }
</script>
