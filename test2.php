<!DOCTYPE HTML>
<html>
<head>
<style>
.this-month {
	background: #ff0066;
	cursor:pointer
}
.this-month:hover {
	background: #E36D9E
}
.not-month{
	background: #6699ff
}
.open-section {
	display:block
}
.closed-section {
	display:none
}
#calendar-arrows-mini {
	width:10em
}
#year-month-mini {
	margin-left:2em;
	font-size:1em;
	font-weight:bold
}
#left-arrow-mini {
	float:left;
	color:#00FF00;
	font-size:1em;
	cursor:pointer
}
#right-arrow-mini {
	float:right;
	color:#00FF00;
	font-size:1em;
	cursor:pointer
}
</style>
<script type="text/javascript">
var miniCalendar = function(){
	
	var cur_date = {};
	var date_objt = new Date();
	var calendar_size = 42;
	var cur_month_days, last_month_days, first_day;
	var month_name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];
	
	function findNumberDays(year, month){
		var date_obj = new Date(year, month+1, 0);
		return date_obj.getDate();
	}
	
	function findLastMonthYear(year, month){
		var return_val = {};
		if(month == 0){
			return_val.year = year - 1;
			return_val.month = 11;
		} else {
			return_val.year = year;
			return_val.month = month - 1;
		}
		return return_val;
	}
	
	function findDayOfWeekFirstDay(year, month){
		var date_obj = new Date(year, month, 1);
		return date_obj.getDay();
	}
	
	return {
		set: function(year, month){
			cur_date.year = Number(year);
			cur_date.month = Number(month);
			cur_month_days = findNumberDays(cur_date.year, cur_date.month);
			last_month = findLastMonthYear(cur_date.year, cur_date.month);
			last_month_days = findNumberDays(last_month.year, last_month.month);
			first_day = findDayOfWeekFirstDay(cur_date.year, cur_date.month);
		},
		show: function(){
			console.log(cur_date);
			console.log(cur_month_days);
			console.log(last_month_days);
			console.log(first_day);
		},
		create: function (){
			var day_count, month_class;
			var weekday_count = 1;
			var table = "<table><thead><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr>";
			if(cur_month_days == 0){
				day_count = 1;
				month_class = "this-month";
			} else {
				day_count = last_month_days - first_day + 1;
				month_class = "not-month";
			}
			for(i= 1; i <= calendar_size; i++){
				if(weekday_count == 8){
					table += "</tr><tr>";
					weekday_count = 1;
				}
				if(month_class == "not-month"){
					if(day_count > last_month_days){
						month_class = "this-month";
						day_count = 1;
					}
				} else {
					if(day_count > cur_month_days){
						month_class = "not-month";
						day_count = 1;
					}
				}
				table += "<td class='"+month_class+"' data-day='"+day_count+"'>"+day_count+"</td>";
				day_count++;
				weekday_count++;
			}
			table += "</tr></tbody></table>";
			var calendar_header = '<div id="calendar-arrows-mini" data-month="'+cur_date.month+'" data-year="'+cur_date.year+'"> \
			<span id="left-arrow-mini">&#x21E6</span> \
			<span id="year-month-mini">'+month_name[cur_date.month]+' '+cur_date.year+'</span> \
			<span id="right-arrow-mini">&#x21E8</span></div>';			 
			return calendar_header+table;
		}
	}
}();
function addListeners(){
	document.getElementById("test-mini").addEventListener('submit', miniTest , false);
	document.getElementById("test").addEventListener('submit', mainTest, false);
	document.getElementById("calendar-icon").addEventListener('click', openCalendar, false);
	document.getElementById("mini-calendar").addEventListener('click', miniCalendarClick, false);
}
function miniTest(ev){
	ev.preventDefault();
	var year = document.getElementById("test-year").value;
	var month = document.getElementById("test-month").value;
	miniCalendar.set(year, month);
	miniCalendar.show();
}
function mainTest(ev){
	ev.preventDefault();
}
function openCalendar(ev){
	var cur_date = new Date();
	var cur_year = cur_date.getFullYear();
	var cur_month = cur_date.getMonth();
	miniCalendar.set(2016, 7);
	miniCalendar.show();
	document.getElementById("mini-calendar").innerHTML = miniCalendar.create();
    document.getElementById("mini-calendar").classList.remove("closed-section");
    document.getElementById("mini-calendar").classList.add("open-section");
}
function miniCalendarClick(ev){
	if(ev.target.id == "left-arrow-mini" || ev.target.id == "right-arrow-mini"){ changeMonth(ev);}
	if(ev.target.classList.contains("this-month")){ addDateToForm(ev);}
}
function changeMonth(ev){
	//this changes month either by up one or down one
	var disp_year = Number(document.getElementById("calendar-arrows-mini").getAttribute("data-year"));
	var disp_month = Number(document.getElementById("calendar-arrows-mini").getAttribute("data-month"));
	if(ev.target.id == "left-arrow-mini"){
		if(disp_month == 0){
			var new_year = disp_year-1;
			var new_month = 11;
		} else {
			var new_year = disp_year;
			var new_month = disp_month-1;
		}
	} else {
		if(disp_month == 11){
			var new_year = disp_year+1;
			var new_month = 1;
		} else {
			var new_year = disp_year;
			var new_month = disp_month+1;
		}
	}
	miniCalendar.set(new_year, new_month);
	miniCalendar.show();
	document.getElementById("mini-calendar").innerHTML = miniCalendar.create();
}
function addDateToForm(ev){
	var add_year = document.getElementById("calendar-arrows-mini").getAttribute("data-year");
	var add_month = Number(document.getElementById("calendar-arrows-mini").getAttribute("data-month"))+1;
	var add_day = ev.target.getAttribute("data-day");
	document.getElementById("year").value = add_year;
	document.getElementById("month").value = add_month;
	document.getElementById("day").value = add_day;
    document.getElementById("mini-calendar").classList.add("closed-section");
    document.getElementById("mini-calendar").classList.remove("open-section");
}
document.addEventListener('DOMContentLoaded', addListeners);
</script>
</head>

<body>
<form id="test-mini">
month:<input type="text" id="test-month">
year:<input type="text" id="test-year">
    <input type="submit" >
</form>
<img src="images/calendar.png" id="calendar-icon" ><div id="mini-calendar" class="closed-section"></div>
<form id="test">
<p>year <input type="text" id="year"></p>
<p>month <input type="text" id="month"></p>
<p>day <input type="day" id="day"></p>
<input type="submit">
</form>

<div id="res-area"></div>
</body>
</html>