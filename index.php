<?php
include("crt_functions.php");
include("class/Jwt_signature.php");
include("class/Read_url.php");
if(isset($_REQUEST['request'])){
    $read_url = new Read_url($_REQUEST['request']);
    switch ($read_url->end_point){
        case "home":
            $main_pannel = "htmlfrag/home.php";
            break;
        case "about":
            $main_pannel = "htmlfrag/about.php";
            break;
        case "archive":
            $main_pannel = "htmlfrag/archive.php";
            break;
        case "members":
            $main_pannel = "htmlfrag/members.php";
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
