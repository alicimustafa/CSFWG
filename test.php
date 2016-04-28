	<div id="front-pannel" class="rotateable  ">
		<button class='rotate-button' type='button'>&#8617</button>		<h2> Wednesday group </h2>
		<h3>Meets on Wednesday</h3>
		<div class="group-pic"><img src="images/groupPics/defaultGroup.png" alt="group picture"></div>
		<div class="group-disc"><p></p></div>
		<div class="group-members">
			<h4>Group Officer</hr>
		    <div class="officer-list">
			    <a href="index.php?request=profile/2" data-link="profile/2">
				    <img src="images/profilePics/default.jpg" alt="member picture">
					<p>Henry</p>
				</a>
			</div>
			<h4> Members </h4>
			<div class="group-member">
			<a href="index.php?request=profile/6" data-link="profile/6">
			<img src="images/profilePics/default.jpg" alt="member picture"><p>Becky</p></a>
			</div><div class="group-member">
			<a href="index.php?request=profile/7" data-link="profile/7">
			<img src="images/profilePics/default.jpg" alt="member picture"><p>Billie</p></a>
			</div>		
		</div>
	</div>
	<div id="back-pannel" class="rotateable ">
		<button class='rotate-button' type='button'>&#8617</button>		<h2> Wednesday group </h2>
		<h3>Meets on <select id="weekday-select">
		<option value="4">Wednesday</option>
		<option value='0'> Sunday </option>
		<option value='1'> Monday </option>
		<option value='2'> Tuesday </option>
		<option value='4'> Thursday </option>
		<option value='5'> Friday </option>
		<option value='6'> Saturday </option></h3>
		<div class="group-pic">
		    <img src="images/groupPics/defaultGroup.png" alt="group picture">
		<br>
			<form enctype="multipart/form-data" action="" method="POST" id="upload-pic">
				<input type="hidden" name="MAX_FILE_SIZE" value="300000" >
				<input name="userpic" type="file"  accept=".jpg , .png , .gif" >
				<input type="submit" id="submit-pic" value="Send picture" >
			</form>	
		</div>
		<div class="group-disc"><p></p></div>
		<div class="group-members">
			<h4>Group Officer</hr>
		    <div class="officer-list">
			    <a href="index.php?request=profile/2" data-link="profile/2">
				    <img src="images/profilePics/default.jpg" alt="member picture">
					<p>Henry</p>
				</a>
			</div>
			<h4> Members </h4>
			<div class="group-member"><a href="index.php?request=profile/6" data-link="profile/6"><img src="images/profilePics/default.jpg" alt="member picture"><p>Becky</p></a></div><div class="group-member"><a href="index.php?request=profile/7" data-link="profile/7"><img src="images/profilePics/default.jpg" alt="member picture"><p>Billie</p></a></div>		</div>
	</div>
    
	