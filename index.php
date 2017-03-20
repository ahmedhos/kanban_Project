<?php
session_start ();
include_once 'Dbconnect.php';

if (! isset ( $_SESSION ['usr_id'] )) {
	header ( "Location: Login.php" );
	
}
?>
<!doctype html>
<html lang="en">
<head>
<link rel="stylesheet"
	href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet"
	href="https://jqueryui.com/resources/demos/style.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
	type="text/css" />
<style>
body {
	font-family: Arial;
}

h2 {
	margin: 5px;
}

input[type=text] {
	margin: 10px
}

input[type=button] {
	margin: 10px
}

.container {
	width: 20%;
	float: left;
	clear: right;
	margin: 10px;
	border-radius: 5px;
}

.sortable {
	list-style-type: none;
	margin: 0;
	padding: 2px;
	min-height: 30px;
	border-radius: 5px;
}

.sortable li {
	margin: 3px 3px 3px 3px;
	padding: 0.4em;
	padding-left: 1.5em;
	font-size: 1.4em; /*height: 18px;*/
}

.sortable li span {
	position: absolute;
	margin-left: -1.3em;
}

.card {
	background-color: white;
	border-radius: 3px;
}
</style>
<script>



  
//   $(function() {
    
  

//     $('.add-button').on('click', function() {
//         var txtNewItem = $('#name').val();
//         $(this).closest('div.container').find('ul').append('<li class="card">'+txtNewItem+'<button color="red"> x </button>'+'</li>');
//     });

  $(document).ready(function(){
	  $( ".sortable" ).sortable({
	      connectWith: ".connectedSortable",
	      receive: function( event, ui ) {
		    var iD = $(ui.item).find("button").data().id;
	        var status = $(event.target).prop("id");
// 	        console.log($(ui.item).prop("id"));
	        $(this).css({"background-color":"blue"});
	        $.ajax({
				url:"http://localhost/kanbanProject/update.php",
				method:"POST",
				data:{
					   v1:iD,
					   v2:status,
					  },
				success: function(data){
					console.log('data1= ',data);
                    
				}
			})


	        
	     // POST to server using $.post or $.ajax
// 	        $.ajax({
// 	            data: sort,
// 	            type: 'POST',
// 	            url: 'http://localhost/kanbanProject/update.php'
// 	        });
	      }
	    }).disableSelection();

	  
		$("#comment_input_form").on("submit", function(event){
			event.preventDefault();
			$.ajax({
				url:"http://localhost/kanbanProject/ajax.php",
				method:"POST",
				data:$("#comment_input_form").serialize(),
				success: function(data){
					console.log('data1= ',data);
					var ele = JSON.parse(data);
                    $("#toDo").append('<li class="card">' + ele.task_title + '<button id="delete_button" class="btn btn-danger" > x </button>'+'</li>');
					console.log('ele= ',ele.task_title);
				}
			})
		});
		
		function displayFromDatabase(){
			$.ajax({
				url: "http://localhost/kanbanProject/ajax.php",
				type:"GET",
				success: function(d){
					$.each(JSON.parse(d), function(i, ele){
						var btn = '<button id="delete_button" class="btn btn-danger" data-id="' + ele.task_id + '"> x </button>';
						switch(ele.task_status) {
							case "todo":
								$("#toDo").append('<li class="card">' + ele.task_id + ">" + ele.task_title + " : " + ele.task_descp + btn +'</li>');
								break;
							case "inprogress":
								$("#inProgress").append('<li class="card">' + ele.task_id + ">" + ele.task_title + " : " + ele.task_descp + btn +'</li>');
								break;
							case "test":
								$("#test").append('<li class="card">' + ele.task_id + ">" + ele.task_title + " : " + ele.task_descp + btn +'</li>');
								break;
							case "done":
								$("#done").append('<li class="card">' + ele.task_id + ">" + ele.task_title + " : " + ele.task_descp + btn +'</li>');
								break;
						}
					});
					delete_row();
				}
			});	
		}

		displayFromDatabase();

		//===============================
		function delete_row() {
			$("#toDo").find("button").on("click", function(){
				var ele = $(this);
					id = ele.data().id;
				$.ajax({
					  type:'POST',
					  url:"http://localhost/kanbanProject/deleteTask.php",
					  data:{
					   delete_row:'delete_row',
					   row_id:id,
					  },
					  success:function(response) {
					   if(response=="success"){
						ele.parent().remove();
					   }
					  }
					 });
			});
		}


	//===============================
		


		
  });
 
  
  </script>
</head>
<body>

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#navbar1">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Koding Made Simple</a>
			</div>
			<div class="collapse navbar-collapse" id="navbar1">
				<ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['usr_id'])) { ?>
                <li><p class="navbar-text">Signed in as <?php echo $_SESSION['usr_name']; ?></p></li>
					<li><a href="logout.php">Log Out</a></li>
                <?php } else { ?>
                <li><a href="Login.php">Login</a></li>
					<li><a href="Register.php">Sign Up</a></li>
                <?php } ?>
            </ul>
			</div>
		</div>
	</nav>
</body>
<div>
	<div class="container" style="background-color: pink;">
		<h2>TODO</h2>

		<ul class="sortable connectedSortable" id="toDo">
<!-- 			<li class="card">Activity A1 -->
<!-- 				<button color="red" class="btn btn-danger remove-item">x</button> -->
<!-- 			</li> -->
<!-- 			<li class="card">Activity A2 -->
<!-- 				<button color="red" class="btn btn-danger remove-item">x</button> -->
<!-- 			</li> -->
<!-- 			<li class="card">Activity A3 -->
<!-- 				<button color="red" class="btn btn-danger remove-item">x</button> -->
<!-- 			</li> -->
		</ul>
		<form class="link-div" name="taskForm" id="comment_input_form">

			<label>Task Title:</label> <input type="text" name="task_title"
				id="task_title" /> <label>Task Description:</label>
			<textarea id="task_descp" name="task_descp"></textarea>
			<input type="hidden" name="display" value="1" /> <input type="submit"
				name="btnAddNew" id="submit_comment" value="Add" class="add-button" />

		</form>
	</div>
	<div class="container" style="background-color: orange;">
		<h2>In Progress</h2>

		<ul class="sortable connectedSortable" id="inProgress">

<!-- 			<li class="card">Activity B1 -->
<!-- 				<button color="red">x</button> -->
<!-- 			</li> -->
<!-- 			<li class="card">Activity B2 -->
<!-- 				<button color="red">x</button> -->
<!-- 			</li> -->
		</ul>

	</div>
	<div class="container" style="background-color: yellow;">
		<h2>Verification</h2>
		<ul class="sortable connectedSortable" id="test">
<!-- 			<li class="card">Activity C1 -->
<!-- 				<button color="red">x</button> -->
<!-- 			</li> -->
<!-- 			<li class="card">Activity C2 -->
<!-- 				<button color="red">x</button> -->
<!-- 			</li> -->
		</ul>
	</div>
	<div class="container" style="background-color: green;">
		<h2>Done</h2>
		<ul class="sortable connectedSortable" id="done">
		</ul>
	</div>
</div>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>