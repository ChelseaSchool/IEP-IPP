<?php

/*! @file
 *  @brief 	manage and edit student accomodations
 * @copyright 	2014 Chelsea School 
 * @copyright 	2005 Grasslands Regional Division #6
 * @copyright		This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * @authors		Rik Goldman, Sabre Goldman, Jason Banks, Alex, James, Paul, Bryan, TJ, Jonathan, Micah, Stephen, Joseph, Sean
 * @author		M. Nielson
 * @todo		Filter input
 */
 
 
/** @var		$MINIMUM_AUTHORIZATION_LEVEL
 *  @brief		the authorization level for this page. Integer.
 *  @author	M. Nielson
 *  @todo
 * 	1. Cast as a function to be called with the auth level as a parameter
 *  2. Call function with auth level from all code in code base
 */
$MINIMUM_AUTHORIZATION_LEVEL = 100; //everybody



/*   INPUTS: $_GET['student_id']
 *
 */

/** @var		$system_message
 *  @brief		Makes sure the value is cleared.
 *  @details	The purpose of this variable is unclear. But it's the only one that is secured on most pages.
 *  @todo		call is a function...perhaps a single page init function
 */

$system_message = "";

/** @var
 *  @brief		constant - relative path to IEP-IPP directory
 *  @detail		
 * 	Provides most pages the path to IEP-IPP/ wherein all project code can be found, including  and include.
 * 	@author		M. Nielson
 * 	@todo		
 * 	1. Move contents of  to parent directory
 *  2. Change the definition of IPP_PATH throughout code base
 *  3. Replace with function
 *  4. It's a constant - reconsider whether it should be in all files
 * 
 */
 
define('IPP_PATH','./');

/* eGPS required files. */
require_once(IPP_PATH . 'etc/init.php');
require_once(IPP_PATH . 'include/db.php');
require_once(IPP_PATH . 'include/auth.php');
require_once(IPP_PATH . 'include/log.php');
require_once(IPP_PATH . 'include/user_functions.php');
require_once(IPP_PATH . 'include/navbar.php');
require_once(IPP_PATH . 'include/supporting_functions.php');

header('Pragma: no-cache'); //don't cache this page!

if(isset($_POST['LOGIN_NAME']) && isset( $_POST['PASSWORD'] )) {
    if(!validate( $_POST['LOGIN_NAME'] , $_POST['PASSWORD'] )) {
        $system_message = $system_message . $error_message;
        IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
        require(IPP_PATH . 'login.php');
        exit();
    }
} else {
    if (!validate()) {
        $system_message = $system_message . $error_message;
        IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
        require(IPP_PATH . 'login.php');
        exit();
    }
}
//************* SESSION active past here **************************

$student_id=""; //cleared for security
	if (isset($_GET['student_id'])) $student_id= $_GET['student_id'];  //in case of get
	if (isset($_POST['student_id'])) $student_id = $_POST['student_id']; //in case of post

   if ($student_id=="") {
   //we shouldn't be here without a student id.
		echo "You've entered this page without supplying a valid student id. Fatal, quitting";
		exit();
}
//check permission levels
$permission_level = getPermissionLevel($_SESSION['egps_username']);
if ($permission_level > $MINIMUM_AUTHORIZATION_LEVEL || $permission_level == NULL) {
    $system_message = $system_message . "You do not have permission to view this page (IP: " . $_SERVER['REMOTE_ADDR'] . ")";
    IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
    require(IPP_PATH . 'security_error.php');
    exit();
}

$our_permission = getStudentPermission($student_id);
if($our_permission == "WRITE" || $our_permission == "ASSIGN" || $our_permission == "ALL") {
    //we have write permission.
    $have_write_permission = true;
}  else {
    $have_write_permission = false;
}

//************** validated past here SESSION ACTIVE WRITE PERMISSION CONFIRMED****************

