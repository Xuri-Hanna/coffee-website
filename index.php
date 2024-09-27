<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coffe</title>
    <link rel="stylesheet" href="styles/Nav.css">
    <link rel="stylesheet" href="styles/MainBase.css">
</head>
<body>
    <?php
        $authenticatedLayouts = ["Login","Register"];
        if(!in_array($_GET['page'], $authenticatedLayouts)){
            require 'Components/NavBar.php';
        }
        require './Views/' . $_GET['page'] . '.php';
        if(!in_array($_GET['page'], $authenticatedLayouts)){
        require 'Components/Footer.php';
        }
    ?>

</body>
</html>
