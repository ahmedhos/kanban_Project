<?php 


session_start();

include("Dbconnect.php");

//display from database
if(isset($_GET) && !isset($_POST['display'])){
	$sql="SELECT * FROM task_drap";
	$result=mysqli_query($con,$sql);

	// Fetch all
	$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
	echo json_encode($data);

	mysqli_close($con);
}


?>