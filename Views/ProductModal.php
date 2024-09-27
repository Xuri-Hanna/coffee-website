<?php
    $productId = null;
    require_once('./Database/database.php');
    include('./Database/admin_role.php');

    $config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'coffee-website'
    ];
    $db = new Database($config);
    $productName = '';
    $productPrice = 0;
    $productStatus = 1;
    $productImage = null;
    $productCategory = 'foods';
    $productDescription  = '';
    if(isset($_GET['id'])){
        $productId = $_GET['id'];
        $response = $db->table('product')->get(['id' => $productId]);
        $productName = $response['name'];
        $productPrice = $response['price'];
        $productStatus = $response['status'];
        $productImage = $response['image'];
        $productCategory = $response['category'];
        $productDescription = $response['description'];
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!empty($_POST['submit']) && $_POST['submit'] == 'save'){
            if(!empty($_POST['product-name']) && !empty($_POST['product-price'])){
                $productName = $_POST['product-name'];
                $productPrice = $_POST['product-price'];
                $productStatus = isset($_POST['product-status']) ? 1 : 0;
                $productImage = isset($_FILES['image']) ? $_FILES['image'] : null;
                $productCategory = $_POST['product-category'];
                $productDescription = $_POST['product-description'];
                $data = ['name' => $productName, 'price' => $productPrice, 'status' => $productStatus,'category' => $productCategory,'description' => $productDescription];
                if(isset($productImage)){
                    $path = 'uploads/' . $productImage['name'];
                    $moved = move_uploaded_file($productImage['tmp_name'], $path);
                    if( $moved ) {
                        echo "Successfully uploaded";
                        $data = array_merge($data, ['image' => $path]);
                    } else {
                        echo "Not uploaded because of error #".$_FILES["image"]["error"];
                    }
                }
                if(isset($productId)){
                    $db->table('product')->update($productId,$data);
                }
                else{
                    $db->table('product')->insert($data);
                }
                header('location: ?page=ProductAdmin');
            }
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
    <link rel="stylesheet" href="./Styles/ProductModal.css">
    <script src="./Scripts/ProductModal.js"></script>
</head>
<body>
<div class="product-modal-container container">
    <h1 class="main-title">
        <?php
            echo $productId ? "Edit Product" : "Add Product";
        ?>
    </h1>
    <form class="content-wrapper" method="post" enctype="multipart/form-data">
        <label class="image-wrapper" for="image-input">
            <input type="file" title=" " onchange="imageOnChange(this)" id="image-input" name="image" >

            <img src="<?php echo (isset($productImage) ? $productImage : './Images/no-image.jpg')?>" alt="" id="image">
        </label>
        <div>
            <div class="input-wrapper">
                <label for="product-name">Product Name:</label>
                <input type="text" class="input" id="product-name" placeholder="Product Name" name="product-name" value="<?php echo isset($productName) ? $productName : null?>">
            </div>
            <div class="input-wrapper">
                <label for="product-price">Product Price:</label>
                <input type="number" class="input" id="product-price" placeholder="Product Price" name="product-price" value="<?php echo isset($productPrice) ? $productPrice : null?>">
            </div>
            <div class="input-wrapper">
                <label for="product-description">Product Description:</label>
                <input type="text" class="input" id="product-description" placeholder="Product Description" name="product-description" value="<?php echo isset($productDescription) ? $productDescription : null?>">
            </div>
            <div class="radio-wrapper">
                <div>
                    <input type="radio" name="product-category" id='foods' <?php echo ($productCategory === 'foods' ? 'checked' : '')?> value="foods">
                    <label for="foods">Foods</label>
                </div>
                <div>
                    <input type="radio" name="product-category" id="drinks" <?php echo ($productCategory === 'drinks' ? 'checked' : '')?> value="drinks">
                    <label for="drinks">Drinks</label>
                </div>
                <div>
                    <input type="radio" name="product-category" id="equipment" <?php echo ($productCategory === 'equipment' ? 'checked' : '')?> value="equipment">
                    <label for="equipment">Equipment</label>
                </div>
            </div>
            <div class="checkbox-wrapper">
                <input type="checkbox" name="product-status" id="status-1" <?php echo ($productStatus == 1 ? 'checked' : '');?>>
                <label for="status-1">Con hang</label>
            </div>
            <button class="primary-button" type="submit" name="submit" value="save">Save changes</button>
        </div>
    </form>
</div>
</body>
</html>
