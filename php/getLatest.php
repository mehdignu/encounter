<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("config.php");
$id = $_POST['id'];
if(preg_match("/[a-z]/i", $id) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $id)){
    header("Location: ../index.html");
    return false;
}

$stmt = $connection->prepare('SELECT messages FROM scheduled WHERE id= ?');
$stmt->bind_param('s', $id);

$stmt->execute();

$result = $stmt->get_result();



echo json_encode($result->fetch_array()[0], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

