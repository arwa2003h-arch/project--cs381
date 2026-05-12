<?php
session_start();
require_once "includes/db.php";

$course_id = $_GET['id'] ?? 1;
$message = "";
$messageColor = "#dc2626";

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = :id");
$stmt->execute([':id' => $course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST["rating"] ?? "";
    $comment = trim($_POST["comment"] ?? "");

    if (!isset($_SESSION["user_id"])) {
        $message = "Please login first.";
    } elseif ($rating == "" || $comment == "") {
        $message = "Please provide both rating and comment.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO feedback (user_id, course_id, rating, comment)
            VALUES (:user_id, :course_id, :rating, :comment)
        ");

        $stmt->execute([
            ":user_id" => $_SESSION["user_id"],
            ":course_id" => $course_id,
            ":rating" => $rating,
            ":comment" => $comment
        ]);

        $message = "Feedback submitted successfully.";
        $messageColor = "green";
    }
}

$stmt = $pdo->prepare("SELECT AVG(rating) AS average_rating, COUNT(*) AS total_feedback FROM feedback WHERE course_id = :course_id");
$stmt->execute([':course_id' => $course_id]);
$stats = $stmt->fetch();

$averageRating = $stats['average_rating'] ? number_format($stats['average_rating'], 1) : "Not rated";
$totalFeedback = $stats['total_feedback'];

$stmt = $pdo->prepare("SELECT comment FROM feedback WHERE course_id = :course_id ORDER BY id DESC LIMIT 3");
$stmt->execute([':course_id' => $course_id]);
$comments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Details - YIC Course Feedback System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="site-header">
    <div class="container header-content">
      <div class="brand">
        <h1>YIC Course Feedback System</h1>
        <p>Browse courses and share your feedback</p>
      </div>

      <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php" class="register-btn">Register</a>
      </nav>
    </div>
  </header>

  <main class="page-section">
    <div class="container">

      <section class="detail-card">
        <div class="detail-header">
          <div>
            <span class="course-major"><?php echo htmlspecialchars($course['major']); ?></span>
            <h2><?php echo htmlspecialchars($course['course_code']); ?> - <?php echo htmlspecialchars($course['course_name']); ?></h2>
            <p class="detail-description">
              <?php echo htmlspecialchars($course['description']); ?>
            </p>
          </div>
        </div>
      </section>

      <section class="stats-grid">
        <div class="stat-card">
          <h3>Average Rating</h3>
          <p><?php echo $averageRating; ?> / 5</p>
        </div>

        <div class="stat-card">
          <h3>Total Feedback</h3>
          <p><?php echo $totalFeedback; ?></p>
        </div>
      </section>

      <section class="detail-card">
        <h3>Recent Comments</h3>

        <?php if (count($comments) > 0): ?>
          <?php foreach ($comments as $comment): ?>
            <div class="comment-box">
              <p>"<?php echo htmlspecialchars($comment['comment']); ?>"</p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="comment-box">
            <p>No comments yet.</p>
          </div>
        <?php endif; ?>
      </section>

      <section class="detail-card">
        <h3>Submit Feedback</h3>

        <form method="POST" class="form-layout">
          <div class="form-group">
            <label for="rating">Rating</label>
            <select id="rating" name="rating">
              <option value="">Select rating</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
          </div>

          <div class="form-group">
            <label for="comment">Comment</label>
            <textarea id="comment" name="comment" rows="5" placeholder="Write your feedback here"></textarea>
          </div>

          <?php if ($message != ""): ?>
            <p class="form-message" style="color: <?php echo $messageColor; ?>;">
              <?php echo htmlspecialchars($message); ?>
            </p>
          <?php endif; ?>

          <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
      </section>

    </div>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>CS381 Project | YIC Course Feedback System</p>
    </div>
  </footer>

</body>
</html>