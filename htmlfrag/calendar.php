<?php 
/*
this section is for generating the correct calendar for the month
and display events for the month.  It will also handle adding and
modifying events
*/
switch($request_obj->arg[0]){
	case "displayMonth":
		$request_obj->arg[2] = date("n");
		$request_obj->arg[1] = date("Y");
		$cal_obj = new EventCalendar($request_obj);
		echo $cal_obj->createTable();
	    break;
	case "changeMonth":
		$cal_obj = new EventCalendar($request_obj);
		echo $cal_obj->createTable();
		break;
	case "eventDay":
	    switch($request_obj->action){
			case "POST":
			    if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){createNewEvent($request_obj);}
				$cal_obj = new EventCalendar($request_obj);
				echo $cal_obj->createTable();
				break;
			case "PUT":
			    if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){updateEvent($request_obj);}
				$cal_obj = new EventCalendar($request_obj);
				echo $cal_obj->createTable();
				break;
			case "GET":
			    $event_day = new EventDay($request_obj);
				$event = getEventInfo($request_obj);
				$event_day->fillEventInfo($event);
				echo $event_day->displayEvent();
				break;
			case "DELETE":
			    if($request_obj->account_priv == "Officer" or $request_obj->account_priv == "Admin"){deleteEvent($request_obj);}
				$cal_obj = new EventCalendar($request_obj);
				echo $cal_obj->createTable();
				break;
		}
		break;
	case "blankForm":
	    $event_day = new EventDay($request_obj);
		echo $event_day->createEventForm();
		break;
}

function createNewEvent($request_obj){
	$up_array[':title'] = $_REQUEST['event_title'];
	$up_array[':date'] = $_REQUEST['event_date'];
	$up_array[':repeat'] = $_REQUEST['repeat_type'];
	if($_REQUEST['end_date'] == "null"){
		$end_date_injection = "null";
	} else {
		$end_date_injection = ":end";
		$up_array[':end'] = $_REQUEST['end_date'];
	}
	$up_array[':type'] = $_REQUEST['event_type'];
	$up_array[':disc'] = $_REQUEST['event_discription'];
	$col_select = "
	INSERT INTO events 
	(event_title, event_date, repeat_type, repeat_end, event_type, event_discl)
    VALUES 
	(:title , :date , :repeat , $end_date_injection , :type , :disc )
	";
	print_r($up_array);
	include "class/connect.php";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute($up_array);
}

function updateEvent($request_obj){
	$up_array[':title'] = $_REQUEST['event_title'];
	$up_array[':date'] = $_REQUEST['event_date'];
	$up_array[':repeat'] = $_REQUEST['repeat_type'];
	if($_REQUEST['end_date'] == "null"){
		$end_date_injection = "null";
	} else {
		$end_date_injection = ":end";
		$up_array[':end'] = $_REQUEST['end_date'];
	}
	$up_array[':type'] = $_REQUEST['event_type'];
	$up_array[':disc'] = $_REQUEST['event_discription'];
	$up_array[':id'] = $_REQUEST['event_id'];
	$col_select = "
	UPDATE events
	SET 
	event_title = :title ,
	event_date = :date ,
	repeat_type = :repeat ,
	repeat_end = $end_date_injection ,
	event_type = :type ,
	event_discl = :disc
	WHERE event_id = :id
	";
	include "class/connect.php";
	$stmt = $pdo->prepare($col_select);
	$stmt->execute($up_array);
}
function deleteEvent($request_obj){
	$col_select = "
	DELETE FROM events
	WHERE event_id = :id
	";
	include "class/connect.php";
	$stmt = $pdo->prepare($col_select);
	$up_array[':id'] = $_REQUEST['event_id'];
	$stmt->execute($up_array);
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
	$up_array[':id'] = $request_obj->arg[4];
	$stmt->execute($up_array);
	return $stmt->fetch(PDO::FETCH_OBJ);
}
