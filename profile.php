<?php
// Start the session at the very beginning
session_start();

// Define the error message variable
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the health message from HTML form
    $message = isset($_POST['health_message']) ? $_POST['health_message'] : '';

    // Establish database connection
    $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "leont1", "leont1", "leont1");
    if (!$db) {
        $error = "Connection failed: " . mysqli_connect_error();
    } else {
        // Ensures message is not empty and not too long
        if (empty($message)) {
            $error = "Please enter a message.";
        } elseif (strlen($message) > 200) {
            $error = "Your message was too long, only 200 characters allowed.";
        } else {
            // Prepare and bind
            $stmt = mysqli_prepare($db, "INSERT INTO health_messages (message_content, message_time) VALUES (?, NOW())");
            mysqli_stmt_bind_param($stmt, 's', $message);
            if (mysqli_stmt_execute($stmt)) {
                $error = "Message sent successfully";
                // Clears the input form for next message
                $_POST['health_message'] = '';
            } else {
                $error = "Database error: " . mysqli_error($db);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dining Dashboard</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php 
    $pageTitle = 'College Dining Dashboard';
    $headerTitle = 'Welcome to Your Dining Account!';
    include 'header.php'; 
    ?>

    <div class="total">
        <section class="health">
            <h3>myHealth Advisor</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="health_message" placeholder="Send a message..." value="<?php echo isset($_POST['health_message']) ? htmlspecialchars($_POST['health_message']) : ''; ?>">
                <button type="submit">Submit</button>
            </form>
            <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </section>
    </div>
    <footer>
        <p>© UMBC Dining Services</p>
    </footer>
</body>
</html>
