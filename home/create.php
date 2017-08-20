<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}
?>

<!DOCTYPE html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <meta name="description" content="Mehdi Dridi. I'm a software engineer obsessed with code quality, software architecture, and building experiences.">
    <script src="../js/home.js"></script>

    <!-- maps loccation choose -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyClipRGnKbruxY1Kr8asJARGaX4RnMmxPs'></script>
    <script src="../js/locationpicker.jquery.js"></script>

    <title>create encounter</title>
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
    <form name="register" id='register' action='../php/register.php' method='post' accept-charset='UTF-8'>
        <h3>Register:</h3>

        <label><b>Title</b></label>
        <input type="text" placeholder="Title" name="title" required><br />

        <label><b>Description</b></label>
        <textarea placeholder="Description" class="text" cols="35" rows ="5" name="description"></textarea><br />

        <label><b>location</b></label>
        Location: <input type="text" id="us3-address" style="width: 200px"/>
       <div id="loc" style="width: 500px; height: 400px;" ></div><br />

        <label><b>date</b></label>
        

        <br />
        <button type="submit">Register</button>

    </form>
</div>


<script>
    $('#loc').locationpicker({
        location: {
            latitude: 46.15242437752303,
            longitude: 2.7470703125
        },
        radius: 300,
        inputBinding: {
            locationNameInput: $('#us3-address')
        },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {
            //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
        }
    });
</script>

</body>
</html>

