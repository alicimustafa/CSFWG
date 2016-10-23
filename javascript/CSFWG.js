var myaMiniCalendar = function(){
	
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
			for(var i= 1; i <= calendar_size; i++){
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

var myaCSFWG = function(){
	/*this section will hold javascript utility functions start*/
    function findClassAncestor( elem , classF){
        /*
        this function takes an element and class name
        and returns the parent of the element with that class name
		or returns the element if it contains the class
        */
		if(elem.classList.contains(classF)) {return elem;}
        var par = elem.parentElement;
        if(par == null){ return null;
        } else {
            if(par.classList.contains(classF)) { return par;
            }else { return findClassAncestor(par , classF); }
        }
    };
	function ClassDescendantsExist( elem , classF){
		/* 
		checks to see if an class exist on a element or
		it's disendents
		*/
		if( elem.classList.contains(classF)){
			return true ;
		} else {
			var elem_list = elem.getElementsByClassName(classF);
			if( elem_list.length == 0){
				return false;
			} else {
				return true;
			}
		}
	}
	function createParam(params){
		/*
		this function serialized representation of a object
		it loops throug each member puts them into an array 
		format of membername = data uri encoding both. If member 
		is a array it does the same with the array with the format
		membername[] = data. after loops throug the object it will
		join then with &
		*/
		var param_array = [];
		var array_item;
		for(var i in params){
			if(Array.isArray(params[i])){
				for(var x = 0; x < params[i].length; x++){
					array_item = encodeURIComponent(i+"[]") + "=" + encodeURIComponent(params[i][x]);
					param_array.push(array_item);
				}
			} else {
				array_item = encodeURIComponent(i) + "=" + encodeURIComponent(params[i]);
				param_array.push(array_item);
			}
		}
		return param_array.join("&");
	}
	function AJAXRequest(params , callBack){
		/*
		this function uses ajax to get data from server
		it takes two arguments one an object holding method
		url and data to send.  the other function used a 
		callback once the request returns
		*/
		var xhr = new XMLHttpRequest();
		if(params.method != "GET"){params.url += "?sync_token="+document.getElementById("sync-token").value;}
		xhr.onload = function() {
			if(xhr.status == 200 && xhr.responseText != null){
				callBack(true, xhr.responseText);
			} else {
				callBack(false, xhr.status);
			} 
		}
		xhr.open(params.method, params.url, true);
		if(params.setHeader){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");}
		xhr.send(params.data);
	}
/* utility end */

	// fallowing hold varius variables used in throughout the website
	var nav_links = document.getElementsByClassName("nav-link");
	var push_link = "/";
	var url_link = "/section/";
	var state_obj = {page: "/home"};

	function calendarClickHandler(ev){
		if(ev.target.id == "left-arrow" || ev.target.id == "right-arrow"){changeCalendarMonth(ev);}
		if(ev.target.classList.contains("add-event")){getBlankEvent(ev);}
		if(ev.target.classList.contains("cal-event-list")){getEventInfo(ev);}
	}
	
	function eventClickHandler(ev){
		if(ev.target.classList.contains("rotate-button")){rotatePannel(ev);}
		if(ev.target.classList.contains("exit-event-day")){closeCalendarEvent(ev);}
		if(ev.target.id == "mini-calendar-icon"){toggleMiniCalendar(ev);}
		if(ev.target.id == "left-arrow-mini" || ev.target.id == "right-arrow-mini"){changeMiniCalendarMonth(ev);}
		if(ev.target.id == "event-delete"){deleteEvent(ev);}
		if(ev.target.nodeName == "TD"){fillForm(ev);}
	}
	
	function eventSubmitHandler(ev){
		if(ev.target.id == "event-day-form"){createOrUpdateEvent(ev);}
	}
	
	function eventChangeHandler(ev){
		if(ev.target.id == "event-repeat-type"){toggleEndDate(ev);}
	}
	
	function mainClickHandler(ev){
		/*
		this function deals with click events that happen on main-pannel div
		since main pannel uses ajax to load info need to use event delegation
		this determines what elements was cliked and call the apropreate function
		*/
		//rotate pannel 
		if(ev.target.classList.contains("rotate-button")){rotatePannel(ev);}
		//group page
		if(findClassAncestor(ev.target,"group-link") != null){navigateLink(ev);}
		//archibe page
		if(ev.target.classList.contains("arc-year")){openArchive(ev);}
		if(ev.target.classList.contains("arc-link")){navigateLink(ev);}
		//profile page
		if(ev.target.classList.contains("profile-tab")){switchProfileSection(ev);}
		//members page
		if(ev.target.classList.contains("remove-but")){removeMemberGroup(ev);}
		if(ev.target.classList.contains("group-remove")){removeGroup(ev);}
		if(ev.target.classList.contains("group-name")){changeGroupName(ev);}
		if(ev.target.classList.contains("nav-link")){navigateLink(ev);}
		if(ev.target.id == "open-reactivate"){openReactivateForm(ev);}
	}
	
	function mainChangeHandler(ev){
		/*
		this function deals with change events that happen on main-pannel div
		since main pannel uses ajax to load info need to use event delegation
		this determines what elements was cliked and call the apropreate function
		*/
		//groups page
		if(ev.target.id == "weekday-select"){updateGroupWeekday(ev);}
		//members page
		if(ev.target.classList.contains("rank-select")){changeMemberRank(ev);}
		if(ev.target.id == "reactivate-select"){ selectMemberReactivate(ev);}
		if(ev.target.classList.contains("officer-select")){changeGroupOfficer(ev);}
		
	}
	
	function mainSubmitHandler(ev){
		/*
		this function deals with submit events that happen on main-pannel div
		since main pannel uses ajax to load info need to use event delegation
		this determines what elements was cliked and call the apropreate function
		*/
		//groups page
		ev.preventDefault();
		if(ev.target.id == "disc-update"){updateGroupDiscription(ev);}
		//profile page
		if(ev.target.id == "profile-form"){memberProfileUpdate(ev);}
		if(ev.target.id == "upload-pic"){memberPictureUpload(ev);}
		if(ev.target.id == "upload-file"){uploalSubmitionFile(ev);}
		if(ev.target.id == "change-password"){changeUserPassword(ev);}
		if(ev.target.id == "change-email"){changeUserEmail(ev);}
		if(ev.target.id == "personal-quote-form"){updatePersonalQuote(ev);}
		//members page
		if(ev.target.id == "add-member"){addNewMember(ev);}
		if(ev.target.id == "add-group"){addNewGroup(ev);}
		if(ev.target.id == "reactivate-form"){reactivateOldMember(ev);}
	}
	
	function mainKeyPressHandler(ev){
		//profile page
		if(ev.target.id == "new-email" || ev.target.id == "confirm-new-email"){checkValidEmail(ev);}
		if(ev.target.id == "new-password" || ev.target.id == "verify-password"){checkValidPassword(ev);}
	}
	
	function logClickHandler(ev){
		if(ev.target.id == "profile-log"){navigateLink(ev);}
		if(ev.target.id == "key-reset"){resetKey(ev);}
	}
	
	function logSubmitHandler(ev){
		if(ev.target.id == "logging-form"){loggingSubmition(ev);}
	}
	
	function rotatePannel(ev){ //flipp pannels for rotateable pannel
		var main_container = ev.target.parentElement.parentElement;
		var div_list = main_container.getElementsByClassName("rotateable");
		for(var i=0 ; i < div_list.length ; i++){
			class_list = div_list[i].classList;
			if(class_list.contains("flipped")){ 
			    class_list.remove("flipped");
			} else {
				class_list.add("flipped");
			}
		}
	}
	/*---------- event handlers for the calendar start ------------*/
	function openCalendar(ev){
		// opens and closes the calendar 
		var sec = document.getElementById("calendar-pannel");
		if(sec.classList.contains("open-section")){
			sec.classList.add("closed-section");
			sec.classList.remove("open-section");
		} else {
			var params = {};
			params.method = "GET";
			params.url =  url_link+"calendar/displayMonth";
			params.data = null;
			params.setHeader = false;
			AJAXRequest(params, calendarResponse);
		}
	}
	function changeCalendarMonth(ev){
		//this changes month either by up one or down one
		var disp_year = Number(document.getElementById("calendar-arrows").getAttribute("data-year"));
		var disp_month = Number(document.getElementById("calendar-arrows").getAttribute("data-month"));
		if(ev.target.id == "left-arrow"){
			if(disp_month == 1){
				var link_year = disp_year-1;
				var link_month = 12;
			} else {
				var link_year = disp_year;
				var link_month = disp_month-1;
			}
		} else {
			if(disp_month == 12){
				var link_year = disp_year+1;
				var link_month = 1;
			} else {
				var link_year = disp_year;
				var link_month = disp_month+1;
			}
		}
		var params = {};
		params.method = "GET";
		params.url =  url_link+"calendar/changeMonth/"+link_year+"/"+link_month;
		params.data = null;
		params.setHeader = false;
		AJAXRequest(params, calendarResponse);
	}
	function calendarResponse(response_ok, response_text){
		//callback for the open calendar and change month
		if(response_ok){
			var sec = document.getElementById("calendar-pannel");
			var event_sec = document.getElementById("calendar-event");
			sec.innerHTML = response_text;
			sec.classList.add("open-section");
			sec.classList.remove("closed-section");
			event_sec.classList.add("closed-section");
			event_sec.classList.remove("open-section");
		} else {
			window.alert("there was error error: "+response_text);
		}
	}
	function closeCalendarEvent(ev){
		var sec = document.getElementById("calendar-event");
		sec.classList.add("closed-section");
		sec.classList.remove("open-section");
	}
	function toggleEndDate(ev){
		if(ev.target.value == 0){
			document.getElementById("event-end-area").classList.add("closed-section");
			document.getElementById("event-end-area").classList.remove("open-section");
			document.getElementById("mini-calendar").classList.remove("open-section");
			document.getElementById("mini-calendar").classList.add("closed-section");
			document.getElementById("event-end-day").value = "";
			document.getElementById("event-end-month").value = "";
			document.getElementById("event-end-year").value = "";
		} else {
			document.getElementById("event-end-area").classList.add("open-section");
			document.getElementById("event-end-area").classList.remove("closed-section");
		}
	}
	function toggleMiniCalendar(ev){
		var mini_calendar = document.getElementById("mini-calendar");
		if(mini_calendar.classList.contains("closed-section")){
			var event_year = Number(document.getElementById("event-year").value);
			var event_month = Number(document.getElementById("event-month").value)-1;
			myaMiniCalendar.set(event_year, event_month);
			mini_calendar.innerHTML = myaMiniCalendar.create();
			mini_calendar.classList.add("open-section");
			mini_calendar.classList.remove("closed-section");
		} else {
			mini_calendar.classList.remove("open-section");
			mini_calendar.classList.add("closed-section");
		}
	}
	function changeMiniCalendarMonth(ev){
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
		myaMiniCalendar.set(new_year, new_month);
		document.getElementById("mini-calendar").innerHTML = myaMiniCalendar.create();
	}
	function fillForm(ev){
		var day = ev.target.getAttribute("data-day");
		var month = Number(document.getElementById("calendar-arrows-mini").getAttribute("data-month"));
		var year = document.getElementById("calendar-arrows-mini").getAttribute("data-year");
		document.getElementById("event-end-day").value = day;
		document.getElementById("event-end-month").value = month+1;
		document.getElementById("event-end-year").value = year;
	    document.getElementById("mini-calendar").classList.remove("open-section");
	    document.getElementById("mini-calendar").classList.add("closed-section");
	}
	function getBlankEvent(ev){
		//get a blank event form when some one click the the add event button
		var link_year = document.getElementById("calendar-arrows").getAttribute("data-year");
		var link_month = document.getElementById("calendar-arrows").getAttribute("data-month");
		var link_day = ev.target.getAttribute("data-day");
		var params = {};
		params.method = "GET";
		params.url = url_link+"calendar/blankForm/"+link_year+"/"+link_month+"/"+link_day;
		params.data = null;
		AJAXRequest(params, calendarEventResponse);
	}
	function getEventInfo(ev){
		var link_year = document.getElementById("calendar-arrows").getAttribute("data-year");
		var link_month = document.getElementById("calendar-arrows").getAttribute("data-month");
		var link_day = ev.target.getAttribute("data-day");
		var event_id = ev.target.getAttribute("data-id");
		var params = {};
		params.method = "GET";
		params.url = url_link+"calendar/eventDay/"+link_year+"/"+link_month+"/"+link_day+"/"+event_id;
		params.data = null;
		params.setHeader = false;
		AJAXRequest(params, calendarEventResponse);
	}
	function createOrUpdateEvent(ev){
        ev.preventDefault();
		var data_object = {};
		var params = {};
		var day = document.getElementById("event-day").value;
		var month = document.getElementById("event-month").value;
		var year = document.getElementById("event-year").value;
		var start_day = Number(document.getElementById("event-start-day").value);
		var start_month = Number(document.getElementById("event-start-month").value);
		var start_year = Number(document.getElementById("event-start-year").value);
		var end_day = Number(document.getElementById("event-end-day").value);
		var end_month = Number(document.getElementById("event-end-month").value);
		var end_year = Number(document.getElementById("event-end-year").value);
		data_object.event_date = start_year+"-"+start_month+"-"+start_day;
		if(end_day == 0 || end_month == 0 || end_year == 0){
			data_object.end_date = null;
		} else {
			if(end_day < start_day || end_month < start_month || end_year < start_year ){
				data_object.end_date = null;
			} else {
		        data_object.end_date = end_year+"-"+end_month+"-"+end_day;
			}
		}
		if(document.getElementById("event-submit").value == "Update Event"){
			data_object.event_id = document.getElementById("event-id").value;
		    params.method = "PUT";
		} else  if(document.getElementById("event-submit").value == "Create Event"){
		    params.method = "POST";
		}
		data_object.event_title = document.getElementById("event-title").value;
		data_object.event_discription = document.getElementById("event-discription").value;
		data_object.repeat_type = document.getElementById("event-repeat-type").value;
		data_object.event_type = null;
		params.url = url_link+"calendar/eventDay/"+year+"/"+month+"/"+day;
		params.data = createParam(data_object);
		AJAXRequest(params, calendarResponse);
	}
	function deleteEvent(ev){
		var params = {};
		var day = document.getElementById("event-day").value;
		var month = document.getElementById("event-month").value;
		var year = document.getElementById("event-year").value;
		params.method = "DELETE";
		params.url = url_link+"calendar/eventDay/"+year+"/"+month+"/"+day;
		params.data = "event_id="+document.getElementById("event-id").value;
		params.setHeader = true;
		AJAXRequest(params, calendarResponse);
	}
	function calendarEventResponse(response_ok, response_text){
		//call back to event display
		if(response_ok){
			var sec = document.getElementById("calendar-event");
			sec.innerHTML = response_text;
			sec.classList.add("open-section");
			sec.classList.remove("closed-section");
		} else {
			window.alert("there was error error: "+response_text);
		}
	}
	/*-----------event hadlers for the calendar end -----------------*/
	/* ----------event handlers for the common areas start---------- */
	
	function navigateLink(ev){
    /*
    this is an event handler for the navigation bar for the varius pages 
    it will load the page using ajax.  create push state in history and  
    change the url to match new page.  after that it will call a function to 
    add the appropriate event listeners. It will call function to display
    loading gif while waiting for a response from the server
    */
        ev.preventDefault();
        toggleLoadingGif(true);
		if(ev.target.nodeName == "A"){
			var link_elem = ev.target;
		} else {
			var link_elem = ev.target.parentElement;
		}
        state_obj.page = link_elem.getAttribute("data-link");
		var params = {};
		params.method = "GET";
		params.url =  url_link+state_obj.page;
		params.data = null;
		params.setHeader = false;
		AJAXRequest(params, navigationResponse);
    };
	function navigationResponse(response_ok, response_text){
		if(response_ok){
			document.getElementById("main-pannel").innerHTML = response_text;
			history.pushState( state_obj , "", push_link+state_obj.page);
			toggleLoadingGif(false);
		} else {
			window.alert("there was a problem loading page error: "+response_text);
		}
	}
    function loggingSubmition(ev){
    /*
    this function is the event handler for the submit button on the loggin pannell
    it will check to see if logging or out and ajax to server it also disables the 
    submit till responce from server
    */
        ev.preventDefault();
		var params = {};
        var type_log = document.getElementById("logging-val").value;
        if(type_log == "loggoff"){               
            document.getElementById("log-submit").disabled = true; // disables the submit till risponse from server
			params.method = "DELETE";
			params.url = url_link + "logging";
			params.data = null;
			params.setHeader = false;
			AJAXRequest(params, loggoffResponse);
        } else if(type_log == "loggon"){
            document.getElementById("log-submit").disabled = true; // disables the submit till response from server
			var data_object = {};
            data_object.user_name = document.getElementById("user-input").value;
            data_object.password = document.getElementById("password-input").value;
			params.method = "POST";
			params.url = url_link + "logging";
			params.data = createParam(data_object);
			params.setHeader = true;
			if(params.password == "1234"){window.alert("you are using the default password please change it on your profile");}
			AJAXRequest(params, loggonResponse);
	    }   
    };
	function resetKey(ev){
		var confirm_reset = window.confirm("Are you sure you want to reset encryption key");
		if(confirm_reset){
			var params = {};
			params.method = "PUT";
			params.url = url_link + "logging";
			params.data = null;
			params.setHeader = false;
			AJAXRequest(params, loggoffResponse);
		}
	}
	function loggoffResponse(response_ok, response_text){
		if(response_ok){
			document.getElementById("log-pannel").innerHTML = response_text;
			nav_links[5].style.display = "none";
			nav_links[6].style.display = "none";
			refreshMainPannel();
		} else {
			document.getElementById("log-submit").disabled = false;
			window.alert("there was error error: "+response_text);
		}
	}
	function loggonResponse(response_ok, response_text){
		if(response_ok){
			document.getElementById("log-pannel").innerHTML = response_text;
			nav_links[5].style.display = "inline";
			nav_links[6].style.display = "inline";
			refreshMainPannel();
		} else {
			document.getElementById("log-submit").disabled = false;
			window.alert("there was error connecting to server error: "+response_text);
		}
	}
	function refreshMainPannel(){
		/*for refreshing the page when sertan things happen like after a log in or out */
		toggleLoadingGif(true);
		var params = {};
		params.method = "GET";
		params.url = url_link+state_obj.page;
		params.data = null;
		params.setHeader = false;
		AJAXRequest(params, main_pannelResponse);
	}
	
    /* ---------event handlers for the common area end-------*/
	
	/* -------- event handlers for the members page start ----*/
	function enterDragHandler(ev){  //event hadler for drag and drop enter effect
	    if(findClassAncestor(ev.target, "drop-area") != null){
			ev.target.classList.add("target-drop-area");
			var tab_target = findClassAncestor(ev.target , "drop-area");
			if (ClassDescendantsExist(tab_target , "target-drop-area")){
				tab_target.classList.add("high-drop");
			} else {
				tab_target.classList.remove("high-drop")
			}
		}
	}
	function leaveHandler(ev){ //event hadler for the drag and drop leave effect
	    if(findClassAncestor(ev.target, "drop-area") != null){
			ev.target.classList.remove("target-drop-area");
			var tab_target = findClassAncestor(ev.target , "drop-area");
			if (ClassDescendantsExist(tab_target , "target-drop-area")){
				tab_target.classList.add("high-drop");
			} else {
				tab_target.classList.remove("high-drop")
			}
		}
	}
	function allowDrag(ev){ //event handler for the drag and drop all drag over
	    if(findClassAncestor(ev.target, "drop-area") != null){
			ev.preventDefault();
		}
	};
	function dragStartHandler(ev){ //event hadler for the start of drag and drop
	    if(ev.target.classList.contains("drag")){
			var mem_id = ev.target.getAttribute("data-member");
			ev.dataTransfer.setData("text/id", mem_id);
			ev.dataTransfer.dropEffect = "copy";
		}
	};
	function dropHandler(ev){ //event handler for the end of drag and drop
	    if(findClassAncestor(ev.target, "drop-area") != null){
			ev.preventDefault();
			var data_object = {};
			data_object.member_id = ev.dataTransfer.getData("text/id");
			var group_elem = findClassAncestor (ev.target , "drop-area");
			group_elem.classList.remove("high-drop");
			ev.target.classList.remove("target-drop-area");
			if(isNameInGroup(group_elem , data_object.member_id)){
				data_object.group_id = group_elem.getAttribute("data-group");
				var params = {};
				params.method = "POST";
				params.url = url_link+"back/members/groupAssignment";
				params.data = createParam(data_object);
				toggleLoadingGif(true);
				params.setHeader = true;
				AJAXRequest(params, main_pannelResponse);
			} 
		}
	};
    function isNameInGroup(elem,mem_id){
		/*
		This function checks to see if a name
		is allready in a group if it is returns false
		*/
		name_list = elem.getElementsByTagName("li");
		for(var i=0 ; i < name_list.length ; i++){
			elem_mem = name_list[i].getAttribute("data-id");
			if( elem_mem == mem_id){return false;}
		}
		return true;
	}
	function removeMemberGroup(ev){ //event hadler for the removing some one from group
	    var data_object = {};
		var params = {};
		toggleLoadingGif(true);
		data_object.member_id = ev.target.parentElement.getAttribute("data-id");
		var group_elem = findClassAncestor(ev.target, "drop-area");
		data_object.group_id = group_elem.getAttribute("data-group");
		params.method = "DELETE";
		params.url = url_link+"back/members/groupAssignment";
		params.data = createParam(data_object);
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	};
	function addNewMember(ev){ //hadler for adding a brand new member
		ev.preventDefault();
		toggleLoadingGif(true);
		var data_object = {};
		var params = {};
		data_object.first_name = document.getElementById("first-add-member").value;
		data_object.last_name = document.getElementById("last-add-member").value;
		data_object.email = document.getElementById("email-add-member").value;
		params.method = "POST";
		params.url = url_link+"back/members/memberList";
		params.data = createParam(data_object);
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	};
	function changeMemberRank(ev){ // changing rank of members
		toggleLoadingGif(true);
	    var data_object = {}, params = {};
		data_object.new_rank = ev.target.value;
		data_object.mem_id = ev.target.getAttribute("data-member");
		params.method = "PUT";
		params.url = url_link+"back/members/memberList";
		params.data= createParam(data_object);
		AJAXRequest(params, main_pannelResponse);
	}
	function addNewGroup(ev){ // adding a new group
		ev.preventDefault();
		toggleLoadingGif(true);
	    var params = {};
		var group_name = document.getElementById("new-group").value;
		params.method = "POST";
		params.url = url_link+"back/members/groupList";
		params.data = "new_name="+group_name;
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function removeGroup(ev){ // removing a group
		var group_elem = findClassAncestor(ev.target, "drop-area");
		var item_list = group_elem.getElementsByTagName("li");
		var group_name = group_elem.getElementsByTagName("input")[0].value;
		var group_id = group_elem.getAttribute("data-group");
		if(item_list.length > 0){
			window.alert("You must remove everyone from "+group_name+" before removing the group");
		}else{
			toggleLoadingGif(true);
			var params = {};
			params.method = "DELETE";
			params.url = url_link+"back/members/groupList";
		    params.data = "group_id="+group_id;
			params.setHeader = true;
			AJAXRequest(params, main_pannelResponse);
		}
	}
	function changeGroupName(ev){ // changing the group name
		toggleLoadingGif(true);
	    var data_object = {}, params = {};
		var group_elem = findClassAncestor(ev.target, "drop-area");
		data_object.group_id = group_elem.getAttribute("data-group");
		data_object.new_name = group_elem.getElementsByTagName("input")[0].value;
		params.method = "PUT";
		params.url = url_link+"back/members/groupList";
		params.data = createParam(data_object);
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function openReactivateForm(ev){ //opening the reactivate form
		toggleLoadingGif(true);
		var params = {};
		params.method = "GET";
		params.url = url_link+"back/members/reactivate";
		params.data = null;
		params.setHeader = false;
		AJAXRequest(params, main_pannelResponse);
	}
	function selectMemberReactivate(ev){ // used to fill out the member info onto the form
		var x = ev.target.selectedIndex;
		var option_list = ev.target.options;
		document.getElementById("first-reactivate").value = option_list[x].getAttribute("data-fn");
		document.getElementById("last-reactivate").value = option_list[x].getAttribute("data-ln");
		document.getElementById("id-reactivate").value = option_list[x].getAttribute("data-id");
	}
	function reactivateOldMember(ev){ //submitting the reactivation form
		ev.preventDefault();
		toggleLoadingGif(true);
		var params = {}, data_object = {};
		data_object.member_id = document.getElementById("id-reactivate").value;
		data_object.email = document.getElementById("email-reactivate").value;
		params.method = "PUT";
		params.url = url_link+"back/members/reactivate";
		params.data = createParam(data_object);
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function changeGroupOfficer(ev){//changing the officer for group
		toggleLoadingGif(true);
		var params = {}, data_object = {};
		data_object.officer_id = ev.target.value;
		var group_elem = findClassAncestor(ev.target, "drop-area");
		data_object.group_id = group_elem.getAttribute("data-group");
		params.method = "PUT";
		params.url = url_link+"back/members/officerGroup";
		params.data= createParam(data_object);
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	/* -------- event handlers for the members page end ------*/
	
	/* -------- event handlers for the member profile begin ---*/
	function switchProfileSection(ev){
		/*
		this function switches between tabs in the user profile section
		*/
		var section_id = ev.target.getAttribute("data-section");
		var new_section = document.getElementById(section_id);
		var old_section = document.getElementsByClassName("open-section");
		var old_tab = document.getElementsByClassName("open-tab");
		old_section[0].classList.add("closed-section");
		old_section[0].classList.remove("open-section");
		old_tab[0].classList.add("closed-tab");
		old_tab[0].classList.remove("open-tab");
		ev.target.classList.add("open-tab");
		ev.target.classList.remove("closed-tab");
		new_section.classList.add("open-section");
		new_section.classList.remove("closed-section");
	}
	function updatePersonalQuote(ev){
		ev.preventDefault();
		toggleLoadingGif(true);
		var params = {};
		params.method = "PUT";
		params.url = url_link+"back"+state_obj.page+"/updateQuote";
		params.data = "personal_qt="+document.getElementById("personal-quote").value;
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function memberProfileUpdate(ev){
		/*
		for the form that updates user profile information
		*/
		ev.preventDefault();
		toggleLoadingGif(true);
		var data_object = {}, params = {};
		data_object.address = document.getElementById("update-address").value;
		data_object.city = document.getElementById("update-city").value;
		data_object.state = document.getElementById("update-state").value;
		data_object.zip = document.getElementById("update-zip").value;
		data_object.phone = document.getElementById("update-phone").value;
		data_object.privacy = document.getElementById("update-privacy").value;
		params.method = "PUT";
		params.url = url_link+"back"+state_obj.page+"/updateProfile";
		params.data = createParam(data_object);
		AJAXRequest(params, main_pannelResponse);
	}
	function checkValidPassword(ev){
		var new_password = document.getElementById("new-password");
		var validate = document.getElementById("verify-password");
		var submit_but = document.getElementById("change-password-submit");
		if(new_password.value == validate.value && new_password != ""){
			submit_but.disabled = false;
			validate.nextSibling.innerHTML = "";
		} else {
			validate.nextSibling.innerHTML = "This must match the field above";
			submit_but.disabled = true;
		}
	}
	function changeUserPassword(ev){
		ev.preventDefault();
		toggleLoadingGif(true);
		var data_object = {}, params = {};
		if(document.getElementById("current-password") != null){
			data_object.current_password = document.getElementById("current-password").value;
			data_object.new_password = document.getElementById("new-password").value;
			data_object.verify_password = document.getElementById("verify-password").value;
		    params.data = createParam(data_object);
		} else {
			params.data = null;
		}
		params.method = "PUT";
		params.url = url_link+"back"+state_obj.page+"/updatePass";
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function checkValidEmail(ev){
		/*
		checks to see if the new email matches varify email real time 
		submit button disabled until it does
		*/
		var new_email = document.getElementById("new-email");
		var validate = document.getElementById("confirm-new-email");
		var submit_but = document.getElementById("new-email-submit");
		if(new_email.value == validate.value && new_email != ""){
			submit_but.disabled = false;
			validate.nextSibling.innerHTML = "";
		} else {
			validate.nextSibling.innerHTML = "This must match the field above";
			submit_but.disabled = true;
		}
	}
	function changeUserEmail(ev){
		ev.preventDefault();
		toggleLoadingGif(true);
		var params = {};
		params.method = "PUT";
		params.url = url_link+"back"+state_obj.page+"/updateEmail";
		params.data = "new_email="+document.getElementById("new-email").value;
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function main_pannelResponse(response_ok, response_text){
		if(response_ok){
			document.getElementById("main-pannel").innerHTML = response_text;
			toggleLoadingGif(false);
		} else {
			toggleLoadingGif(false);
			window.alert("there was a problem loading page error: "+response_text);
		}
	}
	function memberPictureUpload(ev){
		/*
		form for uploading user picture for the profile picture
		*/
		ev.preventDefault();
		toggleLoadingGif(true);
		var params = {};
        document.getElementById("submit-pic").disabled = true; // disables the submit till response from server
		params.method = "POST";
		params.url = url_link+"back"+state_obj.page+"/uploadPic";
		params.data = new FormData(ev.target);
		params.setHeader = false;
		AJAXRequest(params, pictureUploadResponse);
	}
	function pictureUploadResponse(response_ok, response_text){
		if(response_ok){
			document.getElementById("main-pannel").innerHTML = response_text;
			toggleLoadingGif(false);
		} else {
			toggleLoadingGif(false);
            document.getElementById("submit-pic").disabled = false; // disables the submit till response from server
			window.alert("there was a problem loading page error: "+response_text);
		}
	}
	function uploalSubmitionFile(ev){
		/*
		form for uploading user submitions
		*/
		ev.preventDefault();
		toggleLoadingGif(true);
		document.getElementById("submit-file").disabled = true;
		var params = {};
		params.method = "POST";
		params.url = url_link+"back"+state_obj.page+"/submitionUpload";
		params.data = new FormData(ev.target);
		params.setHeader = false;
		AJAXRequest(params, uploadFileResponse);
		var xhr = new XMLHttpRequest();
	}
	function uploadFileResponse(response_ok, response_text){
		if(response_ok){
			document.getElementById("main-pannel").innerHTML = response_text;
			toggleLoadingGif(false);
		} else {
			toggleLoadingGif(false);
            document.getElementById("submit-file").disabled = false; // disables the submit till response from server
			window.alert("there was a problem loading page error: "+response_text);
		}
	}
	/* --------event handlers for the members profile ends ----*/
    
	/* --------event handler for the group page start ---------*/
	function updateGroupWeekday(ev){ //updating the group weekday using ajax
	    var params = {};
		params.method = "PUT";
		params.url = url_link+"back/"+state_obj.page+"/updateGroupWeekday";
		params.data = "weekday="+ev.target.value;
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	function updateGroupDiscription(ev){ //updating the group discription using ajax
		ev.preventDefault();
		var params = {};
		params.method = "PUT";
		params.url = url_link+"back/"+state_obj.page+"/updateDisc";
		params.data = "discription="+document.getElementById("disc-field").value;
		params.setHeader = true;
		AJAXRequest(params, main_pannelResponse);
	}
	/* --------event heanler for the group page end -----------*/
	
	/* --------event handlers for the arhive page start -------*/
	function openArchive(ev){
		var arc_table = ev.target.nextSibling;
		if(arc_table.classList.contains('closed-section')){
			arc_table.classList.add('open-section');
			arc_table.classList.remove('closed-section');
		} else {
			arc_table.classList.add('closed-section');
			arc_table.classList.remove('open-section');
		}
	}
	/* --------event handlers for archive page end ------------*/
    function toggleLoadingGif(load){
    /*
    this adds loading gif to main pannel and removes it
    after getting response from server. this is to give feed back
	to the user of the website
    */
        if(load){
            document.getElementById("main-pannel").style.opacity = "0.5";
            document.getElementById("loading-pannel").style.display = "block";
        } else {
            document.getElementById("main-pannel").style.opacity = "1";
            document.getElementById("loading-pannel").style.display = "none";
        }
    }
    return {
		/*
		    fallowing function are exposed to the outside of this 
	    closure. start get attached to the domcontenloaded event.  It 
		will add all of the other event listeners needed based on 
		page and the universal event hadlers
		    popstate gets attached to the popstate event and will 
		load proper page based on event state object so the back 
		button works on the browser
		*/
		start: function(){
			var url_info = location.pathname;
			if(url_info == "/"){
				var repl_link = "home";
			} else {
				state_obj.page = url_info;
				var repl_link = state_obj.page;
			}
	        for (var i=0; i < nav_links.length; i++){
                nav_links[i].addEventListener('click', navigateLink, false);
            }
			history.replaceState(state_obj, "", repl_link);
			//adding events to calendar pannel div
			var calendar_pannel = document.getElementById("calendar-pannel");
			calendar_pannel.addEventListener('click', calendarClickHandler, false);
			//adding event handlers to calendar icon
			document.getElementById("calendar-icon").addEventListener('click', openCalendar, false);
			//adding events to calendar-event div
			var calendar_event = document.getElementById("calendar-event");
			calendar_event.addEventListener('click', eventClickHandler, false);
			calendar_event.addEventListener('submit', eventSubmitHandler, false);
			calendar_event.addEventListener('change', eventChangeHandler, false);
			//adding events to log-pannel div
			var log_pannel = document.getElementById("log-pannel");
			log_pannel.addEventListener('click', logClickHandler, false);
			log_pannel.addEventListener('submit', logSubmitHandler, false);
			//adding events to main-pannel div
			var main_pannel = document.getElementById("main-pannel");
			main_pannel.addEventListener('click', mainClickHandler, false);
			main_pannel.addEventListener('change', mainChangeHandler, false);
			main_pannel.addEventListener('submit', mainSubmitHandler, false);
			main_pannel.addEventListener('dragstart', dragStartHandler , false);
			main_pannel.addEventListener('drop' , dropHandler , false );
			main_pannel.addEventListener('dragover' , allowDrag , false );
			main_pannel.addEventListener('dragenter' , enterDragHandler , false );
			main_pannel.addEventListener('dragleave' , leaveHandler , false );
			main_pannel.addEventListener('keyup' , mainKeyPressHandler , false);
	    } ,
		popstate: function(event){
			if(event.state != null){
                toggleLoadingGif(true);
                xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if(xhr.readyState == 4 && xhr.status == 200){
                        document.getElementById("main-pannel").innerHTML = xhr.responseText;
                        toggleLoadingGif(false);
                    };
                };
                xhr.open("get", url_link+event.state.page, true);
                xhr.send();
            }

	    }
    }		
}();
window.addEventListener('popstate' , myaCSFWG.popstate);
document.addEventListener('DOMContentLoaded', myaCSFWG.start );
