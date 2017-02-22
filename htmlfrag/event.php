<?php  
/*
this is a page where event info is displayed
*/
if(isset($request_obj->arg[0])){
    $event_obj = getEventInfo($request_obj);
    $request_obj->arg[1] = $event_obj->year;
    $request_obj->arg[2] = $event_obj->month;
    $request_obj->arg[3] = $event_obj->day;
    $event_day = new EventDay($request_obj);
	$event_day->fillEventInfo($event_obj);
	echo $event_day->displayEvent(false);    
} else {
    echo "<p>No event</p>";
}
function getEventInfo($request_obj){
	$col_select = "
	SELECT 
	event_id,
	event_title,
	EXTRACT(YEAR FROM event_date) AS year,
	EXTRACT(MONTH FROM event_date) AS month,
	EXTRACT(DAY FROM event_date) AS day,
	repeat_type,
	EXTRACT(YEAR FROM repeat_end) AS end_year,
	EXTRACT(MONTH FROM repeat_end) AS end_month,
	EXTRACT(DAY FROM repeat_end) AS end_day,
	event_discl
	FROM events
	WHERE event_id = :id
	";
	include "class/connect.php";
	$stmt = $pdo->prepare($col_select);
	$up_array[':id'] = $request_obj->arg[0];
	$stmt->execute($up_array);
	return $stmt->fetch(PDO::FETCH_OBJ);
}
?>
