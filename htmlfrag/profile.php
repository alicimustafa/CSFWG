<?php 
/* 
this file creates and displays member profile
it will also give certain member ability to modify
this info
*/
// default for error
$error['password']="";
$error['verify']="";
// variable that hold default state for tabs and sections
$tabs['profile_section'] ="open-section";
$tabs['submit_section'] ="closed-section";
$tabs['dues_section'] ="closed-section";
$tabs['profile_tab'] ="open-tab";
$tabs['submit_tab'] ="closed-tab";
$tabs['dues_tab'] ="closed-tab";

$full_display = false; // this variable conrols if you can see the whole profile or not
if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer" or $request_obj->user_id == $request_obj->arg[0]){
	/* 
	this section for options for members that
	can modify profile info, update picture and upload submition
	if will diplay proper info on the back pannel
	 this includes officers and the member that owns the profile
	*/
	if(isset($request_obj->arg[1])){ 
		if($request_obj->arg[1] == "updateProfile"){updateMemberProfile($request_obj);}
		if($request_obj->arg[1] == "updatePass"){updateUserPassword($request_obj, $error);}
		if($request_obj->arg[1] == "updateEmail"){updateUserEmail($request_obj);}
		if($request_obj->arg[1] == "updateQuote"){updatPesonalQuote($request_obj);}
		if($request_obj->arg[1] == "uploadPic"){uploadMemberPicture($request_obj);}
		
		if($request_obj->arg[1] == "submitionUpload"){
			$tabs['profile_section'] ="closed-section";
			$tabs['submit_section'] ="open-section";
			$tabs['profile_tab'] ="closed-tab";
			$tabs['submit_tab'] ="open-tab";
			uploadMemberSubmitions($request_obj);
		}
        if($request_obj->arg[1] == "recordDuePayment"){
            $tabs['profile_section'] ="closed-section";
            $tabs['dues_section'] ="open-section";
            $tabs['profile_tab'] ="closed-tab";
            $tabs['dues_tab'] ="open-tab";
            recordDuePayment($request_obj);
        }
        if($request_obj->arg[1] == "paypal"){
            $tabs['profile_section'] ="closed-section";
            $tabs['dues_section'] ="open-section";
            $tabs['profile_tab'] ="closed-tab";
            $tabs['dues_tab'] ="open-tab";
        }
	}
	$row = getMemberInformation($request_obj);
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
	for($i=1; $i< 4; $i++){//places the privacy level select and sets what was in the database as the default
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
	$quote_form = '
	    <textarea id="personal-quote" form="personal-quote-form" cols="50" rows="18">'.$row['member_qt'].'</textarea><br>
		<input type="submit" value"Change personal Quote">
	';
	//variable that hold the change password form
    
	if($request_obj->user_id == $request_obj->arg[0]){
		$change_password = '
			<p>Enter current password: <input type="password" id="current-password"><span class="error">'.$error['password'].'</span></p>
			<p>Enter new password: <input type="password" id="new-password"></p>
			<p>Confirm password: <input type="password" id="verify-password"><span class="error">'.$error['verify'].'</span></p>
			<input type="submit" value="Change password" disabled id="change-password-submit" >
		';
        if(isset($request_obj->arg[1]) and  $request_obj->arg[1] == "paypal"){
            $due_payment_form ="
                <div id='paypal-button' data-year='".$request_obj->arg[2]."' data-id='".$request_obj->arg[0]."'></div>
                <script src='https://www.paypalobjects.com/api/checkout.js'></script>
                <script>
                    paypal.Button.render({
                    
                        env: 'sandbox', // Optional: specify 'sandbox' environment
                    
                        client: {
                            sandbox:    'AXNDn_b_4KxjdPK0uOo6Vfq95PDXIuoKWKvk8OWCZUHQyGsCyk1XX4IhQZhLZK_80dH85RRZJgR2hVq5',
                            production: 'xxxxxxxxx'
                        },

                        payment: function() {
                        
                            var env    = this.props.env;
                            var client = this.props.client;
                        
                            return paypal.rest.payment.create(env, client, {
                                transactions: [
                                    {
                                        amount: { total: '25.00', currency: 'USD' }
                                    }
                                ]
                            });
                        },

                        commit: true, // Optional: show a 'Pay Now' button in the checkout flow

                        onAuthorize: function(data, actions) {
                        
                            // Optional: display a confirmation page here
                        
                            return actions.payment.execute().then(function() {
                                myaCSFWG.sendPayment();
                            });
                        }

                    }, '#paypal-button');
                </script>
            ";
        } else {
            $due_payment_form = "<p>Payment for year: <select id='payment-year-select'>";
            foreach(generatePaymentYear() as $value){
                $due_payment_form .= "<option value='".$value."'>".$value."</option>";
            }  
            $due_payment_form .="</select><button type='button' id='payment-year-button' >Create paypal button</button></p>";
        }
	} else {
		$change_password = '
		    <p>Reset this members password to default</p>
		    <input type="submit" value="Reset password">
		';
        $due_payment_form = "<p>Payment for year: <select id='payment-year-select'>";
        foreach(generatePaymentYear() as $value){
            $due_payment_form .= "<option value='".$value."'>".$value."</option>";
        }    
        $due_payment_form .="</select>";
        $due_payment_form .="<button type='button' id='submit-payment' data-id='".$request_obj->arg[0]."'>Submit Payment</button></p>";
	}
	//variable that holds the change email form
	$change_email = '
	    <p>Current email: '.$row['email'].'</p>
	    <p>Enter new email: <input type="text" id="new-email"></p>
		<p>Confirm new email: <input type="text" id="confirm-new-email"><span class="error"></span></p>
		<input type="submit" id="new-email-submit" disabled value="Change email">
	';
	//variable that holds the file upload form
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
		WHERE member_id = :id
        LIMIT 10
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
    $paymetn_list_table = createPaymentList($request_obj);
} else { //this is the section that will display info for those that do not have modify priviladge
	$row = getMemberInformation($request_obj);
	$flip_button = "";
	$profile_form = "";
	$picture_form = "";
	$change_password ="";
	$change_email = "";
	$quote_form = "";
	$file_upload ="";
	$submit_table = "";
    $due_payment_form ="";
    $paymetn_list_table="";
	if($row['member_privacy'] == "1"){$full_display = true;} //this will show full display to everyone if the pricacy is set to 1
	if($row['member_privacy'] == "2" and $request_obj->valid_user){$full_display = true;}// this show info to loged in members
}
$welcome = "<h4 id='profile-title' data-id='".$row['member_id']."'>This is profile for ".$row['first_nm']."</h4>";
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
function recordDuePayment($request_obj){
    $col_select = "
        INSERT INTO due_payment
        (payment_date, payment_year, payment_vouch, member_id)
        VALUES 
        (curdate(), :year, :vouch, :member)    
    ";
    $up_array[':year'] = $_REQUEST['paymentYear'];
    $up_array[':vouch'] = $_REQUEST['vouch'] == 0 ? 0 : $request_obj->user_id;
    $up_array[':member'] = $request_obj->arg[0];
	include("class/connect.php");
    $stmt = $pdo->prepare($col_select);
	$stmt->execute($up_array);
}
function createPaymentList($request_obj){
    $col_select = "
        SELECT 
        members.first_nm,
        due_payment.payment_date,
        due_payment.payment_year,
        due_payment.payment_vouch
        FROM due_payment
        LEFT OUTER JOIN members ON due_payment.payment_vouch = members.member_id
        WHERE due_payment.member_id = :id
        ORDER BY due_payment.payment_year DESC
    ";
    $payment_table = "";
	include("class/connect.php");
    $stmt = $pdo->prepare($col_select);
	$stmt->execute(array(":id"=>$request_obj->arg[0]));
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $payment_method = $row['payment_vouch'] == 0 ? "Paypal" : "In Person -".$row['first_nm'];
        $payment_table .= "<tr><td>".$row['payment_year']."</td><td>".$row['payment_date']."</td><td>".$payment_method."</td></tr>";
    }
    return $payment_table;
}
function generatePaymentYear(){
    $year_array = array();
    $start_year = date("Y") - 2;
    for( $i = 0; $i <= 4; $i++){
        $year_array[$i] = $start_year;
        $start_year++;
    }
    return $year_array;
}
function updatPesonalQuote($request_obj){
	$up_array = array(":qt" => $_REQUEST['personal_qt'], ":id" => $request_obj->arg[0]);
	$col_input = "
	    UPDATE member_profile
		SET member_qt = :qt
		WHERE member_id = :id
	";
	include("class/connect.php");
	$stmt = $pdo->prepare($col_input);
	$stmt->execute($up_array);
}
function updateUserEmail($request_obj){
    $up_array = array(":email" => $_REQUEST['new_email'], ":id"=> $request_obj->arg[0]);
	include("class/connect.php");
	$col_input = "
	    UPDATE members
		SET email = :email
		WHERE member_id = :id
	";
	$stmt = $pdo->prepare($col_input);
	$stmt->execute($up_array);
	$col_input = "
	    UPDATE log_in
		SET log_un = :email
		WHERE member_id = :id
	";
	$stmt = $pdo->prepare($col_input);
	$stmt->execute($up_array);
}
function updateUserPassword($request_obj, $error){
	include("class/connect.php");
	$up_array[':id'] = $request_obj->arg[0];
	if($request_obj->user_id == $request_obj->arg[0]){
		if($_REQUEST['new_password'] === $_REQUEST['verify_password']){//checks to see if the password maches the verification password
			$col_input = "
				SELECT log_pw
				FROM log_in
				WHERE member_id = :id
			";
		    $stmt = $pdo->prepare($col_input);
			$stmt->execute($up_array);
			$row = $stmt->fetch(PDO::FETCH_NUM);
			if($_REQUEST['current_password'] === $row[0] or password_verify($_REQUEST['current_password'], $row[0])){ // checks to see if current password is correct 
				$up_array[':password'] = password_hash($_REQUEST['new_password'], PASSWORD_DEFAULT);
				$col_input = "
				    UPDATE log_in
					SET log_pw = :password
					WHERE member_id = :id
				";
				$stmt = $pdo->prepare($col_input);
				$stmt->execute($up_array);
			} else {
				$error['password'] = "your password is incorrect";
			}
		} else {
			$error['verify'] = "this must match the field above";
		}
	} else {
		$col_input = "
			UPDATE log_in
			SET log_pw = 1234
			WHERE member_id = :id
		";
		$stmt = $pdo->prepare($col_input);
		$stmt->execute($up_array);
	}
}
function updateMemberProfile($request_obj){
	$up_array[':address'] = $_REQUEST['address'];
	$up_array[':city'] = $_REQUEST['city'];
	$up_array[':state'] = $_REQUEST['state'];
	$up_array[':zip'] = $_REQUEST['zip'];
	$up_array[':privacy'] = $_REQUEST['privacy'];
	$up_array[':id'] = $request_obj->arg[0];
	$up_array[':phone'] = $_REQUEST['phone'];
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
function uploadMemberPicture($request_obj){
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
		generating path where the picture will be stored for actual file and for the database
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
function getMemberInformation($request_obj){
	include("class/connect.php");
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
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(":id"=>$request_obj->arg[0]));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function uploadMemberSubmitions($request_obj){
	/*
	this is for dealing with uploaded files for users submition
	if there is no errors it will rename the file using fallowing rule
	username+user id+month of submition+number of the submition 
	All files will be given a pdf extention
	
	*/
	if($_FILES['userfile']['error'] > 0){
		$file_error = "there was a problem with the file. error code:".$_FILES['userfile']['error'];	
	} else {
		include('class/connect.php');
		$col_input = "
		    SELECT first_nm 
			FROM members
			WHERE member_id = :id
		";
		$stmt = $pdo->prepare($col_input);
		$stmt-> execute(array(':id'=>$request_obj->arg[0]));
		$row = $stmt->fetch();
		$partial_path = "files/archive/member".$request_obj->user_id."/".$row['first_nm']. date("F"). date("Y");
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
			$up_array[':disc'] = $_REQUEST['file_disc'];
			$stmt = $pdo->prepare($col_input);
			$stmt->execute($up_array);
		}
	} 
}?>

<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button ?>
	<?php echo $welcome; ?>
	<div class="profile-pic"><img src="<?php echo $request_obj->full_url.$pic_path; ?>" alt="profle picture"></div>
	<div class="profile-qte">
	    <p><?php echo $row['member_qt'] ?></p>
	</div>
	<div class="profile-info">
	    <p>Name: <?php echo $profile_name; ?> Rank: <?php echo $row['rank_name']; ?></p>
		<?php echo $profile_addres, $profile_contact;?>
	</div>
</div>
<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button ?>
	<?php echo $welcome; ?> 
	<div class="profile-pic"><img src="<?php echo $request_obj->full_url.$pic_path; ?>" alt="profile picture">
		<form enctype="multipart/form-data" action="" method="POST" id="upload-pic">
		    <?php echo $picture_form; ?>
		</form>	
		
	</div>
	<div class="profile-qte">
	    <form id="personal-quote-form">
		    <?php echo $quote_form ?>
		</form>
	</div>
	<div id="profile-nav">
	    <ul>
		    <li data-section="profile-update" class="profile-tab <?php echo $tabs['profile_tab']; ?>">Profile Information</li>
			<li data-section="archive-update" class="profile-tab <?php echo $tabs['submit_tab']; ?>">Submitions</li>
			<li data-section="dues-section" class="profile-tab <?php echo $tabs['dues_tab']; ?>">Membership dues</li>
		</ul>
	</div>
	<div id="profile-update" class="<?php echo $tabs['profile_section']; ?>">
	    <p>Name: <?php echo $profile_name; ?> Rank: <?php echo $row['rank_name']; ?></p>
	    <form id="profile-form">
          <fieldset>
            <legend>Profile Area</legend>
		    <?php echo $profile_form; ?>
          </fieldset>
		</form>
		<form id="change-password">
          <fieldset>
            <legend>Password Area</legend>
		    <?php echo $change_password; ?>
          </fieldset>
		</form>
		<form id="change-email">
          <fieldset>
            <legend>Email Area</legend>
		    <?php echo $change_email; ?>
          </fieldset>
		</form>
	</div>
	<div id="archive-update" class="<?php echo $tabs['submit_section']; ?> auto-scroll">
	    <form enctype="multipart/form-data" action="" method="POST" id="upload-file">
		    <?php echo $file_upload; ?>
		</form>
		<table>
		    <thead>
		        <tr><th>File Name</th><th>File Discription</th><th>Upload Date</th></tr>
			</thead>
			<tbody>
			<?php echo $submit_table; ?>
			</tbody>
		</table>
	</div>
	<div id="dues-section" class="<?php echo $tabs['dues_section'] ?>">
	    <form id="due-payment-form">
          <fieldset>
            <legend>Due Payment Area</legend>
            <?php echo $due_payment_form; ?>
          </fieldset>  
        </form>
        <table>
            <tr>
                <th>Payment Year</th>
                <th>Date Payment Submited</th>
                <th>Payment Method</th>
            </tr>
            <?php echo $paymetn_list_table; ?>
        </table>
	</div>
</div>
