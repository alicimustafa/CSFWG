/*this section will hold javascript utility functions start*/
function findClassA( elem , classF){
/*
this function takes an element and class name
and returns the parent of the element with that class name
*/
    var par = elem.parentElement;
    if(par == null){ return null;
    } else {
        if(par.classList.contains(classF)) { return par;
        }else { return findClassA(par , classF); }
    }
};


/* utility end */
function page_listen(page){
/*
this add the event listeners to each page as needed
*/
console.log(page);
};
function loading_gif(load){
/*
this adds loading gif to main pannel and romoves it
after getting response from server 
*/
if(load){
    document.getElementById("main-pannel").style.opacity = "0.5";
    document.getElementById("loading-pannel").style.display = "block";
} else {
    document.getElementById("main-pannel").style.opacity = "1";
    document.getElementById("loading-pannel").style.display = "none";
}
}
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
    var state_obj = {page: ev.target.getAttribute("data-link")};
    var push_link = "index.php?request="+ state_obj.page;
    var link = "index.php?request=section/" + state_obj.page;
    xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200){
            document.getElementById("main-pannel").innerHTML = xhr.responseText;
            history.pushState( state_obj , "", push_link);
            page_listen(state_obj.page);
            loading_gif(false);
        };
    };
    xhr.open("get", link, true);
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
	    xhr = new XMLHttpRequest();
	    xhr.open("DELETE", "index.php?request=section/logging", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	    xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("log-pannel").innerHTML = xhr.responseText;
                document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
                var x = document.getElementsByClassName("nav-link");
                x[5].style.display = "none";
                x[6].style.display = "none";
                document.getElementById("log-submit").disabled = false;
		    }
        }
	    xhr.send(); 
    } else if(type_log == "loggon"){
        document.getElementById("log-submit").disabled = true; // disables the submit till risponse from server
        var user_name = document.getElementById("user-input").value;
        var password = document.getElementById("password-input").value;
	    var datasend = "user_name="+user_name+"&password="+password;
	    xhr = new XMLHttpRequest();
	    xhr.open("POST", "index.php?request=section/logging", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	    xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("log-pannel").innerHTML = xhr.responseText;
                document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
                var x = document.getElementsByClassName("nav-link");
                x[5].style.display = "inline";
                x[6].style.display = "inline";
                document.getElementById("log-submit").disabled = false;
		    }
        }
	    xhr.send(datasend); 
	}   
};
/* section of the javascript that will add drag and drop to a page start */

function enter_handler(ev){
    ev.target.classList.add("target-drop-area");
    var tab_target = findClassA(ev.target , "drop-area");
    if ( tab_target != null){
        var has_class = tab_target.getElementsByClassName("target-drop-area");
        if(has_class.length == 0){ tab_target.classList.remove("high-drop");
        } else { tab_target.classList.add("high-drop"); }
    }
}
function leave_handler(ev){
    ev.target.classList.remove("target-drop-area");
    var tab_target = findClassA(ev.target , "drop-area");
    if ( tab_target != null){
        var has_class = tab_target.getElementsByClassName("target-drop-area");
        if(has_class.length == 0){ tab_target.classList.remove("high-drop");
        } else { tab_target.classList.add("high-drop"); }
    }
}
function allow_drag(ev){
    ev.preventDefault();
};
function drag_start(ev){
    var mem_id = ev.target.getAttribute("data-member");
    var mem_name = ev.target.innerHTML;
    ev.dataTransfer.setData("text/id", mem_id);
    ev.dataTransfer.setData("text/name", mem_name);
    ev.dataTransfer.dropEffect = "copy";
};
function drop_handle(ev){
    ev.preventDefault();
    var mem_id = ev.dataTransfer.getData("text/id");
    var mem_name = ev.dataTransfer.getData("text/name");
    var element = findClassA (ev.target , "drop-area");
    var new_elem = document.createElement("LI");
    var text_node = document.createTextNode(mem_name);
    new_elem.appendChild(text_node);
    var new_button = document.createElement("BUTTON")
    var button_name = document.createTextNode("Remove");
    new_button.appendChild(button_name);
    new_button.setAttribute("type" , "button");
    new_elem.appendChild(new_button);
    new_button.addEventListener("click" , rm_button , false);
    new_elem.setAttribute("data-member" , mem_name );
    element.children[1].appendChild(new_elem);
    new_elem.classList.add("temp-place");
    element.classList.remove("high-drop");
    ev.target.classList.remove("target-drop-area");   
};
function rm_button(ev){
    var parr = ev.target.parentElement;
    if(parr.classList.contains("temp-place")){
        parr.parentElement.removeChild(parr);
    } else {
        if(parr.classList.contains("temp-remove")){
            parr.classList.remove("temp-remove");
            ev.target.innerHTML = "Remove";
        } else {
            parr.classList.add("temp-remove");
            ev.target.innerHTML = "Undo";
        }
    }
    
};

/* drag and drop ends */

/*section adds event listeners when page loads start */
window.onpopstate = function(event){
    if(event.state != null){
        loading_gif(true);
        var link = "index.php?request=section/" + event.state.page;
        xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if(xhr.readyState == 4 && xhr.status == 200){
                document.getElementById("main-pannel").innerHTML = xhr.responseText;
                page_listen(event.state.page);
                loading_gif(false);
            };
        };
        xhr.open("get", link, true);
        xhr.send();
    }
}
document.addEventListener('DOMContentLoaded', function () {
    var nav_links = document.getElementsByClassName("nav-link");
    for (i=0; i<nav_links.length; i++){
        nav_links[i].addEventListener('click', navigate_link, false);
    }
    document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
    var url_info = location.search;
    if (url_info == ""){
        var new_link = "index.php?request=home";
        var new_obj = {page:"home"};
    } else {
        var number = url_info.search("=");
        var info_stuff = url_info.substr(number+1);
        var new_obj ={page: info_stuff};
        var new_link =  "index.php?request="+info_stuff;
    }
    history.replaceState(new_obj, "",new_link);
    page_listen(new_obj.page);
     
/*
    var dragel = document.getElementsByClassName("drag");
    for ( i= 0; i < dragel.length ; i++){
        dragel[i].addEventListener('dragstart', drag_start , false);
    }
    var dropel = document.getElementsByClassName("drop-area");
    for ( i= 0 ;i < dropel.length ; i++){
        dropel[i].addEventListener('drop' , drop_handle , false );
        dropel[i].addEventListener('dragover' , allow_drag , false );
        dropel[i].addEventListener('dragenter' , enter_handler , false );
        dropel[i].addEventListener('dragleave' , leave_handler , false );
    }
    var remove_but = document.getElementsByClassName("remove-but");
    for ( i= 0 ; i < remove_but.length ; i++){
        remove_but[i].addEventListener('click' , rm_button , false);
    }
*/
});

/* event listeners end */

