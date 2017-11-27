<?php

include("config.php");


/*
 * get the available events
 */
function eventsArray($userName = '')
{


    $result = mysqli_query($GLOBALS['connection'], "SELECT city FROM `users` WHERE users.UserName = '$userName' ");

    $row = mysqli_fetch_row($result);

    $city = $row[0];

    try {

        // Find out how many items are in the table
        $result = mysqli_query($GLOBALS['connection'], "SELECT * FROM `scheduled` WHERE scheduled.city = '$city'");

        // Find out how many items are in the table
        $total =  mysqli_num_rows($result);
        // How many items to list per page
        $limit = 2;

        // How many pages will there be
        $pages = ceil($total / $limit);

        // What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = ($page - 1)  * $limit;

        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);

        // Prepare the paged query

        $stmt = mysqli_query($GLOBALS['connection'], "SELECT * FROM `scheduled` WHERE scheduled.city = '$city' order BY `Date` LIMIT $limit OFFSET $offset");



        // Do we have any results?
        if ($stmt) {
            // Define how we want to fetch the results
            return  $stmt;


        } else {
           // echo '<p>No results could be displayed.</p>';
        }

    } catch (Exception $e) {
        echo '<p>', $e->getMessage(), '</p>';
    }

}

/**
 * get the city of the User
 */
function getCity($userName = ''){
    $result = mysqli_query($GLOBALS['connection'], "SELECT city FROM `users` WHERE users.UserName = '$userName' ");

    $row = mysqli_fetch_row($result);

    return $row[0];
}

/**
 * function to display the pagination informations
 */
function pagination($userName = ''){
    $result = mysqli_query($GLOBALS['connection'], "SELECT city FROM `users` WHERE users.UserName = '$userName' ");

    $row = mysqli_fetch_row($result);

    $city = $row[0];


    try {

        // Find out how many items are in the table
        $result = mysqli_query($GLOBALS['connection'], "SELECT city FROM `users` WHERE users.UserName = '$userName' ");

        // Find out how many items are in the table
        $total =  mysqli_num_rows($result);

        // How many items to list per page
        $limit = 2;

        // How many pages will there be
        $pages = ceil($total / $limit);

        // What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = ($page - 1)  * $limit;

        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);


        // Display the paging information

        //echo '<div aria-label="Page navigation example"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';
        $s = "";
        $t = "";
        $tt = "";



        //start


if($pages > 1){

        $s .= ($page > 1) ? "<li class=\"page-item\"> <a class=\"page-link\" href=\"  ?page=" . ($page - 1) . " \" tabindex=\" " . ($page - 1) . " \">Previous</a> </li>" : '<li class="page-item disabled"> <a class="page-link" href="#" tabindex="-1">Previous</a> </li>';



        $tt .= ($page == $pages && $page-2 > 0) ? "<li class=\"page-item\"> <a class=\"page-link\" href=\"  ?page=" . ($page - 2) . " \" tabindex=\" " . ($page - 2) . " \">".($page-2)."</a> </li>" : '';

        if($tt != "")
            $s.= $tt;

            $s .= ($page-1 > 0) ? "<li class=\"page-item\"> <a class=\"page-link\" href=\"  ?page=" . ($page - 1) . " \" tabindex=\" " . ($page - 1) . " \">".($page-1)."</a> </li>" : '';
                $t .= ($page-1 <= 0 && $page+2 <=  $pages) ? "<li class=\"page-item\"> <a class=\"page-link\" href=\"  ?page=" . ($page + 2) . " \" tabindex=\" " . ($page + 2) . " \">".($page+2)."</a> </li>" : '';





        $s .= "<li class=\"page-item active \"> <a class=\"page-link\" href=\"  ?page=" . ($page) . " \" tabindex=\" " . ($page) . " \">$page</a> </li>";



            $s .= ($page+1 <= $pages  ) ? "<li class=\"page-item\"> <a class=\"page-link\" href=\"  ?page=" . ($page + 1) . " \" tabindex=\" " . ($page + 1) . " \">".($page+1)."</a> </li>" : '';

        if($t != "")
            $s.= $t;





        //end of the pagination
        $s .= ($page < $pages) ? "<li class=\"page-item\"><a class=\"page-link\" href=\"  ?page=" . ($page + 1) . " \" tabindex=\" " . ($page + 1) . " \">Next</a></li>" : '<li class="page-item disabled"> <a class="page-link" href="#" tabindex="-1">Next</a> </li>';


}
        echo  $s;

    } catch (Exception $e) {
        echo '<p>', $e->getMessage(), '</p>';
    }

}


