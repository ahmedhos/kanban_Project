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
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet"
	href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet"
	href="https://jqueryui.com/resources/demos/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
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
                    $("#toDo").append('<li id="'+ele.task_id +'" class="card">' + ele.task_title +
                             '<button id="delete_button" class="btn btn-danger" > x </button>'+
                             '<button id="update_button" class="update btn btn-success" data-toggle="modal" data-target="#myModal"> <i class="fa fa-pencil" aria-hidden="true"></i></button>'+'</li>');
					console.log('ele= ',ele.task_title);
				}
			})
		});
		
		function displayFromDatabase(){
// 			$("#inProgress").empty();
// 			$("#toDo").empty();
// 			$("#test").empty();
// 			$("#done").empty();
			$.ajax({
				url: "http://localhost/kanbanProject/display.php",
				type:"GET",
				success: function(d){
					$.each(JSON.parse(d), function(i, ele){
						var btn = '<button id="delete_button" class="btn btn-danger" data-id="' + ele.task_id + '"> x </button>'
						+'<button class="btn btn-success updat_utton" data-toggle="modal" data-target="#myModal" ><i class="fa fa-pencil" aria-hidden="true"></i></button>';
						switch(ele.task_status) {
							case "todo":
								$("#toDo").append('<li id="'+ele.task_id +'" class="card">' +"<span>" +  ele.task_id + ") " + ele.task_title + " : " + ele.task_descp +"</span>"+ "<br>"+ btn  +'</li>');
								break;
							case "inprogress":
								$("#inProgress").append('<li id="'+ele.task_id +'" class="card">' + "<span>" +  ele.task_id + ") " + ele.task_title + " : " + ele.task_descp + "</span>"+"<br>"+ btn +'</li>');
								break;
							case "test":
								$("#test").append('<li id="'+ele.task_id +'" class="card">' +"<span>" +  ele.task_id + ") " + ele.task_title + " : " + ele.task_descp + "</span>"+"<br>"+ btn +'</li>');
								break;
							case "done":
								$("#done").append('<li id="'+ele.task_id +'" class="card">' + "<span>" + ele.task_id +") " + ele.task_title + " : " + ele.task_descp + "</span>" +"<br>"+ btn +'</li>');
								break;
						}
					});
					delete_row();
					update_task();
				}
			});	
		}

		displayFromDatabase();

		//==============  Delete   =================
		function delete_row() {
			$(".container").find(".btn").on("click", function(){
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

			$("#comment_input_form2").find("#submit_comment2").on("click", function(){
				var title = $("#task_title2").val();
		        var desp = $("#task_descp2").val();
				var id=$("#myModal").find(".put_id").attr("id");
				console.log(desp);
		        console.log(id);
		        console.log(title);
			        $.ajax({
						url:"http://localhost/kanbanProject/UpdateTask.php",
						method:"POST",
						data:{
							   v1:title,
							   v2:desp,
							   v3:id
							   
							  },
						success: function(data){
							$("#"+id).find("span").text("(" + id + ")" + title + ":" + desp);
							$('#myModal').modal('hide');
							
		                    
						}
					});
			});
			
			$(".updat_utton").on("click", function(event){
				var id=$(this).parent("li").attr("id");
				$("#myModal").find(".put_id").attr("id", id);
				
			});

			
			
		}


	//===============================

		//==============  Update Task_content   =================
		function update_task() {
			
		}
		
			
		
// 		$("#comment_input_form2").on("submit", function(event){
// 			event.preventDefault();
// 			$.ajax({
// 				url:"http://localhost/kanbanProject/display.php",
// 				method:"POST",
// 				data:$("#comment_input_form2").serialize(),
// 				success: function(data){
// 					console.log('data1= ',data);
// 					var ele = JSON.parse(data);
//                     $("#toDo").append('<li class="card">' + ele.task_title +
//                              '<button id="delete_button" class="btn btn-danger" > x </button>'+
//                              '<button id="update_button" class="update btn btn-success" data-toggle="modal" data-target="#myModal"> <i class="fa fa-pencil" aria-hidden="true"></i></button>'+'</li>');
// 					console.log('ele= ',ele.task_title);
// 				}
// 			})
// 		});
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

		</ul>

	</div>
	<div class="container" style="background-color: yellow;">
		<h2>Verification</h2>
		<ul class="sortable connectedSortable" id="test">

		</ul>
	</div>
	<div class="container" style="background-color: green;">
		<h2>Done</h2>
		<ul class="sortable connectedSortable" id="done">
		</ul>
	</div>
</div>

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    	
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <form class="link-div" name="taskForm2" id="comment_input_form2">
			<input type="hidden" class="put_id" id="task_id">
			<label>Task Title:</label><br/>
			<input type="text" name="task_title2" id="task_title2" /></br>
			<label>Task Description:</label></br>
			<textarea id="task_descp2" name="task_descp2"></textarea></br>
			<input type="hidden" name="display" value="1" /></br>
			<button type="button" name="btnAddNew2" id="submit_comment2" class="btn btn-success add-button" > Save </button></br>

		</form>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>