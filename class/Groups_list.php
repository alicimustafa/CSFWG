<?php 
class Groups_list {
	/*
	This class has 2 function one to generate a 3d array
	using input from the querry the other formats and outputs
	list based on priviledge levels members officers and admin
	*/
	public $groups = array();
	private $i = 0;
	private $first_row = true;
	private $group_name = "";
    private $remove_button = '<button type="button" class="remove-but">Remove</button>';
	
	public function proces_row($row){ //this function generates the array
		$row_array['fn'] = $row['fname'];
		$row_array['ln'] = $row['lname'];
		$row_array['id'] = $row['member_id'];
		$off_array['fn'] = $row['offname'];
		$off_array['ln'] = $row['offlname'];
		$off_array['id'] = $row['offid'];
		if($this->first_row){
			$this->group_name = $row['grp_name'];
			$this->first_row = false;
			$this->groups[$this->group_name]['officer'] = $off_array;
			$this->groups[$this->group_name]['group_id'] = $row['grp_id'];
		}
		if($this->group_name == $row['grp_name']){
			if($row['member_id']){
			    $this->groups[$this->group_name][$this->i]= $row_array;
				$this->i++;
			}
		} else {
			$this->group_name = $row['grp_name'];
			$this->i = 0;
			$this->groups[$this->group_name]['officer'] = $off_array;
			$this->groups[$this->group_name]['group_id'] = $row['grp_id'];
			if($row['member_id']){
				$this->groups[$this->group_name][$this->i]= $row_array;
				$this->i++;
			}
		}
	}
	public function display($type){ // this outputs group list 
		foreach($this->groups as $grp_name=>$grp_items){ // this loops through all of the groups
			switch($type){ 
			/*
			this determins what kind of field set to display
			depends on the rank. each rank has diferent options
			*/
				case "Admin":
				    echo "<fieldset class='drop-area' data-group='",$grp_items['group_id'],"'><legend>";
					echo "<input type='text' value='$grp_name'><button type='button' class='group-name'>change name</button>";
					echo "<button type='button' class='group-remove'> Remove group</button></legend>";
				    echo "<p data-off-id='".$grp_items['officer']['id']."'>Group officer: ";
					/*
					this section generates a drop down menue for all possible 
					candidates for officer it will display only members in groups
					*/
					echo "<select data-group='$grp_name' class='officer-select' >";
					// set the current officer as first option
					echo "<option value='".$grp_items['officer']['id']."' >".$grp_items['officer']['fn']." ".$grp_items['officer']['ln']."</option>";
					for( $x= 0 ; $x < (Count($grp_items)-2) ; $x++){//loops through all members in group
						if($grp_items[$x]['id'] == $grp_items['officer']['id']){continue;}// eliminates the current officer from list so there is no repeat
						echo "<option value='".$grp_items[$x]['id']."' >".$grp_items[$x]['fn']." ".$grp_items[$x]['ln']."</option>";
					}
					echo "</select>";
					echo "</p><ul>";
					break;
			    case "Officer":
				    echo "<fieldset class='drop-area' data-group='",$grp_items['group_id'],"'><legend> $grp_name </legend>";
				    echo "<p data-off-id='".$grp_items['officer']['id']."'>Group officer: ".$grp_items['officer']['fn']." ".$grp_items['officer']['ln']."</p><ul>";
					break;
				default :
				    echo "<fieldset><legend> $grp_name </legend>";
				    echo "<p>Group officer: ".$grp_items['officer']['fn']." ".$grp_items['officer']['ln']."</p><ul>";
			}
			for( $x= 0 ; $x < (Count($grp_items)-2) ; $x++){// loops through and displays all members
				if($type == "Admin" or $type =="Officer"){ // officers and admin get additional options for the rows
					echo "<li data-id='".$grp_items[$x]['id']."' data-fn='".$grp_items[$x]['fn']."' data-ln='".$grp_items[$x]['ln']."' >".$grp_items[$x]['fn']." ".$grp_items[$x]['ln'];
					echo "<button type='button' class='remove-but' >Remove</button></li>";
				}else {
					echo "<li>".$grp_items[$x]['fn']." ".$grp_items[$x]['ln']."</li>";
				}
			}
			echo "</ul></fieldset>";
		}
	}
}
?>