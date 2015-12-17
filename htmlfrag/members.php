<?php
//if($read_url->valid_user){
    include("class/connect.php");
    $col_select = "
    SELECT 
    members_tbl.member_first_nm AS name,
    members_tbl.member_rank AS rank,
    group_member_list_tbl.group_name AS grp_name
    FROM members_tbl
    LEFT JOIN group_member_list_tbl on members_tbl.member_id = group_member_list_tbl.member_id
    ";
    $stmt = $pdo->query($col_select);
    ?>
    <table>
        <thead>
          <tr>
            <th>Name</th>  
            <th>Rank</th>
            <th>Group</th>
          </tr>
        </thead>
        <tbody>
    <?php
    while($row= $stmt->fetch()){
        echo  "<tr><td>",$row['name'],"</td><td>",$row['rank'],"</td><td>",$row['grp_name'],"</td></tr>";
    }
    ?>
        </tbody>
    </table>
<?php
/*
} else {
    echo "<h1>You need to be loged on to see this page</h1>
          <h1>Click <a href = 'index.php?request=home'>here</a> to goto home page";
} */

