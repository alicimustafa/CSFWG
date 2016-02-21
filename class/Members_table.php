<?php 
class Members_table {
	/*this class will hold and generate the rows
	for the members table it will generate 3 tables
	one for members one for officers and one for admin
	  */
	public $member_rows = "";
	public $officer_rows = "";
	public $admin_rows = "";
	private $rank_types = array("Admin","Alumni","Member","Officer","Inactive");
	private $drag_status = 'draggable="true" class="drag"';
	public function proces_row($row){
		$this->member_rows .= "<tr><td>".$row['name']." ".$row['lname']."</td><td>".$row['rank']."</td></tr>";
		$this->officer_rows .= "<tr><td ".$this->drag_status." data-member='".$row['id']."' >".$row['name']." ".$row['lname']."</td><td>".$row['rank']."</td></tr>";
		$this->admin_rows .= "<tr><td ".$this->drag_status." data-member='".$row['id']."' >".$row['name']." ".$row['lname']."</td><td>";
		$this->admin_rows .= "<select data-member='".$row['id']."'class='rank-select'>"."<option value='".$row['rank']."'>".$row['rank']."</option>";
		foreach($this->rank_types as $value){
			if($value == $row['rank']){ continue;}
			$this->admin_rows .= "<option value='$value'>$value</option>";
		}
        $this->admin_rows .= "</td></tr>";
	}
	public function display($type){
		switch($type){
			case "Admin": 
			    echo $this->admin_rows;
				break;
			case "Officer":
			    echo $this->officer_rows;
				break;
		    default :
			    echo $this->member_rows;
		}
	}
}
?>