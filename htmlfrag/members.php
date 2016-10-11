<?php 
/*
this section displays list of members and the groups they belong
can only be accesed for members that are loged in
for the admin and officer there is additional functionality
officer and admin will be able to assign and remove members from groups
and they can add new members
addmin will be able to add new groups and changed member ranks
assign officers to the group. rename groups.
*/
//this section changes effect based on if the person is officer or admin
if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){ 
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
	$flip_button = "<button class='rotate-button' type='button'>&#8617</button>";

} else {
	$add_member_form = "";
	$reactivate_button = "";
	$flip_button = "";
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
		    if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){//make sure some is allowed to make change
				switch ($request_obj->action){
					case "DELETE": // remove from group
					    deleteMemberFromGroup($request_obj);
						break;
					case "POST": // add to group
					    addMemberToGroup($request_obj);
						break;
				}
			}
		    break;
		case "groupList": // this for adding groups, removeing groups or changeging group name
		    if($request_obj->account_priv == "Admin"){
				switch ($request_obj->action){
					case "DELETE": 
					    deleteGroup($request_obj);
						break;
					case "POST":
					    createNewGroup($request_obj);
						break;
					case "PUT":
					    changeGroupName($request_obj);
						break;
				}	
            }				
			break;
		case "memberList": // this is for adding people to members or changing their rank
		    switch ($request_obj->action){
				case "POST":
				    if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){
						createNewAccount($request_obj);
					}
					break;
			    case "PUT":
				    if($request_obj->account_priv == "Admin"){
						changeMemberRank($request_obj);
					}
				    break;
			}
			break;
		case "reactivate":
		    if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){
				switch($request_obj->action){
					case "GET":
					    $reactivate_button = displayReactivateForm($request_obj);
						break;
					case "PUT":
					    reactivateMember($request_obj);
					    break;
				}
			}
			break;
		case "officerGroup":
		    if($request_obj->account_priv == "Admin"){
				changeGroupOfficer($request_obj);
			}
			break;
		default:
		  
	}
} 
function deleteMemberFromGroup($request_obj){
	include "class/connect.php";
	$col_select = "
		DELETE FROM group_member_list
		WHERE group_id = :id
		AND member_id = :member ";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':id'=>$_REQUEST['group_id'], ':member'=>$_REQUEST['member_id']));
	//this will remove officer from the group if the officer was removed from group
	$col_select = "
		UPDATE groups
		SET group_officer = NULL
		WHERE group_officer = :member
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':member'=>$_REQUEST['member_id']));
}
function addMemberToGroup($request_obj){
	include "class/connect.php";
	$col_select = "
		INSERT INTO group_member_list (member_id , group_id)
		VALUES ( :member , :id)";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':id'=>$_REQUEST['group_id'], ':member'=>$_REQUEST['member_id']));
}
function deleteGroup($request_obj){
	include "class/connect.php";
	$col_select = "
		DELETE FROM groups
		WHERE group_id = :id
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':id'=> $_REQUEST['group_id']));
}
function createNewGroup($request_obj){
	include "class/connect.php";
	$col_select = "
		INSERT INTO groups (group_name,weekday_id)
		VALUES (:name, 1);
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':name'=> $_REQUEST['new_name']));
}
function changeGroupName($request_obj){
	include "class/connect.php";
	$col_select = "
		UPDATE groups
		SET group_name = :newname
		WHERE group_id = :id
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':newname'=>$_REQUEST['new_name'] , ':id'=>$_REQUEST['group_id']));
}
function createNewAccount($request_obj){
	include("class/connect.php");
	$col_select = "
		INSERT INTO members (first_nm , last_nm , email , rank_id)
		VALUES (:fname , :lname , :email , 3)
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':fname'=>$_REQUEST['first_name'] , ':lname'=>$_REQUEST['last_name'] , ':email'=>$_REQUEST['email']));
	$insert_id = $pdo->lastInsertId();
	$col_select = "
		INSERT INTO log_in (log_un , log_pw , member_id)
		VALUES (:uname , 1234 , :memid )
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':uname'=>$_REQUEST['email'] , 'memid'=>$insert_id));
	$col_select = "
		INSERT INTO member_profile (member_id)
		VALUES ($insert_id);
	";
	$stmt = $pdo->query($col_select);
	$col_select = "
		INSERT INTO member_dues (dues_paid, member_id)
		VALUES (1, $insert_id)
	";
	$stmt = $pdo->query($col_select);
	$direc_path = 'files/archive/member'.$insert_id;
	if(!file_exists($direc_path)){
		mkdir($direc_path , 0755);
	}
}
function changeMemberRank($request_obj){
	include("class/connect.php");
	$col_select = "
		UPDATE members
		SET rank_id = :newrank
		WHERE member_id = :memid
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':newrank'=>$_REQUEST['new_rank'], ':memid'=>$_REQUEST['mem_id']));
	if($_REQUEST['new_rank'] == 5){
		$col_select = "
			DELETE FROM log_in
			WHERE member_id = :memid
		";
		$stmt = $pdo->prepare($col_select);
		$stmt->execute(array(':memid'=>$_REQUEST['mem_id']));
	}
}
function displayReactivateForm($request_obj){
	include("class/connect.php");
	$col_select = "
		SELECT
		first_nm AS fname,
		last_nm AS lname,
		member_id 
		FROM members
		WHERE rank_id = 5
	";
	$stmt = $pdo->query($col_select);
	$reactivate_button = "<p>Inactive member list<select id='reactivate-select'><option> </option>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$reactivate_button .= "<option data-id='".$row['member_id']."' data-fn='".$row['fname']."' data-ln='".$row['lname']."'>".$row['fname']." ".$row['lname']."</option>";
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
    return $reactivate_button;	
}
function reactivateMember($request_obj){
	include("class/connect.php");
	$col_select ="
		UPDATE members
		SET rank_id = 3 , email = :email
		WHERE member_id = :memid
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':email'=>$_REQUEST['email'], ':memid'=>$_REQUEST['member_id']));
	$col_select ="
		INSERT INTO log_in (log_un , log_pw , member_id)
		VALUES (:uname , '1234' , :memid )
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':uname'=>$_REQUEST['email'], ':memid'=>$_REQUEST['member_id']));
}
function changeGroupOfficer($request_obj){
	$officer_id = test_input($_REQUEST['officer_id']);
	$group_id = test_input($_REQUEST['group_id']);
	include("class/connect.php");
	$col_select ="
		UPDATE groups
		SET group_officer = :id
		WHERE group_id = :group
	";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute(array(':id'=>$_REQUEST['officer_id'],':group'=>$_REQUEST['group_id']));
}
$members_tabel = new MembersTable();
fillMemberTable($members_tabel, $request_obj);

