<html>
<head>
<title>insert and retrieve with ajax</title>
<style type="text/css">
body {
	background: #fafcfc;
}

#wrapper {
	width: 50%;
	height: auto;
	margin: 10px auto;
	border: 1px solid #cbcbcb;
	background: white;
}

#comment_input_form li {
	list-style-type: none;
	margin: 10px;
}

#comment_input_form {
	width: 50%;
	margin: 100px auto;
	background: #fafcfc;
	padding: 20px;
}

#name, textarea {
	width: 80%;
}

#display_area {
	width: 90%;
	margin: 10px auto;
}
</style>
</head>
<body>
	<div id="wrapper">
		<ul id="display_area"></ul>
		<form name="taskForm" id="comment_input_form">
			<ul>
				<li>Name:</li>
				<li><input type="text" name="name" id="name"></li>
				<li>Comment:</li>
				<li><textarea id="comment" name="comments"></textarea></li>
				<input type="hidden" name="display"value="1" />
				<li><input type="submit" id="submit_comment" value="POST"></li>
			</ul>			
		</form>
	</div>

	<script type="text/javascript" src="jquery-3.2.0.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#comment_input_form").on("submit", function(event){
				event.preventDefault();
				$.ajax({
					url:"http://localhost/php_project/ajax.php",
					method:"POST",
					data:$("#comment_input_form").serialize(),
					success: function(data){
						var ele = JSON.parse(data);
						$("#display_area").append("<li>" + ele.name + " : " + ele.comments + "</li>");
					}
				})
			});
		
			function displayFromDatabase(){
				$.ajax({
					url: "http://localhost/php_project/ajax.php",
					type:"GET",
					success: function(d){
						$.each(JSON.parse(d), function(i, ele){
							$("#display_area").append("<li id=" + ele.id + ">" + ele.name + " : " + ele.comments + "</li>");
						});
					}
				});	
			}

			displayFromDatabase();
		});
	</script>
</body>

</html>



<!-- add jquery -->


