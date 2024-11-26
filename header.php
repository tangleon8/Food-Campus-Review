<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic title based on the $pageTitle variable with defaults to 'UMBC Dining' if not set -->
    <title><?php echo isset($pageTitle) ? $pageTitle : 'UMBC Dining'; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header section containing the UMBC logo and a title -->
    <header class="header-container">
        <img src="https://styleguide.umbc.edu/wp-content/uploads/sites/113/2019/03/UMBC-retriever-social-media.png" alt="UMBC Logo" class="logo">
        <!-- Dynamic header title based on the $headerTitle variable with defaults to 'UMBC Dining Services' if not set -->
        <h1 class="header-title"><?php echo isset($headerTitle) ? $headerTitle : 'UMBC Dining Services'; ?></h1>
    </header>

     <!-- Navigation section -->
    <nav>
        <a href="index.php" class="back-button">← Back to Home</a>
    </nav>
