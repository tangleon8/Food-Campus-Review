<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://styleguide.umbc.edu/wp-content/uploads/sites/113/2019/03/UMBC-retriever-social-media.png" alt="UMBC Logo" class="logo">
            <h1 class="header-title">Customer Reviews</h1>
        </div>
    </header>

    <?php
    // Database connection
    $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "leont1", "leont1", "leont1");
    if (mysqli_connect_errno()) {
        echo "Error - could not connect to MySQL";
        exit;
    }

    // Fetch all reviews, ordered by most recent
    $reviews = [];
    $review_query = "SELECT r.name AS restaurant_name, re.reviewer_name, re.review_text, 
                     re.rating, re.review_date 
                     FROM reviews re 
                     JOIN restaurants r ON re.restaurant_id = r.id 
                     ORDER BY re.review_date DESC";
    $review_result = $db->query($review_query);
    if ($review_result->num_rows > 0) {
        while ($row = $review_result->fetch_assoc()) {
            $reviews[] = $row;
        }
    } else {
        echo "<p>No reviews found.</p>";
    }
    $db->close();
    ?>

    <!-- Main content of the screen -->
    <main class="content">
        <div class="review-container">
            <!-- Back button -->
            <a href="index.php" class="back-button">← Back to Home</a>

            <!-- Section for all customer reviews -->
            <section class="all-reviews">
                <h2>All Customer Reviews</h2>
                
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <p><strong><?= htmlspecialchars($review['reviewer_name']) ?></strong> reviewed 
                               <strong><?= htmlspecialchars($review['restaurant_name']) ?></strong> on 
                               <?= date('F j, Y, g:i a', strtotime($review['review_date'])) ?></p>
                            <blockquote>"<?= htmlspecialchars($review['review_text']) ?>"</blockquote>
                            <p>Rating: <?= str_repeat("★", (int)$review['rating']) . 
                                         str_repeat("☆", 5 - (int)$review['rating']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No recent reviews found.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <footer>
        <p>© UMBC Dining Services</p>
    </footer>
</body>
</html>
