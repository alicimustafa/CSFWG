<?php 
include_once("/crt_functions.php");
include_once("class/Members_table.php");
include_once("class/Groups_list.php");
$rank_types = array("Admin","Alumni","Member","Officer");
/*
this section displays list of members and the groups they belong
can only be accesed for members that are loged in
for the admin and officer there is additional functionality
officer and admin will be able to assign and remove members from groups
and they can add new members
addmin will be able to add new groups and changed member ranks
*/
//this section changes effect based on if the person is officer or admin
if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){ 
    $drag_status = 'draggable="true" class="drag"';
    $remove_button = '<button type="button" class="remove-but">Remove</button>';
	$add_member_form = '
		<form id="add-member">
			<label for="first-add-member">First Name: </label>
			<input type="text" id="first-add-member" required><br>
			<label for="last-add-member">Last Name: </label>
			<input type="text" id="last-add-member" required><br>
			<label for="email-add-member">Email: </label>
			<input type="text" id="email-add-member" required><br>
			<input type="submit" value="Add member">
		</form>
	';
	$reactivate_button = '<button type="button" id="open-reactivate">Reactivate member</button>';

} else {
    $drag_status = "";
    $remove_button = "";
	$add_member_form = "";
	$reactivate_button = "";
}
if($request_obj->account_priv == "Admin"){
    $add_group_form = '
		<form id="add-group">
			<label for="new-group">Group name: </label>
			<input type="text" id="new-group">
			<input type="submit" value="Add group">
		</form>
	';
} else {
   $add_group_form = "";
}

if(isset($request_obj->arg[0])){
	switch ($request_obj->arg[0]){  //this checks what you want to effect and what action you want to use on this page
		case "groupAssignment":  // this for adding or removing some one from a group
		    if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){
				include("class/connect.php");
				$group_name = test_input($_REQUEST['group_name']);
				$member_id = test_input($_REQUEST['member_id']);
				switch ($request_obj->action){
					case "DELETE": // remove from group
						$col_select = "
							DELETE FROM group_member_list_tbl
							WHERE group_name = :name
							AND member_id = :member ";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':name'=>$group_name, ':member'=>$member_id));
						$col_select = "
						    UPDATE group_tbl
							SET group_officer_id = NULL,
							group_officer_first_name = NULL,
							group_officer_last_name = NULL
							WHERE group_officer_id = :member
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':member'=>$member_id));
						break;
					case "POST": // add to group
						$col_select = "
							INSERT INTO group_member_list_tbl (member_id , group_name)
							VALUES ( :member , :name)";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':name'=>$group_name, ':member'=>$member_id));
						break;
				}
			}
		    break;
		case "groupList": // this for adding groups, removeing groups or changeging group name
		    if($request_obj->account_priv == "Admin"){
				$group_name = test_input($_REQUEST['group_name']);
				print_r($_REQUEST);
				switch ($request_obj->action){
					case "DELETE": 
						include("class/connect.php");
						$col_select = "
							DELETE FROM group_tbl
							WHERE group_name = :name
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':name'=> $group_name));
						$col_select = "
							DELETE FROM group_names_tbl
							WHERE group_name = :name
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':name'=> $group_name));
						break;
					case "POST":
					echo "got the right area";
						include("class/connect.php");
						$col_select = "
							INSERT INTO group_names_tbl (group_name)
							VALUES (:name)
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':name'=> $group_name));
						$col_select = "
						    INSERT INTO group_tbl (group_name)
							VALUES (:name);
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':name'=> $group_name));
						break;
					case "PUT":
					    echo "right area";
						include("class/connect.php");
						$new_name = test_input($_REQUEST['new_name']);
						$col_select = "
							UPDATE group_member_list_tbl
							SET group_name = :newname
							WHERE group_name = :name
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':newname'=>$new_name , ':name'=>$group_name));
						$col_select = "
							UPDATE group_names_tbl
							SET group_name = :newname
							WHERE group_name = :name
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':newname'=>$new_name , ':name'=>$group_name));
						$col_select = "
							UPDATE group_tbl
							SET group_name = :newname
							WHERE group_name = :name
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':newname'=>$new_name , ':name'=>$group_name));
						break;
				}	
            }				
			break;
		case "memberList": // this is for adding people to members or changing their rank
		    switch ($request_obj->action){
				case "POST":
				    if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){
						include("class/connect.php");
						$first_name = test_input($_REQUEST['first_name']);
						$last_name = test_input($_REQUEST['last_name']);
						$input_email = test_input($_REQUEST['email']);
						$col_select = "
							INSERT INTO members_tbl (member_first_nm , member_last_nm , member_email , member_rank)
							VALUES (:fname , :lname , :email , 'Member')
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':fname'=>$first_name , ':lname'=>$last_name , ':email'=>$input_email));
						$inser_id = $pdo->lastInsertId();
						$col_select = "
							INSERT INTO log_in_tbl (log_un , log_pw , member_id)
							VALUES (:uname , '1234' , :memid )
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':uname'=>$input_email , 'memid'=>$inser_id));
					}
					break;
			    case "PUT":
				    if($request_obj->account_priv == "Admin"){
						include("class/connect.php");
						$new_rank = test_input($_REQUEST['new_rank']);
						$member_id = test_input($_REQUEST['mem_id']);
						$col_select = "
							UPDATE members_tbl
							SET member_rank = :newrank
							WHERE member_id = :memid
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':newrank'=>$new_rank, ':memid'=>$member_id));
					}
				    break;
			}
			break;
		case "reactivate":
		    if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){
				switch($request_obj->action){
					case "GET":
					    include("class/connect.php");
						$col_select = "
						    SELECT
							member_first_nm AS name,
							member_last_nm AS lname,
							member_id 
							FROM members_tbl
							WHERE member_rank = 'Inactive'
						";
						$stmt = $pdo->query($col_select);
						$reactivate_button = "<p>Inactive member list<select id='reactivate-select'>";
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$reactivate_button .= "<option data-id='".$row['member_id']."' data-fn='".$row['name']."' data-ln='".$row['lname']."'>".$row['name']." ".$row['lname']."</option>";
						}
						$reactivate_button .= '
						    </select></p><br>
							<form id="reactivate-form">
								<label for="first-reactivate">First Name: </label>
								<input type="text" id="first-reactivate" required readonly ><br>
								<label for="last-reactivate">Last Name: </label>
								<input type="text" id="last-reactivate" required readonly ><br>
								<label for="email-reactivate">Email: </label>
								<input type="text" id="email-reactivate" required><br>
								<input type="hidden" id="id-reactivate">
								<input type="submit" value="Reactivate member">
							</form>
						';           
						break;
					case "PUT":
					    $input_email = test_input($_REQUEST['email']);
						$member_id = test_input($_REQUEST['member_id']);
						include("class/connect.php");
						$col_select ="
						    UPDATE members_tbl
							SET member_rank = 'Member'
							WHERE member_id = :memid
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':memid'=>$member_id));
						$col_select ="
							INSERT INTO log_in_tbl (log_un , log_pw , member_id)
							VALUES (:uname , '1234' , :memid )
						";
						$stmt = $pdo->prepare($col_select);
						$stmt->execute(array(':uname'=>$input_email, ':memid'=>$member_id));
					    break;
				}
			}
			break;
		case "officerGroup":
		    if($request_obj->account_priv == "Admin"){
				$off_id = test_input($_REQUEST['id']);
				$off_fn = test_input($_REQUEST['fn']);
				$off_ln = test_input($_REQUEST['ln']);
				$group = test_input($_REQUEST['group']);
				include("class/connect.php");
				$col_select ="
				    UPDATE group_tbl
					SET
					group_officer_id = :id,
					group_officer_first_name = :fn,
					group_officer_last_name = :ln
					WHERE group_name = :group
				";
				$stmt = $pdo->prepare($col_select);
				$stmt->execute(array(':id'=>$off_id,':fn'=>$off_fn,':ln'=>$off_ln,':group'=>$group));
			}
			break;
		default:
		  
	}
} 
include("class/connect.php");
$first_row = true;
$col_select = "
    SELECT 
    member_first_nm AS name,
    member_last_nm AS lname,
    member_rank AS rank,
    member_id AS id
    FROM members_tbl
	WHERE member_rank IN ('Admin' , 'Officer' ,'Member' , 'Alumni')
    ORDER BY FIELD(rank ,'Admin' , 'Officer' ,'Member' , 'Alumni'), name ASC
    ";
