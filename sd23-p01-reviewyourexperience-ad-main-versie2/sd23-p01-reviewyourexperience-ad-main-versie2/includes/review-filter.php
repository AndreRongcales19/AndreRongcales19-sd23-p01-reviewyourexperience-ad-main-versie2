<?php
/**
 * Review Filter Functions
 * This file contains reusable functions for filtering reviews by date and rating
 */

/**
 * Get filtered reviews based on filter parameters
 * @param PDO $db Database connection
 * @param int $bike_id The bike ID to filter reviews for
 * @param string $filter_type The type of filter to apply
 * @param string $start_date Start date for date range filter
 * @param string $end_date End date for date range filter
 * @param int $min_rating Minimum rating filter (1-5)
 * @param string $reviewer_name Name filter for reviewer
 * @return array Array of filtered reviews
 */
function getFilteredReviews($db, $bike_id, $filter_type = 'all', $start_date = '', $end_date = '', $min_rating = 0, $reviewer_name = '') {
    // Build the SQL query based on filter
    $sql = "SELECT name, content, rating, created_at FROM review WHERE bike_id = :bike_id";
    $params = [':bike_id' => $bike_id];

    if ($filter_type === 'date-range' && ($start_date || $end_date)) {
        if ($start_date && $end_date) {
            $sql .= " AND DATE(created_at) BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $start_date;
            $params[':end_date'] = $end_date;
        } elseif ($start_date) {
            $sql .= " AND DATE(created_at) >= :start_date";
            $params[':start_date'] = $start_date;
        } elseif ($end_date) {
            $sql .= " AND DATE(created_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }
    } elseif ($filter_type === 'last-week') {
        $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    } elseif ($filter_type === 'last-month') {
        $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    } elseif ($filter_type === 'last-year') {
        $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
    }

    // Add rating filter
    if ($min_rating > 0) {
        $sql .= " AND rating = :min_rating";
        $params[':min_rating'] = $min_rating;
    }

    // Add name filter
    if (!empty($reviewer_name)) {
        $sql .= " AND name LIKE :reviewer_name";
        $params[':reviewer_name'] = '%' . $reviewer_name . '%';
    }

    $sql .= " ORDER BY created_at DESC";

    // Fetch reviews with filter
    $reviews = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $reviews->bindValue($key, $value);
    }
    $reviews->execute();
    return $reviews->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get total review count for a specific bike
 * @param PDO $db Database connection
 * @param int $bike_id The bike ID
 * @return int Total number of reviews
 */
function getTotalReviewCount($db, $bike_id) {
    $total_reviews = $db->prepare("SELECT COUNT(*) as total FROM review WHERE bike_id = :bike_id");
    $total_reviews->bindParam(':bike_id', $bike_id);
    $total_reviews->execute();
    return $total_reviews->fetch(PDO::FETCH_ASSOC)['total'];
}

/**
 * Get average rating for a specific bike
 * @param PDO $db Database connection
 * @param int $bike_id The bike ID
 * @return float Average rating
 */
function getAverageRating($db, $bike_id) {
    $avg_rating = $db->prepare("SELECT AVG(rating) as average FROM review WHERE bike_id = :bike_id");
    $avg_rating->bindParam(':bike_id', $bike_id);
    $avg_rating->execute();
    $result = $avg_rating->fetch(PDO::FETCH_ASSOC);
    return $result['average'] ? round($result['average'], 1) : 0;
}

/**
 * Render star rating HTML
 * @param int $rating The rating (1-5)
 * @param bool $editable Whether the stars should be clickable for editing
 * @param string $field_name The name attribute for the input field
 * @return string HTML for star rating
 */
function renderStarRating($rating, $editable = false, $field_name = 'rating') {
    $html = '<div class="star-rating">';
    
    if ($editable) {
        $html .= '<input type="hidden" name="' . $field_name . '" id="' . $field_name . '" value="' . $rating . '">';
    }
    
    for ($i = 1; $i <= 5; $i++) {
        $star_class = $i <= $rating ? 'star filled' : 'star';
        $star_icon = $i <= $rating ? '★' : '☆';
        
        if ($editable) {
            $html .= '<span class="' . $star_class . '" data-rating="' . $i . '" onclick="setRating(' . $i . ', \'' . $field_name . '\')">' . $star_icon . '</span>';
        } else {
            $html .= '<span class="' . $star_class . '">' . $star_icon . '</span>';
        }
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Render the review filter form HTML
 * @param string $filter_type Current filter type
 * @param string $start_date Current start date
 * @param string $end_date Current end date
 * @param int $min_rating Current minimum rating filter
 * @param string $reviewer_name Current reviewer name filter
 * @param string $current_page Current page URL for form action
 */
function renderReviewFilterForm($filter_type = 'all', $start_date = '', $end_date = '', $min_rating = 0, $reviewer_name = '', $current_page = '') {
    ?>
    <section class="container-fluid py-4">
        <div class="review-filter-section">
            <h4>Filter Reviews</h4>
            <form method="get" action="<?= htmlspecialchars($current_page) ?>" class="filter-form">
                <div class="filter-group">
                    <label for="filter-type">Filter Type:</label>
                    <select name="filter_type" id="filter-type" class="form-control">
                        <option value="all" <?= $filter_type === 'all' ? 'selected' : '' ?>>All Reviews</option>
                        <option value="date-range" <?= $filter_type === 'date-range' ? 'selected' : '' ?>>Date Range</option>
                        <option value="last-week" <?= $filter_type === 'last-week' ? 'selected' : '' ?>>Last Week</option>
                        <option value="last-month" <?= $filter_type === 'last-month' ? 'selected' : '' ?>>Last Month</option>
                        <option value="last-year" <?= $filter_type === 'last-year' ? 'selected' : '' ?>>Last Year</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="start-date">Start Date:</label>
                    <input type="date" name="start_date" id="start-date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
                </div>
                
                <div class="filter-group">
                    <label for="end-date">End Date:</label>
                    <input type="date" name="end_date" id="end-date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
                </div>
                
                <div class="filter-group">
                    <label for="min-rating">Filter by Rating:</label>
                    <select name="min_rating" id="min-rating" class="form-control">
                        <option value="0" <?= $min_rating == 0 ? 'selected' : '' ?>>All Ratings</option>
                        <option value="5" <?= $min_rating == 5 ? 'selected' : '' ?>>5 Stars Only</option>
                        <option value="4" <?= $min_rating == 4 ? 'selected' : '' ?>>4 Stars Only</option>
                        <option value="3" <?= $min_rating == 3 ? 'selected' : '' ?>>3 Stars Only</option>
                        <option value="2" <?= $min_rating == 2 ? 'selected' : '' ?>>2 Stars Only</option>
                        <option value="1" <?= $min_rating == 1 ? 'selected' : '' ?>>1 Star Only</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="reviewer-name">Reviewer Name:</label>
                    <input type="text" name="reviewer_name" id="reviewer-name" class="form-control" placeholder="Search by name..." value="<?= htmlspecialchars($reviewer_name) ?>">
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-apply">Apply Filter</button>
                    <a href="<?= htmlspecialchars($current_page) ?>" class="btn-filter btn-clear">Clear Filter</a>
                </div>
            </form>
        </div>
    </section>
    <?php
}

/**
 * Render the reviews display section
 * @param array $review_list Array of reviews to display
 * @param int $total_count Total number of reviews for this bike
 * @param float $average_rating Average rating for this bike
 */
function renderReviewsSection($review_list, $total_count, $average_rating = 0) {
    ?>
    <section class="container-fluid py-4">
        <div class="review-stats">
            <div>
                <h3>Reviews</h3>
                <?php if ($average_rating > 0): ?>
                    <div class="average-rating">
                        <span class="rating-text">Average Rating:</span>
                        <?= renderStarRating(round($average_rating)) ?>
                        <span class="rating-value">(<?= $average_rating ?>)</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="review-count">
                Showing <strong><?= count($review_list) ?></strong> of <strong><?= $total_count ?></strong> reviews
            </div>
        </div>
        
        <?php if ($review_list): ?>
            <?php foreach ($review_list as $review): ?>
                <div class="card w-75 mb-3 review">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex">
                                <img class="me-1 review-profile-photo" src="../../img/Profile-PNG-File.png" alt="">
                                <h5 class="card-title"><strong><?= htmlspecialchars($review['name']) ?></strong></h5>
                            </div>
                            <div class="review-rating">
                                <?= renderStarRating($review['rating']) ?>
                            </div>
                        </div>
                        <p class="fw-light"><em>(<?= date('F j, Y g:i A', strtotime($review['created_at'])) ?>)</em></p>
                        <p class="card-text"><?= htmlspecialchars($review['content']) ?></p>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews found for the selected filter criteria.</p>
        <?php endif; ?>
    </section>
    <?php
}

/**
 * Render the review form with rating
 * @param array $errors Form errors
 * @param array $include Form data to include
 */
function renderReviewForm($errors = [], $include = []) {
    $rating = $include['rating'] ?? 5;
    ?>
    <section class="container-fluid py-4">
        <h3>Post a Review</h3>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $include['name'] ?? '' ?>">
                <div class="form-text text-danger"><?= $errors['name'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <?= renderStarRating($rating, true, 'rating') ?>
                <div class="form-text text-danger"><?= $errors['rating'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label for="review" class="form-label">Review</label>
                <textarea name="review" id="review" class="form-control"><?php echo $include['review'] ?? '' ?></textarea>
                <div class="form-text text-danger"><?= $errors['review'] ?? '' ?></div>
            </div>

            <button type="submit" class="btn btn-primary" name="send">Post Review</button>
        </form>
    </section>
    <?php
}

/**
 * Render the CSS styles for the review filter and rating system
 */
function renderReviewFilterStyles() {
    ?>
    <style>
        .review-filter-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }
        .filter-group label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #495057;
            margin-bottom: 5px;
        }
        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .filter-buttons {
            display: flex;
            gap: 10px;
            align-items: end;
        }
        .btn-filter {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .btn-apply {
            background-color: #007bff;
            color: white;
        }
        .btn-clear {
            background-color: #6c757d;
            color: white;
        }
        .review-stats {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .average-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }
        .rating-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .rating-value {
            font-weight: bold;
            color: #495057;
        }
        .review-count {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }
        .review-count strong {
            color: #495057;
        }
        .star-rating {
            display: inline-flex;
            gap: 2px;
        }
        .star {
            font-size: 1.2rem;
            cursor: pointer;
            color: #ddd;
            transition: color 0.2s ease;
        }
        .star.filled {
            color: #ffc107;
        }
        .star:hover {
            color: #ffc107;
        }
        .review-rating {
            margin-left: 10px;
        }
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-buttons {
                justify-content: center;
            }
            .review-stats {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
    <script>
        function setRating(rating, fieldName) {
            document.getElementById(fieldName).value = rating;
            
            // Update star display
            const stars = document.querySelectorAll('[data-rating]');
            stars.forEach(star => {
                const starRating = parseInt(star.getAttribute('data-rating'));
                if (starRating <= rating) {
                    star.classList.add('filled');
                    star.textContent = '★';
                } else {
                    star.classList.remove('filled');
                    star.textContent = '☆';
                }
            });
        }
    </script>
    <?php
}
?> 