$student_query = "SELECT * FROM student WHERE student_id = " . mysql_real_escape_string($student_id);
$student_result = mysql_query($student_query);
if(!$student_result) {
    $error_message = $error_message . "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$student_query'<BR>";
    $system_message=$system_message . $error_message;
    IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
} else {$student_row= mysql_fetch_array($student_result);}

if(isset($_GET['add_accomodation']) && $have_write_permission) {

   //check for duplicate...naw
   //$check_query = "SELECT * FROM accomodation WHERE accomodation='" . mysql_real_escape_string($_GET['program_area']) . "' AND end_date IS NULL AND student_id=" . mysql_real_escape_string($student_id);
   //$check_result = mysql_query($check_query);
   //if(!$check_result) {
   //   $error_message = $error_message . "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$check_query'<BR>";
   //   $system_message=$system_message . $error_message;
   //   IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
   //} else {
   //    if(mysql_num_rows($check_result) > 0) {
   //        $check_row = mysql_fetch_array($check_result);
   //        $system_message = $system_message . "That is already a program area of this student<BR>";
   //    } else {
           
           $add_query = "INSERT INTO accomodation (student_id,accomodation,start_date,end_date,subject) VALUES (" . mysql_real_escape_string($student_id) . ",'" . mysql_real_escape_string($_GET['accomodation']) . "',NOW(),NULL,'" . mysql_real_escape_string($_GET['subject']) . "')";
           $add_result = mysql_query($add_query);
           if(!$add_result) {
              $error_message = $error_message . "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$add_query'<BR>";
              $system_message=$system_message . $error_message;
              IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
           } else {
             unset($_GET['accomodation']);
             unset($_GET['subject']);
           }
    //   }
    //}
   //$system_message = $system_message . $add_query . "<BR>";
}

//check if we are deleting some areas...
if(isset($_GET['delete_x']) && $permission_level <= $IPP_MIN_DELETE_ACCOMODATION && $have_write_permission ) {
    $delete_query = "DELETE FROM accomodation WHERE ";
    foreach($_GET as $key => $value) {
        if(preg_match('/^(\d)*$/',$key))
        $delete_query = $delete_query . "uid=" . $key . " or ";
    }
    //strip trailing 'or' and whitespace
    $delete_query = substr($delete_query, 0, -4);
    //$system_message = $system_message . $delete_query . "<BR>";
    $delete_result = mysql_query($delete_query);
    if(!$delete_result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$delete_query'<BR>";
        $system_message= $system_message . $error_message;
        IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
    }
    //$system_message = $system_message . $delete_query . "<BR>";
}

//check if we are setting some no longer active...
if(isset($_GET['set_not_active']) && $have_write_permission ) {
    $modify_query = "UPDATE accomodation SET end_date=NOW() WHERE ";
    foreach($_GET as $key => $value) {
        if(preg_match('/^(\d)*$/',$key))
        $modify_query = $modify_query . "uid=" . $key . " OR ";
    }
    //strip trailing 'or' and whitespace
    $modify_query = substr($modify_query, 0, -4);
    //$system_message = $system_message . $modify_query . "<BR>";
    $modify_result = mysql_query($modify_query);
    if(!$modify_result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$modify_query'<BR>";
        $system_message= $system_message . $error_message;
        IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
    }
}

//check if we are setting some no longer active...
if(isset($_GET['set_continue']) && $have_write_permission ) {
    $modify_query = "UPDATE accomodation SET end_date=NULL WHERE ";
    foreach($_GET as $key => $value) {
        if(preg_match('/^(\d)*$/',$key))
        $modify_query = $modify_query . "uid=" . $key . " OR ";
    }
    //strip trailing 'or' and whitespace
    $modify_query = substr($modify_query, 0, -4);
    //$system_message = $system_message . $modify_query . "<BR>";
    $modify_result = mysql_query($modify_query);
    if(!$modify_result) {
        $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$modify_query'<BR>";
        $system_message= $system_message . $error_message;
        IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
    }
}

