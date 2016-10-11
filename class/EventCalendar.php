<?php
class EventCalendar 
{
	const CALENDAR_SIZE = 42;
	private $this_month_number_of_days, $last_month_number_of_days, $first_day_of_this_month;
	private $calendar_array = array(); // array that hold the information for the calander days
	private $this_month, $this_year;  // these hold month and year of calander being displayed
	private $calendar_header;
	private $account; //privilage level of the user
	
	public function __construct($request_obj){
		/*
		the contructor will set several variables
		it will set the current day year and mont
		it will set the month year of the month being
		displayed. it will set the how many day in 
		the month being displyed and the previus months
		days. it will the fill the array holding info
		on each day
		*/
		$this->account = $request_obj->account_priv;
		$this->this_month = $request_obj->arg[2];
		$this->this_year = $request_obj->arg[1];
		$this->this_month_number_of_days = date('t', mktime(1,1,1,$this->this_month,1,$this->this_year));
		if($this->this_month == 1 ){
			$last_month = 12;
			$last_year = $request_obj->arg[2]-1;
		} else {
			$last_month = $request_obj->arg[1]-1;
			$last_year = $request_obj->arg[2];
		}
		$this->last_month_number_of_days = date('t', mktime(1,1,1,$last_month,1,$last_year));
		$this->first_day_of_this_month = date('w', mktime(1,1,1,$this->this_month,1,$this->this_year));
		$this->fillCalendar();
		$this->getEventsFromDB($request_obj);
		$this->calendar_header = '
			<div id="calendar-arrows" data-month="'.$this->this_month.'" data-year="'.$this->this_year.'">
			<span id="left-arrow">&#x21E6</span>
			<span id="year-month">'.$this->this_month.' '.$this->this_year.'</span>
			<span id="right-arrow">&#x21E8</span>
			</div>
		';
	}
	
	private function fillCalendar(){
		if($this->first_day_of_this_month == 0){
			$day_ct = 1;
			$not_month = false;
			$add_event = $this->checkToAddEvent($day_ct);
		} else {
			$day_ct = $this->last_month_number_of_days - $this->first_day_of_this_month +1;
			$not_month = true;
			$add_event = false;
		}
		for($i=0; $i < self::CALENDAR_SIZE; $i++){
			if($not_month){
				$add_event = false;
			    if($day_ct > $this->last_month_number_of_days){
					$day_ct = 1;
					$add_event = $this->checkToAddEvent($day_ct);
					$not_month = false;
				}
			} else {
			    $add_event = $this->checkToAddEvent($day_ct);
				if($day_ct > $this->this_month_number_of_days){
					$day_ct = 1;
					$not_month = true;
					$add_event = false;
				}
			}
     		$this->calendar_array[$i] = array('day'=>$day_ct, 'events'=>array(), 'not_month'=>$not_month,'add_event'=>$add_event);
			$day_ct++;
		}
	}
	private function checkToAddEvent($day){
		if($this->account == "Admin" or $this->account == "Officer"){
			return $this->checkIfCurrentDay($day);
		}else {
			return false;
		}
	}
	private function checkIfCurrentDay($day){//checks if the date is a current of future date
		$current_day = date("j");
		$current_month = date("n");
		$current_year = date("Y");
		if($this->this_year > $current_year){ 
		    return true;
		}
		if($this->this_month > $current_month and $this->this_year == $current_year ){
			return true;
		}
		if($this->this_month == $current_month and $this->this_year == $current_year and $day >= $current_day){
			return true;
		}
		return false;
	}
	
