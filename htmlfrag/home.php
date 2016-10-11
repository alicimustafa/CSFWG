<?php 
include("class/connect.php");
$col_select = "
	SELECT log_in.log_un , log_in.log_pw, ranks.rank_name
	FROM log_in
	INNER JOIN members ON log_in.member_id = members.member_id
	INNER JOIN ranks ON members.rank_id = ranks.rank_id
	ORDER BY ranks.rank_id
	";
$stmt = $pdo->query($col_select);
echo "<h1>This the home page</h1>";
echo "<p>Fallowing is a table listing all of the test members, their password and rank</p>";
echo "<p>this is here only for testing</p><br><br>";
echo "<table><tr><th>  Log in Username  </th><th>  Log in password  </th><th>  Member Rank  </th></tr>";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	echo "<tr><td>".$row['log_un']."</td><td>".$row['log_pw']."</td><td>".$row['rank_name']."</td></tr>";
}
echo "</table>";
?> 