function fillMemberTable($members_tabel, $request_obj){
	include("class/connect.php");
	$col_select = "
		SELECT 
		members.first_nm AS name,
		members.last_nm AS lname,
		ranks.rank_name AS rank,
		member_id AS id
		FROM members
		INNER JOIN ranks ON members.rank_id = ranks.rank_id
		WHERE ranks.rank_name IN ('Admin' , 'Officer' ,'Member' , 'Alumni')
		ORDER BY FIELD(rank ,'Admin' , 'Officer' ,'Member' , 'Alumni'), name ASC
		";
	$stmt = $pdo->query($col_select); //gets the list of members 
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$members_tabel->proces_row($row);
	}
}

$group_list = new GroupsList();
fillGroupList($group_list, $request_obj);

function fillGroupList($group_list, $request_obj){
	include("class/connect.php");
	$col_select = "
		SELECT
		members.first_nm AS fname,
		members.last_nm AS lname,
		group_member_list.member_id AS member_id,
		groups.group_name AS grp_name,
		groups.group_id AS grp_id,
		groups.group_officer AS offid,
		(SELECT first_nm FROM members WHERE member_id = offid) AS offname,
		(SELECT last_nm FROM members WHERE member_id = offid) AS offlname
		FROM group_member_list
		LEFT OUTER JOIN members
		ON members.member_id = group_member_list.member_id
		RIGHT OUTER JOIN groups
		ON group_member_list.group_id = groups.group_id
		ORDER BY groups.weekday_id, fname";
	$stmt = $pdo->query($col_select); // this gets the list of groups and members
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$group_list->proces_row($row);
	}
}
?>
<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button ?>
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
<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button ?>
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

