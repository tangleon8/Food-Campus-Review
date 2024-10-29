<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = isset($_POST['health_message']) ? $_POST['health_message'] : '';

    $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "leont1", "leont1", "leont1");
    if (!$db) {
        $error = "Connection failed: " . mysqli_connect_error();
    } else {
        if (empty($message)) {
            $error = "Please enter a message.";
        } elseif (strlen($message) > 200) {
            $error = "Your message was too long, only 200 characters allowed.";
        } else {
            $stmt = mysqli_prepare($db, "INSERT INTO health_messages (message_content, message_time) VALUES (?, NOW())");
            mysqli_stmt_bind_param($stmt, 's', $message);
            if (mysqli_stmt_execute($stmt)) {
                $error = "Message sent successfully";
                $_POST['health_message'] = '';
            } else {
                $error = "Does not work: " . mysqli_error($db);
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="total">
        <header>
            <ul>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Options</a></li>
            </ul>
            <h1>Welcome to Your Dining Account, <span id="username"><?php echo htmlspecialchars(isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?></span>!</h1>
            <form>
                <input type="search" placeholder="Search">
                <button type="submit">Search</button>
            </form>
            <img src="burger.jpg" alt="Burger" class="food-image" width="100" height="100">
        </header>
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
