<?php
// Start the session at the very beginning
session_start();

// Define the error message variable
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the health message and inquiry type from the HTML form
    $message = isset($_POST['health_message']) ? $_POST['health_message'] : '';
    $inquiry_type = isset($_POST['inquiry_type']) ? $_POST['inquiry_type'] : '';

    // Establish database connection
    $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "leont1", "leont1", "leont1");
    if (!$db) {
        $error = "Connection failed: " . mysqli_connect_error();
    } else {
        // Validate inputs
        if (empty($message) || empty($inquiry_type)) {
            $error = "Please enter both a message and select an inquiry type.";
        } elseif (strlen($message) > 200) {
            $error = "Your message was too long, only 200 characters allowed.";
        } else {
            // Prepare and bind
            $stmt = mysqli_prepare($db, "INSERT INTO health_messages (message_content, inquiry_type, message_time) VALUES (?, ?, NOW())");
            mysqli_stmt_bind_param($stmt, 'ss', $message, $inquiry_type);
            if (mysqli_stmt_execute($stmt)) {
                $error = "Message and inquiry type sent successfully.";
                // Clears the input form for next message
                $_POST['health_message'] = '';
                $_POST['inquiry_type'] = '';
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
    <script>
        function fetchInquirySuggestions(query) {
            if (query.length === 0) {
                document.getElementById("suggestions").innerHTML = "";
                return;
            }
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("suggestions").innerHTML = this.responseText;
                }
            };
            xhr.open("GET", "fetch-inquiries.php?q=" + query, true);
            xhr.send();
        }

        function selectSuggestion(value) {
            document.getElementById("inquiry_type").value = value;
            document.getElementById("suggestions").innerHTML = "";
        }
    </script>
    <style>
        #suggestions {
            border: 1px solid #ccc;
            max-width: 300px;
            background-color: white;
            position: absolute;
            z-index: 1000;
        }
        #suggestions div {
            padding: 8px;
            cursor: pointer;
        }
        #suggestions div:hover {
            background-color: #f0f0f0;
        }
    </style>
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
                <!-- Inquiry Type Textbox -->
                <input type="text" id="inquiry_type" name="inquiry_type" placeholder="Inquiry Type"
                    onkeyup="fetchInquirySuggestions(this.value)" value="<?php echo isset($_POST['inquiry_type']) ? htmlspecialchars($_POST['inquiry_type']) : ''; ?>">
                <div id="suggestions"></div>

                <!-- Health Message Textbox -->
                <input type="text" name="health_message" placeholder="Send a message..." 
                    value="<?php echo isset($_POST['health_message']) ? htmlspecialchars($_POST['health_message']) : ''; ?>">
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
