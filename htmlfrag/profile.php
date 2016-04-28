<?php 
/* 
this file creates and displays member profile
it will also give sertan member ability to modify
this info
*/
include_once("crt_functions.php");
// default for error
$password_err="";
$verify_err="";
// variable that hold default state for tabs and sections
$profile_section ="open-section";
$submit_section ="closed-section";
$dues_section ="closed-section";
$profile_tab ="open-tab";
$submit_tab ="closed-tab";
$dues_tab ="closed-tab";

$full_display = false; // this variable conrols if you can see the whole profile or not
$col_select = "
    SELECT 
    members.member_id,
    members.first_nm,
    members.last_nm,
    members.email,
    member_profile.member_phone,
    member_profile.member_address,
    member_profile.member_city,
    member_profile.member_state,
    member_profile.member_zip,
    member_profile.member_privacy,
	member_profile.member_pic,
    member_profile.member_qt,
    ranks.rank_name
	FROM members
	INNER JOIN member_profile
	ON members.member_id = member_profile.member_id
	INNER JOIN ranks
    ON members.rank_id = ranks.rank_id
	WHERE members.member_id = :id
";
if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer" or $request_obj->user_id == $request_obj->arg[0]){
	/* 
	this section for options for members that
	can modify profile info, update picture and upload submition
	if will diplay proper info on the back pannel
	 this includes officers and the member that owns the profile
	*/
	if(isset($request_obj->arg[1])){ 
	    /*
		this section checks to see if an action was set on this page
		like upload picture update profile
		*/
		if($request_obj->arg[1] == "updateProfile"){ // this is for updating profile for a member
			$up_array[':address'] = test_input($_REQUEST['address']);
			$up_array[':city'] = test_input($_REQUEST['city']);
			$up_array[':state'] = test_input($_REQUEST['state']);
			$up_array[':zip'] = test_input($_REQUEST['zip']);
			$up_array[':privacy'] = test_input($_REQUEST['privacy']);
			$up_array[':id'] = $request_obj->arg[0];
			$up_array[':phone'] = test_input($_REQUEST['phone']);
			include("class/connect.php");
			$col_input = "
				UPDATE member_profile
				SET 
				member_phone = :phone,
				member_address = :address,
				member_city = :city,
				member_state = :state,
				member_zip = :zip,
				member_privacy = :privacy
				WHERE member_id = :id		
				";
			$stmt = $pdo->prepare($col_input);
			$stmt->execute($up_array);
			
		}
		if($request_obj->arg[1] == "uploadPic"){ 
			/* 
			this section for dealing with uploaded pictures
			this will check to see if any errors and if they are the proper type
			*/
			if($_FILES['userpic']['error'] > 0){
				$picture_error = "there was an error with the picture. upload code:".$_FILES['userpic']['error'];
			} else {
				$image_type = mime_content_type($_FILES['userpic']['tmp_name']);
				switch ($image_type){
					case "image/jpeg":
						$extention= ".jpg";
						break;
					case "image/gif":
						$extention= ".gif";
						break;
					case "image/png":
						$extention= ".png";
						break;
					default:
					    $picture_error= "this picture is not supported";
				}
				/*
				generating path where the picture will be strored for actual file and for the database
				*/
				$picture_path = "images/profilePics/member".$request_obj->arg[0].$extention;
				$col_input = "
					UPDATE member_profile
					SET member_pic = :path
					WHERE member_id = :id
				";
				$up_array[':path'] = $picture_path;
				$up_array[':id'] = $request_obj->arg[0];
				if(move_uploaded_file($_FILES['userpic']['tmp_name'] , $picture_path)){
					include("class/connect.php");
					$stmt = $pdo->prepare($col_input);
					$stmt->execute($up_array);
				} else {
					$picture_error = "unknows error happend";
				}
			}
		}
		if($request_obj->arg[1] == "updatePass"){
			/*
			option to update password user has to enter their current password,
			a new password and verify their new password
			*/
			if($_REQUEST['new_password'] === $_REQUEST['verify_password']){
				$col_input="
					SELECT count(*) as ct
					FROM log_in
					WHERE member_id = :id
					AND log_pw = :password
				";
			    $up_array[':password']= $_REQUEST['current_password'];
				$up_array[':id']= $request_obj->arg[0];
				include("class/connect.php");
				$stmt = $pdo->prepare($col_input);
				$stmt->execute($up_array);
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if($row['ct'] == 1){
					$col_input="
					    UPDATE log_in
						SET log_pw = :password
						WHERE member_id = :id
					";
					$up_array[':password']= $_REQUEST['new_password'];
					$stmt = $pdo->prepare($col_input);
					$stmt->execute($up_array);
				} else {
					$password_err="password is incorrect";
				}
			} else {
				$verify_err="This must match the new password";
			}
		}
		if($request_obj->arg[1] == "submitionUpload"){
			/*
			this is for dealing with uploaded files for users submition
			if there is no errors it will rename the file using fallowing rule
			username+user id+month of submition+number of the submition 
			All files will be given a pdf extention
			
			*/
			$profile_section ="closed-section";
			$submit_section ="open-section";
			$profile_tab ="closed-tab";
			$submit_tab ="open-tab";
			if($_FILES['userfile']['error'] > 0){
			    $file_error = "there was a problem with the file. error code:".$_FILES['userfile']['error'];	
			} else {
				$partial_path = "files/archive/member".$request_obj->user_id."/".$request_obj->user_name.date("F").date("Y");
				$i= 1;
				while(file_exists($partial_path.$i.".pdf")){
					$i++;
				}
				$file_path = $partial_path.$i.".pdf";
				if(move_uploaded_file($_FILES['userfile']['tmp_name'] , $file_path)){
					$col_input="
						INSERT INTO archive
						(archive_path, member_id,submit_date,archive_disc)
						VALUES
						(:path , :id , curdate(), :disc)
					";
					$up_array[':path'] = $file_path;
					$up_array[':id'] = $request_obj->arg[0];
					$up_array[':disc'] = test_input($_REQUEST['file_disc']);
					include('class/connect.php');
					$stmt = $pdo->prepare($col_input);
					$stmt->execute($up_array);
				}
			}
		}
	}
	include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(":id"=>$request_obj->arg[0]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$flip_button = "<button class='rotate-button' type='button'>&#8617</button>";
	$full_display = true;
	/*
	variable that holds the profile form.  it will be generated for those that
	are able to modify profile
	*/
	$profile_form = "
    <p>Addres: <input type='text' id='update-address' value='".$row['member_address']."'></p>
    <p>City: <input type='text' id='update-city' value='".$row['member_city']."'></p>
    <p>State: <input type='text' id='update-state' value='".$row['member_state']."'></p>
    <p>Zip code: <input type='text' id='update-zip' value='".$row['member_zip']."'></p>
    <p>Phone number: <input type='text' id='update-phone' value='".$row['member_phone']."'></p>
    <select form='profile-form' id='update-privacy'>";
	$option_loop = array("Every one can see all of your info",'Only loged on members can see your info','Only Officers and you can see your info');
	$prc_lbl = $row['member_privacy'];
	$profile_form .="<option value='$prc_lbl'>".$option_loop[$prc_lbl-1]."</option>";
	for($i=1; $i< 4; $i++){//places the provicy level select and sets what was in the database as the default
		if( $i == $row['member_privacy']){continue;}
		$profile_form .= "<option value='$i'>".$option_loop[$i-1]."</option>";
	}
    $profile_form .= "</select><br><input type='submit' value='Update info'>";
	/*
	variable that holds the picture form. it will generate for those that
	are allowed to upload pictures for the account
	*/
	$picture_form = '
		<input type="hidden" name="MAX_FILE_SIZE" value="300000" >
		<input name="userpic" type="file"  accept=".jpg , .png , .gif" >
		<input type="submit" id="submit-pic" value="Send picture" >
	';
	/*
	variable that hold the change password form
	*/
	$change_password = '
	    <p>Enter current password: <input type="password" id="current-password"><span class="error">'.$password_err.'</span></p>
		<p>Enter new password: <input type="password" id="new-password"></p>
		<p>Confirm password: <input type="password" id="verify-password"><span class="error">'.$verify_err.'</span></p>
		<input type="submit" value="Change password">
	';
	/*
	variable that holds the file upload form
	*/
	$file_upload = '
	    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" >
		<input type="file" name="userfile" accept=".pdf" ><br>
		<p>Discription of file: <input type="text" name="file_disc"></p>
		<input type="submit" id="submit-file" value="Send file" >
	';
	/*
	generate the variable that will hold the
	upload file table body. This will querry the
	database for all of the submitions from this 
	person to fill the table
	*/
	$col_input = "
		SELECT 
		archive_path, archive_disc, monthname(submit_date) AS mon
		FROM archive
		WHERE member_id = 1
		ORDER BY mon DESC
	";
	include('class/connect.php');
	$stmt = $pdo->prepare($col_input);
	$stmt->execute(array(":id"=>$request_obj->arg[0]));
	$submit_table = "";
	while($tab_row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$path_part = explode("/" , $tab_row['archive_path']);
		$submit_table .= "<tr><td>".$path_part[3]."</td><td>".$tab_row['archive_disc']."</td><td>".$tab_row['mon']."</td></tr>";
	}
} else { //this is the section that will display info for those that do not have modify priviladge
	include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(":id"=>$request_obj->arg[0]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$flip_button = "";
	$profile_form = "";
	$picture_form = "";
	$change_password ="";
	$file_upload ="";
	$submit_table = "";
	if($row['member_privacy'] == "1"){$full_display = true;} //this will show full display to everyone if the pricacy is set to 1
	if($row['member_privacy'] == "2" and $request_obj->valid_user){$full_display = true;}// this show info to loged in members
}
$welcome = "This is profile for ".$row['first_nm'];
if($full_display){ // this to displaying info for the member based on privacy levels and ranks
	$profile_name = $row['first_nm']." ".$row['last_nm'];
	$profile_addres = "<p>Address: ".$row['member_address']." ".$row['member_city']." ".$row['member_state']." ".$row['member_zip']."</p>";
	$profile_contact = "<p>email: ".$row['email']." phone: ".$row['member_phone']."</p>";
} else {
	$profile_name = $row['first_nm'];
	$profile_addres = "";
	$profile_contact = "";
}
if($row['member_pic']){  // this checks to see if there is a picture for the member in profile if not use the default picture
	$pic_path = $row['member_pic'];
} else {
	$pic_path = "images/profilePics/default.jpg";
}
?>

<div id="front-pannel" class="rotateable <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button ?>
	<h2> <?php echo $welcome; ?> </h2>
	<div class="profile-pic"><img src="<?php echo $pic_path; ?>" alt="profle picture"></div>
	<div class="profile-qte"></div>
	<div class="profile-info">
	    <p>Name: <?php echo $profile_name; ?> Rank: <?php echo $row['rank_name']; ?></p>
		<?php echo $profile_addres, $profile_contact;?>
	</div>
</div>
<div id="back-pannel" class="rotateable <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button ?>
	<h2> <?php echo $welcome; ?> </h2>
	<div class="profile-pic"><img src="<?php echo $pic_path; ?>" alt="profile picture">
		<form enctype="multipart/form-data" action="" method="POST" id="upload-pic">
		    <?php echo $picture_form; ?>
		</form>	
		
	</div>
	<div class="profile-qte"></div>
	<div id="profile-nav">
	    <ul>
		    <li data-section="profile-update" class="<?php echo $profile_tab; ?>">Profile Information</li>
			<li data-section="archive-update" class="<?php echo $submit_tab; ?>">Submitions</li>
			<li data-section="dues-section" class="<?php echo $dues_tab; ?>">Membership dues</li>
		</ul>
	</div>
	<div id="profile-update" class="<?php echo $profile_section; ?>">
	    <p>Name: <?php echo $profile_name; ?> Rank: <?php echo $row['rank_name']; ?></p>
	    <form id="profile-form">
		    <?php echo $profile_form; ?>
		</form>
		<form id="change-password">
		    <?php echo $change_password; ?>
		</form>
	</div>
	<div id="archive-update" class="<?php echo $submit_section; ?>">
	    <form enctype="multipart/form-data" action="" method="POST" id="upload-file">
		    <?php echo $file_upload; ?>
		</form>
		<table>
		    <thead>
		        <tr><th>File Name</th><th>File Discription</th><th>Upload Date</th></tr>
			</thead>
			<tbody>
			<?php echo $submit_table ?>
			</tbody>
		</table>
	</div>
	<div id="dues-section" class="<?php echo $dues_section ?>">
	
	</div>
</div>
