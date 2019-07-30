<?php
 include '../config/database.php';

  $state_id = (int)$_POST['state_id'];
  $sql = "SELECT * FROM cities WHERE state_id=$state_id";
  $result = mysqli_query($db, $sql);

  while($row = mysqli_fetch_assoc($result)){
  	echo "<option value='" . $row['id'] . "'>" . $row['name'] ."</option>";
  }

?>
