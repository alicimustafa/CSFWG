<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="<?php echo $request_obj->full_url ?>css/CSFWG.css" />
<script src="<?php echo $request_obj->full_url ?>javascript/CSFWG.js"></script>
<title>CSFWG website</title>
</head>

<body>
<div id="wrap">
		<div id="calendar-section">
		<img id="calendar-icon" src="<?php echo $request_obj->full_url ?>images/calendar.png">
	    <div id="calendar-pannel" class="closed-section">
	    </div>
		<div id="calendar-event" class="closed-section">
		</div>
		</div>
	<div id="nav-bar">
	    <ul>
            <li>
	            <a href="/home" class="nav-link" data-link="home">Home</a>
	        </li>
	        <li>
	            <a href="/about" class="nav-link" data-link="about">About</a>
	        </li>
	        <li>
	            <a href="/groups" class="nav-link" data-link="groups">Groups</a>
	        </li>
	        <li>
	            <a href="/workshop" class="nav-link" data-link="workshop">Workshop</a>
	        </li>
	        <li>
	            <a href="/resources" class="nav-link" data-link="resources">Resources</a>
	        </li>
	        <li>
	            <a href="/archive" class="nav-link" <?php echo $nav_display ?> data-link="archive">Archive</a>
	        </li>
	        <li>
	            <a href="/members" class="nav-link" <?php echo $nav_display ?> data-link="members">Members</a>
	        </li>
            <li>
                <a href="/admin" class="nav-link" <?php echo $admin_display ?> data-link="admin">Admin page</a>
            </li>
	    </ul>
	</div>
    <div id="logo">
        <img src="<?php echo $request_obj->full_url ?>images/CSFWG2013.jpg" width="200" height="113" alt="logo" />
    </div>
    <div id="right-pannel">
        <?php include "htmlfrag/right_pannel.php"; ?>
    </div>
    <div id="inner-wrap">
    <div id="loading-pannel" style="display:none"></div>
    <div id="main-pannel">
        <?php include($main_pannel);?>
    </div>
    </div>
    <div id="log-pannel">
        <?php include("htmlfrag/logging.php");  ?>
    </div>
    <div id="social-pannel">
        <ul>
            <li>
            <img src="<?php echo $request_obj->full_url ?>images/index.jpg" width="40" height="40" alt="face book" />
            </li>
            <li>
            <img src="<?php echo $request_obj->full_url ?>images/instagram40_tcm3-36282.png" width="40" height="40" alt="intagram" />            
            <li>
            <img src="<?php echo $request_obj->full_url ?>images/twit.jpg" width="40" height="40" alt="twitter" />
            </li>
            <li>
            <img src="<?php echo $request_obj->full_url ?>images/youtube-icon-small.jpg" width="40" height="40" alt="you tube" />
            </li>
        </ul>
    </div>
</div>
</body>

</html>
