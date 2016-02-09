var myaCSFWG = function(){
	// fallowing hold varius variables used in throughout the website
	var nav_links = document.getElementsByClassName("nav-link");
	var push_link = "index.php?request=";
	var url_link = "index.php?request=section/";
	var state_obj = {page: "home"};
	function page_listen(page){
        /*
        this add the event listeners to each page as needed
        */
        console.log(page);
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
		console.log(url_link+state_obj.page);
        xhr = new XMLHttpRequest();
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
	        xhr = new XMLHttpRequest();
	        xhr.open("DELETE", "index.php?request=section/logging", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	        xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("log-pannel").innerHTML = xhr.responseText;
                    document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
                    nav_links[5].style.display = "none";
                    nav_links[6].style.display = "none";
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
                    nav_links[5].style.display = "inline";
                    nav_links[6].style.display = "inline";
		        }
            }
	        xhr.send(datasend); 
	    }   
    };


    return {
		start: function(){
	        var nav_links = document.getElementsByClassName("nav-link");
	        for (i=0; i<nav_links.length; i++){
                nav_links[i].addEventListener('click', navigate_link, false);
            }
			var url_info = location.search;
			if(url_info == ""){
				var repl_link = "index.php?request=home";
			} else {
				var number = url_info.search("=");
				state_obj.page = url_info.substr(number+1);
				var repl_link = "index.php?request="+state_obj.page;
			}
            document.getElementById("logging-form").addEventListener('submit', logging_sub, false);
			history.replaceState(state_obj, "", repl_link);
			page_listen(state_obj.page);
	    } ,
		popstate: function(event){
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
    }		
}();
window.addEventListener('popstate' , myaCSFWG.popstate);
//window.onpopstate = myaCSFWG.popstate;
document.addEventListener('DOMContentLoaded', myaCSFWG.start );
