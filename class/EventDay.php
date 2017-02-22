<?php

class EventDay 
{
	private $event_year, $event_month, $event_day, $number_days, $event_start_day, $event_start_month, $event_start_year;
	private $event_id, $event_title, $event_discription, $event_end_day, $event_end_month, $event_end_year;
	private $event_repeat_type = 0;
	private $submit_value = "Create Event";
	private $delete_button = "";
	private $account; //privilage level of the user
	
	public function __construct($request_obj){
		$this->event_year = $this->event_start_year = $request_obj->arg[1];
		$this->event_month = $this->event_start_month = $request_obj->arg[2];
		$this->event_day = $this->event_start_day = $request_obj->arg[3];
		$this->number_days = date('t', mktime(1,1,1,$request_obj->arg[2],1,$request_obj->arg[1]));
		$this->account = $request_obj->account_priv;
		$this->event_id = $this->event_title = $this->event_discription = $this->event_end_day = $this->event_end_month = $this->event_end_year = "";
	}
	
	public function fillEventInfo($event){
		$this->event_start_year = $event->year;
		$this->event_start_month = $event->month;
		$this->event_start_day = $event->day;
		$this->event_id = $event->event_id;
		$this->event_title = $event->event_title;
		$this->event_discription = $event->event_discl;
		$this->event_repeat_type = $event->repeat_type;
		$this->event_end_day = $event->end_day;
		$this->event_end_month = $event->end_month;
		$this->event_end_year = $event->end_year;
		$this->submit_value = "Update Event";
		if($this->event_start_year == $this->event_year and $this->event_start_month == $this->event_month and $this->event_start_day == $this->event_day){
			$this->delete_button = '<button type="button" id="event-delete">Delete Event</button>';
		}
	}
	
	public function displayEvent($display_back = true){
		$option = $this->createRepeatOptions();
        if($display_back = true){
            $title = '<a href="/event/'.$this->event_id.'" class="nav-link" data-link="event/'.$this->event_id.'">'.$this->event_title.'</a>';
        } else {
            $title = $this->title;
        }
		$front_pannel = '
		    <h3>Event title:'.$title.'</h3>
			<p>Event starts on: '.$this->event_start_month.'-'.$this->event_start_day.'-'.$this->event_start_year.'  Event ends on: '.$this->event_end_month.'-'.$this->event_end_day.'-'.$this->event_end_year.' </p>
			<p>Event repeats:'.$option[$this->event_repeat_type].' </p>
			<p>Event dispription:</p>
			<p>'.$this->event_discription.'</p>
			<br>
			<button type="button" class="exit-event-day">Close this window</button>
		';
		if(($this->account == "Officer" or $this->account == "Admin") and $display_back === true){
			$pannels = '
			    <div  class="rotateable front-pannel">
                    <button class="rotate-button" type="button">&#8617</button>
					'.$front_pannel.'
				</div>
			    <div  class="rotateable back-pannel">
                    <button class="rotate-button" type="button">&#8617</button>
					'.$this->createEventForm().'
				</div>
			';
		} else {
			$pannels = $front_pannel;
		}
		return $pannels;
	}
	
	public function createEventForm(){
		$form = '
			<form id="event-day-form">
				<input type="hidden" id="event-day" value="'.$this->event_day.'">
				<input type="hidden" id="event-month" value="'.$this->event_month.'">
				<input type="hidden" id="event-year" value="'.$this->event_year.'">
				<input type="hidden" id="event-start-day" value="'.$this->event_start_day.'">
				<input type="hidden" id="event-start-month" value="'.$this->event_start_month.'">
				<input type="hidden" id="event-start-year" value="'.$this->event_start_year.'">
				<input type="hidden" id="event-id" value="'.$this->event_id.'">
				<p>Event title:<input type="text" id="event-title" value="'.$this->event_title.'"></p>
				<p>Event discription</p>
				<textarea id="event-discription" rows="12" cols="80" maxlength="1400" placeholder="discriptin of the event max of 1400 charecters" form="event-day-form">'.$this->event_discription.'</textarea>
				<p>Event starts on '.$this->event_start_month.'-'.$this->event_start_day.'-'.$this->event_start_year.'</p>
				<p>does the event repeat <select id="event-repeat-type">';
		$options = $this->createRepeatOptions();
		$form .= '<option value="'.$this->event_repeat_type.'">'.$options[$this->event_repeat_type].'</option>';
		foreach($options as $key => $value){
			if($key == $this->event_repeat_type){continue;}
			$form .= '<option value="'.$key.'">'.$value.'</option>';
		}
		$event_end_area_class = $this->event_repeat_type == 0 ? "closed-section" : "open-section";
		$form .= '
				</select></p>
				<div id="event-end-area" class="'.$event_end_area_class.'">
					<p>When the events ends <img src="images/Calendar.png" id="mini-calendar-icon" alt="mini calendar"><div id="mini-calendar" class="closed-section"></div></p>
					<label for="event-end-month">Month:</label>
					<input type="text" id="event-end-month" maxlength="2" size="2" value="'.$this->event_end_month.'">
					<label for="event-end-day">day:</label>
					<input type="text" id="event-end-day" maxlength="2" size="2" value="'.$this->event_end_day.'">
					<label for="event-end-year">Year:</label>
					<input type="text" id="event-end-year" maxlength="4" size="4" value="'.$this->event_end_year.'">
				</div>
				<br>
				<input type="submit" id="event-submit" value="'.$this->submit_value.'">
				'.$this->delete_button.'
			</form>
			<br>
			<button type="button" class="exit-event-day">Close this window</button>
		';
		return $form;
	}
	
	private function createRepeatOptions(){
		$option[0] = "does not repeat";
		$option[1] = "repeats everyday";
		$option[2] = "repeats on every ".date('l', mktime(1,1,1,$this->event_start_month,$this->event_start_day,$this->event_start_year));
		$option[3] = "repeats once a month on ".$this->event_start_day;
		$option[4] = "repeats once a year on ".date('F', mktime(1,1,1,$this->event_start_month,$this->event_start_day,$this->event_start_year))." ".$this->event_start_day;
		$option[5] = $this->fillOptionNthDay();
		if(($this->number_days - $this->event_start_day) < 7){
			$option[6] = "repeats on last ".date('l', mktime(1,1,1,$this->event_start_month,$this->event_start_day,$this->event_start_year));
		}
		return $option;
	}
	
	private function fillOptionNthDay(){
		$nth_day = $this->findNthDayOfDate($this->event_start_year, $this->event_start_month, $this->event_start_day);
		$nth_day_words = array("first", "second", "third", "fourth", "fifth");
		$weekday_name = date('l', mktime(1,1,1,$this->event_start_month,$this->event_start_day,$this->event_start_year));
		return "repeats on ".$nth_day_words[$nth_day-1]." ".$weekday_name;
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
		return ((7- $date_of_first_day) + $day)/7;
	}
}
?>