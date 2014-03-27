<?php
/** @file
 * @brief 	About the application and developers; link to Chelsea School
 * @copyright 	2014 Chelsea School 
 * @copyright 	2005 Grasslands Regional Division #6
 * @copyright		This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * @authors		Rik Goldman, Sabre Goldman, Jason Banks, Alex, James, Paul, Bryan, TJ, Jonathan, Micah, Stephen, Joseph, Sean
 * @author		M. Nielson
 */

/** @mainpage
 * MyIEP Documentation
 * @todo
 * 1. Deploy new functions
 * 2. Move js (Javascript files) into JS folder and change all references
 * 3. Move css out of style folder and into css folder (standard)
 */


//the authorization level for this page!
$MINIMUM_AUTHORIZATION_LEVEL = 100;    //anybody



/**
 * Path for IPP required files.
 */


define('IPP_PATH','./');
require_once(IPP_PATH . 'etc/init.php');

header('Pragma: no-cache'); //don't cache this page!


?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="About MyIEP">
    <meta name="author" content="Rik Goldman" >
    <link rel="shortcut icon" href="./assets/ico/favicon.ico">

    <title>MyIEP</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">MyIEP</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="main.php">Home</a></li>
            <li class="active"><a href="about.php">About</a></li>
             
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Navigation <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Students</a></li>
                <li class="divider"></li>
                <li><a href="#">Reset Password</a></li>
                <li><a href="#">Goals Database</a></li>
                <li><a href="#">Archive</a></li>
                <li><a href="#">Audit</a></li>
                <li><a href="#">Manage Codes</a></li>
                <li><a href="#">Goals Database</a></li>
                <li><a href="#">Manage Schools</a></li>
                <li><a href="#">View Logs</a></li>
              </ul>
            </li>
          </ul>
         </div><!--/.nav-collapse -->
        <!--<div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form" nctype="multipart/form-data" action="jumbotron.php" method="post">
            <div class="form-group">
              <input type="text" placeholder="User Name" class="form-control" value="<?php echo $LOGIN_NAME;?>">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control" name="PASSWORD" value="">
            </div>
            <button type="submit" value="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>
    <div class="jumbotron">
        <h1>About MyIEP</h1>
        <p>MyIEP (Version <?php echo $IPP_CURRENT_VERSION; ?>) was originally developed as IEP-IPP through the coordinated efforts of many people at Grasslands Public Schools.</p>
        <p>MyIEP is under continuing development by students, faculty, and administrators at <h ref="http://chelseaschool.edu">Chelsea School</a> in Hyattsville, MD.</p>
        <p>
          <a class="btn btn-lg btn-primary" href="main.php" role="button">Return to MyIEP &raquo;</a>
        </p>
      </div>

    </div> <!-- /container -->

       
    </BODY>
</HTML>
