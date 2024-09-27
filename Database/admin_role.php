<?php
//session_start();
if(empty($_SESSION['user_id']) || $_SESSION['role'] != 1) header('location: ?page=Login ');
