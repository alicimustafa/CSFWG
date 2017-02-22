<?php 
$cal = new EventCalendar($request_obj);
?>
<div id="right-pannel">
    <h3>Upcomming Events</h3>
    <?php 
    foreach($cal->getNext3Events(30) as $value){
        echo '<p>'.$value['month'].'-'.$value['day'].'-'.$value['year'].'</p>';
        echo '<p><a href="/event/'.$value['id'].'" class="nav-link" data-link="event/'.$value['id'].'">'.$value['title'].'</a></p>';
    }
    ?>   
</div>
