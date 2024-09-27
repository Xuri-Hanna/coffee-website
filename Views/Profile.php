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
    $userId = null;
    $name = '';
    $email = '';
    $phoneNumber = '';
    $address = '';
    $userImage = null;
    if(isset($_SESSION['user_id'])){
        $userId = $_SESSION['user_id'];
        $response = $db->table('user')->get(['id' => $userId]);
        $name = $response['username'];
        $email = $response['email'];
        $phoneNumber = $response['phoneNumber'];
        $address = $response['address'];
        $userImage = $response['image'];
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(!empty($_POST['submit']) && $_POST['submit'] == 'save'){
            if(!empty($_POST['name']) && !empty($_POST['email'])){

                $name = $_POST['name'];
                $email = $_POST['email'];
                $phoneNumber = $_POST['phone-number'];
                $address = $_POST['address'];
                $userImage = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : null;
                $data =['username' => $name,'email' => $email,'phoneNumber' => $phoneNumber,'address' => $address];
                if(isset($userImage)) {
                    $path = 'uploads/' . $_FILES['image']['name'];
                    $moved = move_uploaded_file($userImage, $path);
                    if( $moved ) {
                        echo "Successfully uploaded";
                        $data = array_merge($data, ['image' => $path]);
                    } else {
                        echo "Not uploaded because of error #".$_FILES["image"]["error"];
                    }
                }
                $db->table('user')->update($userId,$data);
                header('location: ?page=HomePage');
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
    <link rel="stylesheet" href="./Styles/Profile.css">
    <script src="./Scripts/ProductModal.js"></script>
</head>
<body>
    <div class="profile-container container">
        <h1 class="main-title">User Profile</h1>
        <div>
            <form action="" method="post" enctype="multipart/form-data">
                <label class="image-wrapper" for="image-input">
                    <input type="file" title=" " onchange="imageOnChange(this)" id="image-input" name="image" >

                    <img src="<?php echo (isset($userImage) ? $userImage : './Images/profile.png')?>" alt="" id="image">
                </label>
                <div class="input-wrapper">
                    <label for="your-name">Your Name:</label>
                    <input type="text" class="input" id="your-name" placeholder="Your Name" value="<?php echo $name?>" name="name">
                </div>
                <div class="input-wrapper">
                    <label for="your-email">Your Email:</label>
                    <input type="text" class="input" id="your-email" placeholder="Your Email" value="<?php echo $email?>" name="email">
                </div>
                <div class="input-wrapper">
                    <label for="your-phone-number">Your Phone Number:</label>
                    <input type="number" class="input" id="your-phone-number" placeholder="Your Phone Number" value="<?php echo $phoneNumber?>" name="phone-number">
                </div>
                <div class="input-wrapper">
                    <label for="your-address">Your Address:</label>
                    <input type="text" class="input" id="your-address" placeholder="Your Address" value="<?php echo $address?>" name="address">
                </div>
                <button class="primary-button" type="submit" name="submit" value="save">Save changes</button>
            </form>
        </div>
    </div>
</body>
</html>
