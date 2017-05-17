<?php
if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){ 
	$flip_button = "<button class='rotate-button' type='button'>&#8617</button>";
    if(isset($request_obj->arg[0]) and $request_obj->arg[0] === "updateAboutPage"){
        file_put_contents("aboutInfo.txt", $_REQUEST['about_page_text']);
    }
} else {
	$flip_button = "";
}

if(file_exists("aboutInfo.txt")){
    $about_page_info = file_get_contents("aboutInfo.txt");
} else {
    $about_page_info = ""; 
}
?>
<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button; ?>
    <br><br>
    <p id="home-page-announcement"><?php echo $about_page_info; ?></p>
</div>
<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button; ?>
    <form id="update-about-form">
        <textarea id="about-page-text" form="update-about-form" cols="100" rows="35"><?php echo $about_page_info ?></textarea>
        <br>
        <input type="submit" value="Update Info">
    </form>
</div>


