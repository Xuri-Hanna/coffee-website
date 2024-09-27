<script>
    var ex1 = 'foods';
    url = new URL(window.location.href);

    if (!url.searchParams.has('category')) {
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=ShopNow&category=' + ex1;
            window.history.pushState({path: newurl}, '', newurl);
        }
    }
</script>
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
$category = isset($_GET['category']) ? $_GET['category'] : 'foods';
$totalResponse = $db->table('product')->gets(['category' => $category]);
$currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
$limit = 4;
$totalPage = ceil(count($totalResponse) / $limit);
$start = ($currentPage -1 ) * $limit;
$response = $db->table('product')->gets(['category' => $category],$start,$limit);
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_GET['category'])){

        $category = $_GET['category'];
        $response = $db->table('product')->gets(['category' => $category]);
//        $totalResponse = $db->table('product')->gets(['category' => $category]);
//        $totalPage = ceil(count($totalResponse) / $limit);
//        $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
        echo $totalPage;
    }
    if(!empty($_POST['submit']) && $_POST['submit'] == "find"){
        echo $category;
        $response = $db->table('product')->search($_POST['search'],["*"],['category' => $category]);
//        $totalPage = ceil(count($response) / $limit);
//        $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
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
    <link rel="stylesheet" href="./Styles/ShopNow.css">
    <link rel="stylesheet" href="./Styles/MainBase.css">

</head>
<body>
    <div class="products-container container">
        <div>
            <h1 class="main-title">Our Products</h1>
            <form action="" class="search" method="post">
                <input type="text" class="input" id="search" placeholder="Search product" name="search" value="<?php echo $_POST['search'] ?? '' ?>">
                <button class="primary-button" type="submit" name="submit" value="find">Find</button>
            </form>
            <form class="radio-container" method="post">
                <div>
                    <input type="radio" value="foods" name="radio" id="foods" <?php echo $category == 'foods' ? 'checked' : '' ?> onchange="addParameter('foods');this.form.submit()">
                    <label for="foods">Foods</label>
                </div>
                <div>
                    <input type="radio" value="drinks" name="radio" id="drinks" <?php echo $category === 'drinks' ? 'checked' : '' ?> onchange="addParameter('drinks');this.form.submit()">
                    <label for="drinks">Drinks</label>
                </div>
                <div>
                    <input type="radio" value="equipment" name="radio" id="equipment" <?php echo $category == 'equipment' ? 'checked' : '' ?> onchange="addParameter('equipment');this.form.submit()">
                    <label for="equipment">Equipment</label>
                </div>
            </form>
            <div class="grid-container">
                <?php foreach ($response as $item){
                 ?>
                <div class="product-container">
                    <div class="image-wrapper">
                        <img src="<?php echo isset($item['image']) ? $item['image'] : './Images/no-image.jpg'?>">
                    </div>
                    <h2 class="sub-title"><?php echo $item['name']?></h2>
                    <p class="sub-content">
                        $<?php echo $item['price']?>
                    </p>
                    <a class="primary-button" href="?page=Product&id=<?php echo $item['id']?>">Order Now</a>
                </div>
                <?php
                }
                ?>
            </div>
<!--            <div class="center">-->
<!--                <div class="pagination">-->
<!---->
<!--                    --><?php
//                        if($currentPage > 1 && $totalPage > 1){
//                            $leftPage = (int)$currentPage - 1;
//                            echo "<a href='?page=ShopNow&category=$category&currentPage=$leftPage'>&laquo;</a>";
//                        }
//                        for($i = 1; $i <= $totalPage; $i++){
//                            if($i == $currentPage){
//                                echo "<a href='?page=ShopNow&category=$category&currentPage=$i' class='active'>$i</a>";
//                            }
//                            else{
//                                echo "<a href='?page=ShopNow&category=$category&currentPage=$i'>$i</a>";
//                            }
//                        }
//                        if($currentPage < $totalPage && $totalPage > 1){
//                            $rightPage = (int)$currentPage + 1;
//                            echo "<a href='?page=ShopNow&category=$category&currentPage=$rightPage'>&raquo;</a>";
//                        }
//                    ?>
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    function addParameter(category) {
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=ShopNow&category=' + category ;
            window.history.pushState({path: newurl}, '', newurl);
        }
    }
</script>
<style>
    .center {
        text-align: center;
        margin-top: 1rem;
    }

    .pagination {
        display: inline-block;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
        margin: 0 4px;
    }

    .pagination a.active {
        background-color: rgba(188, 110, 50, 1);
        color: white;
        border: 1px solid rgb(80, 44, 20);
    }

    .pagination a:hover:not(.active) {background-color: rgb(80, 44, 20);}
</style>

