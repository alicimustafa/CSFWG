<?php 
if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){ 
	$flip_button = "<button class='rotate-button' type='button'>&#8617</button>";
    if(isset($request_obj->arg[0]) and $request_obj->arg[0] === "updateAnnouncement"){
        file_put_contents("announcement.txt", $_REQUEST['announcement_text']);
    }
} else {
	$flip_button = "";
}
if(file_exists("announcement.txt")){
    $home_page_announcement = file_get_contents("announcement.txt");
} else {
   $home_page_announcement = ""; 
}

?>
<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button; ?>
    <br><br>
    <p id="home-page-announcement"><?php echo $home_page_announcement; ?></p>
</div>
<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button; ?>
    <form id="add-announcement-form">
        <textarea id="announcement-text" form="add-announcement-form" cols="100" rows="35"><?php echo $home_page_announcement ?></textarea>
        <br>
        <input type="submit" value="Update announcement">
    </form>
</div>


