
<?php
  #Connect to the database
  $db = mysqli_connect(
  "studentdb-maria.gl.umbc.edu",
  "ws27852", "ws27852", "ws27852");
  #check that you can connect database
  if (mysqli_connect_errno())
	  exit("Error - could not connect to MySQL");
  
  
  
  #Get my html values and put them in php variables
  $User_id= $_POST['User_id'];
  $Search_time= $_POST['Search_time'];
  $Search_content= $_POST['Search_content'];
  
  #Construct Query
  $constructed_query= "INSERT INTO Search (User_id, Search_time, Search_content) VALUES ('$User_id', '$Search_time', '$Search_content')";
  
  #Execute my Query
  $result= mysqli_query($db, $constructed_query);
  
  ?>
  
  <!DOCTYPE html>
  <html>
<head>
    <title>Search</title>
</head>
<body>
<form action="Search.php" method="POST">
	
	<label for="user_id">user_id:</label>
	<input type="user_id" name="user_id">
    
    <label for="Search_time">Search_time:</label>
    <input type="Search_time" name="Search_time">
    
    <label for="Search_content">Search_content:</label>
    <input type="Search_content" name="Search_content">
    
    <input type="submit" value="Submit">
</form>
  <P>Your values have been inserted into database.  </p>
  </body>
  
  </html>