/*
 * get the notifications requests count for the userName
 */
function getNotiCount($userName = ''){
    $query = "select ReqCount from users where users.UserName = '$userName'";
    $result = mysqli_query($GLOBALS['connection'], $query);

    $row = mysqli_fetch_row($result);

    $count = $row[0];
    return $count;
}


/*
 * get the notifications encounters count for the userName
 */
function getNotiEncCount($userName = ''){
    $query = "select EncCount from users where users.UserName = '$userName'";
    $result = mysqli_query($GLOBALS['connection'], $query);

    $row = mysqli_fetch_row($result);

    $count = $row[0];
    return $count;
}

/*
 * get the owner username of the event from the id
 */
function getOwner($id)
{
    $ownerName = '';
    $result = mysqli_query($GLOBALS['connection'], "SELECT `UserName` FROM `users` where `id` = '$id'");
    while ($row = mysqli_fetch_assoc($result)) {
        $ownerName = $row['UserName'];
        break;
    }
    return $ownerName;
}

/*
 * get the requests already sent to the user to show it on
 * the notification bar
 */
function getRequests($userName = '')
{

    //get requested events form the username and the requester
    $result = mysqli_query($GLOBALS['connection'], "
        SELECT k.UserName as 'requester', d.Title, s.requestID
    FROM users u
        inner join requests s on u.id = s.owner
            inner join scheduled d on d.id = s.eventID
            inner join users k on k.id=s.requester
    WHERE u.UserName = '$userName'
        ");

    $rows = array();
    while ($row = $result->fetch_array()) {
        $rows[] = $row;
    }


    return $rows;

}

/*
 * check if username already requested to join event
 */
function isRequested($userName= '', $id=NULL){

    $userID = mysqli_query($GLOBALS['connection'], "SELECT `id` FROM `users` WHERE `UserName` = '$userName'");
    $userID = mysqli_fetch_assoc($userID);
    $userID = $userID['id'];

    $result = mysqli_query($GLOBALS['connection'], "
        SELECT * FROM `requests` WHERE requester='$userID' and eventID = '$id'
        ");

    if($result->num_rows === 0){
        return false;
    } else {
        return true;
    }
}


/*
 * check if username already accepted to the event
 */
function isAccepted($userName= '', $id=NULL){

    $userID = mysqli_query($GLOBALS['connection'], "SELECT `id` FROM `users` WHERE `UserName` = '$userName'");
    $userID = mysqli_fetch_assoc($userID);
    $userID = $userID['id'];

    $result = mysqli_query($GLOBALS['connection'], "
        SELECT partID FROM `participations` WHERE EventID = '$id' AND MemberID = '$userID'
        ");

    if($result->num_rows === 0){
        return false;
    } else {
        return true;
    }
}


/*
 * value of checked for the intro jumbo
 */
function isChecked($userName = ''){
    $query = "select Checked from users where users.UserName = '$userName'";
    $result = mysqli_query($GLOBALS['connection'], $query);

    $row = mysqli_fetch_row($result);

    $isChecked = $row[0];
    return $isChecked;
}


/**
 * @param string $id
 * get the attenders of the event to show their images
 */
function getAttenders($id=''){

    $result = mysqli_query($GLOBALS['connection'], "
        SELECT u.UserName as 'attenders'
    FROM users u

            INNER JOIN participations p on p.MemberID = u.id
            INNER JOIN scheduled s on s.id = p.EventID
    WHERE s.id = $id
        ");

    $rows = array();
    while ($row = $result->fetch_array()) {
        $rows[] = $row;
    }

    return $rows;

}

/**
 * @param userName $get the encounters of the user
 */
function getOwnEncounters($userName = ''){
    $query = "select id from users where users.UserName = '$userName'";
    $result = mysqli_query($GLOBALS['connection'], $query);

    $row = mysqli_fetch_row($result);

    $id = $row[0];

    $query = "select * from scheduled where scheduled.owner = '$id'";
    $result = mysqli_query($GLOBALS['connection'], $query);


    return $result;
}

/**
 *
 *get the encounter to edit
 */
function getEncounter($id=''){
    $query = "select * from scheduled where scheduled.id = '$id'";
    $result = mysqli_query($GLOBALS['connection'], $query);

    return $result;
}


/**
 *
 *get the encounter to edit
 */
function getUserName($user=''){
    $query = "select FirstName, LastName from users where users.Username = '$user'";
    $result = mysqli_query($GLOBALS['connection'], $query);
    $row = mysqli_fetch_row($result);

    //only first name
    $name = $row[0];

    return $name;
}