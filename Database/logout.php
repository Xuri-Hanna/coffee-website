<?php
    header("location: http://" . $_SERVER['SERVER_NAME'] . '/coffe_website/' . '?page=HomePage');
    session_start();
    session_destroy();
?>
