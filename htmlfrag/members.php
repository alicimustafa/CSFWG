<?php 
include_once("/crt_functions.php");
/*
this section displays list of members and the groups they belong
can only be accesed for members that are loged in
for the admin and officer there is additional functionality
officer and admin will be able to assign and remove members from groups
and they can add new members
addmin will be able to add new groups and changed member ranks
*/
//this section changes effect based on if the person is officer or admin
if($read_url->account_priv == "Officer" or $read_url->account_priv == "Admin"){ 
    $drag_status = 'draggable="true" class="drag"';
    $remove_button = '<button type="button" class="remove-but">Remove</button>';
} else {
    $drag_status = "";
    $remove_button = "";
}
if($read_url->account_priv == "Admin"){

} else {

}
/*
switch ($read_url->arg[0]){  //this checks what you want to effect and what action you want to use on this page
  case "groupAssignment":  // this for adding or removing some one from a group
      include("class/connect.php");
      $group_name = test_input($_POST['group_name']);
      $member_id = test_input($_POST['member_id']);
      switch ($read_url->action){
        case "DELETE": // remove from group
            $col_select = "
                DELETE FROM group_member_list_tbl
                WHERE group_name = :name
                AND member_id = :member ";
            break;
        case "POST": // add to group
            $col_select = "
                INSERT INTO group_member_list_tbl (member_id , group_name)
                VALUES ( :member , :name)";
            break;
      }
      $stmt = $pdo->prepare($col_select);
      $stmt->execute(array(':name'=>$group_name, ':member'=>$member_id));
      break;
  case "groupList": // this for adding groups, removeing groups or changeging group name
      $group_name = test_input($_POST['group_name'])
      switch ($read_url->action){
        case "DELETE": //
            include("class/connect.php");
            $col_select = "
                DELETE FROM group_names_tbl
                WHERE group_name = :name
            ";
            $stmt = $pdo->prepare($col_select);
            $stmt->execute(array(':name'=> $group_name));
            break;
        case "POST":
            include("class/connect.php");
            $col_select = "
                INSERT INTO group_name_tbl (group_name)
                VALUES (:name)
            ";
            $stmt = $pdo->prepare($col_select);
            $stmt->execute(array(':name'=> $group_name));
            break;
        case "PUT":
            include("class/connect.php");
            $new_name = test_input($_POST['new_name']);
            $col_select = "
                UPDATE groupt_name_tbl
                SET group_name = :newname
                WHERE groupt_name = :name
            ";
            $stmt = $pdo->prepare($col_select);
            $stmt->execute(array(':newname'=>$new_name , ':name'=>$group_name));
            break;    
      break;
  case "memberList": // this is for adding people to members or changing their rank
      break;
  default:
      stuff
}
*/
include("class/connect.php");
$first_row = true;
$col_select = "
    SELECT 
    member_first_nm AS name,
    member_last_nm AS lname,
    member_rank AS rank,
    member_id AS id
    FROM members_tbl
    ORDER BY FIELD(rank ,'Admin' , 'Officer' ,'Member' , 'Alumni'), name ASC
    ";
$stmt = $pdo->query($col_select); //gets the list of members 
    ?>
<div id="members-list">
    <table>
        <thead>
          <tr>
            <th>Name</th>  
            <th>Rank</th>
          </tr>
        </thead>
        <tbody>
    <?php
    while($row= $stmt->fetch()){
        echo  "<tr><td $drag_status data-member='",$row['id'],"' >",$row['name']," ",$row['lname'],"</td><td>",$row['rank'],"</td></tr>";
    }
    ?>
        </tbody>
    </table>
</div>
<?php
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
echo "<div id='group-list'>";
while($row = $stmt->fetch()){
    $list_header = "<fieldset class='drop-area'><legend>".$row['grp_name']."</legend>
                    <p data-off-id='".$row['offid']."'>Group officer: ".$row['offname']." ".$row['offlname']."</p><br><ul>";
    $list_item = "<li data-member='".$row['member_id']."'>".$row['name']." ".$row['lname']." $remove_button </li>";
    if($first_row){
        $group_name = $row['grp_name'];
        $first_row = false;
        echo $list_header;
    }
    if($group_name == $row['grp_name']){
        echo $list_item;
    } else {
        $group_name = $row['grp_name'];
        echo "</ul></fieldset>";
        echo $list_header;
        echo $list_item;
    }
}
echo "</div>";

