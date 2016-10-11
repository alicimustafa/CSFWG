<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="css/CSFWG.css" />
<script src="javascript/CSFWG.js"></script>
<title>CSFWG website</title>
</head>

<body>
<div id="wrap">
		<div id="calendar-section">
		<img id="calendar-icon" src="images/calendar.png">
	    <div id="calendar-pannel" class="closed-section">
	    </div>
		<div id="calendar-event" class="closed-section">
		</div>
		</div>
	<div id="nav-bar">
	    <ul>
            <li>
	            <a href="index.php?request=home" class="nav-link" data-link="home">Home</a>
	        </li>
	        <li>
	            <a href="index.php?request=about" class="nav-link" data-link="about">About</a>
	        </li>
	        <li>
	            <a href="index.php?request=groups" class="nav-link" data-link="groups">Groups</a>
	        </li>
	        <li>
	            <a href="index.php?request=workshop" class="nav-link" data-link="workshop">Workshop</a>
	        </li>
	        <li>
	            <a href="index.php?request=resources" class="nav-link" data-link="resources">Resources</a>
	        </li>
	        <li>
	            <a href="index.php?request=archive" class="nav-link" <?php echo $nav_display ?> data-link="archive">Archive</a>
	        </li>
	        <li>
	            <a href="index.php?request=members" class="nav-link" <?php echo $nav_display ?> data-link="members">Members</a>
	        </li>
	    </ul>
	</div>
    <div id="logo">
        <img src="images/CSFWG2013.jpg" width="200" height="113" alt="logo" />
    </div>
    <div id="right-pannel">
        some stuff here for the right pannel
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
            <img src="images/index.jpg" width="40" height="40" alt="face book" />
            </li>
            <li>
            <img src="images/instagram40_tcm3-36282.png" width="40" height="40" alt="intagram" />            
            <li>
            <img src="images/twit.jpg" width="40" height="40" alt="twitter" />
            </li>
            <li>
            <img src="images/youtube-icon-small.jpg" width="40" height="40" alt="you tube" />
            </li>
        </ul>
    </div>
</div>
</body>

</html>
