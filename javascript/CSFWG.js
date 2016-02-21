var myaCSFWG = function(){
	/*this section will hold javascript utility functions start*/
    function findClassA( elem , classF){
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
            }else { return findClassA(par , classF); }
        }
    };
    function findName(elem,mem_id){
		name_list = elem.getElementsByTagName("li");
		for(i=0 ; i < name_list.length ; i++){
			elem_mem = name_list[i].getAttribute("data-member");
			if( elem_mem == mem_id){return false;}
		}
		return true;
	}
	function elemClassE( elem , classF){
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

/* utility end */

	// fallowing hold varius variables used in throughout the website
	var nav_links = document.getElementsByClassName("nav-link");
	var push_link = "index.php?request=";
	var url_link = "index.php?request=section/back/";
	var state_obj = {page: "home"};
	
	function page_listen(page){
        /*
        this add the event listeners to each page as needed
        */
		switch(page){
			case "home" :
			  
			  break;
			case "about" :
			  
			  break;
			case "groups" :
			  
			  break;
			case "workshop" :
			  
			  break;
			case "resources" :
			  
			  break;
			case "archive" :
			  
			  break;
			case "members" :
			    //for listener for drag start on member list table
				var dragel = document.getElementsByClassName("drag");
				for ( i= 0; i < dragel.length ; i++){
					dragel[i].addEventListener('dragstart', drag_start , false);
				}
				//for listener field area in the group area
				var dropel = document.getElementsByClassName("drop-area");
				for ( i= 0 ;i < dropel.length ; i++){
					dropel[i].addEventListener('drop' , drop_handle , false );
					dropel[i].addEventListener('dragover' , allow_drag , false );
					dropel[i].addEventListener('dragenter' , enter_handler , false );
					dropel[i].addEventListener('dragleave' , leave_handler , false );
				}
				// for the remove button on the members in group area
				var rembut = document.getElementsByClassName("remove-but");
				for ( i= 0 ; i < rembut.length ; i++){
					rembut[i].addEventListener('click' , remove_button , false);
				}
				//for the form for adding members 
				var addmem = document.getElementById("add-member")
				if(addmem != null){
					addmem.addEventListener('submit' , add_member , false);
				}
				//for the members list tabel for changing their rank
				var rank = document.getElementsByClassName("rank-select");
				for ( i=0 ; i < rank.length ; i++){
					rank[i].addEventListener('change' , change_rank , false);
				}
				//for the form for adding new groups to the group area
				var addgrp = document.getElementById("add-group");
				if(addgrp != null){
					addgrp.addEventListener('submit' , add_group , false);
				}
				//for the remove button for removing groupss from the group area
				var remgrp = document.getElementsByClassName("group-remove");
				for ( i=0 ; i < remgrp.length ; i++){
					remgrp[i].addEventListener('click' , remove_group , false);
				}
				//for the button for changing the name of a group
				var namegrp = document.getElementsByClassName("group-name");
				for ( i=0 ; i < namegrp.length ; i++){
					namegrp[i].addEventListener('click' , group_name , false);
				}
				//for opening the reactivate member form
				var opnreact = document.getElementById("open-reactivate");
				if(opnreact != null){
					opnreact.addEventListener('click' , open_reactivate ,false);
				}
				//for the select that hold list of inactive members
				var selreact = document.getElementById("reactivate-select");
				if(selreact != null){
					selreact.addEventListener('change', select_reactivate ,false);
				}
				//for form that reactivates members
				var reactmem = document.getElementById("reactivate-form");
				if(reactmem != null){
					reactmem.addEventListener('submit', reactivate_form, false);
				}
				//for the select that hold the list of members 
				var offchg = document.getElementsByClassName("officer-select");
				for (i=0 ; i < offchg.length ; i++){
					offchg[i].addEventListener('change', change_officer, false);
				}
				//for rotate button that rotate pannel
				var rotbtn = document.getElementsByClassName("rotate-button");
				for (i=0; i < rotbtn.length ; i++){
					rotbtn[i].addEventListener('click' , rotate_pannel, false);
				}
			    break;
		}
    };
    function loading_gif(load){
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
	
	/* ----------event handlers for the common areas start---------- */
	
	function navigate_link(ev){
    /*
    this is an event handler for the navigation bar for the varius pages 
    it will load the page using ajax.  create push state in history and  
    change the url to match new page.  after that it will a function to 
    add the appropriate event listeners. It will call function to display
    loading gif while waiting for a response from the server
    */
        ev.preventDefault();
        loading_gif(true);
        state_obj.page = ev.target.getAttribute("data-link");
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if(xhr.readyState == 4 && xhr.status == 200){
                document.getElementById("main-pannel").innerHTML = xhr.responseText;
                history.pushState( state_obj , "", push_link+state_obj.page);
                page_listen(state_obj.page);
                loading_gif(false);
            };
        };
        xhr.open("get", url_link+state_obj.page, true);
        xhr.send();
    };
	function logging_sub(ev){
    /*
    this function is the event handler for the submit button on the loggin pannell
    it will check to see if logging or out and ajax to server it also disables the 
    submit till responce from server
    */
        ev.preventDefault();
        var type_log = document.getElementById("logging-val").value;
        if(type_log == "loggoff"){               
            document.getElementById("log-submit").disabled = true; // disables the submit till risponse from server
	        var xhr = new XMLHttpRequest();
	        xhr.open("DELETE", url_link + "logging", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	        xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("log-pannel").innerHTML = xhr.responseText;
                    document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
                    nav_links[5].style.display = "none";
                    nav_links[6].style.display = "none";
					refresh_page();
		        }
            }
	        xhr.send(); 
        } else if(type_log == "loggon"){
            document.getElementById("log-submit").disabled = true; // disables the submit till response from server
            var user_name = document.getElementById("user-input").value;
            var password = document.getElementById("password-input").value;
	        var datasend = "user_name="+user_name+"&password="+password;
	        var xhr = new XMLHttpRequest();
	        xhr.open("POST", url_link + "logging", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	        xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("log-pannel").innerHTML = xhr.responseText;
                    document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
                    nav_links[5].style.display = "inline";
                    nav_links[6].style.display = "inline";
					refresh_page();
		        }
            }
	        xhr.send(datasend); 
	    }   
    };
	function refresh_page (){
		/*for refreshing the page when serten things happen like after a log in or out */
		var url_info = location.search;
		var number = url_info.search("=");
		var current_page = url_info.substr(number+1);
		loading_gif(true);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if(xhr.readyState == 4 && xhr.status == 200){
                document.getElementById("main-pannel").innerHTML = xhr.responseText;
                page_listen(current_page);
                loading_gif(false);
            };
        };
        xhr.open("get", url_link+current_page, true);
        xhr.send();
	}
	
    /* ---------event handlers for the common area end-------*/
	
	/* -------- event handlers for the members page start ----*/
	function enter_handler(ev){  //event hadler for drag and drop enter effect
		ev.target.classList.add("target-drop-area");
		var tab_target = findClassA(ev.target , "drop-area");
		if (elemClassE(tab_target , "target-drop-area")){
			tab_target.classList.add("high-drop");
		} else {
			tab_target.classList.remove("high-drop")
		}
	}
	function leave_handler(ev){ //event hadler for the drag and drop leave effect
		ev.target.classList.remove("target-drop-area");
		var tab_target = findClassA(ev.target , "drop-area");
		if (elemClassE(tab_target , "target-drop-area")){
			tab_target.classList.add("high-drop");
		} else {
			tab_target.classList.remove("high-drop")
		}
	}
	function allow_drag(ev){ //event handler for the drag and drop all drag over
		ev.preventDefault();
	};
	function drag_start(ev){ //event hadler for the start of drag and drop
		var mem_id = ev.target.getAttribute("data-member");
		ev.dataTransfer.setData("text/id", mem_id);
		ev.dataTransfer.dropEffect = "copy";
	};
	function drop_handle(ev){ //event handler for the end of drag and drop
		ev.preventDefault();
		var mem_id = ev.dataTransfer.getData("text/id");
		var group_elem = findClassA (ev.target , "drop-area");
		group_elem.classList.remove("high-drop");
        ev.target.classList.remove("target-drop-area"); 
		if(findName(group_elem , mem_id)){
			var group_name = group_elem.getAttribute("data-group");
			var datasend = "group_name="+group_name+"&member_id="+mem_id;
            loading_gif(true);
    		var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200){
					document.getElementById("main-pannel").innerHTML = xhr.responseText;
					page_listen("members");
					loading_gif(false);
				}
			}
			xhr.open("POST" ,url_link+"members/groupAssignment", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send(datasend); 
		} 
	};
	function remove_button(ev){ //event hadler for the removing some one from group
		var mem_id = ev.target.parentElement.getAttribute("data-id");
		var group_elem = findClassA(ev.target, "drop-area");
		var group_name = group_elem.getAttribute("data-group");
		var datasend = "group_name="+group_name+"&member_id="+mem_id;
        loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("DELETE" ,url_link+"members/groupAssignment", true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(datasend); 
	};
	function add_member(ev){ //hadler for adding a brand new member
		ev.preventDefault();
		var first_name = document.getElementById("first-add-member").value;
		var last_name = document.getElementById("last-add-member").value;
		var email = document.getElementById("email-add-member").value;
		var datasend = "first_name="+first_name+"&last_name="+last_name+"&email="+email;
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("POST" , url_link+"members/memberList" , true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(datasend);
	};
	function change_rank(ev){ // changing rank of members
		var new_rank = ev.target.value;
		var mem_id = ev.target.getAttribute("data-member");
		var datasend = "new_rank="+new_rank+"&mem_id="+mem_id;
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("PUT" , url_link+"members/memberList" , true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(datasend); 
	}
	function add_group(ev){ // adding a new group
		ev.preventDefault();
		var group_name = document.getElementById("new-group").value;
		var datasend = "group_name="+group_name;
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState ==4 && xhr.status ==200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("POST" , url_link+"members/groupList" , true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(datasend); 
	}
	function remove_group(ev){ // removing a group
		var group_elem = findClassA(ev.target, "drop-area");
		var item_list = group_elem.getElementsByTagName("li");
		var group_name = group_elem.getAttribute("data-group");
		if(item_list.length > 0){
			window.alert("You must remove everyone from "+group_name+" before removing the group");
		}else{
		    var datasend = "group_name="+group_name;
			loading_gif(true);
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4 && xhr.status == 200){
					document.getElementById("main-pannel").innerHTML = xhr.responseText;
					page_listen("members");
					loading_gif(false);
				}
			}
		    xhr.open("DELETE" , url_link+"members/groupList" ,true);
			xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
			xhr.send(datasend);
		}
	}
	function group_name(ev){ // changing the group name
		var group_elem = findClassA(ev.target, "drop-area");
		var group_name = group_elem.getAttribute("data-group");
		var group_input = group_elem.getElementsByTagName("input");
		var new_name = group_input[0].value;
		var datasend = "group_name="+group_name+"&new_name="+new_name;
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("PUT" , url_link+"members/groupList" ,true);
		xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
		xhr.send(datasend);
	}
	function open_reactivate(ev){ //opening the reactivate form
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("GET" ,  url_link+"members/reactivate" , true);
		xhr.send();
	}
	function select_reactivate(ev){ // used to fill out the member info onto the form
		var x = ev.target.selectedIndex;
		var option_list = ev.target.options;
		document.getElementById("first-reactivate").value = option_list[x].getAttribute("data-fn");
		document.getElementById("last-reactivate").value = option_list[x].getAttribute("data-ln");
		document.getElementById("id-reactivate").value = option_list[x].getAttribute("data-id");
	}
	function reactivate_form(ev){ //submitting the reactivation form
		ev.preventDefault();
		var member_id = document.getElementById("id-reactivate").value;
		var email = document.getElementById("email-reactivate").value;
		var datasend = "email="+email+"&member_id="+member_id;
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("PUT" , url_link+"members/reactivate", true);
		xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
		xhr.send(datasend);
	}
	function change_officer(ev){//changing the officer for group
		var x = ev.target.selectedIndex;
		var option_list = ev.target.options;
		var id = option_list[x].getAttribute("data-id");
		var fn = option_list[x].getAttribute("data-fn");
		var ln = option_list[x].getAttribute("data-ln");
		var group_elem = findClassA(ev.target, "drop-area");
		var group_name = group_elem.getAttribute("data-group");
		var datasend = "id="+id+"&fn="+fn+"&ln="+ln+"&group="+group_name;
		loading_gif(true);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("main-pannel").innerHTML = xhr.responseText;
				page_listen("members");
				loading_gif(false);
			}
		}
		xhr.open("PUT" , url_link+"members/officerGroup" , true);
		xhr.send(datasend);
	}
	function rotate_pannel(ev){
		var main_container = ev.target.parentElement.parentElement;
		var div_list = main_container.getElementsByClassName("rotateable");
		for(i=0 ; i < div_list.length ; i++){
			class_list = div_list[i].classList;
			if(class_list.contains("flipped")){ 
			    class_list.remove("flipped");
			} else {
				class_list.add("flipped");
			}
		}
	}
	/* -------- event handlers for the members page end ------*/

    return {
		start: function(){
	        for (i=0; i<nav_links.length; i++){
                nav_links[i].addEventListener('click', navigate_link, false);
            }
			var url_info = location.search;
			if(url_info == ""){
				var repl_link = push_link+"home";
			} else {
				var number = url_info.search("=");
				state_obj.page = url_info.substr(number+1);
				var repl_link = push_link+state_obj.page;
			}
            document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
			history.replaceState(state_obj, "", repl_link);
			page_listen(state_obj.page);
	    } ,
		popstate: function(event){
			if(event.state != null){
                loading_gif(true);
                xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if(xhr.readyState == 4 && xhr.status == 200){
                        document.getElementById("main-pannel").innerHTML = xhr.responseText;
                        page_listen(event.state.page);
                        loading_gif(false);
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
