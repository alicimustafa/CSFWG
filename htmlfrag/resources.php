<?php 
$file_error = "";
if($request_obj->account_priv == "Admin" or $request_obj->account_priv == "Officer"){
    $flip_button = "<button class='rotate-button' type='button'>&#8617</button>";
    if(isset($request_obj->arg[0])){
        switch($request_obj->arg[0]){
            case "resourceList" :
                switch($request_obj->action){
                    case "POST" :
                        addResource($request_obj);
                        break;
                    case "DELETE" :
                        deleteResource($request_obj);
                        break;
                }
                break;
            case "resourcePara" :
                file_put_contents("resourcePara.txt", $_REQUEST['paragraph']);
                break;
        }
    }
} else {
    $flip_button = "";
}
$resource_parag = readResourceParagraph($request_obj);
$resource_list = readResourceList($request_obj);

function addResource($request_obj){
	if($_FILES['resourse_file']['error'] > 0){
		$file_error = "there was a problem with the file. error code:".$_FILES['resourse_file']['error'];	
	} else { 
        $name_array = explode(".", $_FILES['resourse_file']['name']);
        $file_name = $name_array[0] . ".pdf";
		$file_path = "files/resources/" . $file_name;
		if(move_uploaded_file($_FILES['resourse_file']['tmp_name'] , $file_path)){
			$col_input="
                INSERT INTO resources_list
                (resource_path, resource_title, resource_discription)
                VALUES
                (:path , :name , :disc)			
            ";
			$up_array[':path'] = $file_path;
			$up_array[':name'] = $file_name;
			$up_array[':disc'] = $_REQUEST['file_disc'];
            include("class/connect.php");
			$stmt = $pdo->prepare($col_input);
			$stmt->execute($up_array); 
		} 
	} 
}

function deleteResource($request_obj){
    if(unlink($_REQUEST['path'])){
        $col_select = "
            DELETE FROM resources_list
            WHERE resource_id = :id
        ";
        include("class/connect.php");
        $stmt = $pdo->prepare($col_select);
        $stmt->execute(array(':id' => $_REQUEST['resource_id']));
    }
}

function readResourceParagraph($request_obj){
    if(file_exists("resourcePara.txt")){
        return file_get_contents("resourcePara.txt");
    }
    return "<h1>Resource Page</h1>";
}

function readResourceList($request_obj){
    $col_select = "
        SELECT
        resource_id AS id,
        resource_path AS path,
        resource_title AS title,
        resource_discription AS disc
        FROM resources_list
    ";
	include("class/connect.php");
    $stmt = $pdo->query($col_select);
    if($stmt){
        $table_header = "
            <table>
              <tr>
                <th>Resource Title</th>
                <th>Resource Discription</th>
              </tr>        ";
         $table_list = "";
         $table_edit = "";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $table_list .= "<tr><td><a href='".$row['path']."' target='_blank' >".$row['title']."</a></td><td>".$row['disc']."</td></tr>";
            $table_edit .= "<tr><td>".$row['title']."<button type='button' class='delete-resource' data-resource-id='".$row['id']."' data-path='".$row['path']."' >Delete</button></td><td>".$row['disc']."</td></tr>";
        }
        $return['list'] = $table_header . $table_list . "</table>";
        $return['edit'] = $table_header . $table_edit . "</table>";
    } else {
        $return['list'] = "There is no current Resources";
        $return['edit'] = "There is no current Resources";
    }
    return $return;
}
?> 
<div class="rotateable front-pannel <?php if($request_obj->back){echo "flipped";} ?> ">
    <?php echo $flip_button ?>
 <div class="resource-paragraph-area"><?php echo $resource_parag; ?></div>
 <div class="resource-list-area"><?php echo $resource_list['list']; ?></div>
</div>
<div class="rotateable back-pannel <?php if($request_obj->back){echo "flipped";} ?>">
    <?php echo $flip_button ?>
    <div class="resource-paragraph-area">
        <form id="resource-paragraph-form" >
            <textarea id="resource-paragraph" form="resource-paragraph-form" cols="60" rows="5"><?php echo $resource_parag; ?></textarea>
            <br>
            <input type="submit" value="Change Resource Paragraph">
        </form>
    </div>
    <br>
    <div class="resource-list-area">
        <form id="resource-list-form">
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" >
            <input type="file" name="resourse_file" accept=".pdf" ><br>
            <p>Discription of file: <input type="text" name="file_disc"><?php echo $file_error; ?></p>
            <input type="submit" id="submit-file" value="Send file" >
        </form>
        <?php echo $resource_list['edit']; ?>
    </div>
</div>
