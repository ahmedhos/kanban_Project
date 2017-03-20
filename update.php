<?php

session_start();

include("Dbconnect.php");

$id=$_SESSION['usr_id'];

	//insert

	$sql = "UPDATE task_drap set task_status = '{$_POST["v2"]}' where
task_id = '{$_POST["v1"]}';";

	if ($con->query($sql) === TRUE) {
		$data = array("task_title" => $_POST['task_title'], "task_descp" => $_POST['task_descp']);
		echo json_encode($data);
	} else {
		echo "Error: " . $sql . "<br>" . $con->error;
	}

	$con->close();
		


