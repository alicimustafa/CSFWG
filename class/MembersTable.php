<?php 
class MembersTable {
	/*this class will hold and generate the rows
	for the members table it will generate 3 tables
	one for members one for officers and one for admin
	  */
	private $member_rows = "";
	private $officer_rows = "";
	private $admin_rows = "";
	private $rank_types = array("1"=>"Admin","2"=>"Officer","3"=>"Member","4"=>"Alumni","5"=>"Inactive");
	private $drag_status = 'draggable="true" class="drag"';
	public function proces_row($row){
        $payed = $row['year'] ? "yes" : "no";
		$this->member_rows .= "<tr><td><a href='/profile/".$row['id']."' class='nav-link' data-link='profile/".$row['id']."'> ".$row['name']." ".$row['lname']."</a></td><td>".$row['rank']."</td><td>$payed</td></tr>";
		$this->officer_rows .= "<tr><td ".$this->drag_status." data-member='".$row['id']."' >".$row['name']." ".$row['lname']."</td><td>".$row['rank']."</td><td>$payed</td></tr>";
		$this->admin_rows .= "<tr><td ".$this->drag_status." data-member='".$row['id']."' >".$row['name']." ".$row['lname']."</td><td>";
		$this->admin_rows .= "<select data-member='".$row['id']."'class='rank-select'>"."<option value='".$row['rank']."'>".$row['rank']."</option>";
		foreach($this->rank_types as $key=>$value){
			if($value == $row['rank']){ continue;}
			$this->admin_rows .= "<option value='$key'>$value</option>";
		}
        $this->admin_rows .= "</td><td>$payed</td></tr>";
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