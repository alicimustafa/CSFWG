<?php 
/* 
this file show the groups then individule groups
allows officers to add group info
*/
$weekdays = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");//just an index array for weekdays
if(isset($request_obj->arg[0])){// checks to see if you are looking at a individule group. If so display info for the group
	if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){//changes functionality based on proviledge
		if(isset($request_obj->arg[1])){
			if($request_obj->arg[1] == "updateGroupWeekday"){//updating database with a new weekday
			    updateGroupWeekday($request_obj);
			}
			if($request_obj->arg[1] == "updateDisc"){//updating the database with a new group discription
			    updateGroupDiscription($request_obj);
			}
			if($request_obj->arg[1] == "uploadGroupPicture"){
				uploadGroupPicture($request_obj);
			}
		}
		$flip_button = "<button class='rotate-button' type='button'>&#8617</button>";
		$picture_form = '
			<input type="hidden" name="MAX_FILE_SIZE" value="300000" >
			<input name="userpic" type="file"  accept=".jpg , .png , .gif" >
			<input type="submit" id="submit-pic" value="Send picture" >
		';
	} else { // for when some one does not have priviledge
		$flip_button = "";
		$picture_form = "";
	}
	// querry for the group info
	$row = getGroupInfo($request_obj);
	if($row['group_pic']){
		$img_src = $row['group_pic'];
	} else {
		$img_src = "images/groupPics/defaultGroup.png";
	}
	//querry for the members of the group
	$return = createGroupGrid($request_obj, $row['group_officer']);
	$group_member = $return['group_member'];
	$officer_name = $return['officer_name'];
	$officer_pic = $return['officer_pic'];
	//fallowing the html for the display of individule group
	?>
	<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
		<?php echo $flip_button ?>
		<h2> <?php echo $row['group_name']; ?> </h2>
		<h3><?php echo "Meets on ",$row['weekday']; ?></h3>
		<div class="group-pic"><img src="<?php echo $request_obj->full_url.$img_src ?>" alt="group picture"></div>
		<div class="group-disc"><p><?php echo $row['group_description'] ?></p></div>
		<div class="group-members">
			<h4>Group Officer</h4>
		    <div class="officer-list">
			    <a href="/profile/<?php echo $row['group_officer']; ?>" data-link="profile/<?php echo $row['group_officer']; ?>">
				    <img src="<?php echo $request_obj->full_url.$officer_pic; ?>" alt="member picture">
					<p><?php echo $officer_name; ?></p>
				</a>
			</div>
			<h4> Members </h4>
			<?php echo $group_member; ?>
		</div>
	</div>
	<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
		<?php echo $flip_button ?>
		<h2> <?php echo $row['group_name']; ?> </h2>
		<h3><?php echo "Meets on ", '<select id="weekday-select"><option value="',$row['weekday_id'],'">',$row['weekday'],'</option>'; 
		foreach($weekdays as $key => $value){
			$keyp = $key+1;
			if($keyp == $row['weekday_id']){continue;}
			echo "<option value='$keyp'> $value </option>";
		}
		echo "</select>";
		?></h3>
		<div class="group-pic"><img src="<?php echo $request_obj->full_url.$img_src ?>" alt="group picture">
		<form enctype="multipart/form-data" action="" method="POST" id="upload-pic">
		    <?php echo $picture_form; ?>
		</form>	
		</div>
		<div class="group-disc">
		    <form id="disc-update">
			    <textarea id="disc-field" form="disc-update" maxlength="1200" cols="60" rows="20">
				<?php echo $row['group_description']; ?>
				</textarea>
				<input type="submit" value="Change discription">
			</form>
		</div>
		<div class="group-members">
			<h4>Group Officer</h4>
		    <div class="officer-list">
			    <a href="/profile/<?php echo $row['group_officer']; ?>" data-link="profile/<?php echo $row['group_officer']; ?>">
				    <img src="<?php echo $request_obj->full_url.$officer_pic; ?>" alt="member picture">
					<p><?php echo $officer_name; ?></p>
				</a>
			</div>
			<h4> Members </h4>
			<?php echo $group_member; ?>
		</div>
	</div>
