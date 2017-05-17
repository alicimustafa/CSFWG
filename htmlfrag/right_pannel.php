<?php 
$cal = new EventCalendar($request_obj, date("Y"), date("n"));

echo "<h4>Upcomming Events</h4><br>";
$todays_day = date("j");
foreach($cal->getNext3Events($todays_day) as $value){
    echo '<p>'.$value['month'].'-'.$value['day'].'-'.$value['year'].'</p>';
    echo '<p><a href="/event/'.$value['id'].'" class="nav-link" data-link="event/'.$value['id'].'">'.$value['title'].'</a></p>';
}
?>   
