<?php 

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header("Location: /src/views/pages/backend/login.php");
    exit;
}