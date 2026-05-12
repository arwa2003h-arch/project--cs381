<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

$totalCourses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$totalFeedback = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$overallAverage = $pdo->query("SELECT AVG(rating) FROM feedback")->fetchColumn();

if ($overallAverage) {
    $overallAverage = number_format($overallAverage, 1);
} else {
    $overallAverage = "0.0";
}

$stmt = $pdo->query("
    SELECT 
        courses.course_code,
        courses.course_name,
        courses.major,
        COUNT(feedback.id) AS total_feedback,
        AVG(feedback.rating) AS avg_rating
    FROM courses
    LEFT JOIN feedback ON courses.id = feedback.course_id
    GROUP BY courses.id
    ORDER BY courses.id ASC
");
$coursePerformance = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT 
        users.full_name,
        courses.course_code,
        feedback.rating,
        feedback.comment,
        feedback.id
    FROM feedback
    JOIN users ON feedback.user_id = users.id
    JOIN courses ON feedback.course_id = courses.id
    ORDER BY feedback.id DESC
    
");
$recentFeedback = $stmt->fetchAll();

function getStatus($avg) {
    if ($avg === null) {
        return "Average";
    } elseif ($avg >= 4) {
        return "Good";
    } elseif ($avg >= 3) {
        return "Average";
    } else {
        return "Low Rated";
    }
}

function getStatusValue($status) {
    if ($status == "Good") {
        return "good";
    } elseif ($status == "Average") {
        return "average";
    } else {
        return "low";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - YIC Course Feedback System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="site-header">
    <div class="container header-content">
      <div class="brand">
        <h1>YIC Course Feedback System</h1>
        <p>Admin dashboard</p>
      </div>

      <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <main class="page-section">
    <div class="container">

      <section class="section-heading">
        <h2>Admin Dashboard</h2>
        <p>Monitor course performance and recent feedback.</p>
      </section>

      <section class="stats-grid">
        <div class="stat-card">
          <h3>Total Courses</h3>
          <p><?php echo $totalCourses; ?></p>
        </div>

        <div class="stat-card">
          <h3>Total Feedback</h3>
          <p><?php echo $totalFeedback; ?></p>
        </div>

        <div class="stat-card">
          <h3>Overall Average Rating</h3>
          <p><?php echo $overallAverage; ?></p>
        </div>
      </section>

      <section class="detail-card">
        <div class="filters-box dashboard-filters">
          <div class="form-group">
            <label for="dashboardMajor">Filter by Major</label>
            <select id="dashboardMajor" name="dashboardMajor">
              <option value="all">All Majors</option>
              <option value="cs">CS</option>
              <option value="mis">MIS</option>
              <option value="hr">HR</option>
              <option value="ce">CE</option>
              <option value="accounting">Accounting</option>
            </select>
          </div>

          <div class="form-group">
            <label for="dashboardPerformance">Filter by Performance</label>
            <select id="dashboardPerformance" name="dashboardPerformance">
              <option value="all">All</option>
              <option value="good">Good</option>
              <option value="average">Average</option>
              <option value="low">Low Rated</option>
            </select>
          </div>
        </div>
      </section>

      <section class="detail-card">
        <h3>Course Performance Summary</h3>

        <div class="table-wrapper">
          <table id="performanceTable">
            <thead>
              <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Total Feedback</th>
                <th>Avg Rating</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($coursePerformance as $course): ?>
                <?php
                  $avg = $course["avg_rating"];
                  $avgText = $avg ? number_format($avg, 1) : "0.0";
                  $status = getStatus($avg);
                  $statusValue = getStatusValue($status);
                ?>
                <tr data-major="<?php echo strtolower($course['major']); ?>" data-performance="<?php echo $statusValue; ?>">
                  <td><?php echo htmlspecialchars($course["course_code"]); ?></td>
                  <td><?php echo htmlspecialchars($course["course_name"]); ?></td>
                  <td><?php echo $course["total_feedback"]; ?></td>
                  <td><?php echo $avgText; ?></td>
                  <td><?php echo $status; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section class="detail-card">
        <h3>Recent Feedback</h3>

        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Student Name</th>
                <th>Course</th>
                <th>Rating</th>
                <th>Comment</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentFeedback as $feedback): ?>
                <tr>
                  <td><?php echo htmlspecialchars($feedback["full_name"]); ?></td>
                  <td><?php echo htmlspecialchars($feedback["course_code"]); ?></td>
                  <td><?php echo htmlspecialchars($feedback["rating"]); ?></td>
                  <td><?php echo htmlspecialchars($feedback["comment"]); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

    </div>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>CS381 Project | YIC Course Feedback System</p>
    </div>
  </footer>

  <script>
    const dashboardMajor = document.querySelector('#dashboardMajor');
    const dashboardPerformance = document.querySelector('#dashboardPerformance');
    const rows = document.querySelectorAll('#performanceTable tbody tr');

    function filterDashboard() {
      const majorValue = dashboardMajor.value;
      const performanceValue = dashboardPerformance.value;

      rows.forEach(function (row) {
        const rowMajor = row.getAttribute('data-major');
        const rowPerformance = row.getAttribute('data-performance');

        if (
          (majorValue === 'all' || rowMajor === majorValue) &&
          (performanceValue === 'all' || rowPerformance === performanceValue)
        ) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    dashboardMajor.addEventListener('change', filterDashboard);
    dashboardPerformance.addEventListener('change', filterDashboard);
  </script>
</body>
</html>