<?php
session_start();

include("Dbconnect.php");

// $row_no = $_POST["v3"];

$sql = "update task_drap 
set task_title= '{$_POST["v1"]}',
task_descp= '{$_POST["v2"]}'
where task_id = '{$_POST["v3"]}'";

if ($con->query($sql) === TRUE) {
	echo "success";
} else {
	echo "Error deleting record: " . $con->error;
}

$con->close();