$stmt = $pdo->query($col_select); //gets the list of members 
$members_tabel = new Members_table;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$members_tabel->proces_row($row);
}
$col_select = "
    SELECT
    members_tbl.member_first_nm AS name,
    members_tbl.member_last_nm AS lname,
    group_member_list_tbl.member_id AS member_id,
    group_names_tbl.group_name AS grp_name,
    group_tbl.group_officer_id AS offid,
    group_tbl.group_officer_first_name AS offname,
    group_tbl.group_officer_last_name AS offlname,
    group_tbl.group_weekday AS weekday
    FROM group_member_list_tbl
    RIGHT OUTER JOIN group_names_tbl
    ON group_names_tbl.group_name = group_member_list_tbl.group_name
    LEFT OUTER JOIN members_tbl
    ON members_tbl.member_id = group_member_list_tbl.member_id
    LEFT OUTER JOIN group_tbl
    ON group_member_list_tbl.group_name = group_tbl.group_name
    ORDER BY grp_name, name";
$stmt = $pdo->query($col_select); // this gets the list of groups and members
$group_list = new Groups_list;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$group_list->proces_row($row);
}
?>
<div id="front-members" class="rotateable <?php if($request_obj->back){echo "flipped";} ?> ">
    <button class='rotate-button' type='button'>&#8617</button>
	<div class="member-section">
	<div class="members-list">
		<table>
			<thead>
			  <tr><th>Name</th><th>Rank</th></tr>
			</thead>
			<tbody>
		<?php echo $members_tabel->display("Member"); ?>
			</tbody>
		</table>
	</div>

	</div>
	<div class="group-section">
	<div class='group-list'>
	    <?php $group_list->display("Member"); ?>
	</div>
	</div>
</div>
<div id="back-members" class="rotateable <?php if($request_obj->back){echo "flipped";} ?>">
    <button class='rotate-button' type='button'>&#8617</button>
	<div class="member-section">
	<div class="members-list">
		<table>
			<thead>
			  <tr><th>Name</th><th>Rank</th></tr>
			</thead>
			<tbody>
		<?php echo $members_tabel->display($request_obj->account_priv); ?>
			</tbody>
		</table>
		<?php echo $add_member_form; ?>
	</div>
	<div id="reactivate-member">
	<?php echo $reactivate_button; ?>
	</div>

	</div>
	<div class="group-section">
	<div class='group-list'>
	<?php $group_list->display($request_obj->account_priv); ?>
	</div>
	<?php echo $add_group_form; ?>
	</div>
</div>


