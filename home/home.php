<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}
//echo 'success redirected' . $_SESSION['username'];

include_once '../php/show.php';


?>

<!DOCTYPE html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    <script src="./frontend.js"></script>

    <script src="https://unpkg.com/vue"></script>


    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <meta name="description"
          content="Mehdi Dridi. I'm a software engineer obsessed with code quality, software architecture, and building experiences.">
    <script src="../js/home.js"></script>

    <title>home</title>
</head>
<body>

<div class="topnav">
    <div class="boo">
        <a class="active" href="#home">Home</a>
        <a href="#news">News</a>
        <a href="#contact">Contact</a>
        <a href="#about">username : <?php echo $_SESSION['username'] ?></a>
    </div>
    <div class="dropdown">
        <a onclick="dropMenu(this.id,nr)" id="navSec" class="dropbtn">Settings</a>
        <div id="myDropdown" class="dropdown-content">
            <a href="#home">Profile </a>
            <a href="../php/logout.php">Logout</a>
        </div>
    </div>

</div>

<input type="hidden" id="userName" value="<?php echo $_SESSION['username'] ?>">

<div style="padding-left:16px">
    <h2>Top Navigation Example</h2>
    <p>Some content..</p>
    <a class="button button5" href="create.html">Create an encounter</a>
    <a class="button button5">Find an encounter</a>
    <br/>
    <!--    <a class="button button6">some event</a> -->

    <?php

    $result = eventsArray();
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="eventwrapper">
            <button onclick="dropMenu(this.id,nr)" id="" class="eventbtn"><?php printf("%s\n",
                    $row["Title"]); ?></button>
            <div id="myDropdown" class="event-details">

                <div class="wrp">

                    <div class="details">
                        <h4>Description:</h4>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                        industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                        type and scrambled it to make a type specimen book.
                    </div>

                    <h4>Date/Time: <span class="timing">10/12/2017 - 18:15pm</span></h4>

                    <h4>Attenders <span class="timing">1 of 5</span></h4>

                    <?php $owner = getOwner($row['owner'])?>


                    <div class="joining"> <!-- compare owner of the event with the session user if they are the same notify the user that someone want's to join the event -->
                        <a class="button button5" id="boo" >Ask to Join</a>
                        <input type="hidden" id="owner" value="<?php echo $owner ?>">
                    </div>

                </div>

            </div>
        </div>


        <?php

    }

    ?>



</div>


<script>


    //getting the number of elemnts inside the page as global variable
    if (document.querySelectorAll('.eventbtn').length > 0) {
        nr = document.querySelectorAll('.eventbtn').length;
    } else {
        nr = 0;
    }

    //make the id's name different
    var buttons = document.querySelectorAll('.eventbtn');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }
    var buttons = document.querySelectorAll('.event-details');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }



</script>
</body>
</html>

