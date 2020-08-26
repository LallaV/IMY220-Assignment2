<?php
	// See all errors and warnings
//var_dump($r);

	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
	$user = "SELECT user_id FROM tbusers WHERE email='" . $email . "' AND password='" . $pass . "'";
	$result = mysqli_query($mysqli,$user);
	$res = $mysqli->query($user);
	$row = mysqli_fetch_array($res);
	
     $_SESSION['user_id'] = $row['user_id'];
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
    
    if(isset($_POST['submit'])){

	  $name = $_FILES['picToUpload']['name'];
	  $target_dir = "gallery/";
	  $target_file = $target_dir . basename($_FILES["picToUpload"]["name"]);

	  // Select file type
	  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	  
	 

	  $uploadFile = $_FILES["picToUpload"];
			

	  // Valid file extensions
	  $extensions_arr = array("jpg","jpeg");

	  // Check extension
	  if((in_array($imageFileType,$extensions_arr))){
	  		if($uploadFile["size"]<1000000 ){

	  		if($uploadFile["error"] > 0)
	  		{
				echo "Error: " . $uploadFile["error"] . "<br/>";
			} 

			else 
			{
				$getQ = "SELECT * FROM tbgallery";
		    	$r = $mysqli->query($getQ);
		    	//$rowz = mysqli_fetch_array($r);
		    	$exist=false;
		    	if($r->num_rows > 0 || $r->num_rows == 0  ){

		    	
		    	while($rowz = $r->fetch_assoc()){
		    		//echo $name;
		    		//echo $rowz['filename'];
		    		if($name == $rowz['filename'] ){
		    		if($_SESSION['user_id'] == $rowz['user_id']){
						
		    			
		    				$exist=true;
		    				//echo $exist;
		    				//return true;
		    			}
		    		}

		    		
		    	}
		    		

		    			if(!$exist){

		    				$query = "INSERT INTO tbgallery (user_id,filename) VALUES ('". $_SESSION['user_id']."','".$name."')";
				    		mysqli_query($mysqli,$query);
							if(file_exists($target_dir.$name)) unlink($target_dir.$name);
				    		move_uploaded_file($_FILES['picToUpload']['tmp_name'],$target_dir.$name);
						}

						else if($exist){
							echo '<div class="alert alert-danger mt-3" role="alert">file already exists</div>';

						}
		}}

			
		}}

			else 
			{
				echo '<div class="alert alert-danger mt-3" role="alert">Invalid file</div>';
			}
	 
	  }

?>



<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Vibaksha Lalla">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				?>
						<form action="login.php" method="POST" enctype="multipart/form-data" >
							<div class='form-group'>
									<input type="hidden"  name="loginEmail" value="<?php echo $email; ?>">
									<input type="hidden"  name="loginPass" value="<?php echo $pass; ?>">
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>

						</form>
				<?php
				
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
	
	

	<div class="container"><h4> Image Gallery</h4>
	  	<div class="row imageGallery">
	  		
	  			<?php

					$sql1 = "SELECT filename FROM tbgallery WHERE user_id = '" .$_SESSION['user_id']. "'";
					$result1 = $mysqli->query($sql1);
					if ($result1->num_rows > 0) {

					// output data of each row
					while($row = $result1->fetch_assoc()) {
					$path=$row["filename"];

					?>

					<div class="col-3" style="background-image: url(gallery/<?php echo $path;?>)"></div>
					<?php

					}
					

					}

				?>

	  			
    		
  		</div>
  	</div>
	
</body>
</html>