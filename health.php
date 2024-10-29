<?php

	#using Leon's mySQL for all of our tables

$db = mysqli_connect("studentdb-maria.gl.umbc.edu", "leont1", "leont1", "leont1");

	#Check connection
if (mysqli_connect_errno()){
		exit("Error: could not connect to the database");
}

	#Initialize error message
$error = "";

	#Check if form is submitted
if ($_POST) {
    #Get the health message from html form
    $message = $_POST['health_message'];
    
    #ensures message is not empty
    if (empty($message)) {
        $error = "Please enter a message";
    }
	#ensures message is not more than 200 characters, since it message column varchar(200) in database
    elseif (strlen($message) > 200) {
        $error = "Your message was too long, only 200 characters allowed";
    }
    else {
    #need to add more here and get user ID from login validation
        $user_id = 1;
        
    #cleaning message, prevents any injection 
        $clean_message = mysqli_real_escape_string($db, $message);
		
		$message_ID = 1;
        
    #inserts data from html form into mysql
        $sql = "INSERT INTO healthadvisor (message_ID, message_content, user_ID, message_time) 
                VALUES ($message_ID, '$clean_message', $user_id, NOW())";
        
    #feedback to user if message information was correctly inserted
        if (mysqli_query($conn, $sql)) {
            echo "Message sent successfully";
    
	#clears the html form so it is empty for next message
            $message = "";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

