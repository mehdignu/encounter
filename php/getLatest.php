<?php
include("config.php");
$id = $_POST['id'];

$query = "SELECT messages FROM scheduled WHERE id=$id";

$result = mysqli_query($connection, $query);


echo json_encode($result->fetch_array()[0]);

exit;