    private function getEventsFromDB($request_obj){
		/*
		this gets events from the database for the month being displayed and 
		call a function to put them on to the calendar array 
		*/
		$col_select ="
			SELECT 
			event_id,
			event_title,
			EXTRACT(YEAR FROM event_date) AS year,
			EXTRACT(MONTH FROM event_date) AS month,
			EXTRACT(DAY FROM event_date) AS day,
			repeat_type,
			EXTRACT(YEAR FROM repeat_end) AS end_year,
			EXTRACT(MONTH FROM repeat_end) AS end_month,
			EXTRACT(DAY FROM repeat_end) AS end_day
			FROM events
			WHERE 
			(repeat_type = 0 AND EXTRACT(YEAR FROM event_date) = :year AND EXTRACT(MONTH FROM event_date) = :month) OR 
			(repeat_type = 4 AND EXTRACT(MONTH FROM event_date) = :month AND 
			 (EXTRACT(YEAR FROM event_date) <= :year AND (EXTRACT(YEAR FROM repeat_end) >= :year OR EXTRACT(YEAR FROM repeat_end) IS NULL))) OR
			(repeat_type IN(1, 2, 3, 5, 6) AND (
			EXTRACT(YEAR FROM event_date) <= :year AND (EXTRACT(YEAR FROM repeat_end) >= :year OR EXTRACT(YEAR FROM repeat_end) IS NULL) AND
			EXTRACT(MONTH FROM event_date) <= :month AND (EXTRACT(MONTH FROM repeat_end) >= :month OR EXTRACT(MONTH FROM repeat_end) IS NULL)))
		";
		include("class/connect.php");
		$stmt = $pdo->prepare($col_select);
		$stmt->execute(array(':year'=>$this->this_year, ':month'=>$this->this_month));
		while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			$this->addEvent($row);
		}
	}	
	private function addEvent($event){
		/*
		this will add events to calander array. takes the object
		that represents a row from data base. 
		*/
		switch($event->repeat_type) {
			case 0:   //for events that don't repeat
			    $this->addEventOnceMonth($event->day, $event); 
				break; 
			case 1:   //events that repeat everydat for a duration
			    $this->addEventThatRepeats($event, 1); 
				break; 
			case 2:   // events that repeat once a week on a certain day
			    $this->addEventThatRepeats($event, 7); 
				break; 
			case 3:   // events that repeat once a month on a certain day
			    $this->addEventOnceMonth($event->day, $event); 
				break; 
			case 4:   // events that repeat once a year on a certain day
			    $this->addEventOnceMonth($event->day, $event); 
				break; 
			case 5:      // events that happen nth_day day of week like first thursday second friday etc            
			    $nth_day = $this->findNthDayOfDate($event->year, $event->month, $event->day);
			    $day_nth_day = $this->findDateOfNthDay($nth_day['nth_day'], $nth_day['weekday']);
			    $this->addEventOnceMonth($day_nth_day, $event); 
				break; 
			case 6:    // events once a month last weekday of month
				$last_day = $this->findDateOftheLastWeekday(date('w', mktime(1,1,1,$event->month,$event->day,$event->year))); 
				$this->addEventOnceMonth($last_day, $event);
				break; 
		}
	}
	
	private function addEventOnceMonth($day, $event){
		/*
		this places events onto calendar_array for events
		that only apear once a month. it has two arguments $day 
		for the day of the month and other event object
		*/
		$array_index_of_day = $day + $this->first_day_of_this_month - 1; 
		$event_count = count($this->calendar_array[$array_index_of_day]['events']);
		$this->calendar_array[$array_index_of_day]['events'][$event_count]['id'] = $event->event_id;
		$this->calendar_array[$array_index_of_day]['events'][$event_count]['title'] = $event->event_title;
	}
	
	private function addEventThatRepeats($event, $interval){
		/*
		this places events onto calendar_array for events that appear more
		then once per mont. it takes two arguments first the event object. 
		second is the event interval. interval is how often the event repeats. 
		*/
		$start_day = $this->findDayEventBegins($event,$interval);
		$stop_day = $this->findDayEventEnds($event);
		for($i=$start_day; $i<= $stop_day; $i+=$interval){
			$array_index_of_day = $i+ $this->first_day_of_this_month; // this calculates what index the day is in the array
			$event_count = count($this->calendar_array[$array_index_of_day]['events']);
			$this->calendar_array[$array_index_of_day]['events'][$event_count]['id'] = $event->event_id;
			$this->calendar_array[$array_index_of_day]['events'][$event_count]['title'] = $event->event_title;
		}
	}
	
	private function findDayEventBegins ($event,$interval){
		$event_weekday = date('w', mktime(1,1,1,$event->month,$event->day,$event->year));
		$first_weekday = $this->findDateOfNthDay(1, $event_weekday);
		if($event->year == $this->this_year){
			if($event->month == $this->this_month){
				$start_day = $event->day - 1;
			} else {
				$start_day = $interval == 1 ? 0 : $first_weekday - 1;
			}
		} else {
			$start_day = $interval == 1 ? 0 : $first_weekday - 1;
		}
		return $start_day;
	}
	
	private function findDayEventEnds ($event){
		if($event->end_year == $this->this_year){
			if($event->end_month == $this->this_month){
				$stop_day = $event->end_day - 1;
			} else {
				$stop_day = $this->this_month_number_of_days;
			}
		} else {
			$stop_day = $this->this_month_number_of_days;
		}
		return $stop_day;
	}
	
	private function findDateOfNthDay($nth_day, $weekday_of_event_day){
		/*
		this function return a day portion of date given month year
		weekday and nth_day of the week day like first third etc
		*/
		$weekday_of_first_day = date('w', mktime(1,1,1,$this->this_month,1,$this->this_year));
		$date_dif = $weekday_of_event_day - $weekday_of_first_day;
		$date_of_first_day = $date_dif >= 0 ? $date_dif + 1 : $date_dif + 8;
		return ($nth_day * 7) - (7 - $date_of_first_day);
	}
	
	private function findNthDayOfDate($year, $month, $day){
		/*
		This function figures out if a given date's weekday
		like monday, tuesday, etc and if it is the first secon, 
		third etc and return both of the results in a associative
		array index nth_day holding nth_day value and weekday holding weekdau value 
		*/
		$weekday_of_event_day = date('w', mktime(1,1,1,$month,$day,$year));
		$weekday_of_first_day = date('w', mktime(1,1,1,$month,1,$year));
		$date_dif = $weekday_of_event_day - $weekday_of_first_day;
		$date_of_first_day = $date_dif >= 0 ? $date_dif + 1 : $date_dif + 8;
		$return['nth_day'] = ((7- $date_of_first_day) + $day)/7;
		$return['weekday'] = $weekday_of_event_day;
		return $return;
	}

	private function findDateOftheLastWeekday($weekday_of_event_day){
		/*
		this functiion returns the last day of a weekday like
		last friday last tuesday etc. it takes the weekday a param
		*/
		$weekday_of_first_day = date('w', mktime(1,1,1,$this->this_month,1,$this->this_year));
		$date_dif = $weekday_of_event_day - $weekday_of_first_day;
		$date_of_first_day = $date_dif >= 0 ? $date_dif + 1 : $date_dif + 8;
		$nth_day = $this->this_month_number_of_days - $date_of_first_day >= 28 ? 5 : 4;
		return ($nth_day * 7) - (7 - $date_of_first_day);
	}
		
	public function createTable(){
		$weekday_count = 1;
		$table = "
		<table>
		    <thead>
			<tr>
			    <th>Sunday</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
				<th>Saturday</th>
			</tr>
			</thead>
			<tbody>
			<tr>
		";
		foreach($this->calendar_array as $val){
		    if($weekday_count == 8){
				$table .= "</tr><tr>";
				$weekday_count = 1;
			}
			$month = $val['not_month'] ? "not-month" : "this-month";
			$add_button = $val['add_event'] ? "<button type='button' class='add-event' data-day='".$val['day']."'>Add event</button>" : "";
		    $table .= "<td class='$month' data-day='".$val['day']."'><span class='calendar-day'>".$val['day']."</span>$add_button</br><div class='event-area'><ul>";
			foreach($val['events'] as $ev_list){
				$table .= "<li class='cal-event-list' data-day='".$val['day']."' data-id='".$ev_list['id']."'>".$ev_list['title']."</li>";
			}
			$table .= "</u/></div></td>";
            $weekday_count++;			
		}
		$table .= "</tr></tbody></table>";
		return $this->calendar_header.$table;
	}
}
?>