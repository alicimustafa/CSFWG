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
include("class/connect.php");
$pasword_table = "";
$col_select = "
	SELECT log_in.log_un , log_in.log_pw, ranks.rank_name
	FROM log_in
	INNER JOIN members ON log_in.member_id = members.member_id
	INNER JOIN ranks ON members.rank_id = ranks.rank_id
	ORDER BY ranks.rank_id
	";
$stmt = $pdo->query($col_select);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$pasword_table .= "<tr><td>".$row['log_un']."</td><td>".$row['log_pw']."</td><td>".$row['rank_name']."</td></tr>";
}

?>
<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button; ?>
    <h1>This the home page</h1>
    <p>Fallowing is a table listing all of the test members, their password and rank</p>
    <p>this is here only for testing</p><br><br>
    <table><tr><th>  Log in Username  </th><th>  Log in password  </th><th>  Member Rank  </th></tr>
    <?php echo $pasword_table; ?>
    </table>
    <br><br>
    <p id="home-page-announcement"><?php echo $home_page_announcement; ?></p>
</div>
<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button; ?>
    <form id="add-announcement-form">
        <textarea id="announcement-text" form="add-announcement-form" cols="30" rows="30"><?php echo $home_page_announcement ?></textarea>
        <input type="submit" value="Update announcement">
    </form>
</div>