$accomodation_query = "SELECT * FROM accomodation WHERE student_id=" . mysql_real_escape_string($student_id) . " ORDER BY end_date ASC,start_date DESC";
$accomodation_result = mysql_query($accomodation_query);
if(!$accomodation_result) {
    $error_message = $error_message . "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$accomodation_query'<BR>";
    $system_message=$system_message . $error_message;
    IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
}

/******************** popup chooser support function ******************/
    function createJavaScript($dataSource,$arrayName='rows'){
      // validate variable name
      if(!is_string($arrayName)){
        $system_message = $system_message . "Error in popup chooser support function name supplied not a valid string  (" . __FILE__ . ":" . __LINE__ . ")";
        return FALSE;
      }

    // initialize JavaScript string
      $javascript='<!--Begin popup array--><script>var '.$arrayName.'=[];';

    // check if $dataSource is a file or a result set
      if(is_file($dataSource)){
       
        // read data from file
        $row=file($dataSource);

        // build JavaScript array
        for($i=0;$i<count($row);$i++){
          $javascript.=$arrayName.'['.$i.']="'.trim($row[$i]).'";';
        }
      }

      // read data from result set
      else{

        // check if we have a valid result set
        if(!$numRows=mysql_num_rows($dataSource)){
          die('Invalid result set parameter');
        }
        for($i=0;$i<$numRows;$i++){
          // build JavaScript array from result set
          $javascript.=$arrayName.'['.$i.']="';
          $tempOutput='';
          //output only the first column
          $row=mysql_fetch_array($dataSource);

          $tempOutput.=$row[0].' ';

          $javascript.=trim($tempOutput).'";';
        }
      }
      $javascript.='</script><!--End popup array-->'."\n";

      // return JavaScript code
      return $javascript;
    }

    function echoJSServicesArray() {
        global $system_message;
        $acclist_query="SELECT accomodation FROM typical_accomodation WHERE 1 ORDER BY `order` DESC, accomodation ASC LIMIT 400";
        $acclist_result = mysql_query($acclist_query);
        if(!$acclist_result) {
            $error_message = "Database query failed (" . __FILE__ . ":" . __LINE__ . "): " . mysql_error() . "<BR>Query: '$acclist_query'<BR>";
            $system_message= $system_message . $error_message;
            IPP_LOG($system_message,$_SESSION['egps_username'],'ERROR');
        } else {
            //call the function to create the javascript array...
            if(mysql_num_rows($acclist_result)) echo createJavaScript($acclist_result,"popuplist");
        }
    }
/************************ end popup chooser support funtion  ******************/

?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
    <TITLE><?php echo $page_title; ?></TITLE>
    <style type="text/css" media="screen">
        <!--
            @import "<?php echo IPP_PATH;?>layout/greenborders.css";
        -->
    </style>
   
    <script language="javascript" src="<?php echo IPP_PATH . "include/popupchooser.js"; ?>"></script>
    <script language="javascript" src="<?php echo IPP_PATH . "include/autocomplete.js"; ?>"></script>
    <?php
       //output the javascript array for the chooser popup
       echoJSServicesArray();
    ?>
    <SCRIPT LANGUAGE="JavaScript">
      function confirmChecked() {
          var szGetVars = "delete_supervisor=";
          var szConfirmMessage = "Are you sure you want to modify/delete accomodations(s):\n";
          var count = 0;
          form=document.accomodationhistory;
          for(var x=0; x<form.elements.length; x++) {
              if(form.elements[x].type=="checkbox") {
                  if(form.elements[x].checked) {
                     szGetVars = szGetVars + form.elements[x].name + "|";
                     szConfirmMessage = szConfirmMessage + "ID #" + form.elements[x].name + "\n";
                     count++;
                  }
              }
          }
          if(!count) { alert("Nothing Selected"); return false; }
          if(confirm(szConfirmMessage))
              return true;
          else
              return false;
      }

      function noPermission() {
          alert("You don't have the permission level necessary"); return false;
      }
    </SCRIPT>
