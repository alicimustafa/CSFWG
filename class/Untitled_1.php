<?php 
function updateUserPassword($member_id, &$error){
	if($_REQUEST['new_password'] === $_REQUEST['verify_password']){
		$col_input="
			SELECT count(*) as ct
			FROM log_in
			WHERE member_id = :id
			AND log_pw = :password
		";
		$up_array[':password']= $_REQUEST['current_password'];
		$up_array[':id']= $member_id;
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
			$error['password']="password is incorrect";
		}
	} else {
		$error['verify']="This must match the new password";
	}
}
function uploadMemberSubmitions($member_id){
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
		$stmt-> execute(array(':id'=>$member_id));
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
			$up_array[':disc'] = test_input($_REQUEST['file_disc']);
			$stmt = $pdo->prepare($col_input);
			$stmt->execute($up_array);
		}
	}
}