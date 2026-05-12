<?php
require_once "includes/db.php";

$stmt = $pdo->query("
    SELECT 
        courses.*,
        AVG(feedback.rating) AS average_rating
    FROM courses
    LEFT JOIN feedback ON courses.id = feedback.course_id
    GROUP BY courses.id
    ORDER BY courses.id ASC
");

$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>YIC Course Feedback System</title>
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
        <a href="index.php" class="active">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php" class="register-btn">Register</a>
        <a href="logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-content">
        <div class="hero-text">
          <h2>Find your course and submit your feedback easily</h2>
        </div>
      </div>
    </section>

    <section class="filters-section">
      <div class="container">
        <div class="filters-box">
          <div class="form-group">
            <label for="searchInput">Search by course code or name</label>
            <input type="search" id="searchInput" placeholder="Example: CS381 or Systems Analysis">
          </div>

          <div class="form-group">
            <label for="majorFilter">Filter by Major</label>
            <select id="majorFilter">
              <option value="all">All Majors</option>
              <option value="cs">CS</option>
              <option value="mis">MIS</option>
              <option value="hr">HR</option>
              <option value="ce">CE</option>
              <option value="accounting">Accounting</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="courses-section" id="courses">
      <div class="container">
        <div class="section-heading">
          <h2>Available Courses</h2>
          <p>Select a course to view details and feedback.</p>
        </div>

        <div class="courses-grid">
          <?php foreach ($courses as $course): ?>
            <article class="course-card" data-major="<?php echo strtolower($course['major']); ?>">
              <div class="course-top">
                <span class="course-major"><?php echo htmlspecialchars($course['major']); ?></span>
                <span class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></span>
              </div>

              <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>

              <p><?php echo htmlspecialchars($course['description']); ?></p>

              <div class="course-meta">
                <span>Average Rating:</span>
                <strong>
                  <?php
                    if ($course['average_rating']) {
                        echo number_format($course['average_rating'], 1) . " / 5";
                    } else {
                        echo "Not rated yet";
                    }
                  ?>
                </strong>
              </div>

              <a href="course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">View Course</a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>CS381 Project | YIC Course Feedback System</p>
    </div>
  </footer>

  <script>
    const searchInput = document.querySelector('#searchInput');
    const majorFilter = document.querySelector('#majorFilter');
    const courseCards = document.querySelectorAll('.course-card');

    function filterCourses() {
      const searchValue = searchInput.value.toLowerCase().trim();
      const majorValue = majorFilter.value;

      courseCards.forEach(function (card) {
        const cardText = card.textContent.toLowerCase();
        const cardMajor = card.getAttribute('data-major');

        if (
          cardText.includes(searchValue) &&
          (majorValue === 'all' || cardMajor === majorValue)
        ) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    }

    searchInput.addEventListener('input', filterCourses);
    majorFilter.addEventListener('change', filterCourses);
  </script>
</body>
</html>