<?php 
/* 
this file show the groups then individule groups
allows officers to add group info
*/
$weekdays = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");//just an index array for weekdays
$group_member= "";
if(isset($request_obj->arg[0])){// checks to see if you are looking at a individule group. If so display info for the group
	if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){//changes functionality based on proviledge
		if(isset($request_obj->arg[1])){
			if($request_obj->arg[1] == "updateWeekday"){//updating database with a new weekday
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
			if($request_obj->arg[1] == "updateDisc"){//updating the database with a new group discription
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
		}
		$flip_button = "<button class='rotate-button' type='button'>&#8617</button>";
		$picture_form = '
			<input type="hidden" name="MAX_FILE_SIZE" value="300000" >
			<input name="userpic" type="file"  accept=".jpg , .png , .gif" >
			<input type="submit" id="submit-pic" value="Send picture" >
		';
		$disc_form = '
		    
		';
	} else { // for when some one does not hava priviledge
		$flip_button = "";
		$picture_form = "";
	}
	// querry for the group info
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
	if($row['group_pic']){
		$img_src = $row['group_pic'];
	} else {
		$img_src = "images/groupPics/defaultGroup.png";
	}
	//querry for the members of the group
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
		if($group_row['member_id'] == $row['group_officer']){
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
		$group_member .= '<div class="group-member"><a href="index.php?request=profile/'.$group_row['member_id'].'" data-link="profile/'.$group_row['member_id'].'">';
	    $group_member .= '<img src="'.$member_pic.'" alt="member picture"><p>'.$group_row['first_nm'].'</p></a></div>';
	}
	//fallowing the html for the display of individule group
	?>
	<div id="front-pannel" class="rotateable <?php if($request_obj->back){echo "flipped";} ?> ">
		<?php echo $flip_button ?>
		<h2> <?php echo $row['group_name']; ?> </h2>
		<h3><?php echo "Meets on ",$row['weekday']; ?></h3>
		<div class="group-pic"><img src="<?php echo $img_src ?>" alt="group picture"></div>
		<div class="group-disc"><p><?php echo $row['group_description'] ?></p></div>
		<div class="group-members">
			<h4>Group Officer</h4>
		    <div class="officer-list">
			    <a href="index.php?request=profile/<?php echo $row['group_officer']; ?>" data-link="profile/<?php echo $row['group_officer']; ?>">
				    <img src="<?php echo $officer_pic; ?>" alt="member picture">
					<p><?php echo $officer_name; ?></p>
				</a>
			</div>
			<h4> Members </h4>
			<?php echo $group_member; ?>
		</div>
	</div>
	<div id="back-pannel" class="rotateable <?php if($request_obj->back){echo "flipped";} ?>">
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
		<div class="group-pic"><img src="<?php echo $img_src ?>" alt="group picture">
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
			    <a href="index.php?request=profile/<?php echo $row['group_officer']; ?>" data-link="profile/<?php echo $row['group_officer']; ?>">
				    <img src="<?php echo $officer_pic; ?>" alt="member picture">
					<p><?php echo $officer_name; ?></p>
				</a>
			</div>
			<h4> Members </h4>
			<?php echo $group_member; ?>
		</div>
	</div>
<?php
} else { //this is displayed when you first come to the group page all groups listed
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
	while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
		//print_r($row);
		if($row['group_pic']){
			$img_src = $row['group_pic'];
		} else {
			$img_src = "images/groupPics/defaultGroup.png";
		}
		echo '<div class="group-div"><a class="group-link" href="index.php?request=groups/',$row['group_id'],'" data-link="groups/',$row['group_id'],'">';
		echo "<img src='$img_src' alt='group picture'>";
		echo "<p>",$row['group_name']," meets on ",$row['weekday'],"</p>";
		echo "<p>Led by ",$row['first_nm'],"</p>";
		echo '</a></div>';
	}
}
?>    
	