<?php
} else { //this is displayed when you first come to the group page all groups listed
    echo displayAllGroups($request_obj);
}
function updateGroupWeekday($request_obj){
	$col_select = "
		UPDATE groups 
		SET weekday_id = :week
		WHERE group_id = :group
	";
	$up_array[':week'] = $_REQUEST['weekday'];
	$up_array[':group'] = $request_obj->arg[0];
	include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute($up_array);
}
function updateGroupDiscription($request_obj){
	$col_select = "
		UPDATE  groups
		SET group_description = :disc
		WHERE group_id = :group
	";
	$up_array[':disc'] = $_REQUEST['discription'];
	$up_array[':group'] = $request_obj->arg[0];
	include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute($up_array);
}
function createGroupGrid($request_obj, $group_officer){
    $group_member= "";
	$col_select = "
		SELECT 
        group_member_list.member_id,
	    members.first_nm,
        member_profile.member_pic
        FROM members
        INNER JOIN member_profile
        ON members.member_id = member_profile.member_id
        RIGHT JOIN group_member_list
        ON members.member_id = group_member_list.member_id
        WHERE group_member_list.group_id = :group
	";
    include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(":group"=>$request_obj->arg[0]));
	while($group_row = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($group_row['member_id'] == $group_officer){
			if($group_row['member_pic']){
				$officer_pic = $group_row['member_pic'];
			} else {
				$officer_pic = "images/profilePics/default.jpg";
			}
			$officer_name = $group_row['first_nm'];
			continue;
		}
		if($group_row['member_pic']){
			$member_pic = $group_row['member_pic'];
		} else {
			$member_pic = "images/profilePics/default.jpg";
		}
		$group_member .= '<div class="group-member"><a href="/profile/'.$group_row['member_id'].'" data-link="profile/'.$group_row['member_id'].'">';
	    $group_member .= '<img src="'.$request_obj->full_url.$member_pic.'" alt="member picture"><p>'.$group_row['first_nm'].'</p></a></div>';
	}
	$return['officer_name'] = $officer_name;
	$return['group_member'] = $group_member;
	$return['officer_pic'] = $officer_pic;
	
	return $return;
}
function getGroupInfo($request_obj){
	$col_select = "
		SELECT 
		groups.group_name,
		groups.group_pic,
        groups.group_description,
        groups.group_officer,
		weekdays.weekday,
		weekdays.weekday_id
		FROM groups
		LEFT JOIN weekdays
		ON groups.weekday_id = weekdays.weekday_id	
        WHERE groups.group_id = :group
	";
    include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(":group"=>$request_obj->arg[0]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row;
}
function displayAllGroups($request_obj){
	$col_select = "
		SELECT 
		groups.group_id,
		groups.group_name,
		groups.group_pic,
		members.first_nm,
		weekdays.weekday
		FROM groups
		LEFT JOIN members
		ON groups.group_officer = members.member_id
		LEFT JOIN weekdays
		ON groups.weekday_id = weekdays.weekday_id	
	";
    include("class/connect.php");
	$stmt = $pdo->query($col_select);//get list of groups
	$group_display = "";
	while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
		if($row['group_pic']){
			$img_src = $request_obj->full_url.$row['group_pic'];
		} else {
			$img_src = $request_obj->full_url."images/groupPics/defaultGroup.png";
		}
		$group_display .= '<div class="group-div"><a class="group-link" href="/groups/'.$row['group_id'].'" data-link="groups/'.$row['group_id'].'">';
		$group_display .= "<img src='$img_src' alt='group picture'>";
		$group_display .= "<p>".$row['group_name']." meets on ".$row['weekday']."</p>";
		$group_display .= "<p>Led by ".$row['first_nm']."</p>";
		$group_display .= '</a></div>';
	}
	return $group_display;
}
function uploadGroupPicture($request_obj){
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
		$picture_path = "images/profilePics/group".$group_id.$extention;
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

?>    
	