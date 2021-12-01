<?php 

if(!isset($_SESSION['user'])) header("Location: index.php");
$user = $_SESSION['user'];
$data = $user['data'];
$nivel = $data['autoridad'];