</HEAD>
    <BODY>
        <table class="shadow" border="0" cellspacing="0" cellpadding="0" align="center">  
        <tr>
          <td class="shadow-topLeft"></td>
            <td class="shadow-top"></td>
            <td class="shadow-topRight"></td>
        </tr>
        <tr>
            <td class="shadow-left"></td>
            <td class="shadow-center" valign="top">
                <table class="frame" width=620px align=center border="0">
                    <tr align="Center">
                    <td><center><img src="<?php echo $page_logo_path; ?>"></center></td>
                    </tr>
                    <tr><td>
                    <center><?php navbar("student_view.php?student_id=$student_id"); ?></center>
                    </td></tr>
                    <tr>
                        <td valign="top">
                        <div id="main">
                        <?php if ($system_message) { echo "<center><table width=\"80%\"><tr><td><p class=\"message\">" . $system_message . "</p></td></tr></table></center>";} ?>

                        <center><table><tr><td><center><p class="header">-Accommodations (<?php echo $student_row['first_name'] . " " . $student_row['last_name']; ?>)-</p></center></td></tr></table></center>
                        <BR>

                        <!-- BEGIN add accommodation -->
                        <center>
                        <form name="addaccomodation" enctype="multipart/form-data" action="<?php echo IPP_PATH . "accomodations.php"; ?>" method="get" <?php if(!$have_write_permission) echo "onSubmit=\"return noPermission();\"" ?>>
                        <table border="0" cellspacing="0" cellpadding ="0" width="80%">
                        <tr>
                          <td colspan="3">
                          <p class="info_text">Edit and click 'Add'.</p>
                           <input type="hidden" name="add_accomodation" value="1">
                           <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                          </td>
                        </tr>
                        <tr>
                            <td valign="bottom" bgcolor="#E0E2F2">Accommodation: </td><td bgcolor="#E0E2F2">
                            <input type="text" tabindex="1" name="accomodation" size="40" maxsize="255" value="<?php if(isset($_GET['accomodation'])) echo $_GET['accomodation']; ?>">&nbsp;<img src="<?php echo IPP_PATH . "images/choosericon.png"; ?>" height="17" width="17" border=0 onClick="popUpChooser(this,document.all.accomodation)" >
                            </td>
                            <td valign="center" align="center" bgcolor="#E0E2F2" rowspan="2"><input type="submit" tabindex="3" ="add" value="add"></td>
                        </tr>
                        <tr>
                            <td valign="bottom" bgcolor="#E0E2F2">Subject or Area:</td><td bgcolor="#E0E2F2">
                            <input type="text" tabindex="2" name="subject" size="40" maxsize="255" value="<?php if(isset($_GET['subject'])) echo $_GET['subject']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td valign="bottom" align="center" bgcolor="#E0E2F2" colspan="3">&nbsp;</td>
                        </tr>
                        </table>
                        </form>
                        </center>
                        <!-- END add accomodation -->

                        <!-- BEGIN accomodation history table -->
                        <form name="accomodationhistory" onSubmit="return confirmChecked();" enctype="multipart/form-data" action="<?php echo IPP_PATH . "accomodations.php"; ?>" method="get">
                        <input type="hidden" name="student_id" value="<?php echo $student_id ?>">
                        <center><table width="80%" border="0">

                        <?php
                        $bgcolor = "#DFDFDF";

                        //print the header row...
                        echo "<tr><td bgcolor=\"#E0E2F2\">&nbsp;</td><td bgcolor=\"#E0E2F2\">UID</td><td align=\"center\" bgcolor=\"#E0E2F2\">Accommodation</td><td align=\"center\" bgcolor=\"#E0E2F2\">Subject or Area</td><td align=\"center\" bgcolor=\"#E0E2F2\">Start Date</td><td align=\"center\" bgcolor=\"#E0E2F2\">End Date</td></tr>\n";
                        while ($accomodation_row=mysql_fetch_array($accomodation_result)) { //current...
                            echo "<tr>\n";
                            echo "<td bgcolor=\"#E0E2F2\"><input type=\"checkbox\" name=\"" . $accomodation_row['uid'] . "\"></td>";
                            echo "<td bgcolor=\"$bgcolor\" class=\"row_default\">" . $accomodation_row['uid'] . "</td>";
                            echo "<td bgcolor=\"$bgcolor\" class=\"row_default\"><a href=\"" . IPP_PATH . "edit_accomodations.php?uid=" . $accomodation_row['uid'] . "\" class=\"editable_text\">" . checkspelling($accomodation_row['accomodation'])  ."</a></td>\n";
                            echo "<td bgcolor=\"$bgcolor\" class=\"row_default\"><a href=\"" . IPP_PATH . "edit_accomodations.php?uid=" . $accomodation_row['uid'] . "\" class=\"editable_text\">" . $accomodation_row['subject'] . "</a></td>\n";
                            echo "<td bgcolor=\"$bgcolor\" class=\"row_default\"><a href=\"" . IPP_PATH . "edit_accomodations.php?uid=" . $accomodation_row['uid'] . "\" class=\"editable_text\">" . $accomodation_row['start_date'] . "</a></td>\n";
                            if($accomodation_row['end_date'] =="")
                                echo "<td bgcolor=\"$bgcolor\" class=\"row_default\" width=\"60\"><a href=\"" . IPP_PATH . "edit_accomodations.php?uid=" . $accomodation_row['uid'] . "\" class=\"editable_text\">-Current-</a></td>\n";
                            else
                                echo "<td bgcolor=\"$bgcolor\" class=\"row_default\"><a href=\"" . IPP_PATH . "edit_accomodations.php?uid=" . $accomodation_row['uid'] . "\" class=\"editable_text\">" . $accomodation_row['end_date'] . "</a></td>\n";
                            echo "</tr>\n";
                            if($bgcolor=="#DFDFDF") $bgcolor="#CCCCCC";
                            else $bgcolor="#DFDFDF";
                        }
                        ?>
                        <tr>
                          <td colspan="6" align="left">
                             <table>
                             <tr>
                             <td nowrap>
                                <img src="<?php echo IPP_PATH . "images/table_arrow.png"; ?>">&nbsp;With Selected:
                             </td>
                             <td>
                             <?php
                                if($have_write_permission) {
                                    echo "<INPUT NAME=\"set_not_active\" TYPE=\"image\" SRC=\"" . IPP_PATH . "images/smallbutton.php?title=End\" border=\"0\" value=\"set_not_active\">";
                                    echo "<INPUT NAME=\"set_continue\" TYPE=\"image\" SRC=\"" . IPP_PATH . "images/smallbutton.php?title=Continue\" border=\"0\" value=\"set_continue\">";
                                }
                                //if we have permissions also allow delete and set all.
                                if($permission_level <= $IPP_MIN_DELETE_ACCOMODATION && $have_write_permission) {
                                    echo "<INPUT NAME=\"delete\" TYPE=\"image\" SRC=\"" . IPP_PATH . "images/smallbutton.php?title=Delete\" border=\"0\" value=\"delete\">";
                                }
                             ?>
                             </td>
                             </tr>
                             </table>
                          </td>
                        </tr>
                        </table></center>
                        </form>
                        <!-- end accomodation history table -->

                        </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td class="shadow-right"></td>   
        </tr>
        <tr>
            <td class="shadow-left">&nbsp;</td>
            <td class="shadow-center">
            <?php navbar("student_view.php?student_id=$student_id"); ?>
            </td>
            <td class="shadow-right">&nbsp;</td>
        </tr>
        <tr>
            <td class="shadow-bottomLeft"></td>
            <td class="shadow-bottom"></td>
            <td class="shadow-bottomRight"></td>
        </tr>
        </table> 
        <center></center>
    </BODY>
</HTML>
