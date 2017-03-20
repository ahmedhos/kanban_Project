<?php
include("Dbconnect.php");

$row_no = $_POST['row_id'];

$sql = "delete from task_drap where task_id = '$row_no'";

if ($con->query($sql) === TRUE) {
	echo "success";
} else {
	echo "Error deleting record: " . $con->error;
}

$con->close();