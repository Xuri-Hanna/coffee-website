<?php
    session_start();
    require_once('./Database/database.php');
    function capitalExplode($item)
    {
        return preg_replace("([A-Z])", " $0", $item);
    }
    $config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'coffee-website'
    ];
    $db = new Database($config);
    if(isset($_SESSION['user_id'])){
        $userId = $_SESSION['user_id'];
        $response = $db->table('user')->get(['id' => $userId]);
        $userImage = $response['image'];
    }
?>
<nav>
    <div class="nav_container">
        <img src="./Images/coffee-cup.png" alt="">
        <div>
            <div class="nav_horizontal">
                <?php

                $items = array('HomePage','AboutUs','ShopNow');
                if(!$_GET['page']){
                    header('location: ?page=HomePage');
                }
                $viewDir = 'Views/';
                foreach ($items as $item){
                    if(isset($_GET['page']) && $_GET['page'] == $item){
                        echo '<a href="?page=' . $item . '" class="active nav_link"> ' . capitalExplode($item) . '</a></br>';
                    }
                    else{
                        echo '<a href="?page=' . $item . '" class="nav_link">' . capitalExplode($item) . '</a></br>';
                    }
                }
                if(!isset($_SESSION['user_id'])){
                    echo '<a href="?page=Login" class="nav_link">Login</a>';
                }
                ?>

            </div>
            <div class="menu">
                <svg id="menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="width: 40px;height: 40px;color: white;cursor: pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                </svg>
                <div id="sub-menu" >
                    <?php

                    $items = array('HomePage','AboutUs','ShopNow');
                    if(!$_GET['page']){
                        header('location: ?page=HomePage');
                    }
                    $viewDir = 'Views/';
                    foreach ($items as $item){
                        if(isset($_GET['page']) && $_GET['page'] == $item){
                            echo '<a href="?page=' . $item . '" class="active nav_link"> ' . capitalExplode($item) . '</a></br>';
                        }
                        else{
                            echo '<a href="?page=' . $item . '" class="nav_link">' . capitalExplode($item) . '</a></br>';
                        }
                    }
                    ?>
                    <a href="?page=Login" class="nav_link">Login</a>
                </div>
                <div id="profile-menu">
                    <?php
                        if(isset($_SESSION['user_id']) && $_SESSION['role'] == 0){
                            echo '<a href="?page=Order" class="nav_link">Tra cứu đơn hàng</a>';
                        }
                        else{
                            echo '<a href="?page=Order" class="nav_link">Quản lí đơn hàng</a>';
                        }
                        if(isset($_SESSION['user_id']) && $_SESSION['role'] == 0){
                            echo '<a href="?page=Cart" class="nav_link">Giỏ hàng</a>';
                        }

                    ?>
                    <?php
                        if(isset($_SESSION['user_id']) && $_SESSION['role'] == 1){
                            echo  '<a href="?page=ProductAdmin" class="nav_link">Quản lý sản phẩm</a>';
                        }
                    ?>
                    <a href="?page=Profile" class="nav_link">Thông tin người dùng</a>
                    <?php
                        if(isset($_SESSION['user_id'])){
                            echo '<a href="./Database/logout.php" class="nav_link">Đăng xuất</a>';
                        }
                    ?>
                </div>
            </div>
            <?php
            if(isset($_SESSION['user_id'])){
                if(isset($userImage)){
                    echo "<div class='profile-wrapper' id='profile'>
                <img src='$userImage' alt=''>
            </div>";
                }
                else{
                    echo '<div class="profile-wrapper" id="profile">
                <img src="./Images/profile.png" alt="">
            </div>';
                }
            }
            ?>

        </div>
    </div>
</nav>
<script type="text/javascript" src="./Scripts/NavBar.js"></script>

