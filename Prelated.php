<?php
// Database connection
$servername = ("localhost");
$username = ("your_username");
$password = ("your_password");
$dbname = ("your_database");

// Create connection
$conn = mysqli_connect("studentdb-maria.gl.umbc.edu","ws27852","ws27852","ws27852","ws27852");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = ($_POST['name']);
	$User_id = ($_POST['user_id']);
    $email = ($_POST['email']);
    $password = ($_POST['password']);
    $reviews = ($_POST['reviews']);

    // Validate inputs
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name can only contain letters and spaces.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
	}  elseif  (strlen($User_id) < 6) {
		$errors[] = "User_id must have atleast 6 characters.";
    } elseif (empty($reviews)) {
        $errors[] = "Reviews cannot be empty.";
    } else {
        // No validation errors, proceed to insert into database
        $statement = $conn->prepare("INSERT INTO Users (Password, Name, Reviews) VALUES (?, ?, ?)");
        $password = password_hash($password, PASSWORD_DEFAULT);
        $statement->bind_param("sss", $password, $name, $reviews);

        if ($statement->execute()) {
            $_SESSION['username'] = $name; // Store username in session
            echo "Registration successful!";
        } else {
            echo "Error: " . $statement->error;
        }
        $statement->close();
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
</head>
<body>
<!-- HTML for adding user details -->
<form action="Prelated.php" method="POST">
    <label for="name">name:</label>
    <input type="text" name="name">
	
	<label for="user_id">user_id:</label>
	<input type="user_id" name="user_id">
    
    <label for="email">email:</label>
    <input type="email" name="email">
    
    <label for="password">password:</label>
    <input type="password" name="password">
    
    <label for="reviews">reviews:</label>
    <textarea name="reviews"></textarea>
    
    <input type="submit" value="Submit">
</form>
</body>
</html>

