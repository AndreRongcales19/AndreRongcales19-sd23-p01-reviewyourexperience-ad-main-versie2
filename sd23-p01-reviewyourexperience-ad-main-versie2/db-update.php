<?php
// Database update script for adding rating column
try {
    include 'sd23-p01-reviewyourexperience-ad-main-versie2/dbconnect.php';
    
    echo "<h2>Database Update Script</h2>";
    
    // Check if rating column already exists
    $checkColumn = $db->prepare("SHOW COLUMNS FROM review LIKE 'rating'");
    $checkColumn->execute();
    $columnExists = $checkColumn->rowCount() > 0;
    
    if ($columnExists) {
        echo "<p style='color: green;'>✅ Rating column already exists!</p>";
    } else {
        // Add rating column
        echo "<p>Adding rating column...</p>";
        $addColumn = $db->prepare("ALTER TABLE review ADD COLUMN rating INT(1) NOT NULL DEFAULT 5 AFTER content");
        $addColumn->execute();
        echo "<p style='color: green;'>✅ Rating column added successfully!</p>";
        
        // Update existing reviews
        echo "<p>Updating existing reviews...</p>";
        $updateReviews = $db->prepare("UPDATE review SET rating = 5 WHERE rating IS NULL");
        $updateReviews->execute();
        echo "<p style='color: green;'>✅ Existing reviews updated with default rating!</p>";
        
        // Add constraint
        try {
            $addConstraint = $db->prepare("ALTER TABLE review ADD CONSTRAINT check_rating CHECK (rating >= 1 AND rating <= 5)");
            $addConstraint->execute();
            echo "<p style='color: green;'>✅ Rating constraint added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠️ Constraint may already exist or not be supported by your MySQL version.</p>";
        }
    }
    
    // Verify the update
    $verify = $db->prepare("SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating FROM review");
    $verify->execute();
    $result = $verify->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Database Status:</h3>";
    echo "<p>Total reviews: " . $result['total_reviews'] . "</p>";
    echo "<p>Average rating: " . round($result['avg_rating'], 1) . "</p>";
    
    echo "<p style='color: green; font-weight: bold;'>🎉 Database update completed successfully!</p>";
    echo "<p><a href='sd23-p01-reviewyourexperience-ad-main-versie2/index.php'>← Back to Homepage</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?> 