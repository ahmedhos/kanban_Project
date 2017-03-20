<?php

session_start();

include("Dbconnect.php");
//include ("Login.php");

//save data to databa
/*
if (isset($_POST['name'])){
	$name = $_POST['name'];
	$q="select id from users where username= '$name'";
	
}
*/

$id=$_SESSION['usr_id'];

if(isset($_POST['display'])){
	//insert
	
	$sql = "INSERT INTO task_drap (task_title , task_descp, user_ID) VALUES
	('{$_POST["task_title"]}','{$_POST["task_descp"]}','".$id."'  )";
	
	if ($con->query($sql) === TRUE) {
		$data = array("task_title" => $_POST['task_title'], "task_descp" => $_POST['task_descp']);
		echo json_encode($data);
	} else {
		echo "Error: " . $sql . "<br>" . $con->error;
	}
	
	$con->close();
			
	
}

//display from database
if(isset($_GET) && !isset($_POST['display'])){
	$sql="SELECT * FROM task_drap";
	$result=mysqli_query($con,$sql);
	
	// Fetch all
	$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
	echo json_encode($data);
	
	mysqli_close($con);
}