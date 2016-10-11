<?php  
if(isset($request_obj->arg[0])){ //this section show only certain archive list
	echo createArchiveSectionTable($request_obj);
} else { // this sectin displays what shows all of the archive
    echo createWholeArchiveTable($request_obj);
}
function createArchiveSectionTable($request_obj){
	$up_array[':year'] = $request_obj->arg[0];
	$up_array[':month'] = $request_obj->arg[1];
	/*
	fallowing checks to see its for record for ungrouped or certain group
	and creates the apropriate query 
	*/
	if($request_obj->arg[2] == "Ungrouped"){ 
		$col_select = "
			SELECT 
			group_member_list.group_id,
			archive.archive_disc,
			archive.archive_path,
			archive.member_id,
			members.first_nm
			FROM archive
			LEFT JOIN group_member_list ON archive.member_id = group_member_list.member_id
			INNER JOIN members ON archive.member_id = members.member_id
			WHERE group_member_list.group_id IS NULL AND YEAR(archive.submit_date) = :year AND MONTHNAME(archive.submit_date) = :month 
		";
	} else {
		$up_array[':group'] = $request_obj->arg[2];
		$col_select = "
			SELECT 
			groups.group_id,
			groups.group_name,
			archive.archive_disc,
			archive.archive_path,
			group_member_list.member_id,
			members.first_nm
			FROM group_member_list
			INNER JOIN members ON group_member_list.member_id = members.member_id
			INNER JOIN groups ON group_member_list.group_id = groups.group_id
			INNER JOIN archive ON group_member_list.member_id = archive.member_id
			WHERE group_member_list.group_id = :group AND YEAR(archive.submit_date) = :year AND MONTHNAME(archive.submit_date) = :month 
		";
	}
	include("class/connect.php");
	$stmt = $pdo->prepare($col_select);
	$stmt->execute($up_array);
	$table_body = "<tbody>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){//build the table body
		$table_body .= '<tr><td>'.$row['first_nm'].'</td><td>'.$row['archive_disc'].'</td><td><a href="'.$row['archive_path'].'" target="_blank"><img src="images/pdficon.png"</a></td></tr>';
	}
	if(isset($row['group_name'])){// changes the page intro based on group or ungrouped
		echo "<h1>Archive for ".$row['group_name']." ".$up_array[':year']." ".$up_array[':month']."</h1>";
	} else {
		echo "<h1>Archive for ungrouped members ".$up_array[':year']." ".$up_array[':month']."</h1>";
	}
	echo "<table><thead><tr><th>Author name</th><th>File discription</th><th>File</th></tr></thead>";
	return $table_body."</tbody></table>";
}
function createWholeArchiveTable($request_obj){
	$col_select = "
		SELECT 
		COUNT(archive.member_id) AS total,
		EXTRACT(year FROM archive.submit_date) AS arc_year,
		DATE_FORMAT(archive.submit_date, '%M') AS arc_month,
		groups.weekday_id,
		groups.group_id,
		groups.group_name
		FROM group_member_list
		INNER JOIN groups ON group_member_list.group_id = groups.group_id
		RIGHT JOIN archive ON group_member_list.member_id = archive.member_id
		GROUP BY arc_year, arc_month, groups.weekday_id
		ORDER BY DATE_FORMAT(archive.submit_date, '%Y,%m') DESC ,groups.weekday_id ASC	";
	include("class/connect.php");
	$stmt = $pdo->query($col_select);
	$first_row = true;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		/*
		this will build the tables that hold the archive info
		each year will get its own div and an h4 element
		*/
		if(empty($row['group_name'])){ //checks to see if this record belong to a group if not assign Ungrouped to it
			$row['group_name'] = "Ungrouped";
		}
		//table_data will hold info for each entry that is td and a element
		if($row['group_id']){$group_id = $row['group_id'];} else {$group_id = "Ungrouped";}
		$table_data = '<td><a href="index.php?request=archive/'.$row['arc_year'].'/'.$row['arc_month'].'/'.$group_id.'" class="arc-link" 
		               data-link="archive/'.$row['arc_year'].'/'.$row['arc_month'].'/'.$group_id.'">'.$row['arc_month'].'('.$row['total'].')'.'</a></td>';
		if($first_row){
			/*
			this checks to see it the first row from the database
			starts the first div and table
			*/
			$first_row = false;
			$curent_year = $row['arc_year'];
			$curent_month = $row['arc_month'];
			$curent_row = '<tr>'.$table_data;
			$curent_table_body = '<tbody>';
			$curent_table_header = '<thead><tr>'.'<th>'.$row['group_name'].'</th>';
			$year_section = '<div class="year-section"><h4 class="arc-year">'.$row['arc_year'].'</h4><table class="closed-section">';
			$header_disp = true;
		} else {
			if($curent_year == $row['arc_year']){
				/*
				checks to see if wee are on the current year if so continue to build the table
				if new year close the current table and div and start new div and table
				*/
				if($curent_month == $row['arc_month']){
					/*
					each row is a month and if month changes closes the current row 
					and start a new row
					*/
					if($header_disp){
						/*
						build the header for each table. table headers are made up of group name
						Since each result holds group name i just need single set of each name in order
						soon as the header is finnished header_disp is set to false till a new table is
						needed
						*/
						$curent_table_header .= "<th>".$row['group_name']."</th>";
					}
					$curent_row .= $table_data;
				} else {
					$curent_month = $row['arc_month'];
					$curent_table_body .= $curent_row.'</tr>';
					$curent_row = '<tr>'.$table_data;
					$header_disp = false;
				}
			} else {
				$curent_table_body .= $curent_row.'</tr>';
				echo $year_section, $curent_table_header, "</tr></thead>", $curent_table_body, "</tbody></table></div>";
				$year_section = '<div class="year-section"><h4 class="arc-year">'.$row['arc_year'].'</h4><table class="closed-section">';
     			$curent_year = $row['arc_year'];
				$curent_month = $row['arc_month'];
				$curent_row = "<tr>".$table_data;
				$curent_table_body = '<tbody>';
				$curent_table_header = '<thead><tr>'.'<th>'.$row['group_name'].'</th>';
				$header_disp = true;
			}
		}
	}
	//this next section closes the final table and div
	$curent_table_body .= $curent_row.'</tr>';
	return $year_section. $curent_table_header. "</tr></thead>". $curent_table_body. "</tbody></table></div>";
}
?>
