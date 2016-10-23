<?php
/*this is the section that will be run when any request comes to the server
it will read the request send the proper page info
if there no request it will just send the home page */
include("crt_functions.php");
function class_autoloader($class){
	include 'class/' . $class . '.php';
}
spl_autoload_register('class_autoloader');
date_default_timezone_set("America/Denver");
/* this will create and request object that will hold 
info used in the rest of the site */
$request_obj = new RequestObject(); 
$jwt_signature = new JwtSignature($request_obj->enc_key);
$request_obj->checkCookie($jwt_signature);
//fallowing will list navigation bar links displayed depends on loged and valid
if($request_obj->valid_user){
    $nav_display = 'style="display:inline"';
} else {
    $nav_display = 'style="display:none"';
}
if(isset($_REQUEST['request'])){
    $request_obj->read_url($_REQUEST['request']);
    $request_obj->checkSyncToken();
    //print_r($request_obj);
    switch ($request_obj->end_point){
        case "home":
            $main_pannel = "htmlfrag/home.php";
            break;
        case "about":
            $main_pannel = "htmlfrag/about.php";
            break;
        case "archive":
            if($request_obj->valid_user){
                $main_pannel = "htmlfrag/archive.php";
            } else {
                $main_pannel = "htmlfrag/unauthorize.php";
            }
            break;
        case "members":
            if($request_obj->valid_user){
                $main_pannel = "htmlfrag/members.php";
            } else {
                $main_pannel = "htmlfrag/unauthorize.php";
            }
            break;
        case "profile":
            $main_pannel = "htmlfrag/profile.php";
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
        case "logging":
            $main_pannel = "htmlfrag/logging.php";
            break;
		case "calendar":
		    $main_pannel = "htmlfrag/calendar.php";
			break;
        default :
            $main_pannel = "htmlfrag/error.php";
    }
    if($request_obj->section){
        include $main_pannel;
    } else {
        include "htmlfrag/shell.php";  
    } 
} else { 
    $main_pannel = "htmlfrag/home.php";
    include "htmlfrag/shell.php";
}
