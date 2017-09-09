<?php

include("config.php");


/*
 * get the available events
 */
function eventsArray()
{
    $result = mysqli_query($GLOBALS['connection'], "SELECT * FROM `scheduled`");
    return $result;

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
function isRequested($userName= '', $id){

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



