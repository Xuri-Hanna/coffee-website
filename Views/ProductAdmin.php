<?php
    require_once('./Database/database.php');
    include('./Database/admin_role.php');
    $config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'coffee-website'
    ];
    $db = new Database($config);
    $response = $db->table('product')->gets();
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
                <h1 class="main-title">Quản lý sản phẩm</h1>
                <a class="primary-button" href="?page=ProductModal">Thêm sản phẩm</a>
            </div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php
                    if($response != null){
                        foreach ($response as $product){
                ?>
                <tr>
                    <td><?php echo $product['id'] ?></td>
                    <td><?php echo $product['name'] ?></td>
                    <td><?php echo $product['price'] ?></td>
                    <td><?php echo $product['status'] == '1' ? 'Còn hàng' : 'Hết hàng' ?></td>
                    <td class="buttons">
                        <a class="primary-button" href="<?php echo "?page=ProductModal&id=". $product['id']; ?>">Sửa</a>
                        <form action="">
                            <a class="primary-button" href="./Database/delete_product.php?id=<?php echo $product['id']?>" target="_self">Xoá</a>
                        </form>
                    </td>
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
