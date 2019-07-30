<?php
include '../config/database.php';

$country_id = $_POST["country_id"];
$sql = "SELECT * FROM states WHERE country_id=$country_id";
$result = mysqli_query($db, $sql);
while($row = mysqli_fetch_assoc($result)){
	echo "<option value='" . $row['id'] . "'>" . $row['name'] ."</option>";
}

?>
