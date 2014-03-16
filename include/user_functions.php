<?php

/**
 * user_functions.php -- eGPS IPP user functions
 *
 * Copyright (c) 2005 Grasslands Regional Division #6
 * All rights reserved
 *
 * Created: June 06, 2005
 * By: M. Nielsen
 *
 * Modified: 2013
 *
 */


/* SUMMARY: a collection of functions called throughout the code base (this is redundant; see filename and comment on line 4. Includes the following:
 * Contents:
 *  getNumUsers()
 *  getUserSchoolCode()
 *  isLocalAdministrator()
 *  getNumUsersOnline()
 *  usernameToCommonname()
 * 
 */

if(!defined('IPP_PATH')) define('IPP_PATH','../'); //assurance that path to application is defined


function getNumUsers() {
    /*returns the number of users in support_member tables TO DO: Contents of support_member tables
    or NULL on fail.*/
    global $error_message;

    if(!connectIPPDB()) {
        $error_message = $error_message;  //just to remember we need this <- to do: Justify this and add Why? comment
        return NULL;
    }

    $query = "SELECT * FROM support_member WHERE 1=1";
    $result = mysql_query($query);
    if(!$result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$query'<BR>";
        return NULL;
    }

    return mysql_num_rows($result);
}

function getUserSchoolCode($egps_username="") {
   global $error_message;

    if(!connectIPPDB()) {
        $error_message = $error_message;  //just to remember we need this
        return NULL;
    }

    $query = "SELECT school_code FROM support_member WHERE egps_username='" . addslashes($egps_username) . "'"; //example of attempt to escapt output
    $result = mysql_query($query);
    if(!$result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$query'<BR>";
        return NULL;
    }
    $user_row=mysql_fetch_array($result);
    return $user_row['school_code'];
}
//checks for admin privileges (boolean)

function isLocalAdministrator($egps_username="") {
   global $error_message;

    if(!connectIPPDB()) {
        $error_message = $error_message;  //just to remember we need this - TO DO: why? comment
        return NULL;
    }

    $query = "SELECT is_local_ipp_administrator FROM support_member WHERE egps_username='" . addslashes($egps_username) . "'";
    $result = mysql_query($query);
    if(!$result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$query'<BR>"; //to do: check into mysql_error() and make note of output
        return FALSE;
    }
    $user_row=mysql_fetch_array($result);
    if($user_row['is_local_ipp_administrator'] == 'Y') return TRUE;
    return FALSE;
}

function getNumUsersOnline() {
    /*returns the number of users in support_member tables
    or NULL on fail.*/
    global $error_message;

    if(!connectIPPDB()) {
        $error_message = $error_message;  //just to remember we need this
        return NULL;
    }

    $query = "SELECT * FROM logged_in WHERE 1=1";
    $result = mysql_query($query);
    if(!$result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$query'<BR>";
        return NULL;
    }

    return mysql_num_rows($result);
}

//to do: ensure $username is not potential security threat: check against field names

//get change firstname.lastname to Firstname Lastname
  function username_to_common($username="-unknown-") {
    //capitalize the first char...my kingdom for a pointer - To do: here the dev expresses need for a better solution. Provide it.
    if(ord($username[0]) < 123 && ord($username[0]) >  96)
      $username[0] =chr(ord($username[0]) - 32);
    $index = strpos($username, '.');
    $username[$index] = ' ';
    $index++;
    if(ord($username[$index]) < 123 && ord($username[$index]) >  96)
      $username[$index] =chr(ord($username[$index]) - 32);
    return $username;
  }
?>
