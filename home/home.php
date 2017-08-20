<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}
//echo 'success redirected' . $_SESSION['username'];
?>

<!DOCTYPE html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <meta name="description" content="Mehdi Dridi. I'm a software engineer obsessed with code quality, software architecture, and building experiences.">
    <script src="../js/home.js"></script>

    <title>home</title>
</head>
<body>

<div class="topnav">
    <div class="boo">
        <a class="active" href="#home">Home</a>
        <a href="#news">News</a>
        <a href="#contact">Contact</a>
        <a href="#about">About</a>
    </div>
    <div class="dropdown">
        <a onclick="dropMenu()" class="dropbtn">Settings</a>
        <div id="myDropdown" class="dropdown-content">
            <a href="#home">Profile </a>
            <a href="../php/logout.php">Logout</a>
        </div>
    </div>

</div>


<div style="padding-left:16px">
    <h2>Top Navigation Example</h2>
    <p>Some content..</p>
    <a class="button button5" href="create.html">Create an encounter</a>
    <a class="button button5">Find an encounter</a>
</div>


</body>
</html>

