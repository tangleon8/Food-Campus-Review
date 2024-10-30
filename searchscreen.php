<?php
session_start(); //Begins the session

$db = mysqli_connect("studentdb-maria.gl.umbc.edu", "leont1", "leont1", "leont1");// Connect to Leon's database

$rating_filter = isset($_POST['rating']) ? (int)$_POST['rating'] : 0; //Filters form submission for rating
$price_filter = isset($_POST['price']) ? (int)$_POST['price'] : 0; //Form submission for price

//Populates the SQL query for gathering restaurant data with filtering
$query = " 
    SELECT r.id, r.name, r.price_level, IFNULL(avg_reviews.avg_rating, 0) AS avg_rating
    FROM restaurants r
    LEFT JOIN (
        SELECT restaurant_id, AVG(rating) AS avg_rating
        FROM reviews
        GROUP BY restaurant_id
    ) AS avg_reviews ON r.id = avg_reviews.restaurant_id
    WHERE 1 = 1
";

// Adds conditions to the SQL query based on the user input
if ($rating_filter > 0) {
    $query .= " AND IFNULL(avg_reviews.avg_rating, 0) >= $rating_filter";
}
if ($price_filter > 0) {
    $query .= " AND r.price_level <= $price_filter";
}

$query .= " GROUP BY r.id";  //  one row per restaurant

$result = mysqli_query($db, $query);

//Array to hold restaurant data
$restaurants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $restaurants[] = $row;
}

mysqli_close($db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMBC Dining</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://styleguide.umbc.edu/wp-content/uploads/sites/113/2019/03/UMBC-retriever-social-media.png" alt="UMBC Logo" class="logo">
            <h1 class="header-title">UMBC Dining Experience</h1>
        </div>
    </header>

    <main class="content">
        <!-- Filter Form -->
        <div class="filterbar">
            <form method="POST" action="">
                <div class="filter-group">
                    <label for="rating">Average Rating of UMBC Restaurants</label>
                    <select id="rating" name="rating">
                        <!-- Options for specific ratings with PHP to retain the selected value -->
                        <option value="0">All Ratings</option>
                        <option value="5" <?= isset($_POST['rating']) && $_POST['rating'] == '5' ? 'selected' : '' ?>>5 Stars</option>
                        <option value="4" <?= isset($_POST['rating']) && $_POST['rating'] == '4' ? 'selected' : '' ?>>4 Stars</option>
                        <option value="3" <?= isset($_POST['rating']) && $_POST['rating'] == '3' ? 'selected' : '' ?>>3 Stars</option>
                        <option value="2" <?= isset($_POST['rating']) && $_POST['rating'] == '2' ? 'selected' : '' ?>>2 Stars</option>
                        <option value="1" <?= isset($_POST['rating']) && $_POST['rating'] == '1' ? 'selected' : '' ?>>1 Star</option>
                    </select>
                </div>

                <!-- Filter group for choosing price leve -->
                <div class="filter-group">
                    <label for="price">Price of UMBC Restaurants</label>

                    <!--Dropdowjn for selecting price level -->
                    <select id="price" name="price">

                        <!-- Options for the specific price levels with PHP to retain the selected price -->
                        <option value="0">All Prices</option>
                        <option value="5" <?= isset($_POST['price']) && $_POST['price'] == '5' ? 'selected' : '' ?>>$</option>
                        <option value="10" <?= isset($_POST['price']) && $_POST['price'] == '10' ? 'selected' : '' ?>>$$</option>
                        <option value="15" <?= isset($_POST['price']) && $_POST['price'] == '15' ? 'selected' : '' ?>>$$$</option>
                    </select>
                </div>

                <button type="submit" class="filter-button">Filter Results</button>
            </form>
        </div>

        <!-- Restaurant List -->
        <div class="restaurant-list-container">
            <h2>UMBC Restaurants</h2>
            <ul class="restaurant-list">

                <!-- PHP conditional to show message if no restaurants match the filters -->
                <?php if (empty($restaurants)): ?>
                    <li>No restaurants found with the selected filters.</li>
                <?php else: ?>

                    <!-- The foreach loops through the restaurant to display the details -->
                    <?php foreach ($restaurants as $restaurant): ?>
                    <li>
                        <?= htmlspecialchars($restaurant['name']) ?> 
                        (<?= round($restaurant['avg_rating'], 1) ?> Stars) 
                        - <?= str_repeat('$', (int)($restaurant['price_level'] / 5)) ?>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </main>

    <footer>
        <p>© UMBC Dining Services</p>
    </footer>
</body>
</html>
