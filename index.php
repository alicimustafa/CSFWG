<?php
/*this is the section that will be run when any request comes to the server
it will read the request send the proper page info
if there no request it will just send the home page */
include("crt_functions.php");
include("class/Jwt_signature.php");
include("class/Read_url.php");
//fallowing will list navgation bar links displayed only to loged members
$logged_navbar = "";
if(isset($_REQUEST['request'])){
    $read_url = new Read_url($_REQUEST['request']);
    //fallowing will list navgation bar links displayed only to loged members
    if($read_url->valid_user){
        $logged_navbar = '
	        <li>
	            <a href="index.php?request=archive">Archive</a>
	        </li>
	        <li>
	            <a href="index.php?request=profile">Profile</a>
	        </li>
	        <li>
	            <a href="index.php?request=members">Members</a>
	        </li>
        ';
    }
    switch ($read_url->end_point){
        case "home":
            $main_pannel = "htmlfrag/home.php";
            break;
        case "about":
            $main_pannel = "htmlfrag/about.php";
            break;
        case "archive":
            if($read_url->valid_user){
                $main_pannel = "htmlfrag/archive.php";
            } else {
                $main_pannel = "htmlfrag/unauthorize.php";
            }
            break;
        case "members":
            if($read_url->valid_user){
                $main_pannel = "htmlfrag/members.php";
            } else {
                $main_pannel = "htmlfrag/unauthorize.php";
            }
            break;
        case "profile":
            if($read_url->valid_user){
                $main_pannel = "htmlfrag/profile.php";
            } else {
                $main_pannel = "htmlfrag/unauthorize.php";
            }
            break;
        case "workshop":
            $main_pannel = "htmlfrag/workshop.php";
            break;
        case "resources":
            $main_pannel = "htmlfrag/resources.php";
            break;
        case "groups":
            $main_pannel = "htmlfrag/groups.php";
            break;
        default :
            $main_pannel = "htmlfrag/error.php";
    }
    if($read_url->section){
        include($main_pannel);
    } else {
        include("htmlfrag/shell.php");  
    } 
} else { 
    $main_pannel = "htmlfrag/home.php";
    include("htmlfrag/shell.php");
}
