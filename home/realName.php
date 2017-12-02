<?php

include_once '../php/show.php';


$auth = $_GET['auth'];



echo getUserName($auth);